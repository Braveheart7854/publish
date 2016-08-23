<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = '添加项目';
$this->list = [
    '项目列表' => Url::to('@web/project/index'),
    '添加项目' => Url::to('@web/project/add'),
];
?>
<div class="site-index">
    <h2><?=$this->title?></h2>
    <?= Html::beginForm() ?>
    <?= Html::input('hidden', 'TYPE', 'ADD') ?>
    <table class="table table-bordered">
        <tr>
            <td>项目标题</td>
            <td>
                <?= Html::input('text', 'name', '', [
                    'class' => 'form-control',
                ]) ?>
            </td>
        </tr>
        <tr>
            <td>项目分支</td>
            <td>
                <?= Html::input('text', 'trunk', '', [
                    'class' => 'form-control',
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
    </table>
    <?= Html::endForm() ?>
</div>
