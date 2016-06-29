<a href="action_admin.php?module=rck&action=add"><button type="button" class="btn btn-primary btn-lg btn-block">Добавить новый РЦК</button></a>
<hr>
{paginator page='action_admin' add_value='module=rck&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_rck">
    <thead>
    <tr>
        <th>Название</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>
        <th>Действия</th>
    </tr>
    </thead>

    <tbody data-w="rck">
    {foreach from=$data_rck item=rck}

        <tr data-id="{$rck.id}">
            <td>
                {$rck.name_rck}
            </td>
            <td>
               {$rck.added}
            </td>
            <td class="center">
                {if !$rck.last_update}<span class="label label-sm label-default">Не изменялось</span>{else}{$rck.last_update}{/if}
            </td>

            <td>
               <a href="action_admin.php?module=rck&action=edit&id={$rck.id}">Изменить</a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='action_admin' add_value='module=rck&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}