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
            <td>项目名称</td>
            <td>状态</td>
            <td>分支地址</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if (isset($projectList)): ?>
        <?php foreach ($projectList as $project): ?>
        <tr>
            <td><?=$project->id?></td>
            <td><?=$project->name?></td>
            <td><?=$project->status?></td>
            <td><?=$project->trunk?></td>
            <td>
                <a class="btn btn-success" href="<?=Url::to('@web/project/init?id=' . $project->id)?>">初始化</a>
                <a class="btn btn-success" href="<?=Url::to('@web/project/add?TYPE=UPDATE&id=' . $project->id)?>">修改</a>
                <a class="btn btn-danger" href="<?=Url::to('@web/project/del?id=' . $project->id)?>">删除</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
