<?php
use yii\helpers\{Html,Url};
use app\models\User;

$this->title = 'Box ' . User::getFIO();
?>

<div class="work-progres">
    <h4 class="tittle-w3-agileits mb-2">Box for <?= User::getFIO()?></h4>
    <?php //debug($allOrders,1,1)?>
</div>
<?php if( empty($allOrders) ): ?>
    <div class="outer-w3-agile col-xl pt-3 pb-2 mb-3">
        <h4 class="tittle-w3-agileits mb-2">Пусто</h4>
    </div>
<?php endif; ?>

<div class="outer-w3-agile col-xl pt-3 pb-2 mb-3">
    <div class="work-progres">
        <div class="row mb-2">
            <div class="col-sm-6 col-xs-12">
            </div>
            <div class="col-sm-6 col-xs-12">
                <a type="button" href="<?=Url::to(['site/my/','box'=>'putallback'])?>" class="btn btn-outline-danger btn-sm btn-block"><i class="fa-solid fa-cubes-stacked"></i> Put all items back in Store</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr align="center">
                        <th>Photo</th>
                        <th>Name/Category</th>
                        <th>Project</th>
                        <th>View Link</th>
                        <th>Comment</th>
                        <th>Was present in Room/Shelf</th>
                        <th><!--Rent Prices (Total:)--></th>
                        <th>Grabbing Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php foreach( $allOrders as $orderID => $orderData ): ?>

<?php $orderStatus = (int)$orderData['status']?>
<?php $storeditems = $orderData['storeditems']?>
<?php $userData = $orderData['userdata']?>

    <?php foreach( $storeditems as $k => $storedItem ):?>
    <tr align="center">
        <td><img src="<?="/" . $storedItem['mainimage']?>" width="70 rem;"></td>
        <td><?=$storedItem['item_name'] . " / " .$storedItem['item_category']?></td>
        <td><?=htmlentities($storedItem['project'])?></td>
        <td>
            <a class="btn btn-outline-primary btn-sm" href="<?=Url::to(['/site/view/','id'=>$storedItem['id']])?>" role="button"><i class="fa-solid fa-eye"></i></a>
        </td>
        <td>
            <h5><span class="badge badge-pill badge-secondary jbcomment"><?=$storedItem['comment']?></span></h5>
        </td>
        <td>
            <h5><span class="badge badge-pill badge-info"><?=$storedItem['storageroom']?></span> / <span class="badge badge-pill badge-warning"><?=$storedItem['shelfnum']?></span></h5>
        </td>
        <td>
            <h5><span class="badge badge-warning"><?=$storedItem['storeprice']?></span></h5>
        </td>
        <td><?=formatDate($storedItem['grabbingdate'])?></td>
        <td>
            <button type="button" data-orderid="<?=$orderID?>" data-id="<?=$storedItem['id']?>" class="btn btn-sm btn-dark editbtnJewelBox" title="Put back">
                <input class="editJBdata" type="hidden" data-img="<?="/" . $storedItem['mainimage']?>" data-prj="<?=htmlentities($storedItem['project'])?>" data-name="<?=$storedItem['item_name']?>" data-room="<?=$storedItem['storageroom']?>" data-shelf="<?=$storedItem['shelfnum']?>" data-cat="<?=$storedItem['item_category']?>">
                <i class="fa-solid fa-rotate-left"></i>Put back
            </button>
        </td>
    </tr>
    <?php endforeach;?>
<?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>