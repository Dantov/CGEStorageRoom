<?php
use app\models\{User,Common};
?>
<?php $main_Img=false; if($image['status']==1) $main_Img=true; ?>
<div class="card bg-light mb-1 mr-1 mainCard" style="width: 10rem;">
    <div class="card-header p-0 text-center cursorPointer bg-<?=($main_Img)?"success":"dark"; ?> text-white">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="imgMainRadioOption" data-table="tableIMG" data-rowID="<?=$image['id']?>" id="<?=$image['name']?>" <?= $main_Img ? "checked" : ""; ?> value="" />
          <label class="form-check-label" for="<?=$image['name']?>"><?= $main_Img ? " Main":""; ?></label>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="ratio">
        <div class="ratio-inner ratio-4-3">
            <?php $imgname = isset($image['previmg'])?$image['previmg']:$image['name'] ?>
            <div class="ratio-content">
                <img src="/stock/<?=Common::modelPath($stockData['project'],$stockData['id'])?>/images/<?=$imgname?>" class="card-img-top" alt="...">
            </div>
            <a class="btn btn-info btn-sm editBtnMain img_dell" role="button" data-table="tableIMG" data-rowID="<?=$image['id']?>" data-toggle="tooltip" data-placement="bottom" title="Delete image"><i class="fa-solid fa-trash-can"></i></a>
        </div>
    </div>
</div>