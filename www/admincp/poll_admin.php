<?php

/**
 * Polls admin panel

 */

if (!isset($_GET['action']))  {
	$REL_TPL->stdhead("Опросы");


	$pollsrow = sql_query("SELECT * FROM poll_index ORDER BY id DESC");
	while ($row = mysql_fetch_array($pollsrow)){
	$data_poll[]=$row;
	}
	
	$REL_TPL->assignByRef('data_poll',$data_poll);
	$REL_TPL->output("poll_admin","poll_all");

	$REL_TPL->stdfoot();
	// if (!is_valid_id($_GET['pollid'])) stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	// $pollid = $_GET["pollid"];

}
elseif ($_GET['action'] == 'step1') {

	$REL_TPL->stdhead("Добавление опроса");
	$REL_TPL->output("poll_admin_step1","poll_all");
	$REL_TPL->stdfoot();
}
elseif ($_GET['action'] == 'step2') {
	
	$question=$_POST['question'];
	$subject=$_POST['subject'];
	if (!$question AND !$subject)
		stderr("Ошибка", "Не задана тема опроса или кол-во вопросов");
	
	$REL_TPL->assignByRef('data_question',$question);
	$REL_TPL->assignByRef('subject',$subject);
//	$REL_TPL->assignByRef('data_poll',$data_poll);
	//$REL_TPL->assignByRef('data_poll',$data_poll);

	$REL_TPL->stdhead("Добавление опроса");
	$REL_TPL->output("poll_admin_step2","poll_all");
	$REL_TPL->stdfoot();
}
elseif ($_GET['action'] == 'step3') {
	$subject = trim($_POST["subject"]);
	sql_query("INSERT INTO `poll_index`( `name`, `added`) VALUES (".implode(",", array_map("sqlesc", array($subject, time()))).");")  or sqlerr(__FILE__, __LINE__);
	$id_p = mysql_insert_id();
	//print "ИД опроса ".$id_p;
	$question=$_POST['question'];
	$answer=$_POST['answer'];
	$i=0;
	foreach ($question as $q_value) {
		$i++;
		sql_query("INSERT INTO `poll_question`( `id_poll`, `question`) VALUES ('".$id_p."',".implode(",", array_map("sqlesc", array($q_value))).");")  or sqlerr(__FILE__, __LINE__);
		$id_q = mysql_insert_id();
			foreach ($answer[$i] as $a_value) {
				sql_query("INSERT INTO `poll_answer`( `id_poll`,`id_question`, `answer`) VALUES ('".$id_p."','".$id_q."',".implode(",", array_map("sqlesc", array($a_value))).");")  or sqlerr(__FILE__, __LINE__);
			}
		
}
	

	$REL_TPL->stdhead("Добавление опроса");
	$REL_TPL->output("poll_admin_step3","poll_all");
	$REL_TPL->stdfoot();
}

else 
	stderr("Ошибка", "В доступе отказано");