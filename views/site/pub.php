<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = '任务发布';
$this->list = [
    '任务列表' => Url::to('@web/site/index'),
    '发布任务' => Url::to('@web/site/add'),
];
$this->registerJsFile('/js/site/pub.js', ['depends' => 'app\assets\AppAsset']);
?>
<div class="site-index">
    <h2><?=$this->title?></h2>
    <div class="panel-group">
        <span>发布<code><?=$task->branches ?></code>到<code><?=$task->projectId ?></code></span>
    </div>
    <div style="background-color: #eee;" class="panel">
        <div id="progress" style="width: 0; height: 10px; background-color: #22a8ee;"></div>
    </div>
    <div>
        <a href="javascript:;" data-bt="pub" data-taskId="<?=$task->id ?>" class="btn btn-success">开始发布</a>
    </div>
</div>