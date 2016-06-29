<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 06.06.2016
     * Time: 21:23
     */

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],$REL_CONFIG['per_page_position'],'position');

    $res=sql_query("SELECT * FROM `position` ".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Города базе не обнаружены","no");
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_position[$i]=$row;
        $data_position[$i]['added']=mkprettytime($row['added'],false);
        if($row['last_update']) {
            $data_position[$i]['last_update'] = mkprettytime($row['last_update'], false);
        }
        $i++;
    }


    $REL_TPL->assignByRef('paginator',$paginator);
    //доп. данные для перехода сортировки и фильтров
    $REL_TPL->assignByRef('add_link',$add_link);
    $REL_TPL->assignByRef('add_sort',$sort['link']);
    $REL_TPL->assignByRef('sort',$sort);

    $REL_TPL->stdhead("Список должностей");
    $REL_TPL->assignByRef('data_position',$data_position);
    $REL_TPL->output("position","reference");
    $REL_TPL->stdfoot();