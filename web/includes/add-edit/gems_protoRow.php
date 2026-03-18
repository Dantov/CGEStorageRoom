<div class="form-row">
    <div class="form-group col-md-3">
        <label for="gems_names">Сырье</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input type="text" editable class="form-control" data-table="tableGems" value="<?=$gem['name']?>" data-rowID="<?=$gem['id']?>" name="name" aria-label="">
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
            <input type="text" editable class="form-control" data-table="tableGems" value="<?=$gem['cut']?>" data-rowID="<?=$gem['id']?>" name="cut" aria-label="">
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
            <input type="text" editable class="form-control" data-table="tableGems" value="<?=$gem['size']?>" data-rowID="<?=$gem['id']?>" name="size" aria-label="">
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
            <input type="text" class="form-control" editable data-table="tableGems" value="<?=$gem['color']?>" data-rowID="<?=$gem['id']?>" name="color" aria-label="">
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
            <input type="number" editable class="form-control" value="<?=$gem['value']?>" data-table="tableGems" data-rowID="<?=$gem['id']?>" name="value" aria-label="">
        </div>
    </div>
    <div class="form-group col-md-1">
        <label for="probe"></label>
        <div class="input-group">
            <div class="input-group-append">
                <button type="button" class="btn btn-light" data-table="tableGems" data-rowID="<?=$gem['id']?>" onclick="duplicateRow(this)"><i class="fa-solid fa-copy"></i></button>
                <button type="button" class="btn btn-light" data-table="tableGems" data-rowID="<?=$gem['id']?>" onclick="deleteRow(this)"><i class="fa-solid fa-trash-can"></i></button>
            </div>
        </div>
    </div>
</div>