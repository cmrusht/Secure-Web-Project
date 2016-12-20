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
  require_once $p_class_path . 'XmlParser.php';
  require_once $p_class_path . 'Validate.php';

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
    
    $m_arr_parsed_sms_messages = [];
    $f_arr_parsed_sms_messages = [];

    $f_obj_xml_parser =  new XmlParser();
    $f_obj_validate =  new Validate();
    
    foreach($f_arr_sms_messages as $f_index => $f_value)
    { 
        $f_obj_xml_parser->set_xml_string_to_parse($f_value);
        $f_obj_xml_parser->parse_the_xml_string();                  // Parsing
        $m_parsed_arr = $f_obj_xml_parser->get_parsed_data();

        foreach($m_parsed_arr as $f_index => $f_value)
        { 
            $f_value = $f_obj_validate->sanitise_string($f_value); // Sanitising
        }

        array_push($m_arr_parsed_sms_messages, $m_parsed_arr);
    }

    foreach($m_arr_parsed_sms_messages as $f_arr_sms) {  // For each of the arrays
        if(strpos($f_arr_sms['message'], 'id":"ccl') !== false) { // If the message contains ccl then
            $f_arr_sms['message'] = json_decode($f_arr_sms['message'], true);
            if (json_last_error() === JSON_ERROR_NONE) {    // Validating that string was of JSON format
                array_push($f_arr_parsed_sms_messages, $f_arr_sms);  // Thats our message so push it onto our array
            }
            
        }
    }

    print_r($f_arr_parsed_sms_messages);
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
      'sms_message' => $f_arr_parsed_sms_messages
  ];

  return $arr_data;
}

function feature_error()
{
  $arr_data = [
  ];
  return $arr_data;
}