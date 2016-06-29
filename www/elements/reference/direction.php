<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 06.06.2016
     * Time: 21:14
     */

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],$REL_CONFIG['per_page_direction'],'direction');



    $res=sql_query("SELECT * FROM `direction` ".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Дирекции базе не обнаружены","no");
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_direction[$i]=$row;
        $data_direction[$i]['added']=mkprettytime($row['added'],false);
        if($row['last_update']) {
            $data_direction[$i]['last_update'] = mkprettytime($row['last_update'], false);
        }
        $i++;
    }

    $REL_TPL->assignByRef('paginator',$paginator);
    //доп. данные для перехода сортировки и фильтров
    $REL_TPL->assignByRef('add_link',$add_link);
    $REL_TPL->assignByRef('add_sort',$sort['link']);
    $REL_TPL->assignByRef('sort',$sort);


    $REL_TPL->stdhead("Список дирекций");
    $REL_TPL->assignByRef('data_direction',$data_direction);
    $REL_TPL->output("direction","reference");
    $REL_TPL->stdfoot();