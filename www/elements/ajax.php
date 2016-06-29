<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 19:14
 */
require_once("../include/connect.php");
dbconn();


// получаем список результатов контактов
if($_GET['action']=='getlocationaddress'){
	
	if(!is_valid_id($_GET['id_city']))
		return;
	$res=sql_query("SELECT id,name_address FROM  `location_address` WHERE id_city = '".$_GET['id_city']."';")  or sqlerr(__FILE__, __LINE__);
		$result[0]="Выберите результат";
		
		while ($row = mysql_fetch_array($res)){
			$result[$row['id']]=$row['name_address'];
			//array_push ($result, $row['id']=>$row['text']);
		}
		echo json_encode($result);
		//print_r($result);
}
elseif($_GET['action'] == 'get_mvz'){

	if(!is_valid_id($_GET['id_rck']))
		return;

	$res=sql_query("SELECT id,name_mvz FROM `mvz` WHERE id_rck = '".$_GET['id_rck']."';")  or sqlerr(__FILE__, __LINE__);
	$result[0]="Выберите результат";

	while ($row = mysql_fetch_array($res)){
		$result[$row['id']]=$row['name_mvz'];

	}
	echo json_encode($result);

}
elseif($_GET['action'] == 'get_department'){

	if(!is_valid_id($_GET['id_department']))
		return;

	$res=sql_query("SELECT id,name_department FROM `department` WHERE id_parent = '".$_GET['id_department']."';")  or sqlerr(__FILE__, __LINE__);
	$result[0]="Выберите результат";

	while ($row = mysql_fetch_array($res)){
		$result[$row['id']]=$row['name_department'];

	}
	echo json_encode($result);

}
?>


