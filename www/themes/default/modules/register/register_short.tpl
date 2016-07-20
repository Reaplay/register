
{paginator page='register' add_value='type=short&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_employee">
    <thead>
    <tr>
        <th>Идентификатор штатной должности</th>
        <th>ФИО</th>
        <th>Дирекция ДРО</th>
        <th>ФИО ФР</th>
        <th>ФИО АР</th>
        <th>Группа функций</th>
        <th>ЦО/РЦК/РП</th>
        <th>Модель</th>
        <th>Штат?</th>
        <th>Драфт/Факт</th>
        <th>Город</th>



    </tr>
    </thead>

    <tbody data-w="employee">
    {foreach from=$data_employee item=employee}

        <tr data-id="{$employee.id}">
            <td>
                {if $employee.uid_post}{$employee.uid_post}{else}Вне штата{/if}
            </td>
            <td>
                <a href="view.php?eid={$employee.e_id}">{$employee.name_employee}</a> <i class="fa fa-external-link"></i>
            </td>
            <td>
                {$employee.name_direction}
            </td>
            <td>
                {$employee.func_mgr.name_employee}
            </td>
            <td>
                {$employee.adm_mgr.name_employee}
            </td>
            <td>

                {$employee.name_functionality}
            </td>
            <td>
                {$employee.name_rck}
            </td>
            <td>

            </td>
            <td>
                {if $employee.uid_post}Да{else}Нет{/if}
            </td>
            <td>
                {if $employee.draft}Факт{else}Драфт{/if}
            </td>
            <td>
                {$employee.name_city}
            </td>


        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='register' add_value='type=short&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}