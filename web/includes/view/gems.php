<?php
$i = 1;    
?>
<div class="p-1 bg-info fontsView text-white Nit_gems">
    <i class="far fa-gem"></i>
    <span>Вставки 3D:</span>
</div>
<table class="table text-muted table_gems">
    <thead>
    <tr>
        <th>№</th><th>Сырьё</th><th>Огранка</th><th>Размер</th><th>Цвет</th><th>Кол-во</th>
    </tr>
    </thead>
    <tbody class="tbody_gems">
    <?php foreach( $model['gems'] as $gem ): ?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$gem['name']?></td>
            <td><?=$gem['cut']?></td>
            <td><?=$gem['size']?></td>
            <td><?=$gem['color']?></td>
            <td><?=$gem['value']?></td>
        </tr>
    <?php endforeach; ?>
    <?php $i = 1; ?>
    </tbody>
</table>