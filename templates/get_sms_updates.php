<?php
  require_once 'C:\xampp\htdocs\sw\proj\myp\wrappers\WrapperMySQL.php';
  require_once 'C:\xampp\htdocs\sw\proj\myp\wrappers\WrapperSQL.php';
  require_once 'C:\xampp\htdocs\sw\proj\myp\wrappers\WrapperSoap.php';

  require_once 'C:\xampp\htdocs\sw\proj\myp\classes\SoapSMSModel.php';
  require_once 'C:\xampp\htdocs\sw\proj\myp\classes\XmlParser.php';
  require_once 'C:\xampp\htdocs\sw\proj\myp\classes\Validate.php';

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
        if(strpos($f_arr_sms['message'], 'id":"llc') !== false) { // If the message contains ccl then
            $f_arr_sms['message'] = json_decode($f_arr_sms['message'], true);
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
    
    $f_obj_mysql_wrapper->safe_query($f_obj_sql_wrapper->get_message());
    $f_message_record_set = $f_obj_mysql_wrapper->safe_fetch_array();

  }
  echo "<table class='smstable'>
  <tr>
    <td>Source</td>
    <td>Destination</td>
    <td>Received</td>
    <td>Bearer</td>
    <td>Message Hash</td>
    <td>Message ID</td>
    <td>Switch 1</td>
    <td>Switch 2</td>
    <td>Switch 3</td>
    <td>Switch 4</td>
    <td>Fan</td>
    <td>Heater</td>
    <td>Keypad</td>
    <td>UpdateMessage</td>
  </tr>";

  foreach($f_message_record_set as $sms_arr)
  { 
    echo "<tr>";

    foreach($sms_arr as $f_index => $f_value) {

      echo "<td>" . $f_value . "</td>";
        
    }

    echo "</tr>";
  }
echo "</table>";
?>