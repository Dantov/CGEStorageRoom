<?php
/** array $material */
?>
<div class="form-row material_row">
    <div class="form-group col-xs-12 col-md-3">
        <label for="part">Деталь</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" value="<?=$material['part']?>" name="part" data-table="tableMats" data-rowID="<?=$material['id']?>" aria-label="">
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
    <div class="form-group col-xs-12 col-md-3">
        <label for="metal">Металл</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" value="<?=$material['metal']?>" name="metal" data-table="tableMats" data-rowID="<?=$material['id']?>" aria-label="">
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
    <div class="form-group col-xs-12 col-md-3">
        <label for="color">Цвет</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" value="<?=$material['color']?>" name="color" data-table="tableMats" data-rowID="<?=$material['id']?>" aria-label="">
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
    <div class="form-group col-xs-12 col-md-2">
        <label for="probe">Проба</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" value="<?=$material['probe']?>" name="probe" data-table="tableMats" data-rowID="<?=$material['id']?>" aria-label="">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu p-1">
                    <?php foreach ($sevData['metal_probe'] as $key => $value): ?>
                    <a class="dropdown-item cursorPointer p-1" elemToAdd><?php echo $value['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-1">
        <label for="probe"></label>
        <div class="input-group">
            <div class="input-group-append">
                <button type="button" class="btn btn-light" data-table="tableMats" data-rowID="<?=$material['id']?>" onclick="duplicateRow(this)"><i class="fa-solid fa-copy"></i></button>
                <button type="button" class="btn btn-light" data-table="tableMats" data-rowID="<?=$material['id']?>" onclick="deleteRow(this)"><i class="fa-solid fa-trash-can"></i></button>
            </div>
        </div>
    </div>
</div>