<form class="clearfix well well-sm search-big nomargin" action="search.php" method="get">
    <div class="input-group">

        <div class="input-group-btn">
            <button data-toggle="dropdown" class="btn btn-default input-lg dropdown-toggle noborder-right" type="button" aria-expanded="false">
                Поиск по <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <label class="radio">
                        <input type="radio" name="radio-btn" value="1" checked="checked">
                        <i></i> Radio 1

                    </label>
                </li>
                <li>
                    <label class="radio">
                    <input type="radio" name="radio-btn" value="2">
                    <i></i> Radio 2
                    </label>
                </li>


            </ul>
        </div>

        <input type="text" placeholder="Найти..." class="form-control input-lg" name="s">
        <div class="input-group-btn">
            <button class="btn btn-default input-lg noborder-left" type="submit">
                <i class="fa fa-search fa-lg nopadding"></i>
            </button>
        </div>
    </div>

</form>
{if $data_search}
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Идентификатор ШЕ</th>
            <th>ФИО</th>
            <th>Должность</th>
            <th>Город</th>

        </tr>
        </thead>

        <tbody data-w="client">
        {foreach from=$data_search item=search}

            <tr data-id="{$search.id}">
                <td>
                    <a href="view.php?uid={$search.uid_post}">{$search.uid_post}</a>
                    <i class="fa fa-external-link"></i>
                </td>
                <td>
                    <a href="view.php?eid={$search.e_id}">{$search.name_employee}</a>
                    <i class="fa fa-external-link"></i>
                </td>
                <td>
                    {$search.name_position}
                </td>
                <td>
                    {$search.name_city}
                </td>


            </tr>
        {/foreach}
        </tbody>
    </table>
    </table>
    {/if}
