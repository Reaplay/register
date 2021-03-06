<a href="action_admin.php?module=employee&action=add"><button type="button" class="btn btn-primary btn-lg btn-block">Добавить нового человека</button></a>
<hr>
{paginator page='action_admin' add_value='module=employee&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_employee">
    <thead>
    <tr>
        <th>ФИО</th>
        <th>Статус</th>
        <th>Функция (без группы)</th>
        <th>Дата приема</th>
        <th>Дата перевода</th>
        <th>Месторасположение</th>
        <th>Действие</th>
      

    </tr>
    </thead>

    <tbody data-w="employee">
    {foreach from=$data_employee item=employee}

        <tr data-id="{$employee.id}">
            <td>
               <a href="view.php?eid={$employee.id}"> {$employee.name_employee}</a>
            </td>
            <td>
               {if $employee.id_uid_post}В штате{else}Вне штата{/if}
            </td>
            <td>
                {$employee.name_functionality}
            </td>
            <td>
                {$employee.date_employment}
            </td>
            <td>
               {$employee.date_transfer}
            </td>
            <td>
                {if $employee.name_city}{$employee.name_city}, {$employee.name_address}, этаж {$employee.floor}, каб. {$employee.room}, место {$employee.place}{else}Не известно{/if}
            </td>

            <td>
                <a href="action_admin.php?module=employee&action=edit&id={$employee.id}">Изменить</a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='action_admin' add_value='module=employee&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}