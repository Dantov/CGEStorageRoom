<div class="p-1 bg-secondary text-white mt-2"><i class="fa-solid fa-file"></i><span>Файлы: (<?=$model['overal_zipsize']?> mb)</span></div>
<table class="table text-muted">
    <tbody class="">
    <?php foreach( $model['d3_files'] as $dfile ): ?>
        <tr>
            <td class="va-mid text-bold"><?=$dfile['type']?></td>
            <td><img src="/web/pictAssets/icon_<?=$dfile['type']?>.png" class="img-fluid" style="width: 3rem;" alt="Responsive image">
            </td>
            <td class="va-mid text-bold"><?=$dfile['zipname']?></td>
            <td class="va-mid text-bold"><?=$dfile['zipsize']?> mb</td>
            <td class="va-mid text-bold" align="middle"><a class="btn btn-sm btn-secondary text-light" href="/stock/<?=$modelPath?>/3dfiles/<?=$dfile['zipname']?>" download="<?=$dfile['zipname']?>">Скачать</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>