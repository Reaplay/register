{literal}
{/literal}
<div class="row">
    <div class="col-md-8">
    Распределение сотрудников
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
                            <th></th>
                            <th>Штат</th>
                            <th>Нештат</th>
                            <th>Всего</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$data_rck item=rck}
                        <tr>
                            <td>{$rck.name}</td>
                            <td>{if $data_es.{$rck.id}.name}{$data_es.{$rck.id}.name}{else}0{/if}</td>
                            <td>{if $data_no_es.{$rck.id}.name}{$data_no_es.{$rck.id}.name}{else}0{/if}</td>
                            <td>{$data_es.{$rck.id}.name + $data_no_es.{$rck.id}.name}</td>
                            {$es = $es+$data_es.{$rck.id}.name}
                            {$no_es = $no_es+$data_no_es.{$rck.id}.name}
                        </tr>
                        {/foreach}
                        <tr>
                            <td>Общая численность</td>
                            <td>{$es}</td>
                            <td>{$no_es}</td>
                            <td>{$es+$no_es}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>