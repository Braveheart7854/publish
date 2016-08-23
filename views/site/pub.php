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
<style type="text/css">
    @keyframes one {
        from {
            background: #a1ff0a;
        }
        to {
            background: #3c9bee;
        }
    }
    #progress {
        width: 0;
        height: 5px;
        transition: width 0.8s;
        background-color: #22a8ee;
        animation: one 2.2s alternate;
        animation-iteration-count: 9999;
    }
</style>
<div class="site-index">
    <h2><?= $this->title ?></h2>
    <div class="panel-group">
        <span>发布<code><?= $task->branches ?></code>到<code><?= $task->projectId ?></code></span>
    </div>
    <div style="background-color: #eee;" class="panel">
        <div id="progress"></div>
    </div>
    <div>
        <a href="javascript:;" data-bt="pub" data-taskId="<?= $task->id ?>" class="btn btn-success">开始发布</a>
        ::<span data-bt="msg" class="panel-default">同步时请不要关闭，或刷新页面</span>
    </div>
</div>