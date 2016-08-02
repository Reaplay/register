<div class="row" >
<form  action="graph.php" method="get">
    <input type="hidden" name="action" value="{$smarty.get.action}">
<div class="col-md-3">

    <div class="fancy-form fancy-form-select">
        <select class="form-control select2" name="id_rck">
            <option value="0">Выберите фильтр</option>
            {foreach from=$data_rck key=id item=name}
                <option value="{$id}" {if $id==$smarty.get.id_rck}selected="selected"{/if}>{$name}</option>
            {/foreach}
        </select>
        <i class="fancy-arrow"></i>

    </div>

</div>
<div class="col-md-3">
    <button type="submit" class="btn btn-primary">Применить</button>

</div>
</form>
</div>

{if $smarty.get.id_rck}
    <br/><br/>
<div class="row">
    <div class="col-md-8">
    <!--Распределение сотрудников-->
        <div id="flot-pie" class="flot-chart height-300"><!-- FLOT CONTAINER --></div>

    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
				<span class="title elipsis">
					<strong>Распределение сотрудников</strong>
				</span>

            </div>


            <div class="panel-body">
                <div class="tab-content transparent">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Город</th>
                            <th>Кол-во</th>

                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$data_city item=city}
                        <tr>
                            <td>{$city.name_city}</td>
                            <td>{$city.num}</td>
                            {$all_num = $all_num + $city.num}
                        </tr>
                        {/foreach}
                        <tr>
                            <td>Общая численность</td>
                            <td>{$all_num}</td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>
{/if}