<?php
use yii\helpers\Url;
?>
<!--// PROTO GEM ROW -->
<div class="form-row gemsProtoRow d-none">
    <div class="form-group col-md-3">
        <label for="gems_names">Сырье</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" data-table="" data-rowID="" name="name" aria-label="">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu p-1">
                    <?php foreach ($sevData['gems_names'] as $key => $value): ?>
                    <a class="dropdown-item p-1" style="cursor: pointer;" elemToAdd><?php echo $value['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-2">
        <label for="gems_cut">Огранка</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" data-table="" data-rowID="" name="cut" aria-label="">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu p-1">
                    <?php foreach ($sevData['gems_cut'] as $key => $value): ?>
                    <a class="dropdown-item p-1" style="cursor: pointer;" elemToAdd><?php echo $value['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-2">
        <label for="gems_sizes">Ø Размеры мм</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" data-table="" data-rowID="" name="size" aria-label="">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu p-1">
                    <?php foreach ($sevData['gems_sizes'] as $key => $sizes): ?>
                    <a class="dropdown-item p-1" style="cursor: pointer;" elemToAdd><?php echo $sizes['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-3">
        <label for="gems_color">Цвет</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" data-table="" data-rowID="" name="color" aria-label="">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu p-1">
                    <?php foreach ($sevData['gems_color'] as $key => $value): ?>
                    <a class="dropdown-item p-1" style="cursor: pointer;" elemToAdd><?php echo $value['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-1">
        <label for="gems_value">Кол-во</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="number" editable class="form-control" data-table="" data-rowID="" name="value" aria-label="">
        </div>
    </div>
    <div class="form-group col-md-1">
        <label for="probe"></label>
        <div class="input-group">
            <div class="input-group-append">
                <button type="button" class="btn btn-light" data-table="" data-rowID="" onclick="duplicateRow(this)"><i class="fa-solid fa-copy"></i></button>
                <button type="button" class="btn btn-light" data-table="" data-rowID="" onclick="deleteRow(this)"><i class="fa-solid fa-trash-can"></i></button>
            </div>
        </div>
    </div>
</div>

<!--// PROTO MATERIALS -->
<div class="form-row matsProtoRow d-none">
    <div class="form-group col-md-3">
        <label for="part">Деталь</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" name="part" data-table="" data-rowID="" aria-label="">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu p-1">
                    <?php foreach ($sevData['model_type'] as $key => $value): ?>
                    <a class="dropdown-item p-1" style="cursor: pointer;" elemToAdd><?php echo $value['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-3">
        <label for="metal">Металл</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" name="metal" data-table="" data-rowID="" aria-label="">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu p-1">
                    <?php foreach ($sevData['model_material'] as $key => $value): ?>
                    <a class="dropdown-item p-1" style="cursor: pointer;" elemToAdd><?php echo $value['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-3">
        <label for="color">Цвет</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" name="color" data-table="" data-rowID="" aria-label="">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu p-1">
                    <?php foreach ($sevData['metal_color'] as $key => $value): ?>
                    <a class="dropdown-item p-1" style="cursor: pointer;" elemToAdd><?php echo $value['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-2">
        <label for="probe">Проба</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" name="probe" data-table="" data-rowID="" aria-label="">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu p-1">
                    <?php foreach ($sevData['metal_probe'] as $key => $mvalue): ?>
                    <a class="dropdown-item p-1" style="cursor: pointer;" elemToAdd><?php echo $mvalue['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-1">
        <label for="probe"></label>
        <div class="input-group">
            <div class="input-group-append">
                <button type="button" class="btn btn-light" data-table="" data-rowID="" onclick="duplicateRow(this)"><i class="fa-solid fa-copy"></i></button>
                <button type="button" class="btn btn-light" data-table="" data-rowID="" onclick="deleteRow(this)"><i class="fa-solid fa-trash-can"></i></button>
            </div>
        </div>
    </div>
</div>

<!--// PROTO IMAGES -->
<div class="card bg-light mb-1 mainCard d-none" id="proto_image_row" style="width: 10rem;">
    <div class="card-header p-0 text-center bg-dark text-white cursorPointer">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="imgMainRadioOption" data-table="" data-rowID="" id="" value="">
          <label class="form-check-label" for="inlineRadio1"></label>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="ratio">
        <div class="ratio-inner ratio-4-3">
            <div class="ratio-content"><img src="" class="card-img-top" alt="..."></div>
            <a class="btn btn-info btn-sm editBtnMain img_dell" role="button" data-toggle="tooltip" data-placement="bottom" title="Редактировать"><i class="fa-solid fa-trash-can"></i></a>
        </div>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item p-1" style="font-size: small;">
            <small class="float-left img_name_show"></small>
            <div class="clearfix"></div>
        </li>
    </ul>
</div>
<!--// PROTO PRE-LOAD IMAGE FILE -->
<div class="progress d-none" id="proto-pre-load-img">
    <div class="progress-bar progress-bar-striped bg-success prog-bar-img-files" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
</div>

<!--// PROTO 3D -->
<a class="list-group-item media d-none justify-content-between align-items-center p-1" id="proto_3d_row"> <!-- d-flex -->
    <div class="contact-wdgt-left">
        <img src="" class="img-fluid imglable3dfile" style="width: 4rem;" alt="Responsive image">
    </div>
    <div class="media-body d-flex justify-content-between align-items-center">
        <div class="contact-wdgt-left">
            <div class="lg-item-heading pl-3 d3filename"></div>
            <small class="lg-item-text pl-3 overallSize"></small>
        </div>
        <div class="contact-wdgt-right">
            <div class="lg-item-heading">
                <button type="button" data-rowid="" class="btn btn-sm btn-outline-danger remove3dfile">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
    </div>
</a>
<!--// PROTO PRE-LOAD DATA FILE -->
<a class="list-group-item media d-none justify-content-between align-items-center p-1" id="proto-pre-load-data"> <!-- d-flex -->
    <div class="progress">
        <div class="progress-bar progress-bar-striped bg-primary prog-bar-data-files" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="media-body d-flex justify-content-between align-items-center">
        <div class="contact-wdgt-left">
            <div class="clearfix"></div>
            <div class="lg-item-heading pl-3 data-file-text-info"></div>
        </div>
    </div>
</a>

