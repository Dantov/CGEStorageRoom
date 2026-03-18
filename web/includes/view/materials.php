<div class="p-1 bg-success text-white Nit_vc_links"><i class="fa-solid fa-clone"></i><span>Материалы:</span></div>
<table class="table text-muted table_vc_links">
    <thead>
    <tr>
        <th>№</th><th>Название</th><th>Метал</th><th>Проба</th><th>Цвет</th>
    </tr>
    </thead>
    <tbody class="tbody_vc_links">
    <?php foreach( $model['materials'] as $mat ): ?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$mat['part']?></td>
            <td><?=$mat['metal']?></td>
            <td><?=$mat['probe']?></td>
            <td><?=$mat['color']?></td>
        </tr>
    <?php endforeach; ?>
    <?php $i = 1; ?>
    </tbody>
</table>