<?php
use yii\helpers\Url;
use app\models\MyLinkPager;
use app\models\User;
//$this->title = 'Powered-Jewelery DataBase';
$pagDraw = null;
if ( $pages )
{
  $pagDraw = ( $pages->totalCount > $pages->limit );
  $pagination = MyLinkPager::widget([
    'pagination' => $pages,
    'pageCssClass' => 'page-item',
    'firstPageCssClass'=>'page-item first',
    'lastPageCssClass'=>'page-item last',
    'prevPageCssClass'=>'page-item prev',
    'nextPageCssClass'=>'page-item next',
    'prevPageLabel'=>'<i class="fa-solid fa-caret-left"></i>',
    'nextPageLabel'=>'<i class="fa-solid fa-caret-right"></i>',
  ]);  
}
$controller = $this->context;
?>

<div class="row justify-content-left">
    <?php //debug(User::permissions(),'perm'); ?>
    <?php //debug($controller->isDesktop,'isDesktop'); ?>
    <?php //debug($controller->isMobile,'ismobile'); ?>
    <?php //debug($stock,1,1); ?>
</div>
<form>
  <div class="form-group mt-1 cursorPointer">
    <input type="range" class="form-control-range" name="tilesControlRange" min="6" step="0.5" max="24" value="<?=$session->get('tilesControlSize')?>" id="tilesControlRange">
  </div>
</form>
<!-- paggination -->
<div class="row float-right mb-2">
  <div class="col-xs-12">
    <nav aria-label="..." class=""><?=$pagDraw?$pagination : "" ?></nav>
  </div>
</div>
<div class="clearfix"></div>
<div class="row justify-content-center" id="cards">
<?php if ( empty($stock) ): ?>
  <div class="col-4 net-not-found-left"></div>
  <div class="col-4">  
    <h4 class="text-center">Nothing found...</h4>
  </div>
  <div class="col-4 net-not-found-right"></div>
<?php endif; ?>

<?php foreach( $stock as $model ): ?>
  <?php require _webDIR_ . 'includes/main/cardProto.php'; ?>
<?php endforeach; ?>
</div>

<!-- paggination -->
<div class="row justify-content-left mt-1">
  <div class="col-xs-12">
    <nav aria-label="..."><?=$pagDraw?$pagination : "" ?></nav>
  </div>
</div>