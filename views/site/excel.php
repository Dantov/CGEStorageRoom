<?php
use yii\helpers\Url;
?>
<script>
    function getExcel()
    {
        $.ajax({
            type:'GET',
            url: 'index.php?r=site/excel', // path to php handler
            data: {
                testExcell:true,
            },
            dataType:'json'
        }).done(function(data){
            let $a = $("<a>");
            $a.attr("href",data);
            $("body").append($a);
            $a.attr("download","Report.xlsx");
            $a[0].click();
            $a.remove();
        });
    }
</script>
<div class="d-flex p-2 bd-highlight bg-light">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td colspan="2">Larry the Bird</td>
            <td>@twitter</td>
        </tr>
        </tbody>
    </table>
</div>
<div class="row justify-content-center bg-light pb-4">
    <button type="button" class="btn btn-outline-primary" onclick="getExcel()">Get Excel</button>
</div>