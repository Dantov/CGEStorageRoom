<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\{Html,Url};
use app\models\User;
use app\models\serviceClasses\Crypt;

$tt=time();
$this->registerJsFile("@web/js/users/Profile.js?v=$tt", ['depends' => [\app\assets\AppAsset::class]]);

$uidC = Crypt::strEncode( User::getID() );
$name = User::getFIO();
$this->title = 'CGE:: ' .$name . ' PROFILE';
?>
<h2 class="main-title-w3layouts mb-2 text-center">Profile</h2>
<nav class="inbox-nav-w3ls p-3 bg-dark text-white">
    <div class="row">
        <div class="inbox-topl col-12">
            <ul class="d-flex align-self-center">
                <li>
                    <h5><?=$name?></h5>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="index-lcontent col-xl-12">
            <div class="container-fluid">
                <div class="row">

                    <!-- left-strip -->
                    <div class="inbox-side-strip-w3-agileits px-2 py-3 col-3">
                        <ul class="text-center">
                            <li>
                                <img src="/images/users/<?=User::getAvatar()?>" class="img-fluid rounded-circle userAvatar" alt="Responsive image">
                                <input type="hidden" id="userid" value="<?=$uidC?>">
                            </li>
                            <li class="pt-3">
                                <button type="button" id="addImageFiles" class="btn btn-primary">
                                    <i class="far fa-images"></i> Upload Photo
                                </button>
                            </li>
                            <li class="pt-3">
                                <a type="button" href="<?=Url::to(['/site/profile/','edit'=>'dellavatar'])?>" class="btn btn-outline-dangery">
                                    <i class="fa-solid fa-explosion"></i> Flush
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- //left-strip -->

                    <!-- right-strip -->
                    <div class="email-list col-9">
                        <ul class="collection">
                            <li class="collection-item d-flex justify-content-left">
                                <i class="fa-solid fa-file-signature text-primary align-self-center mr-4"></i>
                                <div class="mid-cn">
                                    <span class="email-title">Names</span>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="username">Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                                                </div>
                                                <input type="text" editable class="form-control" name="name" id="username" value="<?=User::getName();?>" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="usersurname">Surname</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                                                </div>
                                                <input type="text" editable class="form-control" name="lastname" id="usersurname" value="<?=User::getSurname();?>" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="userthirdname">Lastname</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                                                </div>
                                                <input type="text" editable class="form-control" name="thirdname" id="userthirdname" value="<?=User::getThirdName();?>" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="collection-item d-flex justify-content-left">
                                <i class="fa-regular fa-envelope text-success mr-4"></i>
                                <div class="mid-cn">
                                    <span class="email-title"> Email</span>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                                                </div>
                                                <input type="text" editable class="form-control" name="email" id="email" value="<?=User::getEmail();?>" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="collection-item d-flex justify-content-right">
                                <i class="fa-regular fa-message text-warning mr-4"></i>
                                <div class="mid-cn">
                                    <span class="email-title"> About</span>
                                    <p class="paragraph-agileits-w3layouts">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                                            </div>
                                            <textarea class="form-control" editable name="about" id="description" rows="2"><?=User::getAbout();?></textarea>
                                        </div>
                                    </p>
                                </div>
                            </li>
                            <li class="collection-item d-flex justify-content-right">
                                <i class="fa-regular fa-face-rolling-eyes text-info mr-4"></i>
                                <div class="mid-cn">
                                    <span class="email-title"> Roles</span>
                                    <p class="paragraph-agileits-w3layouts">
                                        <?php foreach( User::getRoles() as $role ):?>
                                            <h4 class="d-inline"><span class="badge badge-secondary"><?=$role['name']?></span></h4>
                                        <?php endforeach;?>
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- right-strip -->
                </div>
            </div>
        </div>
    </div>
</div>
