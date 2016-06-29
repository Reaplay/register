<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 18:48
 */


$REL_TPL->stdhead('Список мест');

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
    $REL_TPL->output("action", "admincp", "location_place");

}
elseif($_POST['action']=="add"){

   sql_query("INSERT INTO `location_place` (`id_address`,`floor`,`room`,`place`,`added`) VALUES ('".$_POST['id_address']."','".$_POST['floor']."','".$_POST['room']."','".$_POST['place']."','".time()."');") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Место добавлено');
}
//если редактируем
if($_GET['action']=="edit"){
    //выбираем данные по месту
    $res=sql_query("SELECT location_place.id_address, location_place.floor, location_place.place, location_place.room,  location_address.id_city FROM `location_place` LEFT JOIN location_address ON location_address.id=location_place.id_address WHERE location_place.id = '".$_GET['id']."'")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Такое место отсутствует в базе","no");
    }
    $data_place = mysql_fetch_array($res);
    //выбираем города
    $res_lc=sql_query("SELECT `id`,`name_city` FROM `location_city`")  or sqlerr(__FILE__, __LINE__);
    while ($row_lc = mysql_fetch_array($res_lc)){
        $data_city[] = $row_lc;
    }
    //выбираем имеющиеся адреса
    $res_la=sql_query("SELECT `id`,`name_address` FROM `location_address` WHERE id_city= '".$data_place['id_city']."'")  or sqlerr(__FILE__, __LINE__);
    while ($row_la = mysql_fetch_array($res_la)){
        $data_address[] = $row_la;
    }

    $action	="edit";
    $REL_TPL->assignByRef("action",$action);
    $REL_TPL->assignByRef("id",$_GET['id']);
    $REL_TPL->assignByRef('data_place',$data_place);
    $REL_TPL->assignByRef('data_address',$data_address);
    $REL_TPL->assignByRef('data_city',$data_city);

    $REL_TPL->output("action", "admincp", "location_place");

}
elseif($_POST['action']=="edit"){
    sql_query("UPDATE `location_place` SET `id_address` = '".$_POST['id_address']."', `floor` = '".$_POST['floor']."', `place` = '".$_POST['place']."', `room` = '".$_POST['room']."',`last_update` = '".time()."' WHERE `id` =".$_GET['id'].";") or sqlerr(__FILE__, __LINE__);
    $REL_TPL->stdmsg('Выполнено','Место изменено');
}



// если нет никакиз активных действий
if (!$_GET['action']){
    $res=sql_query("
SELECT location_place.id, location_place.id_address, location_place.floor, location_place.room, location_place.place, location_place.added, location_place.last_update,  location_city.name_city, location_address.name_address
FROM `location_place`
LEFT JOIN location_address ON location_address.id = location_place.id_address
LEFT JOIN location_city ON location_city.id = location_address.id_city;")  or sqlerr(__FILE__, __LINE__);
    if(mysql_num_rows($res) == 0){
        stderr("Ошибка","Места базе не обнаружены","no");
    }


    $i=0;
    while ($row = mysql_fetch_array($res)){
        $data_place[$i]=$row;
        //форматируем время
        $data_place[$i]['added']=mkprettytime($row['added'],false);
        if($row['last_update']) {
            $data_place[$i]['last_update'] = mkprettytime($row['last_update'], false);
        }

        //$data_place[$i]['name_city'] =  $data_city[$row['id_city']];
        $i++;
    }


    $REL_TPL->assignByRef('data_place',$data_place);
    $REL_TPL->output("index", "admincp", "location_place");
}
$REL_TPL->stdfoot();