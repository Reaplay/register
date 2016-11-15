<div class="toggle toggle-transparent toggle-bordered-simple" >

    <div class="toggle">
        <label>Фильтры</label>
        <div class="toggle-content" >
            <form  action="register.php" method="get">
                <input type="hidden" name="type" value="{$smarty.get.type}">
                <input type="hidden" name="date_history" value="{$smarty.get.date_history}">
                <div class="row margin-bottom-10">

                    <div class="col-md-3">
                        <h4>Дирекция ЦО</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="direction" style="width: 253px">
                                <option value="0">Все</option>
                                {foreach from=$data_filter.direction key=id item=name}
                                    <option value="{$id}" {if $id==$smarty.get.direction}selected="selected"{/if}>{$name}</option>
                                {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>ФИО ФР</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="id_fr" style="width: 253px">
                                <option value="0">Все</option>
                                {foreach from=$data_filter.name_r key=id item=name}
                                    <option value="{$id}" {if $id==$smarty.get.id_fr}selected="selected"{/if}>{$name}</option>
                                {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>ФИО АР</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="id_ar" style="width: 253px">
                                <option value="0">Все</option>
                                {foreach from=$data_filter.name_r key=id item=name}
                                    <option value="{$id}" {if $id==$smarty.get.id_ar}selected="selected"{/if}>{$name}</option>
                                {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>ЦО/РЦК/РП</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="rck" style="width: 253px">
                                <option value="0">Все</option>
                                {foreach from=$data_filter.rck key=id item=name}
                                    <option value="{$id}" {if $id==$smarty.get.rck}selected="selected"{/if}>{$name}</option>
                                {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>


                </div>
                <div class="row margin-bottom-10">
                    <div class="col-md-3">
                        <h4>Модель</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="model" style="width: 253px">
                                <option value="0">Все</option>
                                {foreach from=$data_filter.model key=id item=name}
                                    <option value="{$id}" {if $id==$smarty.get.model}selected="selected"{/if}>{$name}</option>
                                {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>Штат</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="ep" style="width: 253px">
                                <option value="0">Все</option>
                                <option value="1" {if 1==$smarty.get.ep}selected="selected"{/if}>Да</option>
                                <option value="2" {if 2==$smarty.get.ep}selected="selected"{/if}>Нет</option>

                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>Город</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="city" style="width: 253px">
                                <option value="0">Все</option>
                                {foreach from=$data_filter.city key=id item=name}
                                    <option value="{$id}" {if $id==$smarty.get.city}selected="selected"{/if}>{$name}</option>
                                {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>История</h4>
                        <div class="fancy-form">
                            <input type="text" class="form-control datepicker" data-format="dd-mm-yyyy" data-lang="ru" data-RTL="false" value="{$smarty.get.date_history}" name="date_history">
                        </div>
                    </div>

                </div>
                <div class="row margin-bottom-10">
                    <div class="col-md-3">
                        <h4>Подразделение 1</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="department[]" style="width: 253px"">
                                <option value="0">Все</option>
                            {foreach from=$data_filter.department item=department}
                                {if $department.level == 0}<option value="{$department.id}" {if $department.id == $data.id_department.0}selected="selected"{/if}>{$department.name_department}</option>{/if}
                            {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h4>Подразделение 2</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="department[]" style="width: 253px"">
                            <option value="0">Все</option>
                            {foreach from=$data_filter.department item=department}
                                {if $department.level == 1}<option value="{$department.id}" {if $department.id == $data.id_department.1}selected="selected"{/if}>{$department.name_department}</option>{/if}
                            {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h4>Подразделение 3</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="department[]" style="width: 253px"">
                            <option value="0">Все</option>
                            {foreach from=$data_filter.department item=department}
                                {if $department.level == 2}<option value="{$department.id}" {if $department.id == $data.id_department.2}selected="selected"{/if}>{$department.name_department}</option>{/if}
                            {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h4>Подразделение 4</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="department[]" style="width: 253px"">
                            <option value="0">Все</option>
                            {foreach from=$data_filter.department item=department}
                                {if $department.level == 3}<option value="{$department.id}" {if $department.id == $data.id_department.3}selected="selected"{/if}>{$department.name_department}</option>{/if}
                            {/foreach}
                            </select>
                            <i class="fancy-arrow"></i>
                        </div>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary">Применить</button>
            </form>

        </div>
    </div>
</div>
