{*идентификатор штатной должности: <b></b> <br>
ФИО полностью: <b></b> <br>
Должность: <b></b> <br>
Функциональная сфера ( тоже является орг. Единицей): <b>{$data_department.0}</b> <br>
Название подразделения: <b>{$data_department.1}</b> <br>
Дополнительный или операционный офис: <b>{$data_department.type_office}</b> <br>
Вложенное подразделение 1: <b>{$data_department.2}</b> <br>
Вложенное подразделение 2: <b>{$data_department.3}</b> <br>
Вложенное подразделение 3: <b>{$data_department.4}</b> <br>
Вложенное подразделение 4: <b>{$data_department.5}</b> <br>
<h3>Дирекция</h3>
Дирекция ЦО: <b>{$row.name_direction}</b> <br>
Курирующий член УК: <b>{$row.name_curator}</b> <br>
Куратор в ЦО: <b>{$row}</b> <br>
<h3>Функциональный руководитель</h3>
ФР в ЦО или регионе: <b>{$row}</b> <br>
ФИО ФР: <b>{$data_f_m.name_employee}</b> <br>
email  ФР: <b>{$data_f_m.email}</b> <br>
Должность ФР: <b>{$data_f_m.name_position}</b> <br>
<h3>Административный руководитель</h3>
ФИО АР: <b>{$data_a_m.name_employee}</b> <br>
Должность АР: <b>{$data_a_m.name_position}</b> <br>
<h3>Функция</h3>
Группа функций: <b>{foreach from=$list_functionality.group item=f_group}{$f_group},{/foreach}</b> <br>
Функция: <b>{foreach from=$list_functionality.function item=f_function}{$f_function},{/foreach}</b> <br>
Стратегический проект: <b>{$row}</b> <br>
<h3>Сведения о сотруднике</h3>
РЦК?: <b>{$row.name_rck}</b> <br>
мвз: <b>{$row.name_mvz}</b> <br>
Модель (функциональная/сервисная) всплывающий список: <b>{$row}</b> <br>
Штат?: <b>{if $row.uid_post}Да{else}Нет{/if}</b> <br>
драфт: <b>{if $row.draft}Да{else}Нет{/if}</b> <br>
факт: <b>{if $row.draft}Нет{else}Да{/if}</b> <br>
FTE (процент занятости): <b>{$row}</b> <br>
Дата ввода шт/ед/  В штатное расписание по приказу: <b>{$row.date_entry}</b> <br>
Ш.Е. передана?: <b>{if $row.transfer}Да{else}Нет{/if}</b> <br>
Дата приема сотрудника на работу: <b>{$row.date_employment}</b> <br>
ДАТА ПЕРЕВОДА: <b>{$row.date_transfer}</b> <br>
<h3>Место работы</h3>
Город: <b>{$row.name_city}</b> <br>
Адрес: <b>{$row.name_address}</b> <br>
Этаж: <b>{$row.floor}</b> <br>
Комната: <b>{$row.room}</b> <br>
Место: <b>{$row.place}</b> <br>
Готовность?: <b>{if $row.ready}Да{else}Нет{/if}</b> <br>
Дата готовности: <b>{if $row.date_ready}{$row.date_ready}{else}Нет{/if}</b> <br>
Бронь?: <b>{if $row.reservation}Да{else}Нет{/if}</b> <br>
Дата бронирования: <b>{if $row.date_reservation}{$row.date_reservation}{else}Нет{/if}</b> <br>
Свободно?: <b>{if !$row.occupy}Да{else}Нет{/if}</b> <br>
Занято?: <b>{if $row.occupy}Да{else}Нет{/if}</b> <br>
Дата занятия: <b>{if $row.date_occupy}{$row.date_occupy}{else}Нет{/if}</b> <br>
*}
<div class="row">
    <div class="col-md-6">

        <div class="panel panel-default">
            <div class="panel-heading">
				<span class="title elipsis">
					<strong>Сведения о сотруднике</strong>
				</span>
            </div>

            <div class="panel-body">
                <h6>Основные</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <b>ФИО:</b> {$row.em_name}<br />
                        <b>ID ШЕ:</b> {$row.uid_post}<br />
                        <b>Должность:</b> {$row.name_position}<br />
                        <b>Подразделение:</b> {$row.name_block} => {$data_department}<br />
                        <b>Дирекция:</b> {$row.name_direction} ({$row.name_curator})<br />
                        <b>Куратор в ЦО:</b><br />
                        <b>:</b><br />
                    </table>
                </div>
            </div>

            <div class="panel-body">
                <h6>Руководители</h6>
                <div class="tab-content transparent">
                    <table class="table table-striped table-hover table-bordered">
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
                                <td>{$data_f_m.name_employee} ({$row.rck_curator})</td>
                                <td>{$data_f_m.email}</td>
                                <td>{$data_f_m.name_position}</td>
                            </tr>
                            <tr>
                                <td>Адм.</td>
                                <td>{$data_a_m.name_employee}</td>
                                <td>{$data_a_m.email}</td>
                                <td>{$data_a_m.name_position}</td>
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
                    <table class="table table-striped table-hover table-bordered">
                        г. <b>{$row.name_city}</b>, <b>{$row.name_address}</b>.
                        Этаж <b>{$row.floor}</b>, комната: <b>{$row.room}</b>, место: <b>{$row.place}</b> <br><br>
                        <thead>
                            <tr>
                                <th>Готовность</th>
                                <th>Бронь</th>
                                <th>Занято</th>
                            </tr>
                        </thead>
                            <tbody>
                                <tr>
                                    <td>{if $row.ready}Да{else}Нет{/if} {if $row.date_ready}({$row.date_ready}){/if}</td>
                                    <td>{if $row.reservation}Да{else}Нет{/if} {if $row.date_reservation}({$row.date_reservation}){/if}</td>
                                    <td>{if $row.occupy}Да{else}Нет{/if}</> {if $row.date_occupy}({$row.date_occupy}){/if}</td>
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
                <table class="table table-striped table-hover table-bordered">
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
                            <td>{$row.name_rck} ({$row.name_mvz})</td>
                            <td>{$row}</td>
                            <td>{if $row.uid_post}Да{else}Нет{/if}</td>
                            <td>{if $row.draft}Штат{else}Драфт{/if}</td>
                            <td>{$row.fte}</td>
                            <td>{$row.date_entry}</td>
                            <td>{if $row.transfer}Да{else}Нет{/if}</td>
                            <td>{$row.date_employment}</td>
                            <td>{$row.date_transfer}</td>
                        </tr>


                    </tbody>
                </table>



            </div>


        </div>


    </div>
</div>