<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\User;

$this->title = 'Nomenclature';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about text-center mb-2">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-materials" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Materials</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pills-projects-tab" data-toggle="pill" data-target="#pills-projects" type="button" role="tab" aria-controls="pills-projects" aria-selected="false">Projects</button>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" href="<?=Url::to(['users/show-all'])?>">Users</a>
  </li>
</ul>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade" id="pills-materials" role="tabpanel" aria-labelledby="pills-materials-tab">...Materials Pill</div>
  <div class="tab-pane show active" id="pills-projects" role="tabpanel" aria-labelledby="pills-projects-tab">
    <div class="outer-w3-agile mt-3">
        <div class="list-group">
            <?php foreach( $projects as $project ): ?>
              <a href="#" class="list-group-item list-group-item-action border">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1"><?= $project['name']?></h5>
                  <small>3 years ago</small>
                </div>
                <p class="mb-1">Project description.</p>
                <small>And some small print.</small>
              </a>
            <?php endforeach; ?>
        </div>
    </div>
  </div>
</div>

