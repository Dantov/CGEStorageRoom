<?php

    switch ( $switchTableRow )
    {
        case "material":
            ?>
            <tr class="trTd protoRow d-none">
                <td>
                    <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this, this.options[selectedIndex]);" aria-label="">
                        <option>Выбрать...</option>
                        <option value="1">Серебро</option>
                        <option value="2">Золото</option>
                        <option value="3">Платина</option>
                        <option value="3">Керамика</option>
                        <option value="3">Бронза</option>
                        <option value="3">Сталь</option>
                    </select>
                </td>
                <td>
                    <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this, this.options[selectedIndex]);" aria-label="">
                        <option selected>&#176;</option>
                        <option value="1">925&#176;</option>
                        <option value="2">585&#176;</option>
                        <option value="3">750&#176;</option>
                    </select>
                </td>
                <td>
                    <input type="text" aria-label="" class="form-control form-control-sm">
                </td>
                <td>
                    <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this, this.options[selectedIndex]);" aria-label="">
                        <option>Выбрать...</option>
                        <option value="1">Серый</option>
                        <option value="2">Красный</option>
                        <option value="3">Желтый</option>
                        <option value="3">Розовый</option>
                        <option value="3">Белый</option>
                    </select>
                </td>
                <td class="text-right" style="width: 100px">
                    <button class="btn btn-sm btn-outline-info" onclick="duplicateRow(this)" title="Дублировать строку">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="deleteRow(this)" title="Удалить строку">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php
            break;
        case "covering":
            ?>
            <tr class="trTd protoRow d-none">
                <td>
                    <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this, this.options[selectedIndex]);" aria-label="">
                        <option selected>Выбрать...</option>
                        <option value="1">Родий</option>
                        <option value="2">Золочение</option>
                        <option value="3">Чернение</option>
                    </select>
                </td>
                <td>
                    <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this, this.options[selectedIndex]);" aria-label="">
                        <option selected>...</option>
                        <option value="1">Полное</option>
                        <option value="2">Частичное</option>
                        <option value="3">По крапанам</option>
                    </select>
                </td>
                <td>
                    <input type="text" aria-label="" class="form-control form-control-sm">
                </td>
                <td>
                    <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this, this.options[selectedIndex]);" aria-label="">
                        <option selected>Выбрать...</option>
                        <option value="2">Красный</option>
                        <option value="3">Желтый</option>
                        <option value="3">Чёрный</option>
                        <option value="3">Белый</option>
                    </select>
                </td>
                <td class="text-right" style="width: 100px">
                    <button class="btn btn-sm btn-outline-info" onclick="duplicateRow(this)" title="Дублировать строку">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="deleteRow(this)" title="Удалить строку">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php
            break;
        case "gems":
            ?>
            <tr class="trTd protoRow d-none">
                <td>
                    <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this)" aria-label="">
                        <option selected>...</option>
                        <option value="1">Ø1 mm</option>
                        <option value="2">Ø1.25 mm</option>
                        <option value="3">Ø1.5 mm</option>
                    </select>
                </td>
                <td>
                    <input step="1" type="number" aria-label="" class="form-control form-control-sm">
                </td>
                <td>
                    <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this)" aria-label="">
                        <option selected>...</option>
                        <option value="1">Круг</option>
                        <option value="2">Овал</option>
                        <option value="3">Квадрат</option>
                    </select>
                </td>
                <td>
                    <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this)" aria-label="">
                        <option selected>...</option>
                        <option value="1">Циркон</option>
                        <option value="2">Бриллиант</option>
                        <option value="3">Сапфир</option>
                        <option value="4">Рубин</option>
                    </select>
                </td>
                <td>
                    <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this)" aria-label="">
                        <option selected>...</option>
                        <option value="1">Белый</option>
                        <option value="2">Чёрный</option>
                        <option value="3">Синий</option>
                        <option value="3">Зелёный</option>
                    </select>
                </td>
                <td class="p-1 text-right" style="width: 100px">
                    <button class="btn btn-sm btn-outline-info" onclick="duplicateRow(this);" title="Дублировать строку">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="deleteRow(this);" title="Удолить строку">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php
            break;
        case "vc":
            ?>
        <tr class="trTd protoRow d-none">
            <td>
                <select class="custom-select custom-select-sm" data-table="123" data-id="1235" data-column="1234" onchange="selectChange(this)" aria-label="">
                    <option selected>...</option>
                    <option value="1">Швенза</option>
                    <option value="2">Закрутка</option>
                    <option value="3">Каст</option>
                    <option value="4">Накладка</option>
                </select>
            </td>
            <td>
                <input type="text" aria-label="" class="form-control form-control-sm">
            </td>
            <td>
                <input type="text" aria-label="" class="form-control form-control-sm">
            </td>
            <td class="p-1 text-right" style="width: 100px">
                <button class="btn btn-sm btn-outline-info" onclick="duplicateRow(this);" title="Дублировать строку">
                    <i class="fas fa-copy"></i>
                </button>
                <button class="btn btn-sm btn-outline-info" onclick="deleteRow(this);" title="Удолить строку">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        <?php
            break;
        case "repair":
            ?>
            <div class="collapse protoRepair d-none">
                <div class="card card-body">
                    <div class="font-weight-bolder">
                        Ремонт №<sapn class="repairNum">1</sapn>
                        <button type="button" class="close" aria-label="Close" onclick="removeRepairs(this);">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <textarea class="form-control repairs_descr" rows="3" name="repairs_descr[]"></textarea>
                </div>
            </div>
        <?php
            break;
        case "sizeRange":
        ?>
            <div class="tab-pane fade show protoTabPanel d-none" role="tabpanel">
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" title="Вес 3D Гр."><i class="fas fa-balance-scale"></i></span>
                    </div>
                    <input step="0.01" type="number" aria-label="" class="form-control form-control-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-left-0" title="Стоимость печати"><i class="fas fa-file-invoice-dollar"></i></span>
                    </div>
                    <input type="text" aria-label="" class="form-control form-control-sm">
                    <div class="input-group-prepend">
                        <span class="cursorArrow input-group-text border-left-0" title="Статус"><i class="fab fa-black-tie"></i></span>
                    </div>
                    <select class="custom-select custom-select-sm" id="" aria-label="">
                        <option selected>Выбрать Статус...</option>
                        <option value="1">В ремонте</option>
                        <option value="2">Выращено</option>
                        <option value="3">Готовая ММ</option>
                        <option value="4">В росте</option>
                        <option value="4">На проверке</option>
                    </select>
                </div>
                <div class="p-1 bg-info fontsView text-white Nit_gems">
                    <span class="float-left p-1"><i class="far fa-gem"></i> Вставки 3D:</span>
                    <button id="addMaterial" class="btn mr-1 btn-sm pl-2 pr-2 btn-light float-right add_Table_Row" title="Добавить">
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="clearfix"></div>
                </div>
                <table class="table text-muted table_gems">
                    <thead>
                    <tr class="trTd">
                        <th>Размер</th><th width="10%">Кол-во</th><th>Огранка</th><th>Сырьё</th><th>Цвет</th><th></th>
                    </tr>
                    </thead>
                    <tbody class="tbody" <?php $switchTableRow = "gems";?>>
                    <?php require "includes/edit/tableRows.php"?>
                    </tbody>
                </table>
            </div>
        <?php
            break;
    }
?>