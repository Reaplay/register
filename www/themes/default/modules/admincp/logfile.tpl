<ul class="nav nav-tabs">

    <li><a href="action_admin.php?module=logfile">Другое</a></li>
    <li><a href="action_admin.php?module=logfile&type=sql_errors">Ошибки MySQL</a></li>
    <li><a href="action_admin.php?module=logfile&type=client">Клиенты</a></li>
    <li><a href="action_admin.php?module=logfile&type=card">Карты</a></li>
    <li><a href="action_admin.php?module=logfile&type=user">Пользователи</a></li>

</ul>

<!-- HTML DATATABLES -->
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Пользователь</th>
            <th>Текст</th>
            <th>Действия</th>
        </tr>
        </thead>

        <tbody data-w="log">
        {foreach from=$data_log item=log}

            <tr data-id="{$log.id}">
                <td>
                    {$log.added}
                </td>
                <td>
                    {$log.name}
                </td>
                 <td class="center">
                     {$log.txt}
                </td>
                <td>
                    {$log.action}
                </td>

            </tr>
        {/foreach}
        </tbody>
    </table>
    </table>