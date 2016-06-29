            <form  action="reference.php" method="get">
                <input type="hidden" name="type" value="employee">
                <div class="row margin-bottom-10">
                        <div class="col-md-3">
                            <h4>Фильтр по статусу</h4>
                            <div class="fancy-form fancy-form-select">
                                <select class="form-control select2" name="status">
                                    <option value="0">Все</option>
                                    <option value="1">В штате</option>
                                    <option value="2">Вне штата</option>

                                </select>
                                <i class="fancy-arrow"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4>Фильтр по функции</h4>
                            <div class="fancy-form fancy-form-select">
                                <select class="form-control select2" name="function">
                                    <option value="0">Все</option>
                                    {$data_functions}
                                </select>
                                <i class="fancy-arrow"></i>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <h4>По городу</h4>
                            <div class="fancy-form fancy-form-select">
                                <select class="form-control select2" name="city">
                                    <option value="0">Все</option>
                                    {$data_city}
                                </select>
                                <i class="fancy-arrow"></i>
                            </div>
                        </div>
                        <div class="col-md-3">

                            <h4>Фильтр должности</h4>
                            <div class="fancy-form fancy-form-select">
                                <select class="form-control select2" name="position">
                                    <option value="0">Все</option>
                                    {$data_position}
                                </select>
                                <i class="fancy-arrow"></i>
                            </div>

                        </div>
                    </div>
                <button type="submit" class="btn btn-primary">Применить</button>
            </form>

{paginator page='reference' add_value='type=employee&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_employee">
    <thead>
    <tr>
        <th>ФИО</th>
        <th>Статус</th>
        <th>Функция (без группы)</th>
        <th>Дата приема</th>
        <th>Дата перевода</th>
        <th>Месторасположение</th>



    </tr>
    </thead>

    <tbody data-w="employee">
    {foreach from=$data_employee item=employee}

        <tr data-id="{$employee.id}">
            <td>
                <a href="view.php?eid={$employee.id}">{$employee.name_employee}</a> <i class="fa fa-external-link"></i>
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


        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='reference' add_value='type=employee&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}