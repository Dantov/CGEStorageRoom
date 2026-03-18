<?php

use \yii\helpers\Url;

$session = yii::$app->session;
?>

    <!-- main-heading #f1f1f1 -->
    <h2 class="main-title-w3layouts mb-2 text-center text-white"><img src="/web/images/CGEicon2.png" height="70px" class=""> <span class="align-middle">CGE Storage Room</span></h2>
    <!--// main-heading -->
    <div class="form-body-w3-agile text-center w-lg-50 w-sm-75 w-100 mx-auto mt-5">
        <form id="auth_form" method="POST" action="/auth/login">
            <div class="form-group">
                <label>Login Name</label>
                <input type="text" name="login" class="form-control" style="color: #e6e6e6" placeholder="Enter email" required="">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="pass" class="form-control" style="color: #e6e6e6" placeholder="Password" required="">
            </div>
            <?php if ( $session->hasFlash('wrongData') ): ?>
                <div class="d-sm-flex justify-content-between">
                    <div class="forgot col-md-12 text-sm-center text-center">
                        <strong style="color: #f24329;"><?= $session->getFlash('wrongData') ?></strong>
                    </div>
                </div>
            <?php endif;?>
            <div class="d-sm-flex justify-content-between">
                <div class="form-check col-md-6 text-sm-left text-center">
                    <input type="checkbox" name="memeMe" class="form-check-input" id="memeMe">
                    <label class="form-check-label" for="memeMe">Remember me</label>
                </div>
                <div class="forgot col-md-6 text-sm-right text-center">
                    <a href="/">forgot password?</a>
                </div>
            </div>
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
            <button type="submit" name="submit" class="btn btn-primary error-w3l-btn mt-sm-5 mt-3 px-4">Login</button>
        </form>
        <p class="paragraph-agileits-w3layouts mt-4">Don't have an account?
            <a href="">Then contact with administrator please.</a>
        </p>
        <h1 class="paragraph-agileits-w3layouts mt-2">
            <a href="<?=Url::to(['/google.com/'])?>">Back to Internet</a>
        </h1>
    </div>

    <!-- Copyright -->
    <div class="copyright py-xl-3 py-2 mt-xl-5 mt-4 text-center">
        <p>© <?=date('Y')?> CGE Storage Room.  Design by Vadym Bykov</p>
    </div>
    <!--// Copyright -->