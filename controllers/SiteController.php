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

    }

    public function actionGetPubStatus()
    {
        echo json_encode(['code' => 50, 'step' => 1]);
    }
}
