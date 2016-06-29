<div class="alert alert-warning margin-bottom-30"><!-- warning -->
    Все изменения вносимые в данной опции влияют на всех пользователей
</div>

<a href="action_admin.php?module=location_city&action=add"><button type="button" class="btn btn-primary btn-lg btn-block">Добавить новый город</button></a>
<hr>
{paginator page='action_admin' add_value='module=location_city&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_city">
    <thead>
    <tr>
        <th>Название</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>
        <th>Действия</th>
    </tr>
    </thead>

    <tbody data-w="city">
    {foreach from=$data_city item=city}

        <tr data-id="{$city.id}">
            <td>
                {$city.name_city}
            </td>
            <td>
               {$city.added}
            </td>
            <td class="center">
                {if !$city.last_update}<span class="label label-sm label-default">Не изменялось</span>{else}{$city.last_update}{/if}
            </td>

            <td>
               <a href="action_admin.php?module=location_city&action=edit&id={$city.id}">Изменить</a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='action_admin' add_value='module=location_city&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}