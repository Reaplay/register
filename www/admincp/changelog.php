<?php




$action = (string)$_GET["action"];
$REL_TPL->stdhead("ChangeLog");
if ($action == 'delete'){
	$id = (int)$_GET["id"];
	if (!is_valid_id($id))
	stderr("Ошибка","Неверный ID","no");

	

	sql_query("DELETE FROM changelog WHERE id=$id") or sqlerr(__FILE__, __LINE__);
	//sql_query("DELETE FROM comments WHERE toid=$id AND type='changelog'") or sqlerr(__FILE__, __LINE__);
	//sql_query("DELETE FROM notifs WHERE type='changelogcomments' AND checkid=$id") or sqlerr(__FILE__, __LINE__);

/*	$REL_CACHE->clearGroupCache("block-changelog");
	if ($returnto != "")
		safe_redirect($returnto);
	else*/
		$msg = "Список изменений <b>успешно</b> удален";
}

elseif ($action == 'add') {

	$num_rev = ((string)$_POST["num_rev"]);
	if (!$num_rev)
		stderr("Ошибка","Надо указать версию!","no");
	
	if ($_POST["date"]) {
		$n_date  = explode("/",$_POST["date"]);
		$date = mktime( 0,0,0,$n_date['1'],$n_date['0'],$n_date['2']);
	}

	
	$text = ((string)$_POST["text"]);
	if (!$text)
		stderr("Ошибка","Необходимо указать список изменений","no");

	$added = time();

	sql_query("INSERT INTO changelog (added, date, rev, text) VALUES (".
	$added . ", '".$date."', " . sqlesc($num_rev) . ", " . sqlesc($text) . ")") or sqlerr(__FILE__, __LINE__);

	$msg = "Изменения внесены в базу";
	

}

elseif ($action == 'edit') {

	$id = (int)$_GET["id"];
	if (!is_valid_id($id))
		stderr("Ошибка","Неверный ID","no");

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		$text = ((string)$_POST["text"]);
		if (!$text)
			stderr("Ошибка","Необходимо указать список изменений","no");
		
		

		$num_rev = ((string)$_POST["num_rev"]);
		if (!$num_rev)
			stderr("Ошибка","Надо указать версию!","no");

		if ($_POST["date"]) {
			$n_date  = explode("/",$_POST["date"]);
			$date = mktime( 0,0,0,$n_date['1'],$n_date['0'],$n_date['2']);
		}	
		
		$text = sqlesc($text);

		$rev = sqlesc($rev);

		//$editedat = sqlesc(time());

		sql_query("UPDATE changelog SET text=$text, rev='$num_rev', date='$date' WHERE id='$id'") or sqlerr(__FILE__, __LINE__);


			$msg = "Список изменений <b>успешно</b> отредактирован";
	}
	else {
		$res = sql_query("SELECT * FROM changelog WHERE id=$id") or sqlerr(__FILE__, __LINE__);

		if (mysql_num_rows($res) != 1)
		stderr("Ошибка","Неверный ID");

		$data_changelog = mysql_fetch_array($res);
		if ($data_changelog['date']){
			$data_changelog['date'] = date("d/m/Y",$data_changelog['date']);
		}
		$REL_TPL->assignByRef('data_changelog',$data_changelog);
		

	}
}


$REL_TPL->assignByRef('msg',$msg);
$REL_TPL->output("changelog","admincp");
$REL_TPL->stdfoot();
?>