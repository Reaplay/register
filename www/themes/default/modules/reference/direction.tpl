{paginator page='reference' add_value='type=direction&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_direction">
    <thead>
    <tr>
        <th>Название</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>
    </tr>
    </thead>

    <tbody data-w="direction">
    {foreach from=$data_direction item=direction}

        <tr data-id="{$direction.id}">
            <td>
                {$direction.name_direction}
            </td>

            <td>
                {$direction.added}
            </td>
            <td class="center">
                {if !$direction.last_update}<span class="label label-sm label-default">Не изменялось</span>{else}{$direction.last_update}{/if}
            </td>


        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='reference' add_value='type=direction&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}