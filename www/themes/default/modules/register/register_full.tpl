{paginator page='register' add_value='type=full&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}

<div style="overflow: auto;">
    <table class="table table-striped table-hover table-bordered" id="list_employee">
        <thead>
        <tr>
            <th>Идентификатор штатной должности</th>
            <th>ФИО</th>
            <th>Должность</th>
            <th>Функц. сфера</th>
            <th>Название подразделение</th>
            <th>ДО/ОО</th>
            <th>Вложенное подразделение 1</th>
            <th>Вложенное подразделение 2</th>
            <th>Вложенное подразделение 3</th>
            <th>Вложенное подразделение 4</th>
            <th>Дирекция ДРО</th>
            <th>Курирующий член УК</th>
            <th>Куратор в ЦО</th>
            <th>ФР в ЦО или регионе</th>
            <th>ФИО ФР</th>
            <th>email  ФР</th>
            <th>Должность ФР</th>
            <th>ФИО АР</th>
            <th>Должность АР</th>
            <th>Группа функций</th>
            <th>Функция</th>
            <th>Стратегический проект</th>
            <th>ЦО/РЦК/РП</th>
            <th>МВЗ</th>
            <th>Модель</th>
            <th>Штат?</th>
            <th>Драфт</th>
            <th>Факт</th>
            <th>FTE</th>
            <th>Дата ввода шт/ед/  В штатное расписание по приказу</th>
            <th>Ш.Е. передана?</th>
            <th>Дата приема сотрудника на работу</th>
            <th>Дата перевода</th>
            <th>Город</th>
            <th>Адрес</th>
            <th>Этаж</th>
            <th>Комната</th>
            <th>Место</th>
            <th>Готовность?</th>
            <th>Дата готовности</th>
            <th>Бронь?</th>
            <th>Дата бронирования</th>
            <th>Свободно?</th>
            <th>Занято?</th>
            <th>Дата занятия</th>


        </tr>
        </thead>

        <tbody data-w="employee">
        {foreach from=$data_employee item=employee}

            <tr data-id="{$employee.id}">
                <td>{if $employee.uid_post}{$employee.uid_post}{else}Вне штата{/if}</td>
                <td><a href="view.php?eid={$employee.e_id}">{$employee.name_employee}</a> <i class="fa fa-external-link"></i></td>
                <td>{$employee.name_position}</td>
                <td>{$employee.name_block}</td>
                <td>{$employee.department.0}</td>
                <td>

                </td>
                <td>{$employee.department.1}</td>
                <td>{$employee.department.2}</td>
                <td>{$employee.department.3}</td>
                <td>{$employee.department.4}</td>
                <td>{$employee.name_direction}</td>
                <td>{$employee.name_cur_direction}</td>
                <td>
2
                </td>
                <td>
3
                </td>
                <td>{$employee.func_mgr.name_employee}</td>
                <td>{$employee.func_mgr.email}</td>
                <td>{$employee.func_mgr.name_position}</td>
                <td>{$employee.adm_mgr.name_employee}</td>
                <td>{$employee.adm_mgr.name_position}</td>
                <td>{$employee.group_functionality}</td>
                <td>{$employee.name_functionality}</td>
                <td>
                    <b>Проект</b>
                </td>
                <td>{$employee.name_rck}</td>
                <td>{$employee.name_mvz}</td>
                <td>
<b>Модель</b>
                </td>
                <td>{if $employee.uid_post}Да{else}Нет{/if}</td>
                <td>{if $employee.draft}Нет{else}Да{/if}</td>
                <td>{if $employee.draft}Да{else}Нет{/if}</td>
                <td>{$employee.fte}</td>
                <td>{$employee.date_entry}</td>
                <td>{if $employee.transfer}Да{else}Нет{/if}</td>
                <td>{$employee.date_employment}</td>
                <td>{$employee.date_transfer}</td>
                <td>{$employee.name_city}</td>
                <td>{$employee.name_address}</td>
                <td>{$employee.floor}</td>
                <td>{$employee.room}</td>
                <td>{$employee.place}</td>
                <td>{if $employee.ready}Да{else}Нет{/if}</td>
                <td>{$employee.date_ready}</td>
                <td>{if $employee.reservation}Да{else}Нет{/if}</td>

                <td>{$employee.date_reservation}</td>

                <td>{if !$employee.occupy}Да{else}Нет{/if}</td>
                <td>{if $employee.occupy}Да{else}Нет{/if}</td>
                <td>{$employee.date_occupy}</td>

            </tr>
        {/foreach}
        </tbody>
    </table>

</div>
{paginator page='register' add_value='type=full&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}