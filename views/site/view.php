<?php
use yii\helpers\Url;
use app\models\{User,Common};

$this->title = $model['number_3d'] . '-' . $model['model_type'];

$modelPath = Common::modelPath($model['client'],$model['id']);

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

$modelDeleted = ((int)$model['model_status']===2);
$modelNonPublished = ((int)$model['model_status']===0);
$modelPublished = ((int)$model['model_status']===1);

?>

<div class="row justify-content-center bg-light mb-2">
    <div class="col-sm-12">
        <?php if ( $modelPublished ):?>
            <?php if ( User::hasPermission('jewelbox') && !User::isAdmin() ):?>
            <div class="d-flex justify-content-between">
                <?php if ( User::hasFilesAccess($model['id'])  ):?>
                <button type="button" class="btn btn-success btn-lg btn-block mt-2">Можно скачать файлы</button>
                <?php elseif( $model['stored'] ):?>
                <button type="button" class="btn btn-info btn-lg btn-block mt-2">Модель уже в Шкатулке</button>
                <?php else:?>
                <button type="button" data-id="<?=$model['id']?>" class="btn btn-primary btn-lg btn-block mt-2 jewelboxBtnView">
                    <input class="addJBdata" type="hidden" data-img="/stock/<?=$modelPath?>/images/<?=$model['mainimage']?>" data-link="<?=Url::to(['site/view','id'=>$model['id'] ])?>" data-n3d="<?=$model['number_3d']?>" data-mtype="<?=$model['model_type']?>" data-client="<?=htmlentities($model['client'])?>">
                    Добавть Модель в Шкатулку
                </button>
                <?php endif;?>
            </div>
            <?php endif;?>
        <?php endif;?>
        <?php if ( $modelNonPublished ):?>
            <div class="d-flex justify-content-between">
                <button type="button" data-publish="pub" class="btn btn-success btn-lg btn-block mt-2">Модель не опубликована</button>
            </div>
        <?php endif;?>
        <?php if ( $modelDeleted ):?>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-danger btn-lg btn-block mt-2">Модель была удалена!</button>
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
        <?php if ( User::hasPermission('downloadfiles') || User::hasFilesAccess($model['id']) ): ?>
        <div class="row">
            <div class="col-12">
            <?php require "includes/view/datafiles.php"?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-sm-12 col-md-6 bg-light position-relative" id="descriptions">
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-user-tie" data-toggle="tooltip" data-placement="top" title="Заказчик"></i>
                <span class="d-none d-lg-inline">Заказчик:</span>
            </div>
            <div class="p-1 bg-light" id="client">
                <b><i>
                    <a class="text-primary" href="<?=Url::to(['search/select','by'=>'client','v'=>$model['clientID']??0 ])?>" id="collection"><?=$model['client']?>
                    </a>
                </i></b>
            </div>
        </div>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-asterisk" data-toggle="tooltip" data-placement="top" title="Номер модели"></i>
                <span class="d-none d-lg-inline">№3D:</span>
            </div>
            <div class="p-1 bg-light" id="num3d"><b><?=$model['number_3d']?></b></div>
        </div>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-regular fa-calendar" data-toggle="tooltip" data-placement="top" title="Дата создания 3Д"></i>
                <span class="d-none d-lg-inline">Дата создания 3Д:</span>
            </div>
            <div class="p-1 bg-light" id="create_date"><b><?=formatDate($model['create_date'])?></b></div>
        </div>
        
        <?php if ( isset($model['author']) ):?>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fas fa-user-edit" data-toggle="tooltip" data-placement="top" title="Автор"></i>
                <span class="d-none d-lg-inline">Автор:</span>
            </div>
            <div class="p-1 bg-light" id="author"><b></b></div>
        </div>
        <?php endif;?>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fas fa-user-cog" data-toggle="tooltip" data-placement="top" title="3D модельер"></i>
                <span class="d-none d-lg-inline">3D модельер</span>
            </div>
            <div class="p-1 bg-light" id="modeller3d"><b><?=$model['modeller3d']?></b></div>
        </div>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="far fa-eye" data-toggle="tooltip" data-placement="top" title="Вид модели"></i>
                <span class="d-none d-lg-inline">Вид модели</span>
            </div>
            <div class="p-1 bg-light" id="modelType"><b><?=$model['model_type']?></b></div>
        </div>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fas fa-balance-scale-left"></i>
                <span class="d-none d-lg-inline">Вес в 3D</span>
            </div>
            <div class="p-1 bg-light">
                <b><?=$model['model_weight']?> гр.</b>
            </div>
        </div>
        <?php if (User::hasPermission('model_price')): ?>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-hand-holding-dollar"></i>
                <span class="d-none d-lg-inline">Стоимость 3Д</span>
            </div>
            <div class="p-1 bg-light">
                <b><span><?=$model['model_cost']?></span></b>
            </div>
        </div>
        <?php endif;?>
        <div class="d-flex justify-content-between bg-dots fontsView">
            <div class="p-1 bg-light">
                <i class="fa-solid fa-ring"></i>
                <span class="d-none d-lg-inline">Размерный Ряд</span>
            </div>
            <div class="p-1 bg-light">
                <b>
                <?php foreach( $model['size_range'] as $sr ): ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary"><?=$sr?></button>
                <?php endforeach; ?>
                </b>
            </div>
        </div>
        <div class="d-none d-lg-block" id="notes">
            <div class="alert alert-light" role="alert">
                <h5 class="alert-heading"><i class="fas fa-comment-alt"></i> Примечания:</h5>
                <p><?=$model['description']?></p>
            </div>
        </div>
        <?php if ( count($model['hashtags']) ):?>
        <hr>
        <div class=""><i class="fa-solid fa-hashtag"></i><b>Хештеги:</b></div>
        <div class="d-flex justify-content-left ">
            <div class="">
            <?php foreach( $model['hashtags'] as $htag ): ?>
                <span class="badge badge-<?=$model['hashtags_colors'][random_int(0,6)]?> p-2 mb-1"><i class="fas fa-tag"></i> <?=$htag?></span>
            <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <hr>
        <div class="d-none d-lg-block">
            <?php require "includes/view/gems.php"?>
        </div>
        <div class="d-none d-lg-block">
            <?php require "includes/view/materials.php"?>
        </div>
    </div>
</div>

<div class="row bg-light mb-2 d-lg-none pt-1" id="tablesSM">
    <div class="col-12">
        <?php require "includes/view/gems.php"?>
    </div>
    <div class="col-12">
        <?php require "includes/view/materials.php"?>
    </div>
</div>

<div class="row d-lg-none" id="notesSM">
    <div class="col">
        <div class="alert alert-light" role="alert">
            <h5 class="alert-heading"><i class="fas fa-comment-alt"></i> Примечания:</h5>
            <p><?=$model['description']?></p>
        </div>
    </div>
</div>

<div class="row bg-light pb-2 pt-2" id="bottomRow">
    <div class="col">
        <div class="float-left">
            <div class="input-group">
                <div class="input-group-prepend">
                    <a href="<?=Url::previous()?>" role="button" class="btn btn-outline-secondary">
                        <i class="fas fa-caret-left"></i>
                        <span>Назад</span>
                    </a>
                </div>
                <?php if ( $model['isEditBtn'] ): ?>
                <div class="input-group-append">
                    <a href="<?=Url::to(["site/edits", 'model'=>$model['id']])?>" role="button" class="btn btn-outline-info">
                        <i class="fas fa-pencil-alt"></i>
                        <span>Редактировать</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <small class="float-right p-2">
            <span title="Создатель">
                Добавил:&nbsp;<i><?=User::getUsernameByID($model['creator_id'])?> - </i>
            </span>
            <span title="Дата добавления в базу:">
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

