<?php
/* @var $this yii\web\View */

use yii\helpers\{Url,Html};
use app\models\User;
use app\models\serviceClasses\Crypt;
$session = Yii::$app->session;

$this->params['breadcrumbs'][] = $this->title;
$tt=time();
$this->registerJsFile("@web/js/users/Users.js?v=$tt");
?>
<script class="d-none dellscript">
let appliedRights = [];
</script>
<div class="site-about">
     <code><?php //echo debug($allroles) ?></code>
     <code><?php //echo debug($clients) ?></code>
     <code><?php //echo debug($permissions,1,1) ?></code>
</div>
<div class="row validform">
    <div class="col-md-2 order-md-1 validform2"></div>
    <div class="col-md-8 order-md-1 validform2">
    <div class="outer-w3-agile mt-3"> 
        <h4 class="tittle-w3-agileits mb-4"><?= $this->title = 'Add New User' ?></h4> 
        <?php if( $session->hasFlash('saveErrors') ):?>
            <div class="alert alert-danger" role="alert"><?=$session->getFlash('saveErrors')?></div>
        <?php endif;?>
            <div class="col-md-12 order-md-1 validform2">
                <form action="<?=Url::to(['users/add/'])?>" method="post" class="needs-validation" novalidate="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address">Login</label>
                            <input type="text" class="form-control" min="6" name="logname" id="address" placeholder="">
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
                            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="" value="" required="">
                            <div class="invalid-feedback d-block">
                               <?= $session->getFlash('firstName')?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lastName">Last name</label>
                            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="" value="" required="">
                            <div class="invalid-feedback d-block">
                                <?= $session->getFlash('lastName')?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="thirdname">Third name</label>
                            <div class="input-group">
                                <input type="text" value="" name="thirdname" class="form-control" id="thirdname" placeholder="">
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
                            <input type="email" class="form-control" name="email" id="email" value="" placeholder="you@example.com">
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
                                <?php foreach ($clients??[] as $client): ?>
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
                            <textarea class="form-control" name="usernote" id="aboutControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-3 d-none">
                            <label for="allrights">All Righs:</label>
                            <select multiple="10" class="custom-select d-block w-100 cursorPointer" style="height: 15rem !important;" id="allrights">
                            </select>
                        </div>
                        <div class="col-md-12 mb-0 d-none">
                            <label>Applied Righs:</label>
                        </div>
                        <div class="col-md-12 appliedPermAll">
                        </div>
                    </div>
                    <hr>
                    <button class="btn btn-primary btn-lg btn-block error-w3l-btn" type="submit">Save</button>
                    <input type="hidden" value="" name="uid" id="uid" >
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-2 order-md-1 validform2"></div>
</div>