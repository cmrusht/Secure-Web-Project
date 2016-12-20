<?php

$obj_application->get('/processrequest', function() use ($obj_application)
{
  $f_class_path = $obj_application->config('classes.path');
  $f_wrapper_path = $obj_application->config('wrappers.path');
  $f_css_path = $obj_application->config('css.path');
  $f_doc_root = $obj_application->config('docroot');
  $f_arr_features = $obj_application->config('features');
  
  $f_feature = $obj_application->request->get('feature');
  $f_validated_feature = validation($f_feature, $f_class_path, $f_arr_features);

  switch ($f_validated_feature)
  {
    case 'display_messages':
      $arr_data = display_SMS_form($f_css_path, $f_doc_root, $f_wrapper_path, $f_class_path);
      $feature = 'displaySMSmessages.php';
      break;
    default:
      $arr_data = feature_error();
      $feature ='feature_error.php';
  }

  $obj_application->render($feature, $arr_data);

});

function validation($p_feature, $p_class_path, $p_arr_features)
{
  require_once $p_class_path . 'Validate.php';
  $f_obj_validate = new Validate();

  $f_validated_feature = $f_obj_validate->validate_feature($p_feature, $p_arr_features);
  return $f_validated_feature;
}

function display_SMS_form($p_css_path, $p_doc_root, $p_wrapper_path, $p_class_path)
{
  require_once $p_wrapper_path . 'WrapperSoap.php';
  require_once $p_class_path . 'SoapSMSModel.php';

  $f_obj_soap_client_handle = null;
  $f_obj_soap_wrapper = new WrapperSoap();
  $f_obj_soap_wrapper->set_wsdl('https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl');
  $f_obj_soap_wrapper->make_soap_client();
  // If we are connected to the soap client
  if ($f_obj_soap_wrapper->get_soap_client_connection_status() === true)
  {
    $f_obj_soap_client_handle = $f_obj_soap_wrapper->get_soap_client_handle();
  }

  if ($f_obj_soap_client_handle != null)
  {
    $f_obj_download_details = new SoapSMSModel();
    $f_obj_download_details->set_soap_client_handle($f_obj_soap_client_handle);
    $f_arr_sms_messages = $f_obj_download_details->get_sms_messages();
    
    $f_arr_split_sms_messages = [];
    foreach($f_arr_sms_messages as $f_index => $f_value)
    { 
      array_push($f_arr_split_sms_messages, splitMessage($f_value));
    }
  }
  $f_page_heading_2 = 'Showing download SMS messages';
  $f_page_text  = '...';

  $f_css_file =  $p_css_path . CSS_NAME .'.css';
  $f_application_name = APP_NAME;

  $arr_data = [
      'landing_page' => $p_doc_root,
      'action' => 'processrequest',
      'method' => 'get',
      'css_filename' => $f_css_file,
      'page_title' => $f_application_name,
      'page_heading_1' => $f_application_name,
      'page_heading_2' => $f_page_heading_2,
      'page_text' => $f_page_text,
      'sms_message' => $f_arr_split_sms_messages
  ];

  return $arr_data;
}

function splitMessage($p_messagerx) {
  $f_sourcemsisdn = implode(preg_split("/.*\<(sourcemsisdn)\>|\<(\/sourcemsisdn).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));
  $f_destinationmsisdn = implode(preg_split("/.*\<(destinationmsisdn)\>|\<(\/destinationmsisdn).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));
  $f_receivedtime = implode(preg_split("/.*\<(receivedtime)\>|\<(\/receivedtime).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));
  $f_bearer = implode(preg_split("/.*\<(bearer)\>|\<(\/bearer).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));
  $f_messageref = implode(preg_split("/.*\<(messageref)\>|\<(\/messageref).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));
  $f_message = implode(preg_split("/.*\<(message)\>|\<(\/message).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));

  $f_arr_message = [
      'sourcemsidn' => $f_sourcemsisdn,
      'desinationmsidn' => $f_destinationmsisdn,
      'receivedtime' => $f_receivedtime,
      'bearer' => $f_bearer,
      'messageref' => $f_messageref,
      'message' => $f_message
  ];

  return $f_arr_message;
}

function feature_error()
{
  $arr_data = [
  ];
  return $arr_data;
}