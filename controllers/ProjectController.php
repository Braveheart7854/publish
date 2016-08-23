<?php

namespace app\controllers;

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
            't' => $svn->t(),
        ]);
    }

    public function actionAdd()
    {
        echo 'add project';
    }
}
