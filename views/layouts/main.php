<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - Svn Publish</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap ">
    <?php
    NavBar::begin([
        'brandLabel' => 'Publish 项目发布系统',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => [
            'class' => 'navbar-nav navbar-right',
            'style' => 'margin-right: 0;',
        ],
        'items' => [
            ['label' => 'Tasks', 'url' => ['/site/index']],
            ['label' => 'Project', 'url' => ['/project/index']],
        ],
    ]);
    NavBar::end();
    ?>



    <div class="container" style="padding-top: 40px;">
        <?php if (isset($this->list)) { ?>
            <div class="container" style="background-color: #eee; width: 100%; border-top: 1px solid #E7E7E7; padding: 20px 15px 10px">
                <div class="site-index">
                    <?php foreach ($this->list as $name => $url) { ?>
                        <a href="<?=$url?>"><?=$name?></a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>
<!--
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Publish <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
-->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
