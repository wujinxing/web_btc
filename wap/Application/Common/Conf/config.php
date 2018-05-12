<?php

return array(
	'DB_TYPE'              => DB_TYPE,
	'DB_HOST'              => DB_HOST,
	'DB_NAME'              => DB_NAME,
	'DB_USER'              => DB_USER,
	'DB_PWD'               => DB_PWD,
	'DB_PORT'              => DB_PORT,
	'DB_PREFIX'            => 'qqbtc_',
	'ACTION_SUFFIX'        => '',
	'MULTI_MODULE'         => true,
	'MODULE_DENY_LIST'     => array('Common', 'Runtime'),
	'MODULE_ALLOW_LIST'    => array('Home', 'Admin'),
	'DEFAULT_MODULE'       => 'Home',
	'URL_CASE_INSENSITIVE' => false,
	'URL_MODEL'            => 2,
	'URL_HTML_SUFFIX'      => 'html',
	'UPDATE_PATH'          => './Database/Update/',
	'CLOUD_PATH'           => './Database/Cloud/',
    'HOST_IP'	=> '',
	'TMPL_CACHFILE_SUFFIX' =>'.html',
	'DATA_CACHE_TYPE'      => 'file',
	'URL_PARAMS_SAFE'	   => true,
	'URL_PARAMS_FILTER'    => true,
	'DEFAULT_FILTER'       =>'check_qqbtc,htmlspecialchars,strip_tags',
	'URL_PARAMS_FILTER_TYPE' =>'check_qqbtc,htmlspecialchars,strip_tags',
	);
?>