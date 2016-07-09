<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 17:47
 */


$REL_TPL->stdhead('Список адресов');

//если мы добавляем в список
if($_GET['action']=="add"){
    //получаем список городов
    $res_p=sql_query("SELECT `id`,`name_city` FROM `location_city`")  or sqlerr(__FILE__, __LINE__);

    while ($row_p = mysql_fetch_array($res_p)){
        $data_city[] = $row_p;
    }
    $action	="add";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef('data_city',$data_city);
    $REL_TPL->output("action", "admincp", "location_address");

}
elseif($_POST['action']=="add"){

    sql_query("INSERT INTO `location_address` (`name_address`,`id_city`,`added`) VALUES (".sqlesc($_POST['name_address']).",'".(int)$_POST['id_city']."','".time()."');") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Адрес добавлен');
}
//если редактируем
if($_GET['action']=="edit"){

    $res=sql_query("SELECT `name_address`,`id_city` FROM `location_address` WHERE id = '".$_GET['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такой адрес отсутствует в базе","no");
    }
    $data_address = mysql_fetch_array($res);

    $res_p=sql_query("SELECT `id`,`name_city` FROM `location_city`")  or sqlerr(__FILE__, __LINE__);

    while ($row_p = mysql_fetch_array($res_p)){
        $data_city[] = $row_p;
    }

    $action	="edit";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef("id",$_GET['id']);
    $REL_TPL->assignByRef('data_address',$data_address);
    $REL_TPL->assignByRef('data_city',$data_city);

    $REL_TPL->output("action", "admincp", "location_address");

}
elseif($_POST['action']=="edit"){
    sql_query("UPDATE `location_address` SET `name_address` = ".sqlesc($_POST['name_address']).", `last_update` = '".time()."',`id_city` = '".(int)$_POST['id_city']."' WHERE `id` =".$_GET['id'].";") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Адрес изменен');
}



// если нет никакиз активных действий
if (!$_GET['action']){

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],"30",'location_address');

    $res=sql_query("SELECT * FROM `location_address` WHERE is_deleted = 0 ".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Адреса базе не обнаружены","no");
    }

    //получаем список городов
    $sub_res=sql_query("SELECT `id`,`name_city` FROM `location_city` WHERE is_deleted = 0;")  or sqlerr(__FILE__, __LINE__);
    while ($subrow = mysql_fetch_array($sub_res)){
       $data_city[$subrow['id']]= $subrow['name_city'];
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_address[$i]=$row;
        //форматируем время
        $data_address[$i]['added']=mkprettytime($row['added'],false);
        if($row['last_update']) {
            $data_address[$i]['last_update'] = mkprettytime($row['last_update'], false);
        }

        $data_address[$i]['name_city'] =  $data_city[$row['id_city']];
        $i++;
    }

    $REL_TPL->assignByRef('paginator',$paginator);
    $REL_TPL->assignByRef('data_address',$data_address);
    $REL_TPL->output("index", "admincp", "location_address");
}
$REL_TPL->stdfoot();