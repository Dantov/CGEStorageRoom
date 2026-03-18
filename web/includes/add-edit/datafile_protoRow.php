<?php 
$datafileType = $datafile['type'];
?>
<a class="list-group-item media d-flex justify-content-between align-items-center p-1">
    <div class="contact-wdgt-left">
        <img src="/web/pictAssets/icon_<?=$datafileType?>.png" class="img-fluid imglable3dfile" style="width: 4rem;" alt="Responsive image">
    </div>
    <div class="media-body d-flex justify-content-between align-items-center">
        <div class="contact-wdgt-left">
            <div class="lg-item-heading pl-3 d3filename"><?=$datafile['name']?></div>
            <small class="lg-item-text pl-3 overallSize"><?=$datafile['size']?></small>
        </div>
        <div class="contact-wdgt-right">
            <div class="lg-item-heading">
                <button type="button" data-rowid="<?=$datafile['id']?>" data-filetype="<?=$datafileType?>" class="btn btn-sm btn-outline-danger remove3dfile">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
    </div>
</a>