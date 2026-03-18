<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\User;

$this->title = 'Номенклатура';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about text-center mb-2">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-modeltypes" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Типы моделей</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-gems" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Камни</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-materials" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Материалы</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-clients" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Клиенты</button>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" href="<?=Url::to(['users/show-all'])?>">Пользователи</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-modeltypes" role="tabpanel" aria-labelledby="pills-home-tab">
    <div class="outer-w3-agile mt-3">
        <h4 class="tittle-w3-agileits mb-4">Типы моделей</h4>
        <table class="table table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col" align="center">Name</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $modelTypes as $key => $modelType ): ?>
                <tr>
                    <th scope="row"><?=$key?></th>
                    <td><input type="text" class="form-control" data-tab="model_type" data-id="<?=$modelType['id']?>" value="<?=$modelType['name']?>" placeholder=""></td>
                    <td align="right"><button type="button" class="btn btn-dark"><i class="fa-regular fa-trash-can"></i></button></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td align="left"><button type="button" class="btn btn-dark"><i class="fa-solid fa-square-plus"></i></button></td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
  <!-- HOME PILL END -->

  <div class="tab-pane fade" id="pills-gems" role="tabpanel" aria-labelledby="pills-profile-tab">
      <div class="outer-w3-agile mt-3">
        <h4 class="tittle-w3-agileits mb-4">Камни</h4>
        <div class="row">
            <div class="col col-md-6">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col" align="center">Название сырья</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $gemsNames as $key => $gemsName ): ?>
                        <tr>
                            <th scope="row"><?=$key?></th>
                            <td><input type="text" class="form-control" data-tab="gems_names" data-id="<?=$gemsName['id']?>" value="<?=$gemsName['name']?>" placeholder=""></td>
                            <td align="right"><button type="button" class="btn btn-dark"><i class="fa-regular fa-trash-can"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td align="left"><button type="button" class="btn btn-dark"><i class="fa-solid fa-square-plus"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div> 
            <div class="col col-md-6">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col" align="center">Цвета</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $gemsColors as $key => $gemColor ): ?>
                        <tr>
                            <th scope="row"><?=$key?></th>
                            <td><input type="text" class="form-control" data-tab="gems_color" data-id="<?=$gemColor['id']?>" value="<?=$gemColor['name']?>" placeholder=""></td>
                            <td align="right"><button type="button" class="btn btn-dark"><i class="fa-regular fa-trash-can"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td align="left"><button type="button" class="btn btn-dark"><i class="fa-solid fa-square-plus"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>   
        </div>
        <div class="row">
            <div class="col col-md-6">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col" align="center">Огранки</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $gemsCuts as $key => $gemsCut ): ?>
                        <tr>
                            <th scope="row"><?=$key?></th>
                            <td><input type="text" class="form-control" data-tab="gems_cut" data-id="<?=$gemsCut['id']?>" value="<?=$gemsCut['name']?>" placeholder=""></td>
                            <td align="right"><button type="button" class="btn btn-dark"><i class="fa-regular fa-trash-can"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td align="left"><button type="button" class="btn btn-dark"><i class="fa-solid fa-square-plus"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col col-md-6">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col" align="center">Размеры</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $gemsSizes as $key => $gemsSize ): ?>
                        <tr>
                            <th scope="row"><?=$key?></th>
                            <td><input type="text" class="form-control" data-tab="gems_sizes" data-id="<?=$gemsSize['id']?>" value="<?=$gemsSize['name']?>" placeholder=""></td>
                            <td align="right"><button type="button" class="btn btn-dark"><i class="fa-regular fa-trash-can"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td align="left"><button type="button" class="btn btn-dark"><i class="fa-solid fa-square-plus"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
  <div class="tab-pane fade" id="pills-materials" role="tabpanel" aria-labelledby="pills-contact-tab">...materials Pill</div>
  <div class="tab-pane fade" id="pills-clients" role="tabpanel" aria-labelledby="pills-users-tab">
    ..clients Pill
    <div class="outer-w3-agile mt-3">
        <div class="form-group">
            <label for="customers"></label>
            <div class="input-group">
                <input type="text" editable class="form-control" value="" name="users" id="users" aria-label="" >
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"></button>
                    <div class="dropdown-menu">
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

