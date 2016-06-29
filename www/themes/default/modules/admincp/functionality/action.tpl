<div class="panel panel-default">
    <div class="panel-heading panel-heading-transparent">
        <h2 class="panel-title bold">{if $action=="add"}Добавить{else}Изменить{/if} функцию</h2>
    </div>
    <div class="panel-body">
        <form method="post" action="action_admin.php?module=functionality{if $action=="edit"}&id={$id}{/if}">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <label>Корневая функция</label>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control  select2" name="id_parent">
                                <option value="0"{if $data.id_parent == 0}selected="selected"{/if}>Корневая</option>
                                {foreach from=$data_functionality item=functionality}
                                    <option value="{$functionality.id}"{if $functionality.id == $data.id_parent} selected="selected"{/if}>{$functionality.name_functionality}</option>
                                {/foreach}

                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Функция</label>
                        <input type="text" name="name_functionality" class="form-control" placeholder="Введите функцию" value="{$data.name_functionality}">
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

