<div class="alert alert-warning margin-bottom-30"><!-- warning -->
    Все изменения вносимые в данной опции влияют на всех пользователей
</div>

<a href="action_admin.php?module=location_address&action=add"><button type="button" class="btn btn-primary btn-lg btn-block">Добавить новый адрес</button></a>
<hr>
{paginator page='action_admin' add_value='module=location_address&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_address">
    <thead>
    <tr>
        <th>Город</th>
        <th>Адрес</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>
        <th>Действия</th>
    </tr>
    </thead>

    <tbody data-w="address">
    {foreach from=$data_address item=address}

        <tr data-id="{$address.id}">
            <td>
                {$address.name_city}
            </td>
            <td>
                {$address.name_address}
            </td>
            <td>
               {$address.added}
            </td>
            <td class="center">
                {if !$address.last_update}<span class="label label-sm label-default">Не изменялось</span>{else}{$address.last_update}{/if}
            </td>

            <td>
               <a href="action_admin.php?module=location_address&action=edit&id={$address.id}">Изменить</a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='action_admin' add_value='module=location_address&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}