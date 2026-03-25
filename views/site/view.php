<?php
use yii\helpers\Url;
use app\models\{User,Common};

$this->title = $model['item_name'] . '-' . $model['item_category'];

$modelPath = Common::modelPath($model['project'],$model['id']);

$tt=time();
$this->registerCssFile("@web/css/view/view.css?v=$tt");
$this->registerJsFile("@web/js/view/imageViewer_v2.js?v=$tt");
$imgEncode = json_encode($model['images'], JSON_UNESCAPED_UNICODE);
$imgJs = <<<JS
    window.addEventListener('load',function() {
      new ImageViewer($imgEncode,'$modelPath').init();
    }, false);
JS;
$this->registerJs($imgJs);

$quantity = $model['item_quantity'] > 0;
$reserved = $model['reserv_user_id']??false;

$modelDeleted = ((int)$model['item_status']===2);
$modelNonPublished = ((int)$model['item_status']===0);
$modelPublished = ((int)$model['item_status']===1);
?>

<div class="row justify-content-center bg-light mb-2">
    <div class="col-sm-12 pl-0">
        <?php if ( $modelPublished ):?>

            <?php if ( User::hasPermission('mybox') && !User::isAdmin() ):?>
            <div class="d-flex row justify-content-between">

                <?php if( $model['stored'] ):?>
                <div class="col-sm-4">
                    <div class="alert alert-primary mb-0" role="alert"><b>This item is in your box.</b></div>
                </div>
                <div class="col-sm-8">
                    <button type="button" data-id="<?=$model['id']?>" class="btn btn-info btn-lg btn-block putback">
                        Put Back
                    </button>
                </div>
                <?php endif;?>

                <?php if( $quantity && !$model['stored'] ):?>
                <button type="button" data-id="<?=$model['id']?>" class="btn btn-primary btn-lg btn-block mt-2 jewelboxBtnView">
                    <input class="addJBdata" type="hidden" data-img="/stock/<?=$modelPath?>/images/<?=$model['mainimage']?>" data-link="<?=Url::to(['site/view','id'=>$model['id'] ])?>" data-n3d="<?=$model['item_name']?>" data-mtype="<?=$model['item_category']?>" data-client="<?=htmlentities($model['project'])?>">
                    Add item to My Box
                </button>
                <?php endif;?>
                
            </div>
            <?php endif;?>

        <?php endif;?>
        <?php if ( $modelNonPublished ):?>
            <div class="d-flex justify-content-between">
                <button type="button" data-publish="pub" class="btn btn-success btn-lg btn-block mt-2">Item is non Published yet</button>
            </div>
        <?php endif;?>
        <?php if ( $modelDeleted ):?>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-danger btn-lg btn-block mt-2">Item was deleted!</button>
            </div>
        <?php endif;?>
    </div>
</div>


<div class="row justify-content-center mb-2">
    <div class="col-sm-12 col-md-6 bg-light pr-0" id="images_block">
        <div class="row">
            <div class="d-none d-sm-block col-sm-12 p-0 mb-2" id="mainImage">
                <?php $imgUrl = empty($model['mainimage'])?"/pictAssets/default.png":'/stock/'.$modelPath.'/images/'.$model['mainimage']?>
                <div class="mainImage" data-posid="<?=$model['id']?>" data-id="<?=$model['mainimageID']?>" data-name="<?=$model['mainimage']?>" style="background-image: url(<?=$imgUrl?>);"></div>
            </div>
            <div class="col-12 pl-0">
                <div class="row p-0 m-0 dopImages" id="bottomDopImages">
                <?php foreach( $model['images'] as $image ): ?>
                    <div class="col-12 col-sm-6 col-md-3 p-0">
                        <div class="ratio border border-<?=$image['status']?"primary":"light"?> cursorPointer">
                            <div class="ratio-inner ratio-4-3">
                                <?php $imgname = isset($image['previmg'])?$image['previmg']:$image['name'] ?>
                                <div class="ratio-content">
                                    <img class="imageSmall <?=$image['status']?" activeImage":""?>" data-posid="<?=$image['pos_id']?>" data-id="<?=$image['id']?>" src="/stock/<?=$modelPath?>/images/<?=$imgname?>" width="100%" height="100%" style="object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 bg-light position-relative" id="descriptions">
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-brands fa-r-project" data-toggle="tooltip" data-placement="top" title="Project"></i>
                <span class="d-none d-lg-inline">Project:</span>
            </div>
            <div class="p-1 bg-light" id="project">
                <b>
                    <i><a class="text-primary" href="<?=Url::to(['search/select','by'=>'project','v'=>$model['projectID']??0 ])?>" id="collection"><?=$model['project']?>
                    </a></i>
                </b>
            </div>
        </div>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-align-justify" data-toggle="tooltip" data-placement="top" title="Name"></i>
                <span class="d-none d-lg-inline">Name:</span>
            </div>
            <div class="p-1 bg-light" id="num3d"><b><?=$model['item_name']?></b></div>
        </div>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-regular fa-calendar" data-toggle="tooltip" data-placement="top" title="Item receipt date"></i>
                <span class="d-none d-lg-inline">Item receipt date:</span>
            </div>
            <div class="p-1 bg-light" id="create_date"><b><?=formatDate($model['create_date'])?></b></div>
        </div>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-layer-group" data-toggle="tooltip" data-placement="top" title="Item category"></i>
                <span class="d-none d-lg-inline">Item category</span>
            </div>
            <div class="p-1 bg-light" id="modeller3d"><b><?=$model['item_category']?></b></div>
        </div>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-arrow-up-1-9" data-toggle="tooltip" data-placement="top" title="Quantity"></i>
                <span class="d-none d-lg-inline">Quantity</span>
            </div>
            <div class="p-1 bg-light" id="modelType"><b><?=$model['item_quantity']?></b></div>
        </div>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-ruler-combined"></i>
                <span class="d-none d-lg-inline">Item size mm</span>
            </div>
            <div class="p-1 bg-light">
                <b><?=$model['item_size']?></b>
            </div>
        </div>
        <?php if (User::hasPermission('model_price')): ?>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-euro-sign"></i>
                <span class="d-none d-lg-inline">Price</span>
            </div>
            <div class="p-1 bg-light">
                <b><span><?=$model['item_price']?></span></b>
            </div>
        </div>
        <?php endif;?>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-euro-sign"></i>
                <span class="d-none d-lg-inline">Price for rent</span>
            </div>
            <div class="p-1 bg-light">
                <b><span><?=$model['item_price_rent']?></span></b>
            </div>
        </div>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-house"></i>
                <span class="d-none d-lg-inline">Storage Room / Shelf Number</span>
            </div>
            <div class="p-1 bg-light">
                <b><span><?=$model['storageroom']?> / <?=$model['shelfnum']?></span></b>
            </div>
        </div>
        <div class="d-none d-lg-block" id="notes">
            <div class="alert alert-light" role="alert">
                <h5 class="alert-heading"><i class="fas fa-comment-alt"></i> Description:</h5>
                <p><?=$model['description']?></p>
            </div>
        </div>
        <?php if ( count($model['hashtags']) ):?>
        <hr>
        <div class=""><i class="fa-solid fa-hashtag"></i><b>HashTags:</b></div>
        <div class="d-flex justify-content-left ">
            <div class="">
            <?php foreach( $model['hashtags'] as $htag ): ?>
                <span class="badge badge-<?=$model['hashtags_colors'][random_int(0,6)]?> p-2 mb-1"><i class="fas fa-tag"></i> <?=$htag?></span>
            <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row bg-light pb-2 pt-2" id="bottomRow">
    <div class="col">
        <div class="float-left">
            <div class="input-group">
                <div class="input-group-prepend">
                    <a href="<?=Url::previous()?>" role="button" class="btn btn-outline-secondary">
                        <i class="fas fa-caret-left"></i>
                        <span>Back</span>
                    </a>
                </div>
                <?php if ( $model['isEditBtn'] ): ?>
                <div class="input-group-append">
                    <a href="<?=Url::to(["site/edits", 'model'=>$model['id']])?>" role="button" class="btn btn-outline-info">
                        <i class="fas fa-pencil-alt"></i>
                        <span>Edit item info</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <small class="float-right p-2">
            <span title="creator">
                Added by:&nbsp;<i><?=User::getUsernameByID($model['creator_id'])?> - </i>
            </span>
            <span title="added date:">
                <i><?=$mv->dateConvert($model['date'])?></i>
                <i class="fas fa-calendar-alt"></i>
            </span>
        </small>
    </div>
    <div class="clearfix"></div>
</div>
<?php require 'includes/view/imageWrapper.php'; ?>

<div class="modal fade" id="imageViewer" data-id="<?=$model['id']?>" style="height: 100%; width: 100%;" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered m-auto" style="height: 100%; max-width: 100%">
        <div class="modal-content p-0 m-0 imageViewer bg-transparent rounded-0">
            <div class="d-flex flex-row-reverse flex-row">
                <div class="p-2 pl-3 pr-3 bd-highlight rightPanel text-info closeImageViewer" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <div class="p-2 pl-3 pr-3 bd-highlight text-info sizePlus rightPanel">
                    <i class="fas fa-search-plus"></i>
                </div>
                <div class="p-2 pl-3 pr-3 bd-highlight text-info sizeMinus rightPanel">
                    <i class="fas fa-search-minus"></i>
                </div>
            </div>
            <div class="d-flex flex-row bottomImgRow cursorPointer">
            </div>
        </div>
    </div>
</div>

