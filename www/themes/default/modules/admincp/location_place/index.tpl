<div class="alert alert-warning margin-bottom-30"><!-- warning -->
    Все изменения вносимые в данной опции влияют на всех пользователей
</div>

<a href="action_admin.php?module=location_place&action=add"><button type="button" class="btn btn-primary btn-lg btn-block">Добавить новое место</button></a>
<hr>
<table class="table table-striped table-hover table-bordered" id="list_place">
    <thead>
    <tr>
        <th>Город</th>
        <th>Адрес</th>
        <th>Этаж</th>
        <th>Комната</th>
        <th>Место</th>
        <th>Дата добавления</th>
        <th>Последнее изменение</th>
        <th>Действия</th>
    </tr>
    </thead>

    <tbody data-w="place">
    {foreach from=$data_place item=place}

        <tr data-id="{$place.id}">
            <td>
                {$place.name_city}
            </td>
            <td>
                {$place.name_address}
            </td>
            <td>
                {$place.floor}
            </td>
            <td>
                {$place.room}
            </td>
            <td>
                {$place.place}
            </td>
            <td>
               {$place.added}
            </td>
            <td class="center">
                {if !$place.last_update}<span class="label label-sm label-default">Не изменялось</span>{else}{$place.last_update}{/if}
            </td>

            <td>
               <a href="action_admin.php?module=location_place&action=edit&id={$place.id}">Изменить</a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>