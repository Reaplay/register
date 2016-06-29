<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 06.06.2016
     * Time: 21:15
     */

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],$REL_CONFIG['per_page_department'],'department');

    $res=sql_query("SELECT department.*, type_office.name as name_type_office FROM `department` LEFT JOIN type_office ON type_office.id = department.id_type_office ".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Подразделения базе не обнаружены","no");
    }

    //получаем список подразделений для родительских
    $sub_res=sql_query("SELECT id,name_department FROM `department`;")  or sqlerr(__FILE__, __LINE__);
    while ($subrow = mysql_fetch_array($sub_res)){
        $data_array_department[$subrow['id']]= $subrow['name_department'];
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_department[$i]=$row;
        //форматируем время
        $data_department[$i]['added']=mkprettytime($row['added'],false);
        if($row['last_update']) {
            $data_department[$i]['last_update'] = mkprettytime($row['last_update'], false);
        }

        $data_department[$i]['name_parent_department'] =  $data_array_department[$row['id_parent']];
        $i++;
    }

    $REL_TPL->assignByRef('paginator',$paginator);
    //доп. данные для перехода сортировки и фильтров
    $REL_TPL->assignByRef('add_link',$add_link);
    $REL_TPL->assignByRef('add_sort',$sort['link']);
    $REL_TPL->assignByRef('sort',$sort);

    $REL_TPL->stdhead("Список подразделений");
    $REL_TPL->assignByRef('data_department',$data_department);
    $REL_TPL->output("department","reference");
    $REL_TPL->stdfoot();

