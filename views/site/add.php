<?php
use yii\helpers\Url;
use app\models\User;

$tt=time();
$this->registerJsFile("@web/js/add-edit/Validator.js?v=$tt",['depends' => [\app\assets\AppAsset::class]]);
$this->registerJsFile("@web/js/add-edit/AddEdit.js?v=$tt",['depends' => [\app\assets\AppAsset::class]]);
$this->registerJsFile("@web/js/add-edit/HandlerFiles.js?v=$tt",['depends' => [\app\assets\AppAsset::class]]);
//$this->registerCssFile("@web/css/view/view.css?v=$tt");
//debug($datafileSizes,'datafileSizes',1);
//debug($sevData['metal_probe'],'metal_probe',1);

$modelStatus = (int)$stockData['model_status']; 
$session = Yii::$app->session;
$this->title = 'Edit Item';
?>


<?php if ( $session->hasFlash('cloned') ): ?>
    <h1 class="main-title-w3layouts mb-2 text-center">Item cloned success!</h1>
<?php elseif ( $session->hasFlash('editModel') ): ?>
    <h1 class="main-title-w3layouts mb-2 text-center">Edit item</h1>
<?php else: ?>
    <?php $this->title = 'Add New Item'; ?>
    <h1 class="main-title-w3layouts mb-2 text-center">Add new item</h1>
<?php endif; ?>


<!-- TEXT DATA -->
<div class="outer-w3-agile mt-3">
    <h4 class="tittle-w3-agileits mb-4"><i class="fa-regular fa-file-lines"></i> General data</h4>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="item_number">Item №</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                    </div>
                    <input type="text" editable class="form-control" name="item_number" id="item_number" value="<?=$stockData['item_number']?>" placeholder="">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="item_name">Item Name</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text badge-light "><i class="fa-regular fa-square-full"></i></div>
                    </div>
                    <input type="text" editable class="form-control" name="item_name" id="item_name" value="<?=$stockData['item_name']?>" placeholder="" >
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="item_category">Item category</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                    </div>
                    <input type="text" editable class="form-control" name="item_category" id="item_category" value="<?=$stockData['item_category']?>"placeholder="">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                        <div class="dropdown-menu p-1">
                            <?php foreach ($sevData['category'] as $key => $mtype): ?>
                            <a class="dropdown-item p-1" style="cursor: pointer;" elemToAdd><?php echo $mtype['name'] ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="item_size">Item size</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                    </div>
                    <input type="text" editable class="form-control" name="item_size" id="item_size" value="<?=$stockData['item_size']?>" placeholder="">
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="item_price">Price</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                    </div>
                    <input type="text" editable class="form-control" value="<?=$stockData['item_price']?>" name="item_price" id="item_price" placeholder="">
                </div>
            </div>
            <?php if (User::hasPermission('model_price')): ?>
            <div class="form-group col-md-3">
                <label for="item_price_rent">Price for rent</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                    </div>
                    <input type="text" editable class="form-control" value="<?=$stockData['item_price_rent']?>" name="item_price_rent" id="item_price_rent" placeholder="">
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="projects">Projects</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                </div>
                <input type="text" disabled editable class="form-control" value="<?=htmlspecialchars($stockData['project'])?>" name="project" id="project" aria-label="" >
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                    <div class="dropdown-menu">
                        <?php foreach ($sevData['project'] as $key => $value): ?>
                        <a class="dropdown-item" style="cursor: pointer;" elemToAdd><?php echo $value['name'] ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="description"><i class="fa-regular fa-message"></i> Description</label>
            <textarea class="form-control" editable name="description" id="description" rows="3"><?=$stockData['description'] ?></textarea>
        </div>
</div>

<?php if ( User::hasPermission('images') || User::hasPermission('files') ): ?>
<div class="outer-w3-agile mt-3 pt-3 pb-3 <?=($modelStatus === 2)?"d-none":"" ?>">
    <div class="card-deck text-center row">
        <div class="card box-shadow col-xl-12 col-md-12">
            <div class="card-header p-5 border border-secondary rounded" id="drop-area"  title="Загрузить Файлы">
                <p>To upload files drop it in this area.</p>
                <p> Formats: .jpg .jpeg .png .gif .webp</p></br>
                <button type="button" id="addImageFiles" class="btn btn-outline-secondary btn-block"><i class="far fa-images"></i> Select manualy</button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<div class="container-fluid">
    <div class="row">
        <!-- IMAGES -->
        <?php if ( User::hasPermission('images') ): ?>
        <div class="outer-w3-agile col-xl mt-3 mr-xl-3 p-2">
            <h4 class="tittle-w3-agileits">Images</h4>
            <hr>
            <div class="row justify-content-center pl-2 pr-2" id="picts">
                <?php foreach ($stockData['images'] as $image): ?>
                <?php require _webDIR_ . 'includes/add-edit/img_protoRow.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif ?>
        <!--// IMAGES -->
        <!-- 3D Files -->
        <?php if ( User::hasPermission('files') ): ?>
        <div class="outer-w3-agile col-xl mt-3 mr-xl-3 p-2">
            <h4 class="tittle-w3-agileits">
                Addition files: 
                <small>(Origin: <?=$datafileSizes['origin']?>) (Zipped: <?=$datafileSizes['zip']?>)</small>
            </h4>
            <hr>
            <div class="card-body p-1 pt-0">
                <div class="list-group" id="d3-files-area">
                    <?php foreach ($stockData['d3_files'] as $datafile): ?>
                    <?php require _webDIR_ . 'includes/add-edit/datafile_protoRow.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif ?>
        <!--// 3D Files -->
    </div>
</div>
<div class="outer-w3-agile mt-3">
    <div class="form-group">
        <label for="tags"><i class="fa-solid fa-tags"></i> Hashtags:</label>
        <div class="btn-group-toggle" data-toggle="buttons" id="hashtags">
            <?php foreach ($sevData['hashtag'] as $key => $value): ?>
                <label class="btn btn-outline-info shadow-sm mb-1 <?php if ($value['checked'] == 1) echo "active"?>">
                    <input type="checkbox" name="hashtags" <?php if ($value['checked'] == 1) echo "checked"?> value="<?=$value['name']?>" /><span><?php echo $value['name'] ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <label for="mytags"><i class="fa-solid fa-tag"></i> Add own hashtag:</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
            </div>
            <input class="form-control" type="text" onchange="hashtagByText(this)" name="hashtags" id="hashtags" rows="1" value="">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-5">
            <span>Item receipt date: </span>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                </div>
                <input class="form-control" style="width: 13rem;" type="date" name="create_date" editable value="<?=$stockData['create_date'] ?>" />
            </div>
        </div>
        <div class="col-sm-3">
            <br/>
            <?php if ( $modelStatus !== 2 ): ?>
                <a class="btn btn-outline-danger" href="<?=Url::to(["site/view", 'id'=>$stockData['id']])?>" role="button">Просмотр</a>
                <a class="btn btn-outline-warning float-right" id="clone-position" role="button">Клонировать</a>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
         <div class="col-sm-4 float-right">
            <div class="float-right">
                <span>Item adding date:</span>
                <input class="form-control" readonly type="date" name="date" value="<?=$stockData['date'] ?>"/>
                <span>Добавил: <?=User::getUsernameByID($stockData['creator_id'])?></span>
            </div>
        </div>
    </div>
    <div class="form-group row" id="publishRow">
        <div class="col-sm-5 float-left">
            <?php if ( $modelStatus === 0 ): ?>
                <i class="text-danger">Item has not avialable in search.</i></br>
                <i class="text-danger">If all data is correct, publish it!</i></br>
                <button type="button" class="btn btn-success" data-publish="pub">Publish</button>
            <?php endif; ?>
            <?php if ( $modelStatus === 1 ): ?>
                <i class="text-success">Item is published and avialable in search!</i></br>
            <?php endif; ?>
        </div>
        <div class="col-sm-2">
            <i class="text-danger"></i></br>
            <i class="text-danger"></i></br>
             <?php if ( $modelStatus === 1 ): ?>
                <button type="button" class="btn btn-outline-secondary text-center" data-publish="excl">Exclude</button>
            <?php endif; ?>
        </div>
        <div class="col-sm-5 float-right">
            <?php if ( $modelStatus !== 2 ): ?>
                <button type="button" class="btn btn-outline-danger float-right" data-publish="del">Delete</button>
            <?php endif; ?>
        </div>
        <?php if ( $modelStatus === 2 ): ?>
        <div class="col-sm-8 float-left">
            <i class="text-danger">Item has been deleted!</i></br>
            <i class="text-danger">To restore it, please contact an administartor.</i>
        </div>
        <div class="col-sm-4 float-right">
            <?php if ( User::isAdmin() ): ?>
                <button type="button" class="btn btn-sm btn-warning float-right fullyRestore" data-publish="fullyRestore">Restore</button>
                <button type="button" class="btn btn-sm btn-danger float-right fullydell" data-publish="fullydell">Delete completly</button>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if ( User::isAdmin() ): ?>
<!-- jewel-box-modal -->
<div class="modal fade" id="delete-pos-modal" tabindex="-1" aria-labelledby="DellModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="DellModalLabel">Deleting Model Data...</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0 pb-0">
                <tbody>
                    <tr class="gems" align="center"><td class="bg-info"></td></tr>
                    <tr class="materials" align="center"><td class="bg-success"></td></tr>
                    <tr class="images" align="center"><td class="bg-secondary"></td></tr>
                    <tr class="data" align="center"><td class="bg-primary"></td></tr>
                    <tr class="files" align="center"><td class="bg-warning"></td></tr>
                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <a type="button" href="<?=Url::to(['/site'])?>" class="btn btn-secondary">Done</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<input type="hidden" id="modelID" value="<?=$modelID?>" />
<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<?php require _webDIR_.'includes/add-edit/protoRows.php'?>