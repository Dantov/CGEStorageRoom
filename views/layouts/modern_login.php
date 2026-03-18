<?php

use app\assets\LoginAsset;
use yii\helpers\Html;
use yii\helpers\Url;

LoginAsset::register($this);
?>
<!DOCTYPE html>
<?php $this->beginPage() ?>
<html lang="<?= Yii::$app->language ?>">

<head>
    <!-- Meta Tags -->
    <link rel="icon" href="../images/favicon.ico?ver=<?=time()?>">
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- //Meta Tags -->

    <?php $this->head() ?>
    <!--web-fonts-->
    <link href="//fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!--//web-fonts-->
</head>

<?php $this->beginBody() ?>
<body>
    <div class="bg-page py-5">
        <div class="container">
            <?= $content; ?>
        </div>
    </div>
</body>
<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>