<div class="row">

    <div class="col-md-12 col-sm-12">


        <div class="panel panel-default">
            <div class="panel-heading panel-heading-transparent">
                <h2 class="panel-title">История сотрудника {$data_emp.name_employee}</h2>
            </div>

            <div class="panel-body">
                {*<input type="hidden" value="contact_send" name="action">*}
                <div class="row">
                    <form class="nomargin sky-form " method="get" action="history.php?id={$data_emp.id}">
                        <input type="hidden" name="id" value="{$data_emp.id}">
                    <div class="form-group">

                        <div class="col-md-6 col-sm-6">
                            <input type="text" class="form-control datepicker" data-format="dd/mm/yyyy" data-lang="ru" data-RTL="false" placeholder="Выберите дату" name="date_history">
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <button type="submit" class="btn btn-primary">Применить</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        Текущая ревизия сотрудника №{$data_emp.revision} от {$data_emp.update}<br />
                        Текущая ревизия штатной единицы №{$data_esp.revision} от {$data_esp.update}<br /><br />
                        <h5>Доступные ревизии:</h5>
                     </div>
                    <div class="toggle">
                        <label>Для сотрудника</label>
                        <div class="toggle-content" style="display: none;">
                            {$data_revision_emp}
                        </div>
                    </div>
                    <div class="toggle">
                        <label>Для  штатной единицы</label>
                        <div class="toggle-content" style="display: none;">
                            {$data_revision_esp}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>


