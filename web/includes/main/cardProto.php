<?php
use yii\helpers\Url;
use app\models\{User,Common};
$mStored = $model['stored']??false;
$bgcolor = 'secondary';
$mainTitle = "";
if ( $mStored ) {
    $bgcolor = 'primary';
    $mainTitle = "В Шкатулке";
}
if (User::hasFilesAccess($model['id'])) {
    $bgcolor = 'success';
    $mainTitle = "Доступны файлы";
}
$clientName = empty($model['client']) ? '<b class="text-muted">empty</b>' : htmlentities($model['client']);
?>
<div class="card bg-light mb-1 mainCard" data-toggle="tooltip" title="<?=$mainTitle?>" style="width: <?=$session->get('tilesControlSize')?>rem;">
    <div class="card-header p-1 cursorPointer text-truncate bg-<?=$bgcolor?> text-white text-center">
        <small data-toggle="tooltip" title="<?=htmlentities($model['client'])?>" data-placement="top"><?=$clientName;?></small>
        <div class="clearfix"></div>
    </div>
    <a href="<?=Url::to(['site/view','id'=>$model['id']])?>">
        <div class="ratio">
            <div class="ratio-inner ratio-4-3">
                <?php $imgname = isset($model['mainimgprev'])?$model['mainimgprev']:$model['mainimage'] ?>
                <?php $imgUrl = empty($imgname)?"/pictAssets/default.png":'/stock/'.Common::modelPath($model['client'],$model['id']).'/images/'.$imgname?>
                <div class="ratio-content card-main-image" style="background: url('<?=$imgUrl?>');"></div>
                <?php if ( $model['isEditBtn'] ): ?>
                <a class="btn btn-outline-secondary btn-sm editBtnMain border-0" href="<?=Url::to(['site/edits','model'=>$model['id'] ])?>" role="button" data-toggle="tooltip" data-placement="bottom" title="Редактировать">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                <?php endif; ?>
                <?php if ( User::hasPermission('jewelbox') && !$mStored && !User::isAdmin() ): ?>
                <button class="btn btn-primary btn-sm jewelboxBtnMain" role="button" data-id="<?=$model['id']?>" data-placement="bottom" title="Добавить в Шкатулку">
                    <input class="addJBdata" type="hidden" data-img="/stock/<?=Common::modelPath($model['client'],$model['id'])?>/images/<?=$imgname?>" data-link="<?=Url::to(['site/view','id'=>$model['id'] ])?>" data-n3d="<?=$model['number_3d']?>" data-mtype="<?=$model['model_type']?>" data-client="<?=htmlentities($model['client'])?>">
                    <i class="fa-solid fa-basket-shopping"></i>
                </button>
                <?php endif; ?>
                <?php if (User::hasFilesAccess($model['id'])): ?>
                    <span class="jewelboxOpened p-1 pr-2 pl-2 bg-success text-white"><i class="fa-solid fa-cube"></i></span>
                <?php endif; ?>                
            </div>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-1" style="font-size: small;">
                <small onclick="copyValueToClipBoard(this)" class="float-left text-truncate" data-toggle="tooltip" data-placement="top" title="Дата создания"><?=$main->dateConvert($model['create_date'])?></small>
                <small class="float-right"><b><?=$model['model_type']?></b></small>
                <small onclick = "copyValueToClipBoard(this)" class="float-right text-truncate" data-toggle="tooltip" data-placement="top" title="№3D"><?=$model['number_3d']?> |&nbsp;</small>
                <div class="clearfix"></div>
            </li>
        </ul>
    </a>
</div>