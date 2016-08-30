<?php

namespace app\controllers;

use app\components\Svn;
use app\components\SvnEx;
use app\models\Project;
use app\models\Task;
use Yii;
use yii\bootstrap\Progress;
use yii\bootstrap\Widget;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($page = 1)
    {
        $statusList = [
            -1 => '发布失败',
            1  => '等待发布',
            2  => '同步分支',
            3  => '导出项目',
            4  => '同步到服务器',
            5  => '发布完成',
            6  => '已合并',
            7  => '已回滚',
        ];

        $limit = 10;
        $offset = ($page - 1) * $limit;
        $taskList = Task::find()->orderBy('id desc')/*->offset($offset)->limit($limit)*/->all();

        foreach ($taskList as &$task) {
            $task->projectName = Project::findOne($task->projectId)->name;
        }

        return $this->render('index', [
            'taskList' => $taskList,
            'statusList' => $statusList,
            'page' => $page,
        ]);
    }

    public function actionAdd()
    {
        if ('ADD' == Yii::$app->request->post('TYPE')) {
            $title = Yii::$app->request->post('title');
            $projectId = Yii::$app->request->post('projectId');
            $branches = Yii::$app->request->post('branches');

            $task = new Task();
            $task->branches = $branches;
            $task->projectId = $projectId;
            $task->title = $title;
            $task->status = 1;
            $task->errorMsg = 'waiting..';
            $task->save();

            $this->redirect(['@web/site']);
        }

        $projectList = Project::find()->orderBy('id desc')->all();

        return $this->render('add', [
            'projectList' => $projectList,
        ]);
    }

    public function actionDel()
    {
        $taskId = Yii::$app->request->get('id');
        $task = Task::findOne($taskId);
        $task->delete();

        $this->redirect(['@web/site']);
    }

    public function actionPub()
    {
        $taskId = Yii::$app->request->get('id');

        $task = Task::findOne($taskId);
        $task->projectId = Project::findOne($task->projectId)->name;

        return $this->render('pub', [
            'task' => $task,
        ]);
    }

    public function actionStartPub()
    {
        $taskId = Yii::$app->request->get('id');
        $task = Task::findOne($taskId);
        $project = Project::findOne($task->projectId);

        $startTime = microtime(true);

        $svn = $this->getSvn($project);

        $url = $svn->getBranchesUrl($svn::$checkout . '/' . $svn::$name);
        // 主干是否已co
        if ($url == Svn::getTrunk()) {
            // reset & update
            $result = $svn->resetTrunk();
            if ($result === true) {
                $svn->updateTrunk();
                $task->status = 2;
                $task->errorMsg = '更新分支';
                $task->save();
            } else {
                $task->status = -1;
                $task->errorMsg = '更新主干失败';
                $task->save();
                die;
            }
        } else {
            // co trunk
            $result = $svn->downTrunk();
            if ($result === true) {
                $task->status = 2;
                $task->errorMsg = '合并分支';
                $task->save();
            } else {
                $task->status = -1;
                $task->errorMsg = is_array($result) ? '更新分支失败' : $result;
                $task->save();
                die;
            }
        }
        $result = $svn->mergeBranches($task->branches);
        if ($result) {
            $task->status = 3;
            $task->errorMsg = '打包项目';
            $task->save();
        } else {
            $task->status = -1;
            $task->errorMsg = '合并分支失败，请检查分支是否存在';
            $task->save();
            die;
        }
        // 是否有冲突
        $result = $svn->hasConflict();
        if (count($result) > 0) {
            $task->status = -1;
            $task->errorMsg = "需要解决冲突\n" . join("\n", $result);
            $task->save();
            die;
        }
        // 导出项目
        $result = $svn->export();
        if ($result) {
            $task->status = 4;
            $task->errorMsg = '同步到目标机器';
            $task->save();
        } else {
            $task->status = -1;
            $task->errorMsg = '打包失败，请检查目录是否存在';
            $task->save();
            die;
        }
        $result = $svn->sync();
        $endTime = microtime(true);
        if ($result) {
            $task->status = 5;
            $task->errorMsg = '发布完成，耗时:' . round($endTime - $startTime, 3) . 's';
            $task->save();
        } else {
            $task->status = -1;
            $task->errorMsg = $result;
            $task->save();
            die;
        }
    }

    public function actionGetPubStatus()
    {
        $taskId = Yii::$app->request->get('id');
        $task = Task::findOne($taskId);
        if ($task->status != -1) {
            echo json_encode(['code' => ($task->status * 20),'step' => $task->status, 'msg' => $task->errorMsg]);
        } else {
            echo json_encode(['code' => -1, 'msg' => $task->errorMsg]);
        }
    }

    public function actionMerge()
    {
        $taskId = Yii::$app->request->get('id');
        $task = Task::findOne($taskId);
        $project = Project::findOne($task->projectId);

        $svn = $this->getSvn($project);

        $result = $svn->commit(sprintf('%s_%s_%s', date('Y-m-d H:i:s'), $project->name, $task->title));

        if ($result['status'] == 0) {
            $task->status = 6;
            $task->save();
        }

        $mergeInfo = '';
        foreach ($result['output'] as $val) {
            $mergeInfo .= "{$val}\n";
        }

        return $this->render('merge', [
            'task' => $task,
            'project' => $project,
            'result' => $mergeInfo,
        ]);
    }

    /**
     * 项目回滚
     */
    public function actionReset()
    {
        $taskId = Yii::$app->request->get('id');
        $task = Task::findOne($taskId);
        $project = Project::findOne($task->projectId);

        $svn = $this->getSvn($project);
        $svn->resetTrunk();
        $svn->updateTrunk();
        $svn->export();
        $svn->sync();

        $task->status = 7;
        $task->save();

        $this->redirect(['@web/site']);
    }

    private function getSvn($project)
    {
        $svn = new Svn();
        $svn::$name = $project->name;
        $svn::$user = Yii::$app->params['svn_user'];
        $svn::$pass = Yii::$app->params['svn_pass'];
        $svn::$checkout = $project->checkout;
        $svn::$export = $project->export;
        $svn::$trunk = $project->trunk;
        $svn::$remote_host = $project->remote_host;
        $svn::$remote_user = $project->remote_user;
        $svn::$excludes = explode("\n", $project->excludes);
        return $svn;
    }
}
