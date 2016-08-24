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
        $project = null;
        // add
        if ('ADD' == Yii::$app->request->post('TYPE')) {
            $name     = Yii::$app->request->post('name');
            $trunk    = Yii::$app->request->post('trunk');
            $checkout = Yii::$app->request->post('checkout');
            $export   = Yii::$app->request->post('export');
            $host     = Yii::$app->request->post('host');
            $user     = Yii::$app->request->post('user');
            $excludes = Yii::$app->request->post('excludes');

            $project = new Project();
            $project->name = $name;
            $project->trunk = $trunk;
            $project->checkout = $checkout;
            $project->export = $export;
            $project->status = 1;
            $project->remote_host = $host;
            $project->remote_user = $user;
            $project->excludes = $excludes;
            $project->save();

            $this->redirect(['@web/project']);
        }
        else if ('UPDATE' == Yii::$app->request->post('TYPE')) {
            $projectId = Yii::$app->request->post('id');
            $name      = Yii::$app->request->post('name');
            $trunk     = Yii::$app->request->post('trunk');
            $checkout  = Yii::$app->request->post('checkout');
            $export    = Yii::$app->request->post('export');
            $host      = Yii::$app->request->post('host');
            $user      = Yii::$app->request->post('user');
            $excludes  = Yii::$app->request->post('excludes');

            $project = Project::findOne($projectId);
            $project->name = $name;
            $project->trunk = $trunk;
            $project->checkout = $checkout;
            $project->export = $export;
            $project->status = 1;
            $project->remote_host = $host;
            $project->remote_user = $user;
            $project->excludes = $excludes;
            $project->save();

            $this->redirect(['@web/project']);
        }
        if ('UPDATE' == Yii::$app->request->get('TYPE')) {
            $projectId = Yii::$app->request->get('id');
            $project = Project::findOne($projectId);
        }
        return $this->render('add', [
            'project' => $project,
        ]);
    }

    public function actionDel()
    {
        $projectId = Yii::$app->request->get('id');
        $project = Project::findOne($projectId);
        $project->delete();
        $this->redirect(['@web/project']);
    }

    public function actionInit()
    {
        $projectId = Yii::$app->request->get('id');
        $project = Project::findOne($projectId);

        $svn = $this->getSvn($project);

        $svn->downTrunk();

        $this->redirect(['@web/project']);
    }

    public function actionGetBranchesList()
    {
        $projectId = Yii::$app->request->get('id');
        $project = Project::findOne($projectId);

        $svn = $this->getSvn($project);

        $branchesList = $svn->getBranchesList();
        if (count($branchesList) > 0) {
            echo json_encode(['code' => 0, 'msg' => 'success', 'data' => $branchesList]);
        } else {
            echo json_encode(['code' => 1, 'msg' => '找不到路径或没有分支']);
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
