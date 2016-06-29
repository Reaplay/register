<h2>Статус базы данных (MYSQL)</h2><br />
<table id="mysqltable" border="1">
    <tr>
        <td>Mysql работает {$mysql_stat.uptime}. Был запущен {$mysql_stat.localisedDate}
            <table border="0" cellspacing="0" cellpadding="3" align="center">
                <form method="post" action="action_admin.php?module=mysqlstats">
                    <tr>
                        <td>
                            <select name="datatable[]" size="10" multiple="multiple" style="width:400px">{$mysql_stat.content}</select>
                        </td>
                        <td>
                            <table border="0" cellspacing="0" cellpadding="3">
                                <tr>
                                    <td valign="top">
                                        <input type="radio" name="type" value="Optimize" checked>
                                    </td>
                                    <td>Оптимизация базы данных<br /><font class="small">Производя оптимизацию базы данных, Вы уменьшаете её размер и соответственно с этим ускоряете её работу. Рекомендуется использовать данную функцию минимум один раз в неделю.</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <input type="radio" name="type" value="Repair">
                                    </td>
                                    <td>Ремонт базы данных<br /><font class="small">При неожиданной остановке MySQL сервера, во время выполнения каких-либо действий, может произойти повреждение структуры таблиц базы данных, использование этой функции произведёт ремонт повреждённых таблиц.</font></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <input type="hidden" name="op" value="StatusDB">
                    <tr>
                        <td colspan="2" align="center"><input type="submit" value="Выполнить действие"></td>
                    </tr>
                </form>
            </table>
            {if $mysql_stat.optimize}
                {$mysql_stat.optimize}
                <center><font class="option">Оптимизация базы данных: {$mysql_stat.dbname}<br />Общий размер базы данных: {$mysql_stat.totaltotal}<br />Общие накладные расходы: {$mysql_stat.totalfree}<br /><br />
                <table border="0" cellpadding="3" cellspacing="1" width="100%"><tr><td class="colhead" align="center">№</td><td class="colhead">Таблица</td><td class="colhead">Размер</td><td class="colhead">Статус</td><td class="colhead">Накладные расходы</td></tr>
                    {$mysql_stat.optimize}
                </table>";
            {elseif $mysql_stat.repair}
                <center><font class="option">Ремонт базы данных: {$mysql_stat.dbname}<br />Общий размер базы данных: {$mysql_stat.totaltotal}<br /><br />
                    <table border="0" cellpadding="3" cellspacing="1" width="100%"><tr><td class="colhead" align="center">№</td><td class="colhead">Таблица</td><td class="colhead">Размер</td><td class="colhead">Статус</td></tr>
                        {$mysql_stat.repair}
                    </table>
            {/if}


         </td>
    </tr>
</table>



<ul>
    <li><!-- Трафик сервера --> <b>Трафик сервера: </b> Показ таблиц
        сетевого трафика с момента последнего запуска <br />
        <table border="0">
            <tr>
                <td valign="top">
                    <table id="mysqltable" border="0">
                        <tr>
                            <th colspan="2" bgcolor="lightgrey">&nbsp;Трафик&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;&nbsp;За час&nbsp;</th>
                        </tr>
                        <tr>
                            <td bgcolor="#000000">&nbsp;Полученно&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.bytes_received_all}&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.bytes_received_hour}&nbsp;</td>
                        </tr>
                        <tr>
                            <td bgcolor="#000000">&nbsp;Послано&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.bytes_sent_all}&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.bytes_sent_hour}&nbsp;</td>
                        </tr>
                        <tr>
                            <td bgcolor="lightgrey">&nbsp;Всего&nbsp;</td>
                            <td bgcolor="lightgrey" align="right">&nbsp;{$mysql_stat.bytes_r_s_all}&nbsp;</td>
                            <td bgcolor="lightgrey" align="right">&nbsp;{$mysql_stat.bytes_r_s_hour}&nbsp;</td>
                        </tr>
                    </table>
                </td>
                <td valign="top">
                    <table id="mysqltable" border="0">
                        <tr>
                            <th colspan="2" bgcolor="lightgrey">&nbsp;Соединений&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За час&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;%&nbsp;</th>
                        </tr>
                        <tr>
                            <td bgcolor="#000000">&nbsp;Проваленные попытки&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.aborted_connects_all}&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.aborted_connects_hour}&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.aborted_connects}&nbsp;</td>
                        </tr>
                        <tr>
                            <td bgcolor="#000000">&nbsp;Отменено клиентами&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.aborted_clients_all}&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.aborted_clients_hour}&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.aborted_clients}&nbsp;</td>
                        </tr>
                        <tr>
                            <td bgcolor="lightgrey">&nbsp;Всего&nbsp;</td>
                            <td bgcolor="lightgrey" align="right">&nbsp;{$mysql_stat.connections_all}&nbsp;</td>
                            <td bgcolor="lightgrey" align="right">&nbsp;{$mysql_stat.connections_hour}&nbsp;</td>
                            <td bgcolor="lightgrey" align="right">&nbsp;{$mysql_stat.connections}&nbsp;%&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </li>
    <br />
    <li><!-- запросы --> <? print("<b>Статистика Запросов: </b> с момента запуска - {$mysql_stat.questions} запросов было посланно на сервер.
        <table border="0">
            <tr>
                <td colspan="2"><br />
                    <table id="mysqltable" border="0" align="right">
                        <tr>
                            <th bgcolor="lightgrey">&nbsp;Всего&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За&nbsp;Час&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За&nbsp;Минут&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За&nbsp;Секунд&nbsp;</th>
                        </tr>
                        <tr>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.questions_all}&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.questions_hour}&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.questions_minutes}&nbsp;</td>
                            <td bgcolor="#000000" align="right">&nbsp;{$mysql_stat.questions_seconds}&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <table id="mysqltable" border="0">
                        <tr>
                            <th colspan="2" bgcolor="lightgrey">&nbsp;Тип&nbsp;Запроса&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За&nbsp;Час&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;%&nbsp;</th>
                        </tr>
                       {$mysql_stat.querystats}

                    </table>
                </td>
            </tr>
        </table>
    </li>

    <br />
    <li><b>Статус значений</b><br />
        <table border="0">
            <tr>
                <td valign="top">
                    <table id="mysqltable" border="0">
                        <tr>
                            <th bgcolor="lightgrey">&nbsp;Функция&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;Значение&nbsp;</th>
                        </tr>
                        {$mysql_stat.stat_value}
</ul>