<?php

namespace app\controllers;

use Yii;
use app\components\Svn;
use app\models\Project;
use yii\web\Controller;

class ProjectController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $svn = new Svn();
        $projectList = Project::find()->all();

        return $this->render('index', [
            'projectList' => $projectList,
        ]);
    }

    public function actionAdd()
    {
        // add
        if ('ADD' == Yii::$app->request->post('TYPE')) {
            $name = Yii::$app->request->post('name');
            $trunk = Yii::$app->request->post('trunk');
            $project = new Project();
            $project->name = $name;
            $project->trunk = $trunk;
            $project->status = 1;
            $project->save();

            $this->redirect(['@web/project']);
        }

        return $this->render('add');
    }

    public function actionGetBranchesList()
    {
        $projectId = Yii::$app->request->get('id');
        $project = Project::findOne($projectId);

        $svn = new Svn();
        $svn::$name = $project->name;
        $svn::$user = 'publish';
        $svn::$pass = 'test';
        $svn::$checkout = $project->checkout;
        $svn::$export = $project->export;
        $svn::$trunk = $project->trunk;

        $branchesList = $svn->getBranchesList();
        if (count($branchesList) > 0) {
            echo json_encode(['code' => 0, 'msg' => 'success', 'data' => $branchesList]);
        } else {
            echo json_encode(['code' => 1, 'msg' => '找不到路径或没有分支']);
        }
    }
}
