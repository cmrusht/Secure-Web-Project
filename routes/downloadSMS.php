<?php

// Executes when /processrequest on end of web URL
$obj_application->get('/processrequest', function() use ($obj_application)
{
  // Setup config variables
  $f_class_path = $obj_application->config('classes.path');
  $f_wrapper_path = $obj_application->config('wrappers.path');
  $f_css_path = $obj_application->config('css.path');
  $f_doc_root = $obj_application->config('docroot');
  $f_arr_features = $obj_application->config('features');
  $f_feature = $obj_application->request->get('feature');
  // Check feature is valid
  $f_validated_feature = validation($f_feature, $f_class_path, $f_arr_features);

  // Switch on the feature and render accordingly
  switch ($f_validated_feature)
  {
    case 'display_messages':
      $arr_data = display_SMS_form($f_css_path, $f_doc_root, $f_wrapper_path, $f_class_path);
      $feature = 'displaySMSmessages.php';
      break;
    case 'SendMessage':
      $arr_data = display_SMS_form($f_css_path, $f_doc_root, $f_wrapper_path, $f_class_path);
      $feature = 'displaySMSmessages.php';
      break;
    default:
      $arr_data = feature_error();
      $feature ='feature_error.php';
  }

  // Render feature
  $obj_application->render($feature, $arr_data);

});

// Validaton function takes in a feature and validates it and returns
function validation($p_feature, $p_class_path, $p_arr_features)
{
  require_once $p_class_path . 'Validate.php';
  $f_obj_validate = new Validate();

  $f_validated_feature = $f_obj_validate->validate_feature($p_feature, $p_arr_features);
  return $f_validated_feature;
}

// Display sms form function
function display_SMS_form($p_css_path, $p_doc_root, $p_wrapper_path, $p_class_path)
{
  // Includes
  require_once $p_wrapper_path . 'WrapperSoap.php';
  require_once $p_wrapper_path . 'WrapperMySQL.php';
  require_once $p_wrapper_path . 'WrapperSQL.php';
  require_once $p_class_path . 'SoapSMSModel.php';
  require_once $p_class_path . 'XmlParser.php';
  require_once $p_class_path . 'Validate.php';

  // Setup soap connection
  $f_obj_soap_client_handle = null;
  $f_obj_soap_wrapper = new WrapperSoap();
  $f_obj_soap_wrapper->set_wsdl('https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl');
  $f_obj_soap_wrapper->make_soap_client();
  // If we are connected to the soap client
  if ($f_obj_soap_wrapper->get_soap_client_connection_status() === true)
  {
    $f_obj_soap_client_handle = $f_obj_soap_wrapper->get_soap_client_handle();
  }

  // If we are connected
  if ($f_obj_soap_client_handle != null)
  { 
    // Create a new soap model
    $f_obj_download_details = new SoapSMSModel();
    $f_obj_download_details->set_soap_client_handle($f_obj_soap_client_handle);

    
    if(isset($_GET['message'])) {
      // Send a message with get variabes
      $f_obj_download_details->send_sms_message($_GET['number'], $_GET['s1'], $_GET['s2'], $_GET['s3'], $_GET['s4'], $_GET['fan'], $_GET['heater'], $_GET['keypad'], $_GET['message']);
    }

    // Retrieve all SMS messages
    $f_arr_sms_messages = $f_obj_download_details->get_sms_messages();
    
    // Create 2 arrays for parsed messages
    $m_arr_parsed_sms_messages = [];
    $f_arr_parsed_sms_messages = [];

    // Setup validator and parser
    $f_obj_xml_parser =  new XmlParser();
    $f_obj_validate =  new Validate();
    
    // For each of the messages parse the string and then sanitise it
    foreach($f_arr_sms_messages as $f_index => $f_value)
    { 
        $f_obj_xml_parser->set_xml_string_to_parse($f_value);
        $f_obj_xml_parser->parse_the_xml_string();                  // Parsing
        $m_parsed_arr = $f_obj_xml_parser->get_parsed_data();

        foreach($m_parsed_arr as $f_index => $f_value)
        { 
            $f_value = $f_obj_validate->sanitise_string($f_value); // Sanitising
        }
        
        // Create array of new strings
        array_push($m_arr_parsed_sms_messages, $m_parsed_arr);
    }

    // for each of the parsed message
    foreach($m_arr_parsed_sms_messages as $f_arr_sms) {  // For each of the arrays
      if(strpos($f_arr_sms['message'], 'id":"llc') !== false) { // If the message contains llc then
        $f_arr_sms['message'] = json_decode($f_arr_sms['message'], true); // Decode the json string
        if (json_last_error() === JSON_ERROR_NONE) {    // Validating that string was of JSON format
          if(strlen($f_arr_sms['message']['hashid']) == 5) {
            array_push($f_arr_parsed_sms_messages, $f_arr_sms);  // Thats our message so push it onto our array
          }       
        }     
      }
    }


    $f_obj_mysql_wrapper = new WrapperMySQL();  //Setting up SQL Connections and wrapper
    $f_obj_sql_wrapper = new WrapperSQL();
    $f_obj_pdo = $f_obj_mysql_wrapper->connect_to_database();

    
    foreach($f_arr_parsed_sms_messages as $sms_arr) // For every element in the message array
    {   
      // Check to see if any of ours messages have the same hash as one in the database
      $f_obj_mysql_wrapper->safe_query($f_obj_sql_wrapper->check_dupe_message($sms_arr['message']['hashid']));    
      if ($f_obj_mysql_wrapper->count_rows() == 0) {  // If the last query ^ returned any rows dont use that message

        $f_arr_sql_param = [];  // New array for our messages
        $f_arr_sql_key = array('source', 'destination', 'received', 'bearer', 'message_hash', 'message_id', 'switch1', 'switch2', 'switch3', 'switch4', 'fan', 'heater', 'keypad', 'message');    // Create an array of the table fields
        $i = 0; // Incrementer
        foreach($sms_arr as $f_index => $f_value) { // For each of our messages 
          if ($f_index == 'message') {    // If we are at the message data
            foreach($f_value as $m_index => $m_value) { // For each the message data
              $f_arr_sql_param[$f_arr_sql_key[$i]] = $m_value;    // Set the value correlating to the table array fields
              $i++;   // Increment
            }
          }
          else {
            $f_arr_sql_param[$f_arr_sql_key[$i]] = $f_value;    // Set the value correlating to the table array fields
            $i++;
          }
        }

        $f_obj_mysql_wrapper->safe_query($f_obj_sql_wrapper->store_message(), $f_arr_sql_param);    // Insert our messages into the database using our new array of messages
      }  
    }
    
    // Retrieve the messages from the database
    $f_obj_mysql_wrapper->safe_query($f_obj_sql_wrapper->get_message());
    $f_message_record_set = $f_obj_mysql_wrapper->safe_fetch_array();

  }

  // Setup variables and array to pass the page render
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
    'sms_message' => $f_message_record_set
  ];

  return $arr_data;
}

function feature_error()
{
  $arr_data = [
  ];
  return $arr_data;
}