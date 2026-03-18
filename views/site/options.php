<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\models\User;

$name = User::getFIO();
$this->title = 'CGE:: ' .$name . ' OPTIONS';
?>
<div class="site-error">
    <div class="alert alert-danger">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
</div>
