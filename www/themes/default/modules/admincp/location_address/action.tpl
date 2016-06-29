<div class="panel panel-default">
    <div class="panel-heading panel-heading-transparent">
        <h2 class="panel-title bold">{if $action=="add"}Добавить{else}Изменить{/if} адрес</h2>
    </div>
    <div class="panel-body">
        <form method="post" action="action_admin.php?module=location_address{if $action=="edit"}&id={$id}{/if}">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <label>Город</label>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control  select2" name="id_city">
                                {foreach from=$data_city item=city}
                                    <option value="{$city.id}"{if $data_address.id_city == $city.id}selected="selected"{/if}>{$city.name_city}</option>
                                {/foreach}

                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Адрес</label>
                        <input type="text" name="name_address" class="form-control" placeholder="Введите адрес" value="{$data_address.name_address}">
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-3d btn-teal btn-slg btn-block" type="submit">
                        {if $action=="add"}Добавить{else}Изменить{/if}
                    </button>
                    <input type="hidden" name="action" value="{$action}">
                </div>
            </div>
        </form>
    </div>

</div>

