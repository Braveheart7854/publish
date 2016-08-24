<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = '任务列表';
$this->list = [
    '任务列表' => Url::to('@web/site/index'),
    '发布任务' => Url::to('@web/site/add'),
];
?>
<div class="site-index">
    <h2><?=$this->title?></h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <td>ID</td>
            <td>标题</td>
            <td>发布项目</td>
            <td>发布分支</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if (isset($taskList)): ?>
        <?php foreach ($taskList as $task): ?>
        <tr style="height: 51px;">
            <td><?=$task->id?></td>
            <td><?=$task->title?></td>
            <td><?=$task->projectName?></td>
            <td><?=$task->branches?></td>
            <td><?=$statusList[$task->status]?></td>
            <td>
                <?php if ($task->status == 5) { ?>
                <a class="btn btn-primary" href="<?=Url::to('@web/site/merge') . '?id=' . $task->id?>">合并</a>
                <a class="btn btn-default" href="<?=Url::to('@web/site/reset') . '?id=' . $task->id?>">回滚</a>
                <?php } else if ($task->status > 0 && $task->status < 5) { ?>
                <a class="btn btn-success" href="<?=Url::to('@web/site/pub') . '?id=' . $task->id?>">发布</a>
                <a class="btn btn-danger" href="<?=Url::to('@web/site/del') . '?id=' . $task->id?>">删除</a>
                <?php } ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>