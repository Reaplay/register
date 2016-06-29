{paginator page='reference' add_value='type=position&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_position">
    <thead>
    <tr>
        <th>Название</th>
        <th>Руководящая?</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>
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


        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='reference' add_value='type=position&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}