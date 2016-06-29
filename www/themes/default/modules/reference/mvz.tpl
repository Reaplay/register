{paginator page='reference' add_value='type=mvz&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_mvz">
    <thead>
    <tr>
        <th>МВЗ</th>
        <th>РЦК</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>

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


        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='reference' add_value='type=mvz&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}