<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 17:47
 */


$REL_TPL->stdhead('Список функций');

//если мы добавляем в список
if($_GET['action']=="add"){
    //получаем список городов
    $res_p=sql_query("SELECT `id`,`name_functionality` FROM `functionality` WHERE id_parent = 0")  or sqlerr(__FILE__, __LINE__);

    while ($row_p = mysql_fetch_array($res_p)){
        $data_functionality[] = $row_p;
    }
    $action	="add";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef('data_functionality',$data_functionality);
    $REL_TPL->output("action", "admincp", "functionality");

}
elseif($_POST['action']=="add"){

    sql_query("INSERT INTO `functionality` (`name_functionality`,`id_parent`,`added`) VALUES (".sqlesc($_POST['name_functionality']).",'".(int)$_POST['id_parent']."','".time()."');") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Функция добавлена');
}
//если редактируем
if($_GET['action']=="edit"){

    $res=sql_query("SELECT `name_functionality`,`id_parent` FROM `functionality` WHERE id = '".$_GET['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такая функция отсутствует в базе","no");
    }
    $data = mysql_fetch_array($res);

    $res_p=sql_query("SELECT `id`,`name_functionality` FROM `functionality` WHERE id_parent = 0")  or sqlerr(__FILE__, __LINE__);

    while ($row_p = mysql_fetch_array($res_p)){
        $data_functionality[] = $row_p;
    }

    $action	="edit";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef("id",$_GET['id']);
    $REL_TPL->assignByRef('data_functionality',$data_functionality);
    $REL_TPL->assignByRef('data',$data);

    $REL_TPL->output("action", "admincp", "functionality");

}
elseif($_POST['action']=="edit"){
    sql_query("UPDATE `functionality` SET `name_functionality` = ".sqlesc($_POST['name_functionality']).", `last_update` = '".time()."',`id_parent` = '".(int)$_POST['id_parent']."' WHERE `id` =".$_GET['id'].";") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Функция изменена');
}



// если нет никакиз активных действий
if (!$_GET['action']){

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],"30",'functionality');

    $res=sql_query("SELECT * FROM `functionality` WHERE is_deleted = 0 ".$paginator['limit']."; ")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Функционал в базе не обнаружен","no");
    }


    $sub_res=sql_query("SELECT `id`,`name_functionality` FROM `functionality` WHERE id_parent = 0 AND is_deleted = 0 ;")  or sqlerr(__FILE__, __LINE__);
    while ($subrow = mysql_fetch_array($sub_res)){
       $functionality[$subrow['id']]= $subrow['name_functionality'];
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_functionality[$i]=$row;
        //форматируем время
        $data_functionality[$i]['added']=mkprettytime($row['added'],false);
        if($row['last_update']) {
            $data_functionality[$i]['last_update'] = mkprettytime($row['last_update'], false);
        }

        $data_functionality[$i]['name_parent'] =  $functionality[$row['id_parent']];
        $i++;
    }

    $REL_TPL->assignByRef('paginator',$paginator);
    $REL_TPL->assignByRef('data_functionality',$data_functionality);
    $REL_TPL->output("index", "admincp", "functionality");
}
$REL_TPL->stdfoot();