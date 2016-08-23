<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = '项目列表';
$this->list = [
    '项目列表' => Url::to('@web/project/index'),
    '添加项目' => Url::to('@web/project/add'),
];
?>
<div class="site-index">
    <h2><?=$this->title?></h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <td>ID</td>
            <td>标题</td>
            <td>状态</td>
            <td>发布分支</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if (isset($taskList)): ?>
        <?php foreach ($taskList as $task): ?>
        <tr>
            <td><?=$task->id?></td>
            <td><?=$task->title?></td>
            <td><?=$task->status?></td>
            <td><?=$task->branches?></td>
            <td>
                <a class="btn btn-success" href="<?=Url::to('@web/site/pub')?>">发布</a>
                <a class="btn btn-danger" href="<?=Url::to('@web/site/del')?>">删除</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
