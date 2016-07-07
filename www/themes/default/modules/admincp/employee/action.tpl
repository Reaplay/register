<div class="panel panel-default">
    <div class="panel-heading panel-heading-transparent">
        <h2 class="panel-title bold">{if $action=="add"}Добавить{else}Изменить{/if} сотрудника</h2>
    </div>

    <div class="panel-body ">
        <form class="nomargin sky-form " method="post" action="action_admin.php?module=employee{if $action=="edit"}&id={$id}{/if}">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="uid_id">
                            <option value="0">Идентификатор ШЕ</option>
                            {foreach from=$data_ep item=ep}
                                <option value="{$ep.id}"{if $ep.id == $data.id_ep}selected="selected"{/if}>{$ep.uid_post}</option>
                            {/foreach}

                        </select>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_position">
                            <option value="0">Должность</option>
                            {foreach from=$data_position item=position}
                                <option value="{$position.id}"{if $position.id == $data.id_position}selected="selected"{/if}>{$position.name_position}</option>
                            {/foreach}

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-4 col-sm-4">
                        <select class="form-control  select2" name="id_department_1" onchange="load_data(this,'id_department_2')">
                            <option value="0">Город</option>

                        </select>

                    </div>
                    <div class="col-md-4 col-sm-4">
                        <select class="form-control  select2" name="id_department_1" onchange="load_data(this,'id_department_2')">
                            <option value="0">Адрес</option>

                        </select>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <select class="form-control  select2" name="id_department_1" onchange="load_data(this,'id_department_2')">
                            <option value="0">Место</option>

                        </select>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <label class="input">
                            <i class="ico-prepend fa fa-envelope-o"></i>
                            <input type="text" name="uid_post" class="form-control" placeholder="Email" value="{$data.uid_post}">
                        </label>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control  select2" name="id_department_1" onchange="load_data(this,'id_department_2')">
                            <option value="0">Фунционал</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <label class="input">
                            <i class="ico-prepend fa fa-calendar"></i>
                            <input type="text" class="form-control datepicker required" data-format="dd/mm/yyyy" data-lang="ru" data-RTL="false" name="date_entry" placeholder="Дата принема на работу">
                        </label>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label class="input">
                            <i class="ico-prepend fa fa-calendar"></i>
                            <input type="text" class="form-control datepicker required" data-format="dd/mm/yyyy" data-lang="ru" data-RTL="false" name="date_entry" placeholder="Дата перевода">
                        </label>
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

