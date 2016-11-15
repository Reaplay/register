<?php

require_once('Smarty.class.php');
class REL_TPL extends Smarty {

    private $config;


    /**
     * Class constructor depending on releaser's configuration
     * @param array $REL_CONFIG Releaser configuration
     */
    function __construct($REL_CONFIG) {
        global $CURUSER;
        define('SMARTY_RESOURCE_CHAR_SET','utf-8');
        parent::__construct();
        $this->template_dir = ROOT_PATH.'themes/'.$REL_CONFIG['default_theme'];
        $compile_dir = ROOT_PATH.'cache/compiled_template_'.$REL_CONFIG['default_theme'];
        if (!is_dir($compile_dir)) mkdir($compile_dir);
        $this->compile_dir = $compile_dir;
        //$this->config_dir = ROOT_PATH.'include';
        $cachedir = ROOT_PATH.'cache/cached_template_'.$REL_CONFIG['default_theme'];
        if (!is_dir($cachedir)) mkdir($cachedir);
        $this->cache_dir = $cachedir;
        //$this->security = true;
        $this->php_handling = SMARTY_PHP_REMOVE;
        $this->config['stdhead'] = 'stdhead.tpl';
        $this->config['stdfoot'] = 'stdfoot.tpl';
        $this->config['stdhead_ajax'] = 'stdhead_ajax.tpl';
        $this->config['stdfoot_ajax'] = 'stdfoot_ajax.tpl';
        //$this->register->templateFunction('show_blocks','show_blocks');
        if ($CURUSER) {
          //  $this->assignByRef('REL_NOTIFS',generate_notify_array());
           // $this->assignByRef('REL_RATING_POPUP',generate_ratio_popup_warning());
        }
        if ($REL_CONFIG['cache_template']) {
            $this->caching = true;
            $this->cache_lifetime = $REL_CONFIG['cache_template_time'];
        }
        if ($REL_CONFIG['debug_template'] && get_user_class()>=UC_ADMINISTRATOR) {
            $this->debugging = true;
        }

        $this->assignByRef('CURUSER',$CURUSER);
        $this->assignByRef('REL_CONFIG',$REL_CONFIG);
    }

    /**
     * Outputs template's head
     * @param string $title Page title
     * @param string $descradd Page description addition
     * @param string $keywordsadd Page keywords addition
     * @param string $headadd Page <head> tag addition
     */
    function stdhead($title = "", $headadd = '') {
        global $REL_CONFIG, $CURUSER;

        if (!$REL_CONFIG['siteonline'] && get_user_class()<UC_ADMINISTRATOR) {
            $this->display('offline.tpl');
            die();
        }
        $offline = false;
        if (get_user_class() == UC_ADMINISTRATOR && !$REL_CONFIG['siteonline']) {
            $offline=true;
        }
        $this->assignByRef('OFFLINE',$offline);
        $access_overrided=(isset($_COOKIE['override_class']) && $CURUSER);
        $this->assignByRef('access_overrided',$access_overrided);

		$this->assign('IS_ADMINISTRATOR',get_user_class() >= UC_ADMINISTRATOR);
        headers(REL_AJAX);
        $this->assignByRef('title',$title);
         $this->assignByRef('headadd',$headadd);

        if (REL_AJAX) {
           $this->display($this->config['stdhead_ajax']);
        } else {
            $this->display($this->config['stdhead']);
        }
    }

    /**
     * Configures class to use specific templates
     * @param string $param Config parameter to be assigned for
     * @param string $value Value to be assigned
     */
    function configure($param,$value) {
        $this->config[$param]=$value;
    }

    /**
     * Outputs theme footer
     */
    function stdfoot($js_add="") {
        $this->assign('COPYRIGHT',REGISTER_VERSION.(BETA?BETA_NOTICE:""));
      //  generate_post_javascript();
        close_sessions();

        debug();
        $this->assign('js_add',$js_add);
        if (REL_AJAX) {
           // $this->display($this->config['stdfoot_ajax']);
        } else {
            $this->display($this->config['stdfoot']);
        }

    }


    /**
     * Outputs standart information message
     * @param string $heading Message title (blank)
     * @param string $text Message text (blank)
     * @param string $div Message type (success)
     * @param boolean $htmlstrip Strip html? (false)
     */
    function stdmsg($heading = '', $text = '', $div = 'success', $htmlstrip = false) {
        if ($htmlstrip) {
            $heading = strip_tags(trim($heading));
            $text = strip_tags(trim($text));
        }
        $this->assignByRef('MSG_TITLE',$heading);
        $this->assignByRef('MSG_TEXT',$text);
        if (REL_AJAX) $this->display('stdmsg_'.$div.'_ajax.tpl'); else
            $this->display('stdmsg_'.$div.'.tpl');
        return;
    }

    /**
     * Outputs error and die
     * @param string $heading Message title (blank)
     * @param string $text Message text (blank)
     * @param string $div Message type (error)
     * @param boolean $htmlstrip Strip html? (false)
     */
    function stderr($heading = '', $text = '',$head='', $div ='error', $htmlstrip = false) {
        if (!$head){
			$this->stdhead($heading);
		}
        $this->stdmsg($heading, $text, $div, $htmlstrip);
        $this->stdfoot();
        die;
        return;
    }
	
    function stderr_no_head($text = '', $div ='error', $htmlstrip = false) {
        $this->stdmsg($heading, $text, $div, $htmlstrip);
        $this->stdfoot();
        die;
        return;
    }
    /**
     * Displays module tpl located in {THEME_DIR}/modules/$module/$sub_module/$action.tpl
     * @param string $action Action of selected module
     * @param string $module Custom module usage
     */
    function output($action='',$module='',$sub_module='') {
        if (!$action)
            $action='index';
        if ($sub_module)
            $sub_module = $sub_module."/";
        if (!$module)
            $module = str_replace ( ".php", "", basename ( $_SERVER ["PHP_SELF"] ) );
        $this->display("modules/$module/$sub_module$action.tpl");
    }
}
?>