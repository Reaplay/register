{*идентификатор штатной должности: <b></b> <br>
ФИО полностью: <b></b> <br>
Должность: <b></b> <br>
Функциональная сфера ( тоже является орг. Единицей): <b>{$employee_department.0}</b> <br>
Название подразделения: <b>{$employee_department.1}</b> <br>
Дополнительный или операционный офис: <b>{$employee_department.type_office}</b> <br>
Вложенное подразделение 1: <b>{$employee_department.2}</b> <br>
Вложенное подразделение 2: <b>{$employee_department.3}</b> <br>
Вложенное подразделение 3: <b>{$employee_department.4}</b> <br>
Вложенное подразделение 4: <b>{$employee_department.5}</b> <br>
<h3>Дирекция</h3>
Дирекция ЦО: <b>{$employee.name_direction}</b> <br>
Курирующий член УК: <b>{$employee.name_curator}</b> <br>
Куратор в ЦО: <b>{$employee}</b> <br>
<h3>Функциональный руководитель</h3>
ФР в ЦО или регионе: <b>{$employee}</b> <br>
ФИО ФР: <b>{$employee_f_m.name_employee}</b> <br>
email  ФР: <b>{$employee_f_m.email}</b> <br>
Должность ФР: <b>{$employee_f_m.name_position}</b> <br>
<h3>Административный руководитель</h3>
ФИО АР: <b>{$employee_a_m.name_employee}</b> <br>
Должность АР: <b>{$employee_a_m.name_position}</b> <br>
<h3>Функция</h3>
Группа функций: <b>{foreach from=$list_functionality.group item=f_group}{$f_group},{/foreach}</b> <br>
Функция: <b>{foreach from=$list_functionality.function item=f_function}{$f_function},{/foreach}</b> <br>
Стратегический проект: <b>{$employee}</b> <br>
<h3>Сведения о сотруднике</h3>
РЦК?: <b>{$employee.name_rck}</b> <br>
мвз: <b>{$employee.name_mvz}</b> <br>
Модель (функциональная/сервисная) всплывающий список: <b>{$employee}</b> <br>
Штат?: <b>{if $employee.uid_post}Да{else}Нет{/if}</b> <br>
драфт: <b>{if $employee.draft}Да{else}Нет{/if}</b> <br>
факт: <b>{if $employee.draft}Нет{else}Да{/if}</b> <br>
FTE (процент занятости): <b>{$employee}</b> <br>
Дата ввода шт/ед/  В штатное расписание по приказу: <b>{$employee.date_entry}</b> <br>
Ш.Е. передана?: <b>{if $employee.transfer}Да{else}Нет{/if}</b> <br>
Дата приема сотрудника на работу: <b>{$employee.date_employment}</b> <br>
ДАТА ПЕРЕВОДА: <b>{$employee.date_transfer}</b> <br>
<h3>Место работы</h3>
Город: <b>{$employee.name_city}</b> <br>
Адрес: <b>{$employee.name_address}</b> <br>
Этаж: <b>{$employee.floor}</b> <br>
Комната: <b>{$employee.room}</b> <br>
Место: <b>{$employee.place}</b> <br>
Готовность?: <b>{if $employee.ready}Да{else}Нет{/if}</b> <br>
Дата готовности: <b>{if $employee.date_ready}{$employee.date_ready}{else}Нет{/if}</b> <br>
Бронь?: <b>{if $employee.reservation}Да{else}Нет{/if}</b> <br>
Дата бронирования: <b>{if $employee.date_reservation}{$employee.date_reservation}{else}Нет{/if}</b> <br>
Свободно?: <b>{if !$employee.occupy}Да{else}Нет{/if}</b> <br>
Занято?: <b>{if $employee.occupy}Да{else}Нет{/if}</b> <br>
Дата занятия: <b>{if $employee.date_occupy}{$employee.date_occupy}{else}Нет{/if}</b> <br>
*}

{foreach from=$data_employee item=employee name=count_emp}
    {if $smarty.foreach.count_emp.total > 1}
        <div class="toggle">
        <label>{$smarty.foreach.foo.index + 1}. {$employee.em_name}</label>
        <div class="toggle-content" style="display: none;">
    {/if}

    <div class="row">
    <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="title elipsis">
                        <strong>Сведения о сотруднике [<a href="action_admin.php?module=employee&action=edit&id={$employee.eid}">Редактировать</a>]</strong>
                    </span>
                </div>

                <div class="panel-body">
                    <h6>Основные</h6>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <b>ФИО:</b>&nbsp;{$employee.em_name}<br />
                            <b>ID ШЕ:</b>&nbsp;<a href="view.php?uid={$employee.uid_post}">{$employee.uid_post}</a>  <i class="fa fa-external-link"></i><br />
                            <b>Должность:</b>&nbsp;{$employee.name_position}<br />
                            <b>Блок:</b>&nbsp;{$employee.name_block}<br />
                            {foreach from=$employee.department item=name_department name=count_dep}

                                <b>Вложенное подразделение {$smarty.foreach.count_dep.iteration}:</b>{$name_department}<br />{if !$smarty.foreach.count_dep.last}&nbsp;&nbsp;{/if}
                            {/foreach}
                            <b>Дирекция:</b>&nbsp;{$employee.name_direction} ({$employee.name_curator})<br />
                            <b>Куратор в ЦО:</b><br />
                            <b>:</b><br />
                        </table>
                    </div>
                </div>

                <div class="panel-body">
                    <h6>Руководители</h6>
                    <div class="tab-content transparent">
                        <table class="table  table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Тип</th>
                                    <th>ФИО</th>
                                    <th>E-mail</th>
                                    <th>Должность</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Функц.</td>
                                    <td><a href="view.php?eid={$employee.data_f_m.e_id}">{$employee.data_f_m.name_employee}</a> <i class="fa fa-external-link"></i> ({$employee.rck_curator})</td>
                                    <td>{$employee.data_f_m.email}</td>
                                    <td>{$employee.data_f_m.name_position}</td>
                                </tr>
                                <tr>
                                    <td>Адм.</td>
                                    <td><a href="view.php?eid={$employee.data_a_m.e_id}">{$employee.data_a_m.name_employee}</a> <i class="fa fa-external-link"></i></td>
                                    <td>{$employee.data_a_m.email}</td>
                                    <td>{$employee.data_a_m.name_position}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="title elipsis">
                        <strong>Место работы</strong>
                    </span>

                </div>


                <div class="panel-body">
                    <div class="tab-content transparent">
                        <table class="table table-hover table-bordered">
                            г. <b>{$employee.name_city}</b>, <b>{$employee.name_address}</b>.
                            Этаж <b>{$employee.floor}</b>, комната: <b>{$employee.room}</b>, место: <b>{$employee.place}</b> <br><br>
                            <thead>
                                <tr>
                                    <th>Готовность</th>
                                    <th>Бронь</th>
                                    <th>Занято</th>
                                </tr>
                            </thead>
                                <tbody>
                                    <tr>
                                        <td>{if $employee.ready}Да{else}Нет{/if} {if $employee.date_ready}({$employee.date_ready}){/if}</td>
                                        <td>{if $employee.reservation}Да{else}Нет{/if} {if $employee.date_reservation}({$employee.date_reservation}){/if}</td>
                                        <td>{if $employee.occupy}Да{else}Нет{/if} {if $employee.date_occupy}({$employee.date_occupy}){/if}</td>
                                    </tr>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="title elipsis">
                        <strong>Дополнительно</strong>
                    </span>

                </div>


                <div class="panel-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>РКЦ (МВЗ)</th>
                                <th>Модель</th>
                                <th>Штат</th>
                                <th>Драфт/Факт</th>
                                <th>% Занятости</th>
                                <th>Дата ввода</th>
                                <th>ШЕ передана</th>
                                <th>Дата приёма</th>
                                <th>Дата перевода</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{$employee.name_rck} ({$employee.name_mvz})</td>
                                <td>{$employee.name_model}</td>
                                <td>{if $employee.uid_post}Да{else}Нет{/if}</td>
                                <td>{if $employee.draft}Штат{else}Драфт{/if}</td>
                                <td>{$employee.fte}</td>
                                <td>{$employee.date_entry}</td>
                                <td>{if $employee.transfer}Да{else}Нет{/if}</td>
                                <td>{$employee.date_employment}</td>
                                <td>{$employee.date_transfer}</td>
                            </tr>


                        </tbody>
                    </table>



                </div>


            </div>


        </div>
    </div>
    {if $smarty.foreach.foo.total > 1}
        </div>
        </div>
    {/if}
{/foreach}