<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 18.05.2016
 * Time: 12:04
 */

$REL_TPL->stdhead('Список подразделений');

//добавляем
if($_GET['action']=='add'){
    //получаем список подразделений
    $res_d=sql_query("SELECT `id`,`name_department` FROM `department`")  or sqlerr(__FILE__, __LINE__);
    while ($row_d = mysql_fetch_array($res_d)){
        $data_department[] = $row_d;
    }
    //список типов офисов
    $res_t=sql_query("SELECT `id`,`name_office` FROM `type_office`")  or sqlerr(__FILE__, __LINE__);
    while ($row_t = mysql_fetch_array($res_t)){
        $data_type_office[] = $row_t;
    }

    $action	="add";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef('data_department',$data_department);
    $REL_TPL->assignByRef('data_type_office',$data_type_office);
    $REL_TPL->output("action", "admincp", "department");
}
elseif($_POST['action']=="add"){

    sql_query("INSERT INTO `department` (`name_department`,`id_parent`,`id_type_office`,`added`) VALUES (".sqlesc($_POST['name_department']).",'".$_POST['id_parent']."','".$_POST['id_type_office']."','".time()."');") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Подразделение добавлено');
}
//редактируем
elseif($_GET['action']=='edit'){
    //выбираем данные по подразделению
    $res=sql_query("SELECT department.id, department.id_type_office, department.id_parent, department.name_department FROM department WHERE department.id = '".$_GET['id']."'");
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такое подразделение отсутствует в базе","no");
    }
    $data=mysql_fetch_array($res);

    //получаем список подразделений
    $res_d=sql_query("SELECT `id`,`name_department` FROM `department` WHERE id != '".$data['id']."'")  or sqlerr(__FILE__, __LINE__);
    while ($row_d = mysql_fetch_array($res_d)){
        $data_department[] = $row_d;
    }

    //выбираем типы офисов
    $res_t=sql_query("SELECT `id`,`name_office` FROM `type_office`")  or sqlerr(__FILE__, __LINE__);
    while ($row_t = mysql_fetch_array($res_t)){
        $data_type_office[] = $row_t;
    }
    $action	="edit";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef("id",$_GET['id']);
    $REL_TPL->assignByRef('data',$data);
    $REL_TPL->assignByRef('data_type_office',$data_type_office);
    $REL_TPL->assignByRef('data_department',$data_department);
    $REL_TPL->output("action", "admincp", "department");
}
elseif($_POST['action']=='edit'){

    sql_query("UPDATE `department` SET `name_department` = ".sqlesc($_POST['name_department']).", `id_parent` = '".$_POST['id_parent']."',
`id_type_office` = '".$_POST['id_type_office']."', `last_update` = '".time()."' WHERE `id` ='".$_GET['id']."';") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Подразделение изменено');

}

if (!$_GET['action']){

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],"30",'department');

    $res=sql_query("SELECT department.*, type_office.name_office as name_type_office FROM `department` LEFT JOIN type_office ON type_office.id = department.id_type_office ".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Подразделения базе не обнаружены","no");
    }

    //получаем список подразделений для родительских
    /*НУЖЕН ЛИ ЭТОТ СПИСОК?*/
    $sub_res=sql_query("SELECT id,name_department FROM `department`;")  or sqlerr(__FILE__, __LINE__);
    while ($subrow = mysql_fetch_array($sub_res)){
        $data_array_department[$subrow['id']]= $subrow['name_department'];
    }

    $res_office=sql_query("SELECT id,name_office FROM type_office;")  or sqlerr(__FILE__, __LINE__);
    while ($sub_office = mysql_fetch_array($res_office)){
        $data_array_office[$sub_office['id']]= $sub_office['name_office'];
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
        $data_department[$i]['name_office'] =  $data_array_office[$row['id_type_office']];
        $i++;
    }

    $REL_TPL->assignByRef('paginator',$paginator);
    $REL_TPL->assignByRef('data_department',$data_department);
    $REL_TPL->output("index", "admincp", "department");
}


$REL_TPL->stdfoot();