<?php


if (!isset($_GET['action'])){
	$REL_TPL->stdhead("Основные настройки");


	$REL_TPL->output("configadmin","admincp");
	$REL_TPL->stdfoot();

}

elseif ($_GET['action'] == 'save'){
	if($_POST['type']=="site"){
		$reqparametres=array('siteonline','defaultbaseurl','siteemail','adminemail','default_theme','yourcopy','use_blocks','use_gzip');
	}
	elseif($_POST['type']=="crm"){
		$reqparametres=array('per_page_clients','per_page_users','per_page_department','per_page_callback','per_page_card');
	}
	elseif($_POST['type']=="module"){
		$reqparametres=array('deny_users','deny_client','deny_card','deny_statistic');
	}
	elseif($_POST['type']=="notify"){
		$reqparametres=array('default_notifs','default_emailnotifs');
	}
	elseif($_POST['type']=="limit"){
		$reqparametres=array('maxusers','pm_max','pm_delete_sys_days','pm_delete_user_days');
	}
	elseif($_POST['type']=="security"){
		$reqparametres=array('debug_mode','debug_template');
	}
	elseif($_POST['type']="cache"){
		$reqparametres=array('cache_template','cache_template_time','cache_statistic_all');
	}

	//$captcha_param = array('re_publickey','re_privatekey');

	$updateset = array();

	foreach ($reqparametres as $param) {
		if (!isset($_POST[$param])) stderr("Ошибка","Некоторые поля не заполнены ($param)");
		$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
	}

	foreach ($updateset as $query) sql_query($query);

	$REL_CACHE->clearCache('system','config');

	safe_redirect("action_admin.php?module=configadmin");


}

else
	stderr("Ошибка","Неизвестное действие");

?>