<div class="panel panel-default">
    <div class="panel-heading panel-heading-transparent">
        <h2 class="panel-title bold">{if $action=="add"}Добавить{else}Изменить{/if} сотрудника</h2>
    </div>

    <div class="panel-body ">
        <form class="nomargin sky-form " method="post" action="action_admin.php?module=employee{if $action=="edit"}&id={$id}{/if}">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_uid_post">
                            <option value="0">Идентификатор ШЕ (если есть)</option>
                            {foreach from=$data_ep item=ep}
                                <option value="{$ep.id}"{if $ep.id == $data.id_uid_post}selected="selected"{/if}>{$ep.uid_post}</option>
                            {/foreach}

                        </select>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label class="input">
                            <i class="ico-prepend fa fa-user"></i>
                            <input type="text" name="name_employee" class="form-control" placeholder="ФИО сотрудника" value="{$data.name_employee}">
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-4 col-sm-4">
                        <select class="form-control  select2" name="id_city" onchange="load_location(this,'address')">
                            <option value="0">Выберите город</option>
                            {foreach from=$data_city item=city}
                                <option value="{$city.id}" {if $city.id == $data.id_location_city}selected="selected"{/if}>{$city.name_city}</option>
                            {/foreach}

                        </select>
                        <i class="fancy-arrow"></i>

                    </div>
                    <div class="col-md-4 col-sm-4">
                        <select class="form-control  select2" name="id_address" onchange="load_location(this,'place')">
                            <option value="0">Адрес</option>

                            {foreach from=$data_address item=address}
                                <option value="{$address.id}" {if $address.id == $data.id_location_address}selected="selected"{/if}>{$address.name_address}</option>
                            {/foreach}

                        </select>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <select class="form-control  select2" name="id_place">
                            <option value="0">Место</option>
                            {foreach from=$data_place item=place}
                                <option value="{$place.id}" {if $place.id == $data.id_location_place}selected="selected"{/if}>{$place.name_place}</option>
                            {/foreach}
                        </select>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <label class="input">
                            <i class="ico-prepend fa fa-envelope-o"></i>
                            <input type="text" name="email" class="form-control" placeholder="Email" value="{$data.email}">
                        </label>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_functionality">
                            <option value="0">Выберите функционал</option>
                            {foreach from=$data_functionality item=functionality}
                                <option value="{$functionality.id}" {if $functionality.id == $data.id_functionality}selected="selected"{/if}>{$functionality.name_functionality}</option>
                            {/foreach}

                        </select>
                        <i class="fancy-arrow"></i>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <label class="input">
                            <i class="ico-prepend fa fa-calendar"></i>
                            <input type="text" class="form-control datepicker required" data-format="dd/mm/yyyy" data-lang="ru" data-RTL="false" name="date_employment" placeholder="Дата приема на работу" value="{$data.date_employment}">
                        </label>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label class="input">
                            <i class="ico-prepend fa fa-calendar"></i>
                            <input type="text" class="form-control datepicker required" data-format="dd/mm/yyyy" data-lang="ru" data-RTL="false" name="date_transfer" placeholder="Дата перевода" value="{$data.date_transfer}">
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_strategic_poject">
                            <option value="0">Выберите стратегический проект</option>
                            {foreach from=$data_strategic_poject item=strategic_poject}
                                <option value="{$strategic_poject.id}" {if $strategic_poject.id == $data.id_strategic_poject}selected="selected"{/if}>{$strategic_poject.name_project}</option>
                            {/foreach}

                        </select>
                        <i class="fancy-arrow"></i>

                    </div>
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_employee_model">
                            <option value="0">Выберите модель</option>
                            {foreach from=$data_employee_model item=employee_model}
                                <option value="{$employee_model.id}" {if $employee_model.id == $data.id_employee_model}selected="selected"{/if}>{$employee_model.name_model}</option>
                            {/foreach}

                        </select>
                        <i class="fancy-arrow"></i>
                    </div>
                </div>
            </div>

            <div class="row col-md-12">

                <button type="submit" class="btn btn-3d btn-teal btn-slg btn-block">
                    {if $action=="add"}Добавить{else}Изменить{/if}
                </button>
                <input type="hidden" name="action" value="{$action}">
                {if $id}<input type="hidden" name="id" value="{$id}">{/if}

            </div>
        </form>
    </div>

</div>

