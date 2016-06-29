<div class="panel panel-default">
    <div class="panel-heading panel-heading-transparent">
        <h2 class="panel-title bold">{if $action=="add"}Добавить{else}Изменить{/if} штатную еденицу</h2>
    </div>

    <div class="panel-body ">
        <form class="nomargin sky-form " method="post" action="action_admin.php?module=established_post{if $action=="edit"}&id={$id}{/if}">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <input type="text" name="uid_post" class="form-control" placeholder="Введите идентификатор" value="{$data.uid_post}">
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label class="input">
                        <i class="ico-prepend fa fa-calendar"></i>
                            <input type="text" class="form-control datepicker required" data-format="dd/mm/yyyy" data-lang="ru" data-RTL="false" name="date_entry" placeholder="Дата ввода в расписание">
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3 col-sm-3">
                        <select class="form-control  select2" name="id_department_1" onchange="load_data(this,'id_department_2')">
                            <option value="0">Подразделение</option>
                            {foreach from=$data_department item=department}
                                <option value="{$department.id}"{if $department.id == $data.id_department}selected="selected"{/if}>{$department.name_department}</option>
                            {/foreach}

                        </select>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <select class="form-control  select2" name="id_department_2" onchange="load_data(this,'id_department_3')">

                        </select>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <select class="form-control  select2" name="id_department_3" onchange="load_data(this,'id_department_4')">

                        </select>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <select class="form-control  select2" name="id_department_4" >

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control  select2" name="id_direction">
                                <option value="0">Выберите дирекцию</option>
                                {foreach from=$data_direction item=direction}
                                    <option value="{$direction.id}"{if $direction.id == $data.id_direction}selected="selected"{/if}>{$direction.name_direction}</option>
                                {/foreach}

                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control  select2" name="id_city">
                                <option value="0">Выберите город</option>
                                {foreach from=$data_city item=city}
                                    <option value="{$city.id}" {if $city.id == $data.id_location_city}selected="selected"{/if}>{$city.name_city}</option>
                                {/foreach}

                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_position">
                            <option value="0">Выберите должность</option>
                            {foreach from=$data_position item=position}
                                <option value="{$position.id}"{if $position.id == $data.id_position}selected="selected"{/if}>{$position.name_position}</option>
                            {/foreach}

                        </select>
                    </div>
                    <div class="col-md-6 col-sm-6">
мвз
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_rck" onchange="load_mvz(this)">
                                <option value="0">Выберите РЦК</option>
                            {foreach from=$data_rck item=rck}
                                <option value="{$rck.id}"{if $rck.id == $data.id_rck}selected="selected"{/if}>{$rck.name_rck}</option>
                            {/foreach}

                        </select>
                        <i class="fancy-arrow"></i>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_mvz">
                            <option value="0">Выберите МВЗ</option>
                        {foreach from=$data_mvz item=mvz}
                                <option value="{$mvz.id}"{if $mvz.id == $data.id_mvz}selected="selected"{/if}>{$mvz.name_mvz}</option>
                        {/foreach}

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_functional_manager">
                            <option value="0">Выберите функционального руководителя</option>
                            {foreach from=$data_employee item=employee}
                                <option value="{$employee.id}"{if $employee.id == $data.id_functional_manager}selected="selected"{/if}>{$employee.name}</option>
                            {/foreach}

                        </select>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_administrative_manager">
                            <option value="0">Выберите административного руководителя</option>
                            {foreach from=$data_employee item=employee}
                                <option value="{$employee.id}"{if $employee.id == $data.id_administrative_manager}selected="selected"{/if}>{$employee.name}</option>
                            {/foreach}

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="draft">
                            <option value="0" {if $data.draft == 0}selected="selected"{/if}>Драфт</option>
                            <option value="1" {if $data.draft == 1}selected="selected"{/if}>Факт</option>


                        </select>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label class="checkbox nomargin"><input type="checkbox" name="transfer"{if $data.transfer}checked="checked"{/if}><i></i>Перевод</label>
                    </div>
                </div>
            </div>

            <div class="row col-md-12">

                    <button type="submit" class="btn btn-3d btn-teal btn-slg btn-block" type="submit">
                        {if $action=="add"}Добавить{else}Изменить{/if}
                    </button>
                    <input type="hidden" name="action" value="{$action}">
                    {if $id}<input type="hidden" name="id" value="{$id}">{/if}

            </div>
        </form>
    </div>

</div>

