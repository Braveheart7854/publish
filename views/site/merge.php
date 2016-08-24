<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = '分支合并';
$this->list = [
    '任务列表' => Url::to('@web/site/index'),
    '发布任务' => Url::to('@web/site/add'),
];
?>
<div class="site-index">
    <h2><?=$this->title?></h2>
    <div class="panel-group">
        <span>合并分支<code><?=$task->branches ?></code>到<code><?= $project->name ?></code>项目</span>
        <div class="panel-info">
            <pre><?=$result ?></pre>
        </div>
    </div>
</div>