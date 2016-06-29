<div class="panel panel-default">
    <div class="panel-heading panel-heading-transparent">
        <h2 class="panel-title bold">{if $action=="add"}Добавить{else}Изменить{/if} должность</h2>
    </div>
    <div class="panel-body">
        <form method="post" action="action_admin.php?module=position{if $action=="edit"}&id={$id}{/if}">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6">
                        <label>Название должности</label>
                        <input type="text" name="name_position" class="form-control" placeholder="Введите название должности" value="{$data_position.name_position}">
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label class="checkbox nomargin"><input type="checkbox" name="is_head"{if $data_position.is_head}checked="checked"{/if}><i></i>Руководящая должность</label>
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

