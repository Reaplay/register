<div class="panel panel-default">
    <div class="panel-heading panel-heading-transparent">
        <h2 class="panel-title bold">{if $action=="add"}Добавить{else}Изменить{/if} подразделение</h2>
    </div>
    <div class="panel-body">
        <form method="post" action="action_admin.php?module=department{if $action=="edit"}&id={$id}{/if}">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <label>Название подразделения</label>
                        <input type="text" name="name_department" class="form-control" placeholder="Введите название" value='{$data.name_department}'>
                    </div>
                    <div class="col-md-6 col-sm-6">

                        <label>Родительское подразделение</label>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control  select2" name="id_parent">
                                <option value="0">Отсутствует</option>
                                {foreach from=$data_department item=department}
                                    <option value="{$department.id}" {if $department.id == $data.id_parent}selected="selected"{/if}>{$department.name_department}</option>
                                {/foreach}

                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>

                </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6">
                    <label>Тип офиса</label>
                    <select class="form-control  select2" name="id_type_office">
                        <option value="0">Отсутствует</option>
                        {foreach from=$data_type_office item=type_office}
                            <option value="{$type_office.id}" {if $type_office.id == $data.id_type_office}selected="selected"{/if}>{$type_office.name_office}</option>
                        {/foreach}

                    </select>
                    <i class="fancy-arrow"></i>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
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

