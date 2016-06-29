<div class="alert alert-warning margin-bottom-30"><!-- warning -->
    Все изменения вносимые в данной опции влияют на всех пользователей
</div>

<a href="action_admin.php?module=position&action=add"><button type="button" class="btn btn-primary btn-lg btn-block">Добавить новую должность</button></a>
<hr>
<table class="table table-striped table-hover table-bordered" id="list_position">
    <thead>
    <tr>
        <th>Название</th>
        <th>Руководящая?</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>
        <th>Действия</th>
    </tr>
    </thead>

    <tbody data-w="position">
    {foreach from=$data_position item=position}

        <tr data-id="{$position.id}">
            <td>
                {$position.name_position}
            </td>
            <td>
                {if $position.is_head}Да{else}Нет{/if}
            </td>
            <td>
               {$position.added}
            </td>
            <td class="center">
                {if !$position.last_update}<span class="label label-sm label-default">Не изменялось</span>{else}{$position.last_update}{/if}
            </td>

            <td>
               <a href="action_admin.php?module=position&action=edit&id={$position.id}">Изменить</a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>