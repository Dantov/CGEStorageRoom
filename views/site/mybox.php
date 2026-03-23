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

<?php foreach( $allOrders as $orderID => $orderData ): ?>

<?php $orderStatus = (int)$orderData['status']?>
<?php $storedModels = $orderData['storedmodels']?>
<?php $userData = $orderData['userdata']?>
<div class="row mb-2">
    <div class="col-xs-12">
        <button class="btn btn-<?=($orderStatus===2)?"secondary":"info"?> btn-block" type="button" data-toggle="collapse" data-target="#OrderCollapse-<?=$orderID?>" aria-expanded="false" aria-controls="OrderCollapse-<?=$orderID?>">
            <h5 class="tittle-w3-agileits mb-2 pt-2">Заказ №<?=$orderID?> от <?=$orderData['lastdate']?>
                <?php if( $orderStatus === 0 ): ?>
                <span class="badge badge-pill badge-primary">Waiting...</span>
                <?php endif; ?>
                <?php if( $orderStatus === 1 ): ?>
                <span class="badge badge-pill badge-primary">Сформирован и отправлен!</span>
                <?php endif; ?>
                <?php if( $orderStatus === 2 ): ?>
                <span class="badge badge-pill badge-warning">Выполнен!</span>
                <?php endif; ?>
            </h5>
        </button>
    </div>
</div>
<div class="collapse" id="OrderCollapse-<?=$orderID?>">
<div class="outer-w3-agile col-xl pt-3 pb-2 mb-3">
    <div class="work-progres">
        <div class="row mb-2">
            <div class="col-sm-6 col-xs-12">
                <?php if ( $orderStatus == 0 ):?>
                <a type="button" href="<?=Url::to(['site/jewel/','box'=>'sendorder','orderid'=>$orderID])?>" class="btn btn-success btn-sm btn-block"><i class="fa-regular fa-paper-plane"></i> Сформировать заказ (всего моделей: <?=count($storedModels)?>)</a>
                <?php elseif($orderStatus == 1):?>
                <span class="text-danger">Заказ сформирован и отправлен! <br/>Свяжитесь с администратором любым известным вам способом.</span>
                <?php endif;?>
            </div>
            <?php if($orderStatus !== 2):?>
            <div class="col-sm-6 col-xs-12">
                <a type="button" href="<?=Url::to(['site/jewel/','box'=>'removeorder','orderid'=>$orderID])?>" class="btn btn-outline-danger btn-sm btn-block"><i class="fa-regular fa-calendar-xmark"></i> Удалить заказ (всего моделей: <?=count($storedModels)?>)</a>
            </div>
            <?php endif;?>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr align="center">
                        <th>Изделие</th>
                        <th>Вид модели</th>
                        <th>Клиент</th>
                        <th>Ссылка</th>
                        <th>Комментарий</th>
                        <th></th>
                        <?php $priceTotal = 0;?>
                        <?php foreach( $storedModels as $stM ):?>
                            <?php $priceTotal += (int)$stM['storeprice'];?>
                        <?php endforeach;?>
                        <th>Стоимость ( <?=$priceTotal?> )</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $storedModels as $k => $storedModel ):?>
                    <tr align="center">
                        <td><img src="<?="/" . $storedModel['mainimage']?>" width="70 rem;"></td>
                        <td><?=$storedModel['model_type']?></td>
                        <td><?= htmlentities($storedModel['client'])?></td>
                        <td>
                            <a class="btn btn-outline-primary btn-sm" href="<?=Url::to(['/site/view/','id'=>$storedModel['id']])?>" role="button">Перейти</a>
                        </td>
                        <td>
                            <?php if ( User::hasFilesAccess( $storedModel['id'] ) ):?>
                            <h5 data-toggle="tooltip" title="Files Opened" ><span class="badge badge-success"><i class="fa-solid fa-check-to-slot"></i></span> </h5>
                            <?php endif;?>
                            <h5><span class="badge badge-pill badge-secondary jbcomment"><?=$storedModel['comment']?></span></h5>
                        </td>
                        <td>
                            <?php if($orderStatus !== 2):?>
                            <button type="button" data-orderid="<?=$orderID?>" data-id="<?=$storedModel['id']?>" class="btn btn-sm btn-dark editbtnJewelBox" title="Редактировать">
                                <input class="editJBdata" type="hidden" data-img="<?="/" . $storedModel['mainimage']?>" data-link="<?=Url::to(['/site/view/','id'=>$storedModel['id']])?>" data-n3d="<?=$storedModel['number_3d']?>" data-mtype="<?=$storedModel['model_type']?>" data-client="<?=htmlentities($storedModel['client'])?>">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <?php endif;?>
                        </td>
                        <td>
                            <h5><span class="badge badge-warning"><?=$storedModel['storeprice']?></span></h5>
                        </td>
                        <td>
                            <?php if ( $orderStatus !== 2 ):?>
                            <a type="button" href="<?=Url::to(['site/jewel','box'=>'remove','id'=>$storedModel['id'],'orderid'=>$orderID])?>" class="btn btn-sm btn-danger" title="Удалить"><i class="fa-solid fa-xmark"></i></a>
                            <?php endif;?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<?php endforeach;?>