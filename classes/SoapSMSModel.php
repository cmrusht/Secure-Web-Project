<?php

/*
  Sets up the soap client handle, and allows sending and retrieving get_sms_messages
*/

class SoapSMSModel
{
  private $c_obj_soap_client_handle;
  private $c_downloaded_sms;

  public function __construct()
  {
    $this->c_obj_soap_client_handle = null;
    $this->c_downloaded_sms = '';
  }

  public function __destruct(){}

  // Set soap client handle
  public function set_soap_client_handle($p_soap_client_handle)
  {
    $this->c_obj_soap_client_handle = $p_soap_client_handle;
  }

  // Retrieve messages
  public function get_sms_messages()
  {
    $c_downloaded_sms = $this->c_obj_soap_client_handle->peekMessages("16craigd", "Craggz123", 500, "");
    
    return $c_downloaded_sms;
  }
  
  // Send a message
  public function send_sms_message($p_number, $p_s1, $p_s2, $p_s3, $p_s4, $p_fan, $p_heater, $p_keypad, $p_message)
  {
    date_default_timezone_set("Europe/London");
    $m_message = '{"hashid":"'.$p_number.'","id":"llc","s1":"'.$p_s1.'","s2":"'.$p_s2.'","s3":"'.$p_s3.'","s4":"'.$p_s4.'","fan":"'.$p_fan.'","heater":"'.$p_heater.'","keypad":"'.$p_keypad.'","message":"'.$p_message.'"}';
    $this->c_obj_soap_client_handle->sendMessage("16craigd", "Craggz123", "7817814149", $m_message, false, "SMS");
  }

}
