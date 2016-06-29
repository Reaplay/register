<?php

	// ПЕРЕПИСАТЬ НАХРЕН!!!111

$GLOBALS["byteUnits"] = array('байт', 'КБ', 'МБ', 'ГБ', 'ТБ', 'ПБ', 'EБ');

$day_of_week = array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
$month = array('Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря');

$datefmt = '%d %B, %Y в %I:%M %p';
$timespanfmt = '%s дней, %s часов, %s минут %s секунд';
////////////////// FUNCTION LIST /////////////////////////

function formatByteDown($value, $limes = 6, $comma = 0){
	$dh           = pow(10, $comma);
	$li           = pow(10, $limes);
	$return_value = $value;
	$unit         = $GLOBALS['byteUnits'][0];

	for ( $d = 6, $ex = 15; $d >= 1; $d--, $ex-=3 ) {
		if (isset($GLOBALS['byteUnits'][$d]) && $value >= $li * pow(10, $ex)) {
			$value = round($value / ( pow(1024, $d) / $dh) ) /$dh;
			$unit = $GLOBALS['byteUnits'][$d];
			break 1;
		} // end if
	} // end for

	if ($unit != $GLOBALS['byteUnits'][0]) {
		$return_value = number_format($value, $comma, '.', ',');
	} else {
		$return_value = number_format($value, 0, '.', ',');
	}

	return array($return_value, $unit);
} // end of the 'formatByteDown' function


function timespanFormat($seconds){
	$return_string = '';
	$days = floor($seconds / 86400);
	if ($days > 0) {
		$seconds -= $days * 86400;
	}
	$hours = floor($seconds / 3600);
	if ($days > 0 || $hours > 0) {
		$seconds -= $hours * 3600;
	}
	$minutes = floor($seconds / 60);
	if ($days > 0 || $hours > 0 || $minutes > 0) {
		$seconds -= $minutes * 60;
	}
	return (string)$days." Дней ". (string)$hours." Часов ". (string)$minutes." Минут ". (string)$seconds." Секунд ";
}


function localisedDate($timestamp = -1, $format = ''){
	global $datefmt, $month, $day_of_week;

	if ($format == '') {
		$format = $datefmt;
	}

	if ($timestamp == -1) {
		$timestamp = time();
	}

	$date = preg_replace('@%[aA]@', $day_of_week[(int)strftime('%w', $timestamp)], $format);
	$date = preg_replace('@%[bB]@', $month[(int)strftime('%m', $timestamp)-1], $date);

	return strftime($date, $timestamp);
} // end of the 'localisedDate()' function

////////////////////// END FUNCTION LIST /////////////////////////////////////

	$res = @sql_query('SHOW STATUS') or Die(mysql_error());
	while ($row = mysql_fetch_row($res)) {
		$serverStatus[$row[0]] = $row[1];
	}
	@mysql_free_result($res);
	unset($res);
	unset($row);

// просчет времени
	$res = @sql_query('SELECT UNIX_TIMESTAMP() - ' . $serverStatus['Uptime']);
	$row = mysql_fetch_row($res);
//echo sprintf("Server Status Uptime", timespanFormat($serverStatus['Uptime']), localisedDate($row[0])) . "\n";

	$mysql_stat['uptime'] = timespanFormat($serverStatus['Uptime']);
	$mysql_stat['localisedDate']= localisedDate($row[0]);

	$dbname = $mysql_db;

	$result = sql_query("SHOW TABLES FROM ".$dbname."");
	$mysql_stat['dbname'] = $mysql_db;
	while (list($name) = mysql_fetch_array($result))
		$mysql_stat['content'] .= "<option value=\"".$name."\" selected>".$name."</option>";
	if ($_POST['type'] == "Optimize") {
		$result = sql_query("SHOW TABLE STATUS FROM ".$dbname."");
		$tables = array();
		while ($row = mysql_fetch_array($result)) {
			$total = $row['Data_length'] + $row['Index_length'];
			$totaltotal += $total;
			$free = ($row['Data_free']) ? $row['Data_free'] : 0;
			$totalfree += $free;
			$i++;
			$otitle = (!$free) ? "<font color=\"#FF0000\">Не нуждается</font>" : "<font color=\"#009900\">Оптимизирована</font>";
				//sql_query("OPTIMIZE TABLE ".$row[0]."");
				$tables[] = $row[0];
			$mysql_stat['optimize'] .= "<tr class=\"bgcolor1\"><td align=\"center\">".$i."</td><td>".$row[0]."</td><td>".mksize($total)."</td><td align=\"center\">".$otitle."</td><td align=\"center\">".mksize($free)."</td></tr>";
    }
		sql_query("OPTIMIZE TABLE ".implode(", ", $tables));

		$mysql_stat['totaltotal'] = mksize($totaltotal);
		$mysql_stat['totalfree'] = mksize($totalfree);
            }
	elseif ($_POST['type'] == "Repair") {
		$result = sql_query("SHOW TABLE STATUS FROM ".$dbname."");
		while ($row = mysql_fetch_array($result)) {
			$total = $row['Data_length'] + $row['Index_length'];
			$totaltotal += $total;
			$i++;
			$rresult = sql_query("REPAIR TABLE ".$row[0]."");
			$otitle = (!$rresult) ? "<font color=\"#FF0000\">Ошибка</font>" : "<font color=\"#009900\">OK</font>";
			$mysql_stat['repair'] .= "<tr class=\"bgcolor1\"><td align=\"center\">".$i."</td><td>".$row[0]."</td><td>".mksize($total)."</td><td align=\"center\">".$otitle."</td></tr>";
            }

		$mysql_stat['totaltotal'] = mksize($totaltotal);
		$mysql_stat['totalfree'] = mksize($totalfree);
	}

	mysql_free_result($res);
	unset($res);
	unset($row);
	//берем статистику запросов N01heDc=
	$queryStats = array();
	$tmp_array = $serverStatus;
	foreach($tmp_array AS $name => $value) {
		if (substr($name, 0, 4) == 'Com_') {
			$queryStats[str_replace('_', ' ', substr($name, 4))] = $value;
			unset($serverStatus[$name]);
		}
	}
	unset($tmp_array);

//трафик сервера

	$mysql_stat['bytes_received_all'] = join(' ', formatByteDown($serverStatus['Bytes_received']));
	$mysql_stat['bytes_received_hour'] = join(' ', formatByteDown($serverStatus['Bytes_received'] * 3600 / $serverStatus['Uptime']));
	$mysql_stat['bytes_sent_all'] = join(' ', formatByteDown($serverStatus['Bytes_sent']));
	$mysql_stat['bytes_sent_hour'] = join(' ', formatByteDown($serverStatus['Bytes_sent'] * 3600 / $serverStatus['Uptime']));
	$mysql_stat['byte_r_s_all'] = join(' ', formatByteDown($serverStatus['Bytes_received'] + $serverStatus['Bytes_sent']));
	$mysql_stat['byte_r_s_hour'] = join(' ', formatByteDown(($serverStatus['Bytes_received'] + $serverStatus['Bytes_sent']) * 3600 / $serverStatus['Uptime']));
	$mysql_stat['aborted_connects_all'] = number_format($serverStatus['Aborted_connects'], 0, '.', ',');
	$mysql_stat['aborted_connects_hour'] =  number_format(($serverStatus['Aborted_connects'] * 3600 / $serverStatus['Uptime']), 2, '.', ',');
	$mysql_stat['aborted_connects'] = ($serverStatus['Connections'] > 0 ) ? number_format(($serverStatus['Aborted_connects'] * 100 / $serverStatus['Connections']), 2, '.', ','). '&nbsp;%' : '---';
	$mysql_stat['aborted_clients_all'] = number_format($serverStatus['Aborted_clients'], 0, '.', ',');
	$mysql_stat['aborted_clients_hour'] = number_format(($serverStatus['Aborted_clients'] * 3600 / $serverStatus['Uptime']), 2, '.', ',');
	$mysql_stat['aborted_clients'] = ($serverStatus['Connections'] > 0 ) ? number_format(($serverStatus['Aborted_clients'] * 100 / $serverStatus['Connections']), 2 , '.', ',') . '&nbsp;%' : '---';
	$mysql_stat['connections_all'] =  number_format($serverStatus['Connections'], 0, '.', ',');
	$mysql_stat['connections_hour'] = number_format(($serverStatus['Connections'] * 3600 / $serverStatus['Uptime']), 2, '.', ',');
	$mysql_stat['connections'] = number_format(100, 2, '.', ',');


// статистика запросов
	$mysql_stat['questions'] = number_format($serverStatus['Questions'], 0, '.', ',');
	$mysql_stat['questions_all'] = number_format($serverStatus['Questions'], 0, '.', ',');
	$mysql_stat['questions_hour'] = number_format(($serverStatus['Questions'] * 3600 / $serverStatus['Uptime']), 2, '.', ',');
	$mysql_stat['questions_minutes'] = number_format(($serverStatus['Questions'] * 60 / $serverStatus['Uptime']), 2, '.', ',');
	$mysql_stat['questions_seconds'] = number_format(($serverStatus['Questions'] / $serverStatus['Uptime']), 2, '.', ',');

	$useBgcolorOne = TRUE;
	$countRows = 0;
	foreach ($queryStats as $name => $value) {


		$mysql_stat['querystats'] .='<tr>
			<td bgcolor="#000000">&nbsp;'. htmlspecialchars($name).'&nbsp;</td>
			<td bgcolor="#000000" align="right">&nbsp;'.number_format($value, 0, ".", ",").'&nbsp;</td>
			<td bgcolor="#000000" align="right">&nbsp;'.number_format(($value * 3600 / $serverStatus['Uptime']), 2, '.', ',').'&nbsp;</td>
			<td bgcolor="#000000" align="right">&nbsp;'.number_format(($value * 100 / ($serverStatus['Questions'] - $serverStatus['Connections'])), 2, '.', ',').'&nbsp;%&nbsp;</td>
		</tr>';

		$useBgcolorOne = !$useBgcolorOne;
		if (++$countRows == ceil(count($queryStats) / 2)) {
			$useBgcolorOne = TRUE;
			$mysql_stat['querystats'] .='</table>
                </td>
                <td valign="top">
                    <table id="torrenttable" border="0">
                        <tr>
                            <th colspan="2" bgcolor="lightgrey">&nbsp;Тип&nbsp;Запроса&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За&nbsp;Час&nbsp;</th>
                            <th bgcolor="lightgrey">&nbsp;%&nbsp;</th>
                        </tr>';
		}

	}

	unset($countRows);
	unset($useBgcolorOne);

	unset($serverStatus['Aborted_clients']);
	unset($serverStatus['Aborted_connects']);
	unset($serverStatus['Bytes_received']);
	unset($serverStatus['Bytes_sent']);
	unset($serverStatus['Connections']);
	unset($serverStatus['Questions']);
	unset($serverStatus['Uptime']);

	if (!empty($serverStatus)) {
		$useBgcolorOne = TRUE;
		$countRows = 0;
		foreach($serverStatus AS $name => $value) {
			$mysql_stat['stat_value'] .='
<tr>
	<td bgcolor="#000000">&nbsp;'.htmlspecialchars(str_replace('_', ' ', $name)).'&nbsp;</td>
	<td bgcolor="#000000" align="right">&nbsp;'.htmlspecialchars($value).'&nbsp;</td>
</tr>';

	$useBgcolorOne = !$useBgcolorOne;
	if (++$countRows == ceil(count($serverStatus) / 3) || $countRows == ceil(count($serverStatus) * 2 / 3)) {
	$useBgcolorOne = TRUE;
		$mysql_stat['stat_value'] .='</table></td>
<td valign="top">
	<table id="mysqltable" border="0">
		<tr>
			<th bgcolor="lightgrey">&nbsp;Функция&nbsp;</th>
			<th bgcolor="lightgrey">&nbsp;Значение&nbsp;</th>
		</tr>';
		} };
	unset($useBgcolorOne);
		$mysql_stat['stat_value'] .='</table></td></tr></table></li>';
	}




	$REL_TPL->stdhead("Статистка Mysql");
	$REL_TPL->assignByRef('mysql_stat',$mysql_stat);
	$REL_TPL->output("mysqlstat","admincp");
	$REL_TPL->stdfoot(); ?>