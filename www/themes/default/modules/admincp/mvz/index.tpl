
<a href="action_admin.php?module=mvz&action=add"><button type="button" class="btn btn-primary btn-lg btn-block">Добавить новый МВЗ</button></a>
<hr>
{paginator page='action_admin' add_value='module=mvz&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_mvz">
    <thead>
    <tr>
        <th>МВЗ</th>
        <th>РЦК</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>
        <th>Действия</th>
    </tr>
    </thead>

    <tbody data-w="mvz">
    {foreach from=$data_mvz item=mvz}

        <tr data-id="{$mvz.id}">
            <td>
                {$mvz.name_mvz}
            </td>
            <td>
                {$mvz.name_rck}
            </td>
            <td>
               {$mvz.added}
            </td>
            <td class="center">
                {if !$mvz.last_update}<span class="label label-sm label-default">Не изменялось</span>{else}{$mvz.last_update}{/if}
            </td>

            <td>
               <a href="action_admin.php?module=mvz&action=edit&id={$mvz.id}">Изменить</a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='action_admin' add_value='module=mvz&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}