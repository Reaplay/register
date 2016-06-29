<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 24.04.2016
     * Time: 23:09
     */
    if(!$_GET['type']){
        $type = "other";
    }
    else{
        $type = $_GET['type'];
    }
    $res = sql_query("SELECT sitelog.*, users.name FROM sitelog LEFT JOIN users ON users.id = sitelog.userid WHERE `module` = '".$type."'") or sqlerr(__FILE__,__LINE__);
    /*if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Данные логи не найдены");
    }*/
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_log[$i]=$row;
        $data_log[$i]['added']=mkprettytime($row['added'],false);
        $i++;
    }

    $REL_TPL->stdhead('Логи');
    $REL_TPL->assignByRef('data_log',$data_log);

    $REL_TPL->output("logfile","admincp");
    $REL_TPL->stdfoot();