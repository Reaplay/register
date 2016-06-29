<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 16:57
 */


$REL_TPL->stdhead('Список городов');

//если мы добавляем в список
if($_GET['action']=="add"){
    $action	="add";
    $REL_TPL->assignByRef("action",$action);

    $REL_TPL->output("action", "admincp", "location_city");

}
elseif($_POST['action']=="add"){

    sql_query("INSERT INTO `location_city` (`name_city`,`added`) VALUES (".sqlesc($_POST['name_city']).",'".time()."');") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Город добавлен');
}
//если редактируем
if($_GET['action']=="edit"){

    $res=sql_query("SELECT * FROM `location_city` WHERE id = '".$_GET['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такой город отсутствует в базе","no");
    }
    $data_city = mysql_fetch_array($res);

    $action	="edit";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef("id",$_GET['id']);
    $REL_TPL->assignByRef('data_city',$data_city);

    $REL_TPL->output("action", "admincp", "location_city");

}
elseif($_POST['action']=="edit"){
    sql_query("UPDATE `location_city` SET `name_city` = ".sqlesc($_POST['name_city']).", `last_update` = '".time()."' WHERE `id` =".$_GET['id'].";") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Название изменено');
}



// если нет никакиз активных действий
if (!$_GET['action']){

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],"30",'location_city');

    $res=sql_query("SELECT * FROM `location_city` ".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Города базе не обнаружены","no");
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_city[$i]=$row;
        $data_city[$i]['added']=mkprettytime($row['added'],false);
        if($row['last_update']) {
            $data_city[$i]['last_update'] = mkprettytime($row['last_update'], false);
        }
        $i++;
    }

    $REL_TPL->assignByRef('paginator',$paginator);
    $REL_TPL->assignByRef('data_city',$data_city);
    $REL_TPL->output("index", "admincp", "location_city");
}
$REL_TPL->stdfoot();