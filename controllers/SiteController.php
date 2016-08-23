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
    public function actionIndex()
    {
        $taskList = Task::find()->orderBy('id desc')->all();

        foreach ($taskList as &$task) {
            $task->projectId = Project::findOne($task->projectId)->name;
        }

        return $this->render('index', [
            'taskList' => $taskList,
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
            $task->save();

            $this->redirect(['@web/site']);
        }

        $projectList = Project::find()->orderBy('id desc')->all();

        return $this->render('add', [
            'projectList' => $projectList,
        ]);
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

        $svn = $this->getSvn($project);
        $result = $svn->downTrunk();
        if ($result) {
            $task->status = 2;
            $task->errorMsg = '合并分支';
            $task->save();
        } else {
            $task->status = -1;
            $task->errorMsg = $result;
            $task->save();
            die;
        }
        $result = $svn->mergeBranches($task->branches);
        if ($result) {
            $task->status = 3;
            $task->errorMsg = '打包项目';
            $task->save();
        } else {
            $task->status = -1;
            $task->errorMsg = $result;
            $task->save();
            die;
        }
        $result = $svn->export();
        if ($result) {
            $task->status = 4;
            $task->errorMsg = '同步到目标机器';
            $task->save();
        } else {
            $task->status = -1;
            $task->errorMsg = $result;
            $task->save();
            die;
        }
        if ($svn->sync()) {
            $task->status = 5;
            $task->errorMsg = '发布完成';
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
