{paginator page='reference' add_value='type=department&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_department">
    <thead>
    <tr>
        <th>Название подразделение</th>
        <th>Родительское подразделение</th>
        <th>Тип офиса</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>

    </tr>
    </thead>

    <tbody data-w="department">
    {foreach from=$data_department item=department}

        <tr data-id="{$department.id}">
            <td>
                {$department.name_department}
            </td>
            <td>
                {$department.name_parent_department}
            </td>
            <td>
                {$department.name_type_office}
            </td>
            <td>
                {$department.added}
            </td>
            <td class="center">
                {if !$department.last_update}<span class="label label-sm label-default">Не изменялось</span>{else}{$department.last_update}{/if}
            </td>


        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='reference' add_value='type=department&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}