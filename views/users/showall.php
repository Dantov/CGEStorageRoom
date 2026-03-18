<?php
use yii\helpers\Url;
use app\models\User;
use app\models\serviceClasses\Crypt;

$this->title = 'Show all users';
$session = Yii::$app->session;
$tt=time();
$this->registerJsFile("@web/js/add-edit/Validator.js?v=$tt",['depends' => [\app\assets\AppAsset::class]]);

//$this->registerCssFile("@web/css/view/view.css?v=$tt");
//debug($users->getRoles($single['role']),'getRoles',1);
//debug($all,'all');

?>
<?php if( $session->hasFlash('dellgood') ):?>
    <div class="alert alert-success" role="alert"><?=$session->getFlash('dellgood')?></div>
<?php endif;?>
<?php if( $session->hasFlash('dellError') ):?>
    <div class="alert alert-success" role="alert"><?=$session->getFlash('dellError')?></div>
<?php endif;?>
<div class="row justify-content-center">
    <!-- Profile -->
    <div class="col-md-auto">
        <div class="outer-w3-agile mt-3" style="width: 20rem !important;">
            <div class="profile-main-w3ls">
                <div class="profile-pic wthree">
                    <a href="<?=Url::to(['users/add'])?>">
                        <img src="/pictAssets/plususer.png" class="img-fluid" alt="">
                    <h3 class="text-danger">Add New One</h3>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--// Profiile -->
    <?php foreach ($all as $single): ?>
    <!-- Profile -->
    <?php $self = ($single['id']===User::getID())?"bg-info":"" ?>
    <div class="col-md-auto <?=$self?>">
        <div class="outer-w3-agile mt-3" style="width: 20rem !important;">
            <div class="profile-main-w3ls">
                <div class="profile-pic wthree">
                    <img src="/images/defaultUser2.png" class="img-fluid" alt="Responsive image">
                    <h3><?=$single['fio']?></h3>
                    <p><?=$single['role']?></p>
                </div>
                <div class="w3-message">
                    <h5>Note</h5>
                    <p><?=$single['about']?></p>
                    <div class="w3ls-touch">
                       <ul class="nav nav-pills jus justify-content-center">
                          <li class="nav-item">
                            <a class="nav-link active" href="<?=Url::to(['users/edit','id'=>Crypt::strEncode($single['id'])])?>">Edit</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="<?=Url::to(['users/delete','id'=>Crypt::strEncode($single['id'])])?>">Delete</a>
                          </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--// Profiile -->
    <?php endforeach; ?>
</div>