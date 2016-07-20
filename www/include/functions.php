<?php
/**
 * Created by PhpStorm.
 * User: U_03MDH
 * Date: 16.05.2016
 * Time: 14:20
 */

if (!defined("IN_SITE")) die("Direct access to this page not allowed");
define ("BETA", true);
define ("BETA_NOTICE", " This isn't complete release of source!");
define("RELVERSION","0.0.1");

// подключемся к базе

function dbconn($lightmode = false) {
    global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset, $REL_CONFIG, $REL_CACHE, $REL_DB, $REL_TPL;

    require_once(ROOT_PATH . 'classes/database/database.class.php');
    require_once(ROOT_PATH . 'classes/database/database.class.mysqli.php');
    $REL_DB = new REL_DB($mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset);

    // configcache init
    $REL_CONFIG=$REL_CACHE->get('system','config');
    //$REL_CONFIG=false;
    if ($REL_CONFIG===false) {

        $REL_CONFIG = array();

        $cacherow = sql_query("SELECT * FROM cache_stats");

        while ($cacheres = mysql_fetch_array($cacherow))
            $REL_CONFIG[$cacheres['cache_name']] = $cacheres['cache_value'];

        $REL_CACHE->set('system','config',$REL_CONFIG);
    }


    if (!$lightmode)
        userlogin();

    require_once(ROOT_PATH . 'classes/template/template.class.php');
    $REL_TPL = new REL_TPL($REL_CONFIG);

    gzip();


    define ("REGISTER_VERSION", "&copy; ".date("Y")." Created by IT Samara (v".RELVERSION.") .");

    return;
}

// функция запроса
function sql_query($query) {
    global $REL_DB;
    return $REL_DB->query($query);
}

// логин в системе
function userlogin() {
    global  $REL_CONFIG, $REL_CACHE,  $CURUSER;
    unset($GLOBALS["CURUSER"]);

    $ip = getip();
   // $ip =  $_SERVER['HTTP_CLIENT_IP'];

    if (empty($_COOKIE["uid"]) || empty($_COOKIE["pass"])) {
        user_session();
        return;
    }

    if (!is_valid_id($_COOKIE["uid"]) || strlen($_COOKIE["pass"]) != 32) {
        die("FATAL ERROR: Cokie ID invalid or cookie pass hash problem.");

    }
    $id = (int) $_COOKIE["uid"];
    $res = sql_query("SELECT users.* FROM users WHERE id = $id");// or die(mysql_error());
    $row = mysql_fetch_assoc($res);
    if (!$row) {
        user_session();
        return;
    } elseif (($row['enable'] != 1) && !defined("IN_CONTACT")) {
        headers(true);
        die("Аккаунт заблокирован. Причина: ".$row['dis_reason']);

    }

    //доделать
    if ($_COOKIE["pass"] != md5($row["passhash"].COOKIE_SECRET)) {

        $pscheck = htmlspecialchars(trim((string)$_COOKIE['pass']));
        write_log(getip()." with cookie ID = $id <font color=\"red\">with passhash ".$pscheck." -> PASSHASH CHECKSUM FAILED!</font>",'security');
        user_session();
        return;
    }

    $updateset = array();

    if ($ip != $row['ip']) {
        $updateset[] = 'ip = ' . sqlesc($ip);
    }
    $updateset[] = 'last_access = ' . time();

    if (count($updateset))
        sql_query("UPDATE LOW_PRIORITY users SET ".implode(", ", $updateset)." WHERE id=" . $row["id"]);


    $row['ip'] = $ip;
    //таймзона на дефолт
    $row['timezone'] = $REL_CONFIG['site_timezone'];

    $GLOBALS["CURUSER"] = $row;
    user_session();
}

// сессия пользователя
function user_session() {
    global $CURUSER, $REL_CONFIG;

    $ip = getip();
    $url = htmlspecialchars($_SERVER['REQUEST_URI']);

    if (!$CURUSER) {
        $uid = -1;
        $username = '';
        $class = -1;
    } else {
        $uid = $CURUSER['id'];
        $username = $CURUSER['name'];
        $class = $CURUSER['class'];
    }


    $sid = session_id();

    $updateset = array();
    if ($sid) {

        $ctime = time();
        $agent = htmlspecialchars($_SERVER["HTTP_USER_AGENT"]);
        $updateset[] = "sid = ".sqlesc($sid);
        $uid = (int)$uid;
        $updateset[] = "uid = ".$uid;
        $updateset[] = "username = ".sqlesc($username);
        $class = (int)$class;
        $updateset[] = "class = ".$class;
        $updateset[] = "ip = ".sqlesc($ip);
        $updateset[] = "time = ".$ctime;
        $updateset[] = "url = ".sqlesc($url);
        $updateset[] = "useragent = ".sqlesc($agent);
        if (count($updateset))
        sql_query("INSERT INTO sessions (sid, uid, username, class, ip, time, url, useragent) VALUES (".implode(", ", array_map("sqlesc", array($sid, $uid, $username, $class, $ip, $ctime, $url, $agent))).") ON DUPLICATE KEY UPDATE ".implode(", ", $updateset)) or sqlerr(__FILE__,__LINE__);
    }
    if ($CURUSER) {

        $CURUSER['access'] = get_user_class();

       // $allowed_types=array('unread', 'inbox', 'outbox');

       // $secs_system = $REL_CONFIG['pm_delete_sys_days']*86400; // Количество дней
       // $dt_system = time() - $secs_system; // Сегодня минус количество дней
        //$dt_system = 0;
       // $secs_all = $REL_CONFIG['pm_delete_user_days']*86400; // Количество дней
       // $dt_all = time() - $secs_all; // Сегодня минус количество дней
        //$dt_all = 0;
        /*
        foreach ($allowed_types as $type) {
            if ($type=='unread'){
                $addition = "location=1 AND receiver={$CURUSER['id']} AND unread=1 AND IF(archived_receiver=1, 1=1, IF(sender=0,added>$dt_system,added>$dt_all))";
                $table='messages';
                $noadd=true;
            }
            elseif ($type=='inbox'){
                $addition = "location=1 AND receiver={$CURUSER['id']} AND IF(archived_receiver=1, 1=1, IF(sender=0,added>$dt_system,added>$dt_all))";
                $table='messages';
                $noadd=true;
            }
            elseif ($type=='outbox'){
                $addition = "saved=1 AND sender={$CURUSER['id']} AND IF(archived_receiver<>1, 1=1, IF(sender=0,added>$dt_system,added>$dt_all))";
                $table = 'messages';
                $noadd=true;
            }
            elseif ($type=='reports')
                $noadd=true;


            $noselect = @implode(',',@array_map("intval",$_SESSION['visited_'.$type]));

            $string = ($noselect?$sel_id.'id NOT IN ('.$noselect.') AND ':'').($noadd?'':"{$sel_id}added>".$CURUSER['last_login']).$addition;

            $sql_query[]="(SELECT GROUP_CONCAT({$sel_id}id) FROM ".($table?$table:$type).($string?" WHERE $string":'').') AS '.$type;
            unset($addition);
            unset($sel_id);
            unset($table);
            unset($noadd);
            unset($string);
            unset($noselect);
        }

        if ($sql_query) {
            $sql_query = "SELECT ".implode(', ', $sql_query);

            //die($sql_query);
            $notifysql = sql_query($sql_query);
            $notifs = mysql_fetch_assoc($notifysql);
            foreach ($notifs as $type => $value) if ($value) $CURUSER[$type] = explode(',', $value);

            //$notifs = array_combine($allowed_types,explode(',',$notifs));
            //foreach ($notifs as $name => $value) $CURUSER[$name] = $value;
        }*/

    }
    return;
}

// закрываем сессию пользователя
function close_sessions() {
    // close old sessions
    $secs = 1 * 3600;
    $time = time();
    $dt = $time - $secs;
    $updates = sql_query("SELECT uid, time FROM sessions WHERE uid<>-1 AND time < $dt") or sqlerr(__FILE__,__LINE__);
    while ($upd = mysql_fetch_assoc($updates)) {
        sql_query("UPDATE users SET last_login={$upd['time']} WHERE id={$upd['uid']}") or sqlerr(__FILE__,__LINE__);
    }
    sql_query("DELETE FROM sessions WHERE time < $dt") or sqlerr(__FILE__,__LINE__);
}

//gzip сжатие
function gzip() {
    global $REL_CONFIG;
    if (@extension_loaded('zlib') && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler' && $REL_CONFIG['use_gzip']) {
        @ob_start('ob_gzhandler');
    } else @ob_start();
    return;
}

// получение IP
function getip() {
    $ip = false;
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if($ip){
            array_unshift($ips, $ip);
            $ip = false;
        }
        for($i = 0; $i < count($ips); $i++){
            if(!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i])){
                if(version_compare(phpversion(), "5.0.0", ">=")){
                    if(ip2long($ips[$i]) != false){
                        $ip = $ips[$i];
                        break;
                    }
                }
                else{
                    if(ip2long($ips[$i]) != - 1){
                        $ip = $ips[$i];
                        break;
                    }
                }
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

//кавычки
function sqlesc($value) {
    // Quote if not a number or a numeric string
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string((string)$value) . "'";
    }
    return $value;
}

// получаем класс пользователя
function get_user_class() {
    global $CURUSER;

    return is_valid_user_class($CURUSER['class'])?$CURUSER['class']:-1;


}

// прописываем хеадеры
function headers($ajax=false) {
    header("X-Powered-By: register ".RELVERSION);
    header("Cache-Control: no-cache, must-revalidate, max-age=0");
    header("Expires: 0");
    header("Pragma: no-cache");
    if ($ajax)   header ("Content-Type: text/html; charset=utf-8");
    return;
}
// проверка на аякс
function ajaxcheck() {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
        define ("REL_AJAX",true);
    else
        define("REL_AJAX",false);
    return;

}

//ловим ошибки
function sqlerr($file = '', $line = '') {
    global $queries, $CURUSER;
    $err = mysql_error();

    $res = sql_query("SELECT id FROM users WHERE class=".UC_ADMINISTRATOR);
    while (list($id) = mysql_fetch_array($res))
        //write_sys_msg($id,'Ошибка MySQL: '.$err.'<br />Файл: '.$file.'<br />Строка: '.$line.'<br />Ссылка: '.$_SERVER['REQUEST_URI'].'<br />Пользователь: '.$CURUSER['name'].'<br/>GET: '.print_r($_GET,true).'<br />POST:'.print_r($_POST,true),'MySQL error detected!');
print 'Ошибка MySQL: '.$err.'<br />Файл: '.$file.'<br />Строка: '.$line.'<br />Ссылка: '.$_SERVER['REQUEST_URI'];
   // write_log($CURUSER['name'].get_user_class_color($CURUSER['class'],$CURUSER['name'])."</a> SQL ERROR: $text</font>",'sql_errors');
    stderr("Ошибка выполнения."," Во время выполения скрипта произошла ошибка. Администратору отправлено сообщение. Можете на всякий случай его дополнительно оповестить.","no");
    return;
}
//для авторизации
function mkglobal($vars) {
    if (!is_array($vars))
        $vars = explode(":", $vars);
    foreach ($vars as $v) {
        if (isset($_GET[$v]))
            $GLOBALS[$v] = trim($_GET[$v]);
        elseif (isset($_POST[$v]))
            $GLOBALS[$v] = trim($_POST[$v]);
        else
            return false;
    }
    return true;
}
//проверка логина на корректность
function validusername($username){
    if ($username == "")
        return false;

    // The following characters are allowed in user names
    $allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_.";

    for ($i = 0; $i < strlen($username); ++$i)
        if (strpos($allowedchars, $username[$i]) === false)
            return false;

    return true;
}
//добавляем данные в куки
function logincookie($id, $passhash, $language, $updatedb = true, $expires = 0x7fffffff) {
    setcookie("uid", $id, $expires);
    setcookie("pass", md5($passhash.COOKIE_SECRET), $expires);
    setcookie("lang", $language, $expires);

    if ($updatedb)
        sql_query("UPDATE users SET last_access = ".time()." WHERE id = $id") or sqlerr(__FILE__,__LINE__);
    return;
}
//очищаем куки
function logoutcookie() {
    setcookie("uid", "", 0x7fffffff);
    setcookie("pass", "", 0x7fffffff);
    setcookie("lang", "", 0x7fffffff);
    unset($_SESSION);
    return;
}

//вывод ошибок
function stderr($heading = '', $text = '', $head = '', $div ='error', $htmlstrip = false) {
    global $REL_TPL;
    $REL_TPL->stderr($heading,$text,$head,$div,$htmlstrip);
}
//успешное действие
function stdmsg($heading = '', $text = '', $div = 'success', $htmlstrip = false) {
    global $REL_TPL;
    $REL_TPL->stdmsg($heading,$text,$div,$htmlstrip);
}

//проверка что поступившее значение - число и не 0
function is_valid_id($id) {
    return is_numeric($id) && ($id > 0) && (floor($id) == $id);
}

//красивый вывод времени
function mkprettytime($seconds, $time = true) {
    global $CURUSER, $REL_CONFIG;
//	$seconds = $seconds+$REL_CONFIG['site_timezone']*3600;

    if(!$seconds OR $seconds == 0){
        return "Нет данных";
    }

    $seconds = $seconds-date("Z")+$CURUSER['timezone']*3600;
    $search = array('January','February','March','April','May','June','July','August','September','October','November','December');
    $replace = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
    if ($time == true)
        $data = @date("j F Y в H:i:s", $seconds);
    else
        $data = @date("j F Y", $seconds);
    if (!$data)
        $data = 'N/A';
    else
        $data = str_replace($search, $replace, $data);
    return $data;
}


// переводим время в unix
function unix_time ($date){
    $date= trim($date);
    if (!$date)
        return "0";
    $a_time  = preg_split("~\D~",$date);
    $time = mktime(0,0,0,(int)$a_time['1'],(int)$a_time['0'],(int)$a_time['2']);

    return $time;
}
    //проверяем email
    function validemail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL)?true:false;
    }

    // определяем данные для создания переключателя страниц
    function create_paginator($page, $per_page, $table, $left_join = "", $where = ""){
        $paginator['page'] = (int) $page;

        if ($paginator['page'] < 2){
            $paginator['start_page'] = 0;
            $paginator['page'] = 1;
        }
        else {
            $paginator['start_page'] = ($paginator['page'] - 1)*$per_page;
        }
        $paginator['cpp'] = $per_page;
        $paginator['limit'] = "LIMIT ".$paginator['start_page']." , ".$paginator['cpp'];

        // узнаем сколько
        $res = sql_query("SELECT SUM(1) FROM $table $left_join WHERE $table.is_deleted = 0 $where ;") or sqlerr(__FILE__,__LINE__);

        $row = mysql_fetch_array($res);
        //всего записей
        $paginator['count'] = $row[0];
        //всего страниц
        $paginator['max_page'] = ceil($paginator['count'] / $paginator['cpp']);

        return $paginator;
    }
    function safe_redirect($url,$timeout = 0) {
        $url = trim($url);
        /*if (REL_AJAX || ob_get_length())*/ print('
    <script type="text/javascript" language="javascript">
    function Redirect() {
      location.href = "'.addslashes($url).'";
      }
      setTimeout(\'Redirect()\','.($timeout*1000).');
    </script>
');
        //else header("Refresh: $timeout; url=$url");
        return;
    }


    // поиск менеджера
    function select_manager($id_manager){
        if(!$id_manager OR $id_manager == 0) {
            return array();
        }
        $res = sql_query("
SELECT employee.name_employee,employee.id as e_id, employee.email, position.name_position, location_city.co as lc_co
FROM established_post
LEFT JOIN employee ON employee.id_uid_post = established_post.id
LEFT JOIN position ON position.id = established_post.id_position
LEFT JOIN location_city ON location_city.id = established_post.id_location_city
WHERE established_post.id = '".$id_manager."'");
        return mysql_fetch_array($res);
    }

    //данные для фильтра реестра
    function filer_register(){

        //дирекция
        $res_direction = sql_query("SELECT id, name_direction FROM direction WHERE is_deleted = 0");
        while ($row_direction = mysql_fetch_array($res_direction)) {
            $array['direction'][$row_direction['id']] = $row_direction['name_direction'];
        }
        // ФР/АР
        //ЦО/РЦК/РП
        $res_rck = sql_query("SELECT id, name_rck FROM rck WHERE is_deleted = 0");
        while ($row_rck = mysql_fetch_array($res_rck)) {
            $array['rck'][$row_rck['id']] = $row_rck['name_rck'];
        }
        //модель
        $res_model = sql_query("SELECT id, name_model FROM employee_model WHERE is_deleted = 0");
        while ($row_model = mysql_fetch_array($res_model)) {
            $array['model'][$row_model['id']] = $row_model['name_model'];
        }


        //город
        $res_city = sql_query("SELECT id, name_city FROM location_city WHERE is_deleted = 0");
        while ($row_city = mysql_fetch_array($res_city)) {
            $array['city'][$row_city['id']] = $row_city['name_city'];
        }

        //подразделение
    return $array;
    }