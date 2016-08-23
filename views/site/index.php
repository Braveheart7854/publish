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
            <td>发布分支</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if (isset($taskList)): ?>
        <?php foreach ($taskList as $task): ?>
        <tr>
            <td><?=$task->id?></td>
            <td><?=$task->title?></td>
            <td><?=$task->branches?></td>
            <td><?=$task->status?></td>
            <td>
                <a class="btn btn-success" href="<?=Url::to('@web/site/pub') . '?id=' . $task->id?>">发布</a>
                <a class="btn btn-danger" href="<?=Url::to('@web/site/del') . '?id=' . $task->id?>">删除</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
