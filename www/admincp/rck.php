<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 18.05.2016
 * Time: 13:50
 */

$REL_TPL->stdhead('Список РЦК');

//если мы добавляем в список
if($_GET['action']=="add"){
    $action	="add";
    $REL_TPL->assignByRef("action",$action);

    $REL_TPL->output("action", "admincp", "rck");

}
elseif($_POST['action']=="add"){

    sql_query("INSERT INTO `rck` (`name_rck`,`added`) VALUES (".sqlesc($_POST['name_rck']).",'".time()."');") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','РЦК добавлено');
}
//если редактируем
if($_GET['action']=="edit"){

    $res=sql_query("SELECT * FROM `rck` WHERE id = '".$_GET['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такое РЦК отсутствует в базе","no");
    }
    $data_rck = mysql_fetch_array($res);

    $action	="edit";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef("id",$_GET['id']);
    $REL_TPL->assignByRef('data_rck',$data_rck);

    $REL_TPL->output("action", "admincp", "rck");

}
elseif($_POST['action']=="edit"){
    sql_query("UPDATE `rck` SET `name_rck` = ".sqlesc($_POST['name_rck']).", `last_update` = '".time()."' WHERE `id` =".$_GET['id'].";") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Название изменено');
}



// если нет никакиз активных действий
if (!$_GET['action']){

    // формируем переход между страниц и прочие данные
    $paginator = create_paginator($_GET['page'],"30",'rck');


    $res=sql_query("SELECT * FROM `rck` WHERE is_deleted = 0 ".$paginator['limit'].";")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","РЦК базе не обнаружено","no");
    }
    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_rck[$i]=$row;
        $data_rck[$i]['added']=mkprettytime($row['added'],false);
        if($row['last_update']) {
            $data_rck[$i]['last_update'] = mkprettytime($row['last_update'], false);
        }
        $i++;
    }

    $REL_TPL->assignByRef('paginator',$paginator);
    $REL_TPL->assignByRef('data_rck',$data_rck);
    $REL_TPL->output("index", "admincp", "rck");
}
$REL_TPL->stdfoot();