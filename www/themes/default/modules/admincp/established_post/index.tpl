<a href="action_admin.php?module=established_post&action=add"><button type="button" class="btn btn-primary btn-lg btn-block">Добавить новую единицу</button></a>
<hr>
{paginator page='action_admin' add_value='module=established_post&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}
<table class="table table-striped table-hover table-bordered" id="list_established_post">
    <thead>
    <tr>
        <th>Идентификатор</th>
        <th>Должность</th>
        <th>Подразделение</th>
        <th>Дирекция</th>
        <th>МВЗ</th>
        <th>Дата ввода</th>
        <th>Город</th>
        <th>Функц. рук-ль</th>
        <th>Адм. рук-ль</th>
        <th>Статус</th>
        <th>Перевод</th>
        <th>Действие</th>

    </tr>
    </thead>

    <tbody data-w="established_post">
    {foreach from=$data_established_post item=established_post}

        <tr data-id="{$established_post.id}">
            <td>
                {if $established_post.uid_post}{$established_post.uid_post}{else}N/A{/if}
            </td>
            <td>
                {$established_post.name_position}
            </td>
            <td>
                {$established_post.name_department}
            </td>
            <td>
                {$established_post.name_direction}
            </td>
            <td>
                {$established_post.name_mvz}
            </td>
            <td>
                {$established_post.date_entry}
            </td>
            <td>
                {$established_post.name_city}
            </td>
            <td>
                {$established_post.functional_manager}
            </td>
            <td>
                {$established_post.administrative_manager}
            </td>
            <td>
                {if $established_post.draft == 1}Факт{elseif $established_post.draft == 0}Драфт{else}N/A{/if}
            </td>
            <td>
                {if $established_post.transfer}Да{else}Нет{/if}
            </td>
            <td>
                <a href="action_admin.php?module=established_post&action=edit&id={$established_post.id}">Изменить</a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
{paginator page='action_admin' add_value='module=established_post&' add_link=$add_link add_sort=$add_sort  num_page=$paginator.page max_page=$paginator.max_page count=$paginator.count}