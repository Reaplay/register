<div class="panel panel-default">
    <div class="panel-heading panel-heading-transparent">
        <h2 class="panel-title bold">{if $action=="add"}Добавить{else}Изменить{/if} МВЗ</h2>
    </div>
    <div class="panel-body">
        <form method="post" action="action_admin.php?module=mvz{if $action=="edit"}&id={$id}{/if}">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <label>РЦК</label>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control  select2" name="id_rck">
                                {foreach from=$data_rck item=rck}
                                    <option value="{$rck.id}"{if $data_mvz.id_rck == $rck.id}selected="selected"{/if}>{$rck.name_rck}</option>
                                {/foreach}

                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>МВЗ</label>
                        <input type="text" name="name_mvz" class="form-control" placeholder="Введите адрес" value="{$data_mvz.name_mvz}">
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-3d btn-teal btn-slg btn-block" type="submit">
                        {if $action=="add"}Добавить{else}Изменить{/if}
                    </button>
                    <input type="hidden" name="action" value="{$action}">
                    {if $data_mvz}
                    <input type="hidden" name="id_mvz" value="{$data_mvz.id_mvz}">
                    {/if}
                </div>
            </div>
        </form>
    </div>

</div>

