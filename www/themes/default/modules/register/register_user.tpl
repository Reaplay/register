{paginator page='register' add_value='type=full&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}

<div  class="table-responsive" id="scroll">
    <table class="table table-striped table-hover table-bordered" id="list_employee">
        <thead>
        <tr>
            {if !$user_view.1}<th>Идентификатор штатной должности</th>{/if}
            {if !$user_view.2}<th>ФИО</th>{/if}
            {if !$user_view.3}<th>Должность</th>{/if}
            {if !$user_view.4}<th>Функц. сфера</th>{/if}
            {if !$user_view.5}<th>Название подразделение</th>{/if}
            {if !$user_view.6}<th>ДО/ОО</th>{/if}
            {if !$user_view.7}<th>Вложенное подразделение 1</th>{/if}
            {if !$user_view.8}<th>Вложенное подразделение 2</th>{/if}
            {if !$user_view.9}<th>Вложенное подразделение 3</th>{/if}
            {if !$user_view.10}<th>Вложенное подразделение 4</th>{/if}
            {if !$user_view.11}<th>Дирекция ДРО</th>{/if}
            {if !$user_view.12}<th>Курирующий член УК</th>{/if}
            {if !$user_view.13}<th>Куратор в ЦО</th>{/if}
            {if !$user_view.14}<th>ФР в ЦО или регионе</th>{/if}
            {if !$user_view.15}<th>ФИО ФР</th>{/if}
            {if !$user_view.16}<th>email  ФР</th>{/if}
            {if !$user_view.17}<th>Должность ФР</th>{/if}
            {if !$user_view.18}<th>ФИО АР</th>{/if}
            {if !$user_view.19}<th>Должность АР</th>{/if}
            {if !$user_view.20}<th>Группа функций</th>{/if}
            {if !$user_view.21}<th>Функция</th>{/if}
            {if !$user_view.22}<th>Стратегический проект</th>{/if}
            {if !$user_view.23}<th>ЦО/РЦК/РП</th>{/if}
            {if !$user_view.24}<th>МВЗ</th>{/if}
            {if !$user_view.25}<th>Модель</th>{/if}
            {if !$user_view.26}<th>Штат?</th>{/if}
            {if !$user_view.27}<th>Драфт</th>{/if}
            {if !$user_view.28}<th>Факт</th>{/if}
            {if !$user_view.29}<th>FTE</th>{/if}
            {if !$user_view.30}<th>Дата ввода шт/ед/  В штатное расписание по приказу</th>{/if}
            {if !$user_view.31}<th>Ш.Е. передана?</th>{/if}
            {if !$user_view.32}<th>Дата приема сотрудника на работу</th>{/if}
            {if !$user_view.33}<th>Дата перевода</th>{/if}
            {if !$user_view.34}<th>Город</th>{/if}
            {if !$user_view.35}<th>Адрес</th>{/if}
            {if !$user_view.36}<th>Этаж</th>{/if}
            {if !$user_view.37}<th>Комната</th>{/if}
            {if !$user_view.38}<th>Место</th>{/if}
            {if !$user_view.39}<th>Готовность?</th>{/if}
            {if !$user_view.40}<th>Дата готовности</th>{/if}
            {if !$user_view.41}<th>Бронь?</th>{/if}
            {if !$user_view.42}<th>Дата бронирования</th>{/if}
            {if !$user_view.43}<th>Свободно?</th>{/if}
            {if !$user_view.44}<th>Занято?</th>{/if}
            {if !$user_view.45}<th>Дата занятия</th>{/if}


        </tr>
        </thead>

        <tbody data-w="employee">
        {foreach from=$data_employee item=employee}

            <tr data-id="{$employee.id}">
                {if !$user_view.1}<td>{if $employee.uid_post}{$employee.uid_post}{else}Вне штата{/if}</td>{/if}
                {if !$user_view.2}<td><a href="view.php?eid={$employee.e_id}">{$employee.name_employee}</a> <i class="fa fa-external-link"></i></td>{/if}
                {if !$user_view.3}<td>{$employee.name_position}</td>{/if}
                {if !$user_view.4}<td>{$employee.name_block}</td>{/if}
                {if !$user_view.5}<td>{$employee.department.0}</td>{/if}
                {if !$user_view.6}<td>{$employee.type_office}</td>{/if}
                {if !$user_view.7}<td>{$employee.department.1}</td>{/if}
                {if !$user_view.8}<td>{$employee.department.2}</td>{/if}
                {if !$user_view.9}<td>{$employee.department.3}</td>{/if}
                {if !$user_view.10}<td>{$employee.department.4}</td>{/if}
                {if !$user_view.11}<td>{$employee.name_direction}</td>{/if}
                {if !$user_view.12}<td>{$employee.name_cur_direction}</td>{/if}
                {if !$user_view.13}<td>
2
                </td>{/if}
                {if !$user_view.14}<td>{if $employee.func_mgr.lc_co}ЦО{else}Регион{/if}</td>{/if}
                {if !$user_view.15}<td><a href="view.php?eid={$employee.func_mgr.e_id}">{$employee.func_mgr.name_employee}</a> <i class="fa fa-external-link"></i></td>{/if}
                {if !$user_view.16}<td>{$employee.func_mgr.email}</td>{/if}
                {if !$user_view.17}<td>{$employee.func_mgr.name_position}</td>{/if}
                {if !$user_view.18}<td>{$employee.adm_mgr.name_employee}</td>{/if}
                {if !$user_view.19}<td>{$employee.adm_mgr.name_position}</td>{/if}
                {if !$user_view.20}<td>{$employee.group_functionality}</td>{/if}
                {if !$user_view.21}<td>{$employee.name_functionality}</td>{/if}
                {if !$user_view.22}<td>{$employee.project}</td>{/if}
                {if !$user_view.23}<td>{$employee.name_rck}</td>{/if}
                {if !$user_view.24}<td>{$employee.name_mvz}</td>{/if}
                {if !$user_view.25}<td>{$employee.model}</td>{/if}
                {if !$user_view.26}<td>{if $employee.uid_post}Да{else}Нет{/if}</td>{/if}
                {if !$user_view.27}<td>{if $employee.draft}Нет{else}Да{/if}</td>{/if}
                {if !$user_view.28}<td>{if $employee.draft}Да{else}Нет{/if}</td>{/if}
                {if !$user_view.29}<td>{$employee.fte}</td>{/if}
                {if !$user_view.30}<td>{$employee.date_entry}</td>{/if}
                {if !$user_view.31}<td>{if $employee.transfer}Да{else}Нет{/if}</td>{/if}
                {if !$user_view.32}<td>{$employee.date_employment}</td>{/if}
                {if !$user_view.33}<td>{$employee.date_transfer}</td>{/if}
                {if !$user_view.34}<td>{$employee.name_city}</td>{/if}
                {if !$user_view.35}<td>{$employee.name_address}</td>{/if}
                {if !$user_view.36}<td>{$employee.floor}</td>{/if}
                {if !$user_view.37}<td>{$employee.room}</td>{/if}
                {if !$user_view.38}<td>{$employee.place}</td>{/if}
                {if !$user_view.39}<td>{if $employee.ready}Да{else}Нет{/if}</td>{/if}
                {if !$user_view.40}<td>{$employee.date_ready}</td>{/if}
                {if !$user_view.41}<td>{if $employee.reservation}Да{else}Нет{/if}</td>{/if}
                {if !$user_view.42}<td>{$employee.date_reservation}</td>{/if}
                {if !$user_view.43}<td>{if !$employee.occupy}Да{else}Нет{/if}</td>{/if}
                {if !$user_view.44}<td>{if $employee.occupy}Да{else}Нет{/if}</td>{/if}
                {if !$user_view.45}<td>{$employee.date_occupy}</td>{/if}

            </tr>
        {/foreach}
        </tbody>
    </table>

</div>
{paginator page='register' add_value='type=full&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}