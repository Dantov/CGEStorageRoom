<?php
use yii\helpers\{Html,Url};
use app\models\{User,Common};

$this->title = 'Common Box';

//debug($allOrders,'$allOrders',1);
?>

<div class="work-progres">
    <h4 class="tittle-w3-agileits mb-2">Common Box - All Items has stored by users</h4>
</div>
<?php if( empty($allOrders) ): ?>
    <div class="outer-w3-agile col-xl pt-3 pb-2 mb-3">
        <h4 class="tittle-w3-agileits mb-2">Empty</h4>
    </div>
<?php endif; ?>

<?php foreach( $allOrders as $orderID => $orderData ): ?>
<?php $orderStatus = (int)$orderData['status']?>
<?php $storedItems = $orderData['storeditems']?>
<?php $userData = $orderData['userdata']?>

<button class="btn btn-<?=($orderStatus===2)?"success":"secondary"?> mb-2" type="button" data-toggle="collapse" data-target="#OrderCollapse-<?=$orderID?>" aria-expanded="false" aria-controls="OrderCollapse-<?=$orderID?>">
    <h5 class="tittle-w3-agileits mb-2 pt-2">Box №<?=$orderID?> from <?=$userData['fio']??''?> - <?=$orderData['lastdate']?></h5>
</button>
</p>
<div class="collapse" id="OrderCollapse-<?=$orderID?>">
<div class="outer-w3-agile col-xl pt-3 pb-2 mb-3">
    <div class="work-progres">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr align="center">
                        <th>Photo</th>
                        <th>Category</th>
                        <th>Project</th>
                        <th>Link</th>
                        <th>Comment</th>
                        <th>Was in Storage room:</th>
                        <th>Was on Shelf:</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $storedItems as $k => $storedItem ):?>
                    <tr align="center">
                        <td><img src="<?="/" . $storedItem['mainimage']?>" width="70 rem;"></td>
                        <td><?=$storedItem['item_category']?></td>
                        <td><?= htmlentities($storedItem['project'])?></td>
                        <td><a class="btn btn-primary btn-sm" href="<?=Url::to(['/site/view/','id'=>$storedItem['id']])?>" role="button">Go</a></td>
                        <td>
                            <h5><span class="badge badge-pill badge-secondary jbcomment"><?=$storedItem['comment']?></span></h5>
                        </td>
                        <td><?=$storedItem['storageroom']?></td>
                        <td><?=$storedItem['shelfnum']?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<?php endforeach;?>