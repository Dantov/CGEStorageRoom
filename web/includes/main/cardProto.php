<?php
use yii\helpers\Url;
use app\models\{User,Common};
$mStored = $model['stored']??false;
$bgcolor = 'secondary';
$mainTitle = "";
if ( $mStored ) {
    $bgcolor = 'primary';
    $mainTitle = "In Box";
}
if (User::hasFilesAccess($model['id'])) {
    $bgcolor = 'success';
    $mainTitle = "access to files";
}
$clientName = empty($model['project']) ? '<b class="text-muted">empty</b>' : htmlentities($model['project']);
?>
<div class="card bg-light mb-1 mainCard" data-toggle="tooltip" title="<?=$mainTitle?>" style="width: <?=$session->get('tilesControlSize')?>rem;">
    <div class="card-header p-1 cursorPointer text-truncate bg-<?=$bgcolor?> text-white text-center">
        <small data-toggle="tooltip" title="<?=htmlentities($model['project'])?>" data-placement="top"><?=$clientName;?></small>
        <div class="clearfix"></div>
    </div>
    <a href="<?=Url::to(['site/view','id'=>$model['id']])?>">
        <div class="ratio">
            <div class="ratio-inner ratio-4-3">
                <?php $imgname = isset($model['mainimgprev'])?$model['mainimgprev']:$model['mainimage'] ?>
                <?php $imgUrl = empty($imgname)?"/pictAssets/default.png":'/stock/'.Common::modelPath($model['project'],$model['id']).'/images/'.$imgname?>
                <div class="ratio-content card-main-image" style="background: url('<?=$imgUrl?>');"></div>
                <?php if ( $model['isEditBtn'] ): ?>
                <a class="btn btn-outline-secondary btn-sm editBtnMain border-0" href="<?=Url::to(['site/edits','model'=>$model['id'] ])?>" role="button" data-toggle="tooltip" data-placement="bottom" title="Edit">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                <?php endif; ?>
                <?php if ( User::hasPermission('mybox') && !$mStored && !User::isAdmin() ): ?>
                <button class="btn btn-primary btn-sm jewelboxBtnMain" role="button" data-id="<?=$model['id']?>" data-placement="bottom" title="Add to Box">
                    <input class="addJBdata" type="hidden" data-img="/stock/<?=Common::modelPath($model['project'],$model['id'])?>/images/<?=$imgname?>" data-link="<?=Url::to(['site/view','id'=>$model['id'] ])?>" data-n3d="<?=$model['item_name']?>" data-mtype="<?=$model['item_category']?>" data-client="<?=htmlentities($model['project'])?>">
                    <i class="fa-solid fa-dolly"></i>
                </button>
                <?php endif; ?>
                <?php if (User::hasFilesAccess($model['id'])): ?>
                    <span class="jewelboxOpened p-1 pr-2 pl-2 bg-success text-white"><i class="fa-solid fa-cube"></i></span>
                <?php endif; ?>                
            </div>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-1" style="font-size: small;">
                <small class="float-right" data-toggle="tooltip" data-placement="top" title="Category"><b><?=$model['item_category']?></b></small>
                <small onclick = "copyValueToClipBoard(this)" class="float-right text-truncate" data-toggle="tooltip" data-placement="top" title="name"><?=$model['item_name']?> |&nbsp;</small>
                <div class="clearfix"></div>
            </li>
        </ul>
    </a>
</div>