<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = $project ? '修改项目' : '添加项目';
$this->list = [
    '项目列表' => Url::to('@web/project/index'),
    '添加项目' => Url::to('@web/project/add'),
];
?>
<div class="site-index">
    <h2><?=$this->title?></h2>
    <?= Html::beginForm() ?>
    <?= Html::input('hidden', 'TYPE', $project ? 'UPDATE' : 'ADD') ?>
    <?= Html::input('hidden', 'id',   $project ? $project->id : '') ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <td width="20%"></td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>项目标题</td>
            <td>
                <?= Html::input('text', 'name', $project ? $project->name : '', [
                    'class' => 'form-control',
                ]) ?>
            </td>
        </tr>
        <tr>
            <td>项目分支</td>
            <td>
                <?= Html::input('text', 'trunk', $project ? $project->trunk : '', [
                    'class' => 'form-control',
                ]) ?>
            </td>
        </tr>
        <tr>
            <td>检出路径</td>
            <td>
                <?= Html::input('text', 'checkout', $project ? $project->checkout : '', [
                    'class' => 'form-control',
                ]) ?>
            </td>
        </tr>
        <tr>
            <td>目标机器（ip地址）</td>
            <td>
                <?= Html::input('text', 'host', $project ? $project->remote_host : '', [
                    'class' => 'form-control',
                ]) ?>
            </td>
        </tr>
        <tr>
            <td>目标用户</td>
            <td>
                <?= Html::input('text', 'user', $project ? $project->remote_user : '', [
                    'class' => 'form-control',
                ]) ?>
            </td>
        </tr>
        <tr>
            <td>目标路径</td>
            <td>
                <?= Html::input('text', 'export', $project ? $project->export : '', [
                    'class' => 'form-control',
                ]) ?>
            </td>
        </tr>
        <tr>
            <td>忽略文件（一行一个）</td>
            <td>
                <?= Html::textarea('excludes', $project ? $project->excludes : '', [
                    'class' => 'form-control',
                    'style' => 'min-height: 100px;',
                ]) ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?= Html::submitButton('保存', [
                    'class' => 'btn btn-success',
                ]) ?>
            </td>
        </tr>
        </tbody>
    </table>
    <?= Html::endForm() ?>
</div>
