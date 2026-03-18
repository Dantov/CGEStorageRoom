<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\models\User;

$name = User::getFIO();
$this->title = $name . ' OPTIONS';
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <h2>
        OPTIONS
    </h2>
    </div>
    <pre style="display: inline-block !important; vertical-align: top; margin-left: 5px; padding: 5px; border-bottom: 1px solid #0f0f0f; border-left: 1px solid #0f0f0f" >
        <?php print_r($res); ?>
    </pre>
</div>
