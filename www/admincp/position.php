<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 18.05.2016
 * Time: 15:00
 */



$REL_TPL->stdhead('Список должностей');

//если мы добавляем в список
if($_GET['action']=="add"){
    $action	="add";
    $REL_TPL->assignByRef("action",$action);

    $REL_TPL->output("action", "admincp", "position");

}
elseif($_POST['action']=="add"){
    if($_POST['is_head']){
        $is_head = 1;
    }
    else
        $is_head = 0;
    sql_query("INSERT INTO `position` (`name_position`,`added`,`is_head`) VALUES (".sqlesc($_POST['name_position']).",'".time()."','". $is_head."');") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Город добавлен');
}
//если редактируем
if($_GET['action']=="edit"){

    $res=sql_query("SELECT * FROM `position` WHERE id = '".$_GET['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такой город отсутствует в базе","no");
    }
    $data_position = mysql_fetch_array($res);

    $action	="edit";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef("id",$_GET['id']);
    $REL_TPL->assignByRef('data_position',$data_position);

    $REL_TPL->output("action", "admincp", "position");

}
elseif($_POST['action']=="edit"){

    if($_POST['is_head']){
        $is_head = 1;
    }
    else
        $is_head = 0;

    sql_query("UPDATE `position` SET `name_position` = ".sqlesc($_POST['name_position']).", `last_update` = '".time()."', `is_head` = '".$is_head."' WHERE `id` =".$_GET['id'].";") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Название изменено');
}



// если нет никакиз активных действий
if (!$_GET['action']){
    $res=sql_query("SELECT * FROM `position`;")  or sqlerr(__FILE__, __LINE__);
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


    $REL_TPL->assignByRef('data_position',$data_position);
    $REL_TPL->output("index", "admincp", "position");
}
$REL_TPL->stdfoot();