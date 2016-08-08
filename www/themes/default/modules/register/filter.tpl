<div class="toggle toggle-transparent toggle-bordered-simple" >

    <div class="toggle">
        <label>Фильтры</label>
        <div class="toggle-content" >
            <form  action="register.php" method="get">
                <input type="hidden" name="type" value="{$smarty.get.type}">
                <div class="row margin-bottom-10">

                    <div class="col-md-3">
                        <h4>Дирекции</h4>
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

                    <div class="col-md-3">
                        <h4>По модели</h4>
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
                        <h4>По городу</h4>
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
                        <h4>По подразделению</h4>
                        <div class="fancy-form fancy-form-select">
                            <select class="form-control select2" name="department" style="width: 253px">
                                <option value="0">Все</option>
                                {foreach from=$data_filter.department key=id item=name}
                                    <option value="{$id}" {if $id==$smarty.get.department}selected="selected"{/if}>{$name}</option>
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
