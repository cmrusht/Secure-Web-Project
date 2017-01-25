<?php

session_start();

require_once 'Slim/Slim.php';
// required for non-composer (eg Windows) installations
\Slim\Slim::registerAutoloader();

define ('DIRSEP', DIRECTORY_SEPARATOR);
define ('URLSEP', '/');
define ('APP_NAME', 'Message Project');
define ('CSS_NAME', 'messagestyle');

$f_doc_root = extract_document_root();

$obj_application = new \Slim\Slim(array(
		'mode' => 'development',
		'debug' => true,
		'app_route.path' => __DIR__,
		'templates.path' => __DIR__ . DIRSEP . 'templates' . DIRSEP,
		'classes.path'	=>	__DIR__ . DIRSEP . 'classes' . DIRSEP,
		'wrappers.path'	=>	__DIR__ . DIRSEP . 'wrappers' . DIRSEP,
		'css.path'	=>	$f_doc_root . DIRSEP . 'css' . DIRSEP,
		'docroot' => $f_doc_root . DIRSEP,
		'features' => [
				'display_messages' => 'Download and Display SMS Messages',
				'SendMessage' => 'Send SMS Message'
		]
));

require 'routes.php';

$obj_application->run();

function extract_document_root()
{
	$f_doc_root = implode(URLSEP, explode(URLSEP, $_SERVER["SCRIPT_NAME"], -1));
	return $f_doc_root;
}