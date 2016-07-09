<div class="panel panel-default">
    <div class="panel-heading panel-heading-transparent">
        <h2 class="panel-title bold">{if $action=="add"}Добавить{else}Изменить{/if} место</h2>
    </div>
    <div class="panel-body">
        <form method="post" action="action_admin.php?module=location_place{if $action=="edit"}&id={$id}{/if}">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <label>Город</label>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control  select2" name="id_city" onchange="load_location(this,'address')">
                                <option value="0">Выберите город</option>
                                {foreach from=$data_city item=city}
                                    <option value="{$city.id}"{if $data_place.id_city == $city.id}selected="selected"{/if}>{$city.name_city}</option>
                                {/foreach}

                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Адрес</label>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control  select2" name="id_address">
                                <option value="0">Выберите адрес</option>
                                {foreach from=$data_address item=address}
                                    <option value="{$address.id}"{if $data_place.id_address == $address.id}selected="selected"{/if}>{$address.name_address}</option>
                                {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <div class="col-md-3 col-sm-3">
                        <label>Этаж</label>
                        <div class="fancy-form">
                            <input type="text" name="floor" class="form-control" placeholder="Номер этажа" value="{$data_place.floor}">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <label>Комната</label>
                        <div class="fancy-form">
                            <input type="text" name="room" class="form-control" placeholder="Номер комнаты" value="{$data_place.room}">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <label>Место</label>
                        <div class="fancy-form">
                            <input type="text" name="place" class="form-control" placeholder="Номер места" value="{$data_place.place}">
                        </div>
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

