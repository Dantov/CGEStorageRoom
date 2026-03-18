<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\User;
use app\models\serviceClasses\Crypt;
$session = Yii::$app->session;
$this->params['breadcrumbs'][] = $this->title;
$tt=time();
$this->registerJsFile("@web/js/users/Users.js?v=$tt");
$uClients = [];
?>
<script class="d-none dellscript">
let appliedRights = [];
<?php foreach ($uPermissions as $uPermission): ?>                                 
appliedRights.push({
permid: '<?=$uPermission['id']?>',
name: '<?=$uPermission['name']?>',
description: '<?=$uPermission['description']?>'
});
<?php endforeach; ?>
</script>
<div class="site-about"></div>
<div class="row validform">
    <div class="col-md-2 order-md-1 validform2"></div>
    <div class="col-md-8 order-md-1 validform2">
    <div class="input-group">
        <div class="input-group-prepend">
            <a href="<?=Url::previous()?>" role="button" class="btn btn-outline-secondary">
                <i class="fas fa-caret-left"></i>
                <span>Назад</span>
            </a>
        </div>
    </div>
    <div class="outer-w3-agile mt-3"> 
        <h4 class="tittle-w3-agileits mb-4"><?= $this->title = 'Edit User:' . $single['fio'] ?></h4> 
        <?php if( $session->hasFlash('allgood') ):?>
            <div class="alert alert-success" role="alert"><?=$session->getFlash('allgood')?></div>
        <?php endif;?>
        <?php if( $session->hasFlash('saveErrors') ):?>
            <div class="alert alert-danger" role="alert"><?=$session->getFlash('saveErrors')?></div>
        <?php endif;?>
            <div class="col-md-12 order-md-1 validform2">
                <form action="<?=Url::to(['users/edit-user/'])?>" method="post" class="needs-validation" novalidate="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address">Login</label>
                            <input type="text" class="form-control" min="6" max="25" value="<?=$single['login']?>" name="logname" id="address" placeholder="">
                            <div class="invalid-feedback d-block">
                                <?= $session->getFlash('logname')?>
                                <?= $session->getFlash('logexist')?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address2">Password</label>
                            <input type="password" class="form-control" min="8" name="bypass" id="address2" placeholder="">
                            <div class="invalid-feedback d-block">
                                <?= $session->getFlash('bypass')?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="firstName">Name</label>
                            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="" value="<?= $single['name'] ?>" required="">
                            <div class="invalid-feedback d-block">
                                <?= $session->getFlash('firstName')?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lastName">Last name</label>
                            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="" value="<?= $single['lastname'] ?>" required="">
                            <div class="invalid-feedback d-block">
                                <?= $session->getFlash('lastName')?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="thirdname">Third name</label>
                            <div class="input-group">
                                <input type="text" value="<?= $single['thirdname'] ?>" name="thirdname" class="form-control" id="thirdname" placeholder="">
                                <div class="invalid-feedback d-block">
                                    <?= $session->getFlash('thirdname')?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="email">Email
                                <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="email" class="form-control" name="email" id="email" value="<?= $single['email'] ?>" placeholder="you@example.com">
                            <div class="invalid-feedback d-block">
                                <?= $session->getFlash('email')?>
                            </div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="role">Roles:</label><br/>
                            <?php foreach( $allroles as $srole ): ?>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" name="role[]" <?=$srole['active']?"checked":""?> type="checkbox" id="<?=$srole['name']?>" value="<?=$srole['id']?>">
                                  <label class="form-check-label cursorPointer" for="<?=$srole['name']?>"><?=$srole['name']?></label>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3 form-group">
                            <label for="ClientsControlSelect">All Clients:</label>
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <?php foreach ($clients as $client): ?>
                                  <label class="btn btn-outline-primary <?=$client['active']?"active":""?> mb-1">
                                    <input type="checkbox" <?=$client['active']?"checked":""?> value="<?=$client['id']?>" name="clients[]" ><?=$client['name']?>
                                  </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="aboutControlTextarea1">Note</label>
                            <textarea class="form-control" name="usernote" id="aboutControlTextarea1" rows="3"><?=$single['about']?></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="allrights">All Righs:</label>
                            <select multiple="10" class="custom-select d-block w-100 cursorPointer" style="height: 15rem !important;" id="allrights">
                                <?php foreach( $permissions as $permission ): ?>
                                    <option class="rightOpt" data-applied="<?=$permission['applied']?>" data-permid="<?=$permission['id']?>" value="<?=$permission['name']?>"><?=$permission['description']?><?=$permission['applied']?'<span class="right-in-use"> - &#10094;&#10094;applied&#10095;&#10095;':"" ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-0">
                            <label>Applied Righs:</label>
                        </div>
                        <div class="col-md-12 appliedPermAll">
                            <?php foreach ($uPermissions as $uPermission): ?>
                                <div class="alert alert-info alert-dismissible fade show mb-1 appliedPerm" role="alert">
                                  <strong><?=$uPermission['name']?></strong> <?=$uPermission['description']?>
                                  <button type="button" class="close upid" data-upid="<?=$uPermission['id']?>" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <hr>
                    <button class="btn btn-primary btn-lg btn-block error-w3l-btn" type="submit">Save</button>
                    <input type="hidden" value="<?=Crypt::strEncode($single['id'])?>" name="uid" id="uid" >
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-2 order-md-1 validform2"></div>
</div>