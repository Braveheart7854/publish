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
        <span>发布任务<code><?=$task->title ?></code>:<code><?= $task->branches ?></code>到<code><?= $task->projectId ?></code></span>
    </div>
    <div style="background-color: #eee;" class="panel">
        <div id="progress" style="<?=$task->status == 5 ? 'width:100%;' : '' ?>"></div>
    </div>
    <div>
        <?php if ($task->status != 5) { ?>
        <a href="javascript:;" data-bt="pub" data-taskId="<?= $task->id ?>" class="btn btn-success">开始发布</a>
        <?php } else { ?>
        <a href="<?=Url::to(['@web/site/merge?id=' . $task->id]) ?>" class="btn btn-primary">合并</a>
        <a href="<?=Url::to(['@web/site/reset?id=' . $task->id]) ?>" class="btn btn-default">回滚</a>
        <?php } ?>
        ::<span data-bt="msg" class="panel-default"><?=$task->status == 5 ? '发布已完成，请完成后续操作' : '同步时请不要关闭，或刷新页面' ?></span>
    </div>
</div>