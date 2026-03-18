<?php
use yii\helpers\Url;
$this->title = 'Редактировать :: 0006788-Кольцо';
$tt = time();
$this->registerCssFile("@web/css/edit/edit.css?v=$tt");
$this->registerJsFile("@web/js/edit/edit.js?v=$tt");
$this->registerJsFile("@web/js/edit/request.js?v=$tt");
$this->registerJsFile("@web/js/edit/+-.js?v=$tt");
?>

<script>
    let userName = 'EditUser';

    function sendMess()
    {
        $.ajax({
            type: 'GET',
            url: 'index.php?r=site/edit', //путь к скрипту, который обрабатывает задачу
            data: {
                send:true,
                userName: userName, // Кому отправляем
                toUser: 'AllUsers',
            },
            dataType:"json",
            success:function(data) {  //функция обратного вызова, выполняется в случае успешной отработки скрипта

            }
        });
    }
</script>
<script src="<?= "../js/webSocketConnect.js?ver=".time() ?>"></script>

<div class="row justify-content-left">
	<button type="button" class="btn btn-success" onclick="sendMess()">Send</button>
</div>
<!-- заголовок -->
<div class="row bg-white shadowBoxEdit mb-2">
    <div class="col">
        <div class="float-right">
            <div class="btn-group btn-group-sm p-1">
                <a href="<?=Url::previous()?>" role="button" class="btn btn-outline-secondary">
                    <i class="fas fa-caret-left"></i>
                    <span>Назад</span>
                </a>
                <a href="<?=Url::to(["site/view", 'id'=>56])?>" role="button" class="btn btn-outline-info">
                    <i class="fas fa-eye"></i>
                    <span>Просмотр</span>
                </a>
                <a href="<?=Url::to(["site/view", 'id'=>56, 'component'=>3])?>" role="button" class="btn btn-outline-info">
                    <i class="far fa-plus-square"></i>
                    <span>Добавить комплект</span>
                </a>
                <button class="btn btn-outline-danger <?=$shownEdit;?> <?=$PDO_hide;?>" value="Input" onclick="dell_fromServ(<?=$id;?>, false, false, 1);">
                    <i class="far fa-trash-alt"></i> Удалить
                </button>
            </div>
        </div>
        <span class="float-left p-2">
            <span title="Редактировать Модель" data-toggle="tooltip" data-placement="top" class="cursorArrow">
                <i class="fas fa-pencil-alt"></i> 0006766 - Серьги (В Комплекте: <i>Кольцо</i>)
            </span>
        </span>
    </div>
    <div class="clearfix"></div>
</div>
<!-- конец заголовка -->

<!--MAIN FORM-->
<div class="row bg-white shadowBoxEdit pt-3 ">

    <div class="col-12">
        <ul class="nav nav-tabs nav-justified" id="editTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-controls="base" aria-selected="true">Основная информация</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="gems-tab" data-toggle="tab" href="#gems" role="tab" aria-controls="gems" aria-selected="false">Вставки / Ссылки</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="dopData-tab" data-toggle="tab" href="#dopData" role="tab" aria-controls="dopData" aria-selected="false">Доп.Инфо / Файлы / Ремонты</a>
            </li>
        </ul>

        <div class="tab-content" id="editTabContent">

            <div class="tab-pane fade show active" id="base" role="tabpanel" aria-labelledby="base-tab">
                <div class="row mt-2">
                    <div class="col-sm-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="cursorArrow input-group-text" title="По нему формируются комплекты. '000' вводить не обязательно.">№3D</span>
                            </div>
                            <input type="text" aria-label="" class="form-control">
                            <div class="input-group-prepend">
                                <span class="cursorArrow input-group-text border-left-0" title="Добавляется во все изделия в комплекте (если там было пусто)">Арт.</span>
                            </div>
                            <input type="text" aria-label="Last name" class="form-control">
                            <div class="input-group-prepend">
                                <span class="input-group-text border-left-0"><i class="far fa-gem"></i></span>
                            </div>
                            <select class="custom-select" id="inputGroupSelect04" aria-label="Example select with button addon">
                                <option selected>Коллекция...</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12 mb-2 <?=$PDO_hide;?>">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" title="Автор"><i class="fas fa-user-tie"></i></span>
                            </div>
                            <select class="custom-select" id="inputGroupSelect04" aria-label="">
                                <option selected>Автор...</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <div class="input-group-prepend">
                                <span class="input-group-text border-left-0" title="3Д модельер"><i class="fas fa-user-cog"></i></span>
                            </div>
                            <select class="custom-select" id="inputGroupSelect04" aria-label="Example select with button addon">
                                <option selected>3D Модельер...</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <div class="input-group-prepend">
                                <span class="input-group-text border-left-0" title="Вид модели"><i class="far fa-eye"></i></span>
                            </div>
                            <select class="custom-select" id="modelTypeSelect" aria-label="Example select with button addon">
                                <option selected>Вид Модели...</option>
                                <option value="1">Кольцо</option>
                                <option value="2">Серьги</option>
                                <option value="3">Кулон</option>
                                <option value="3">Браслет</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12 mb-2 ringHide">
                        <div class="input-group">
                            <!-- Веса может не быть, зависит от типа модели
                             в кольцах вес указывается вместе с размерами-->
                            <div class="input-group-prepend">
                                <span class="input-group-text border-left-0" title="Вес 3D Гр."><i class="fas fa-balance-scale"></i></span>
                            </div>
                            <input step="0.05" type="number" aria-label="" class="form-control">
                            <div class="input-group-prepend">
                                <span class="input-group-text border-left-0" title="Стоимость печати"><i class="fas fa-file-invoice-dollar"></i></span>
                            </div>
                            <input type="text" aria-label="" class="form-control">
                            <div class="input-group-prepend">
                                <span class="cursorArrow input-group-text border-left-0" title="Статус"><i class="fab fa-black-tie"></i></span>
                            </div>
                            <select class="custom-select" id="" aria-label="">
                                <option selected>Выбрать Статус...</option>
                                <option value="1">В ремонте</option>
                                <option value="2">Выращено</option>
                                <option value="3">Готовая ММ</option>
                                <option value="4">В росте</option>
                                <option value="4">На проверке</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-6 pr-lg-0 border-right">
                        <div class="p-1 pl-2 bg-info fontsView text-white Nit_gems">
                            <span class="float-left p-1"><i class="fas fa-cubes"></i> Материал изделия:</span>
                            <button class="btn mr-1 btn-sm pl-2 pr-2 btn-light float-right add_Table_Row" title="Добавить">
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="clearfix"></div>
                        </div>
                        <table class="table text-muted table_metal">
                            <thead>
                            <tr>
                                <th class="p-1">Тип</th><th class="p-1">Проба</th><th class="p-1">Деталь</th><th class="p-1">Цвет</th><th class="p-1"></th>
                            </tr>
                            </thead>
                            <tbody class="tbody" <?php $switchTableRow = "material";?> >
                            <?php require "includes/edit/tableRows.php"?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 col-lg-6 pl-lg-0">
                        <div class="p-1 pl-2 bg-info fontsView text-white Nit_gems">
                            <span class="float-left p-1"><i class="fas fa-cube fasL"></i> Покрытия:</span>
                            <button class="btn mr-1 btn-sm pl-2 pr-2 btn-light float-right add_Table_Row" title="Добавить">
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="clearfix"></div>
                        </div>
                        <table class="table text-muted table_covering">
                            <thead>
                            <tr>
                                <th class="p-1">Тип</th><th class="p-1">Покрытие</th><th class="p-1">Части</th><th class="p-1">Цвет</th><th class="p-1"></th>
                            </tr>
                            </thead>
                            <tbody class="tbody" <?php $switchTableRow = "covering";?>>
                            <?php require "includes/edit/tableRows.php"?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="row mb-2 <?=$PDO_hide;?>" id="picts">

                    <div class="col-12">
                        <div id="drop-area" title="Загрузить картинку">
                            <p>Загрузить картинки можно перетащив их в эту область</p>
                            <input type="file" id="fileElem" multiple accept="image/*" onchange="handleFiles(this.files)">
                            <label class="button" for="fileElem"><i class="far fa-images"></i> Выбрать изображения</label>
                        </div>
                    </div>

                    <!-- Proto Img Row -->
                    <div class="col-6 col-sm-3 col-lg-2 mb-1 image_row d-none border-right" id="proto_image_row">
                        <button type="button" class="close mr-1" onclick="removeImg(this);" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="ratio border border-light bg-light">
                            <div class="ratio-inner ratio-4-3">
                                <div class="ratio-content">
                                    <img src="" width="100%" class="img-responsive "/>
                                </div>
                            </div>
                        </div>
                        <div class="img_inputs">
                            <select name="imgFor[]" class="custom-select custom-select-sm" aria-label="">
                                <option selected value="1" title="Будет видно на странице показа модели." data-imgFor="0">Нет</option>
                                <option value="1" title="Будет помещено на главной странице, в паспорте и бегунке, а так же в коллекции." data-imgFor="1">Главная</option>
                                <option value="2" title="Будет помещено в паспорте и бегунке." data-imgFor="2">Эскиз</option>
                                <option value="3" title="Будет помещено в паспорте." data-imgFor="3">На теле</option>
                                <option value="4" title="Будет печатать в коллекции как доп. картинка." data-imgFor="4">Деталировка</option>
                                <option value="5" title="Будет помещено в бегунке на последней странице." data-imgFor="5">Схема сборки</option>
                            </select>
                        </div>
                    </div>
                    <!-- //Proto Img Row -->
                </div><!--Picts row-->
                <div class="row mb-2">
                    <div class="col-12 mb-2">
                        <div class="d-flex flex-row justify-content-center bd-highlight mb-2">
                            <div class="p-1">
                                <p class="mb-1 font-weight-bold"><i class="fas fa-tags"></i> Метки:</p>
                                <div class="btn-group btn-group-toggle btnLabels" data-toggle="buttons">
                                    <label class="btn btn-outline-warning text-danger cursorPointer">
                                        <input type="checkbox" autocomplete="off"><i class="fas fa-tag"></i> Срочное!
                                    </label>
                                    <label class="btn btn-outline-primary cursorPointer">
                                        <input type="checkbox" autocomplete="off"><i class="fas fa-tag"></i> Литьё с каммнями
                                    </label>
                                    <label class="btn btn-outline-danger cursorPointer">
                                        <input type="checkbox" autocomplete="off"><i class="fas fa-tag"></i> Эксперимент
                                    </label>
                                    <label class="btn btn-outline-info cursorPointer">
                                        <input type="checkbox" autocomplete="off"><i class="fas fa-tag"></i> Бриллианты
                                    </label>
                                    <label class="btn btn-outline-success cursorPointer">
                                        <input type="checkbox" autocomplete="off"><i class="fas fa-tag"></i> Эксклюзив
                                    </label>
                                    <label class="btn btn-outline-secondary cursorPointer">
                                        <input type="checkbox" autocomplete="off"><i class="fas fa-tag"></i> Ремонт
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <div class="tab-pane fade" id="gems" role="tabpanel" aria-labelledby="gems-tab">
                    <div class="row mt-2">
                        <!-- Размеры -->
                        <div class="col-12 bg-light d-none ringShow">
                            <nav class="mt-1 mb-1">
                                <div class="nav nav-pills fontsView" id="size-range-tab" role="tablist">
                                    <button id="dellSizeRange" class="btn mr-1 btn-sm pl-3 pr-3 btn-light" title="Удалить Размер">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button class="btn mr-1 btn-sm pl-3 pr-3 btn-light" title="Добавить Размер" data-toggle="modal" data-target="#sizeRangeModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <sapn class="p-1 mr-1"><b>Размерный Ряд: </b></sapn>
                                </div>
                            </nav>
                            <div class="tab-content" id="size-range-tabContent">
                                <?php
                                $switchTableRow = "sizeRange";
                                require "includes/edit/tableRows.php";
                                ?>
                            </div>

                            <!-- size Range Modal -->
                            <div class="modal fade" id="sizeRangeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fab fa-quinscape"></i> Выбрать размерный ряд:</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="btn-group-toggle text-justify" data-toggle="buttons">
                                                <?php
                                                $size = 12;
                                                for ( $i=0; $i<27; $i++ )
                                                {
                                                    ?>
                                                    <label class="btn btn-outline-primary cursorPointer mb-1">
                                                        <input type="checkbox" autocomplete="off" value="<?=$size?>"> <?=$size?>
                                                    </label>

                                                    <?php
                                                    $size += 0.5;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary okButtonSR" data-dismiss="modal">OK</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- //Размеры -->

                        <!-- Камни / Ссылки -->
                        <div class="col-12 pt-3 bg-light ringHide">
                            <div class="p-1 bg-info fontsView text-white Nit_gems">
                                <span class="float-left p-1"><i class="far fa-gem"></i> Вставки 3D:</span>
                                <button id="addMaterial" class="btn mr-1 btn-sm pl-2 pr-2 btn-light float-right add_Table_Row" title="Добавить">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div class="clearfix"></div>
                            </div>
                            <table class="table text-muted table_gems">
                                <thead>
                                <tr>
                                    <th class="p-1">Размер</th><th width="10%" class="p-1">Кол-во</th><th class="p-1">Огранка</th><th class="p-1">Сырьё</th><th class="p-1">Цвет</th><th class="p-1"></th>
                                </tr>
                                </thead>
                                <tbody class="tbody" <?php $switchTableRow = "gems";?>>
                                <?php require "includes/edit/tableRows.php"?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 bg-light">
                            <div class="p-1 bg-info fontsView text-white Nit_gems">
                                <span class="float-left p-1"><i class="fas fa-link"></i> Ссылки на другие артикулы:</span>
                                <button id="addLink" class="btn mr-1 btn-sm pl-2 pr-2 btn-light float-right add_Table_Row" title="Добавить">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div class="clearfix"></div>
                            </div>
                            <table class="table text-muted table_gems">
                                <thead>
                                <tr>
                                    <th class="p-1">Название</th><th class="p-1">Артикул / Номер 3D</th><th class="p-1">Описание</th><th class="p-1"></th>
                                </tr>
                                </thead>
                                <tbody class="tbody" <?php $switchTableRow = "vc";?>>
                                <?php require "includes/edit/tableRows.php"?>
                                </tbody>
                            </table>
                        </div><!-- //Камни / Ссылки -->

                    </div>
            </div>





            <div class="tab-pane fade" id="dopData" role="tabpanel" aria-labelledby="dopData-tab">
                <div class="row mt-2">

                    <div class="col-12">
                        <label for="descr" class=""><i class="fas fa-comment-alt"></i> Примечания:</label>
                        <textarea id="descr" class="form-control" rows="3" name="description">
                            Есть много вариантов Lorem Ipsum, но большинство из них имеет не всегда приемлемые модификации, например, юмористические вставки или слова, которые даже отдалённо не напоминают латынь.
                        </textarea>
                        <hr>
                    </div>

                    <!-- STL AI Block -->
                    <div class="col-12 col-lg-6 mb-2 font-14">
                        <div id="drop-areaSTL" title="Загрузить Stl">
                            <form>
                                <p>Загрузить Stl файлы можно перетащив их в эту область</p>
                                <p class="font-12">* Перед загрузкой stl файлов, нужно применить к моделям метод Triangle Reduction в Magics с параметром 0,0025. Для уменьшения размера файлов.</p>
                                <input type="file" id="stlfileElem" multiple accept=".stl" onchange="handleStlFiles(this.files)">
                                <label class="button" for="stlfileElem"><i class="fas fa-file-upload"></i> Выбрать Stl файлы</label>
                            </form>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-2 font-14">
                        <div id="drop-areaAI" title="Загрузить Stl">
                            <p>Загрузить Ai файлы можно перетащив их в эту область</p>
                            <input type="file" id="aifileElem" multiple accept=".ai" onchange="handleAiFiles(this.files)">
                            <label class="button" for="aifileElem"><i class="fas fa-file-upload"></i> Выбрать Ai файлы</label>
                        </div>
                    </div>
                    <!-- STL AI Block END -->
                    <div class="col-12">
                        <hr>
                    </div>

                    <!--РЕМОНТЫ-->
                    <div class="col-12 mt-2 mb-3">
                        <p id="repairButtons">
                            <button class="btn btn-outline-secondary" type="button" data-toggle="collapse" data-target="#repair1" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fas fa-wrench"></i> Ремонт №<sapn class="repairNum">1</sapn> от <sapn class="repairDate"><?=date("d.m.Y")?></sapn>
                            </button>
                            <button class="btn btn-outline-success" title="Добавить ремонт" type="button" id="addRepairs">
                                <i class="fas fa-plus"></i> Добавить Ремонт
                            </button>
                        </p>
                        <div class="collapse" id="repair1">
                            <div class="card card-body">
                                <div class="font-weight-bolder">
                                    Ремонт №<sapn class="repairNum">1</sapn>
                                    <button type="button" class="close" aria-label="Close" onclick="removeRepairs(this);">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <textarea class="form-control repairs_descr" rows="3" name="repairs_descr[]">Здесь ваш текст.." Многие программы электронной вёрстки и редакторы HTML используют Lorem Ipsum в качестве текста по умолчанию, так что поиск по ключевым словам "lorem ipsum" сразу показывает, как много веб-страниц всё ещё дожидаются своего настоящего рождения.</textarea>
                            </div>
                        </div>
                        <!--Прото ремонт-->
                        <?php
                        $switchTableRow = "repair";
                        require "includes/edit/tableRows.php"
                        ?>
                        <!--// Прото ремонт-->
                    </div>
                    <!--//РЕМОНТЫ-->
                </div>
            </div>
        </div>
    </div>
</div>


<img id="imageBoxPrev" width="200px" class="img-thumbnail d-none"/>