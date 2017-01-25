<?php


$obj_application->get('/', function() use ($obj_application)
{	
	// Setup config variables
	$f_css_path = $obj_application->config('css.path');
	$f_doc_root = $obj_application->config('docroot');
	$f_css_file =  $f_css_path . CSS_NAME .'.css';
	$f_application_name = APP_NAME;

	// Create an array of data
	$arr_data = [
	  'landing_page' => $f_doc_root,
		'action' => 'processrequest',
		'method' => 'get',
		'css_filename' => $f_css_file,
		'page_title' => $f_application_name,
		'page_heading_1' => $f_application_name,
	];
	
	// Render the page and pass array in
	$obj_application->render('homepagehtml.php', $arr_data);
})->name('homepage');

