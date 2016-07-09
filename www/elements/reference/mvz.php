<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 06.06.2016
     * Time: 21:23
     */

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],$REL_CONFIG['per_page_mvz'],'mvz');


    $res=sql_query("SELECT * FROM `mvz` WHERE is_deleted = 0 ".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","МВЗ базе не обнаружен","no");
    }

    //получаем список рцк
    $sub_res=sql_query("SELECT `id`,`name_rck` FROM `rck` WHERE is_deleted = 0;")  or sqlerr(__FILE__, __LINE__);
    while ($subrow = mysql_fetch_array($sub_res)){
        $data_rck[$subrow['id']]= $subrow['name_rck'];
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_mvz[$i]=$row;
        //форматируем время
        $data_mvz[$i]['added']=mkprettytime($row['added'],false);
        if($row['last_update']) {
            $data_mvz[$i]['last_update'] = mkprettytime($row['last_update'], false);
        }

        $data_mvz[$i]['name_rck'] =  $data_rck[$row['id_rck']];
        $i++;
    }

    $REL_TPL->assignByRef('paginator',$paginator);
    //доп. данные для перехода сортировки и фильтров
    $REL_TPL->assignByRef('add_link',$add_link);
    $REL_TPL->assignByRef('add_sort',$sort['link']);
    $REL_TPL->assignByRef('sort',$sort);

    $REL_TPL->stdhead("Список МВЗ");
    $REL_TPL->assignByRef('data_mvz',$data_mvz);
    $REL_TPL->output("mvz","reference");
    $REL_TPL->stdfoot();