<?php
use yii\helpers\{Html,Url};
use app\models\{User,Common};

$this->title = 'Jewel Box Orders';

//debug($allOrders,'$allOrders',1);
?>

<div class="work-progres">
    <h4 class="tittle-w3-agileits mb-2">Шкатулка для - <?= User::getFIO()?> - Все заказы</h4>
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

<button class="btn btn-<?=($orderStatus===2)?"success":"secondary"?> mb-2" type="button" data-toggle="collapse" data-target="#OrderCollapse-<?=$orderID?>" aria-expanded="false" aria-controls="OrderCollapse-<?=$orderID?>">
    <h5 class="tittle-w3-agileits mb-2 pt-2">Заказ №<?=$orderID?> от <?=$userData['fio']?> - <?=$orderData['lastdate']?>
        <?php if( $orderStatus === 2 ): ?>
        <span class="badge badge-pill badge-warning">Выполнен!</span>
        <?php endif; ?>
    </h5>
</button>
</p>
<div class="collapse" id="OrderCollapse-<?=$orderID?>">
<div class="outer-w3-agile col-xl pt-3 pb-2 mb-3">
    <div class="work-progres">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr align="center">
                        <th>Изделие</th>
                        <th>Вид модели</th>
                        <th>Клиент</th>
                        <th>Ссылка</th>
                        <th>Комментарий</th>
                        <th>
                        <?php if( $orderStatus !== 2 ): ?>
                            <button type="button" data-orderid="<?=$orderID?>" class="btn btn-sm btn-warning JewelBoxOpenAllModels" title="Открыть доступ ВСЕ">
                                <i class="fa-solid fa-lock-open"></i>
                            </button>
                        <?php endif; ?>
                        </th>
                        <th>Стоимость</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $storedModels as $k => $storedModel ):?>
                    <tr align="center">
                        <td><img src="<?="/" . $storedModel['mainimage']?>" width="70 rem;"></td>
                        <td><?=$storedModel['model_type']?></td>
                        <td><?= htmlentities($storedModel['client'])?></td>
                        <td><a class="btn btn-primary btn-sm" href="<?=Url::to(['/site/view/','id'=>$storedModel['id']])?>" role="button">Перейти</a></td>
                        <td>
                            <h5><span class="badge badge-pill badge-secondary jbcomment"><?=$storedModel['comment']?></span></h5>
                        </td>
                        <td>
                            <?php if ( !$storedModel['access'] ): ?>
                            <button type="button" data-orderid="<?=$orderID?>" data-id="<?=$storedModel['id']?>" class="btn btn-sm btn-dark JewelBoxOpenModel" title="Открыть доступ">
                                <input class="editJBdata" type="hidden" data-img="<?="/" . $storedModel['mainimage']?>" data-link="<?=Url::to(['/site/view/','id'=>$storedModel['id']])?>" data-n3d="<?=$storedModel['number_3d']?>" data-mtype="<?=$storedModel['model_type']?>" data-client="<?=htmlentities($storedModel['client'])?>">
                                <i class="fa-solid fa-lock-open"></i>
                            </button>
                            <?php else:?>
                                <h5><span class="badge badge-success"><i class="fa-solid fa-check-to-slot"></i></span></h5>
                            <?php endif;?>
                        </td>
                        <td>
                            <?php if ( $orderStatus !== 2 ): ?>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text badge-light"><i class="fa-regular fa-square-full"></i></div>
                                </div>
                                <input class="form-control form-control-sm" data-orderid="<?=$orderID?>" data-id="<?=$storedModel['id']?>" type="number" editstoreprice="" value="<?=$storedModel['storeprice']?>">
                            </div>
                            <?php else:?>
                                <input class="form-control form-control-sm text-center" disabled value="<?=$storedModel['storeprice']?>">
                            <?php endif;?>
                        </td>
                        <td>
                            <?php if ( $orderStatus == 0 ):?>
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