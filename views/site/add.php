<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = '发布任务';
$this->list = [
    '任务列表' => Url::to('@web/site/index'),
    '发布任务' => Url::to('@web/site/add'),
];
$this->registerJsFile('/js/site/add.js', ['depends' => 'app\assets\AppAsset']);
?>
<div class="site-index">
    <h2><?=$this->title?></h2>
    <?= Html::beginForm() ?>
    <?= Html::input('hidden', 'TYPE', 'ADD') ?>
    <table class="table table-bordered">
        <tr>
            <td>任务标题</td>
            <td>
                <?= Html::input('text', 'title', '', [
                    'class' => 'form-control'
                ]) ?>
            </td>
        </tr>
        <tr>
            <td>发布项目</td>
            <td>
                <label>
                    <select name="projectId" class="form-control">
                        <?php foreach ($projectList as $project): ?>
                        <option value="<?= $project->id ?>"><?= $project->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </td>
        </tr>
        <tr>
            <td>发布分支</td>
            <td>
                <label>
                    <select name="branches" class="form-control">
                    </select>
                </label>
                <a href="javascript:;" data-bt="f5" class="btn btn-group">刷新</a>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?= Html::submitButton('提交', [
                    'class' => 'btn btn-success',
                ]) ?>
            </td>
        </tr>
    </table>
    <?= Html::endForm() ?>
</div>