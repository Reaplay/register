
<a href="action_admin.php?module=functionality&action=add"><button type="button" class="btn btn-primary btn-lg btn-block">Добавить новый функционал</button></a>
<hr>
<table class="table table-striped table-hover table-bordered" id="list_functionality">
    <thead>
    <tr>
        <th>Название</th>
        <th>Родитель</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>
        <th>Действия</th>
    </tr>
    </thead>

    <tbody data-w="functionality">
    {foreach from=$data_functionality item=functionality}

        <tr data-id="{$functionality.id}">
            <td>
                {$functionality.name_functionality}
            </td>
            <td>
                {$functionality.name_parent}
            </td>
            <td>
               {$functionality.added}
            </td>
            <td class="center">
                {if !$functionality.last_update}<span class="label label-sm label-default">Не изменялось</span>{else}{$functionality.last_update}{/if}
            </td>

            <td>
               <a href="action_admin.php?module=functionality&action=edit&id={$functionality.id}">Изменить</a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>