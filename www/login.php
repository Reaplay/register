<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 15:25
 */

require_once("include/connect.php");

dbconn();

if ($CURUSER)
stderr("Ошибка", "Вы уже вошли в систему!");

if($_POST['login'] AND $_POST['password']){
	if (!mkglobal("login:password"))
		die();


	$login = (string)$login;
	if (!validusername($login))
		stderr("Не правильный формат логина","no");

	//var_dump($password);
	$res = sql_query("SELECT * FROM users WHERE login = ". sqlesc($login));
	$row = @mysql_fetch_array($res);



	if (!$row) {
		stderr("Ошибка",'Вы не зарегестрированы, или ввели не правильный логин/пароль. <a href="login.php">Попробуйте еще раз</a>.');
	}



	if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
		stderr("Ошибка",'Вы не зарегестрированы, или ввели не правильный логин/пароль. <a href="login.php">Попробуйте еще раз</a>.');

	if ($row["banned"])
		stderr("Аккаунт заблокирован","Ваш аккаунт заблокирован с этим комментарием: ".$row['dis_reason']);

	logincookie($row["id"], $row["passhash"], $row["language"]);
	$CURUSER = $row;
	sql_query("UPDATE LOW_PRIORITY users SET last_login = '".time()."' WHERE id=" . $CURUSER["id"]);
	$returnto = strip_tags(trim((string)$_POST['returnto']));

	$REL_TPL->stdhead("Вход в систему");
	stdmsg("Вход осуществлен","<a href=\"".$REL_CONFIG['defaultbaseurl']."\">Продолжить</a>");
	$REL_TPL->stdfoot();

	die();
}

$REL_TPL->stdhead("Вход");

$REL_TPL->output("login","basic");

$REL_TPL->stdfoot();

?>