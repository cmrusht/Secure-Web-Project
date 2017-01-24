<?php

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


  public function set_soap_client_handle($p_soap_client_handle)
  {
    $this->c_obj_soap_client_handle = $p_soap_client_handle;
  }

  public function get_sms_messages()
  {
    $c_downloaded_sms = $this->c_obj_soap_client_handle->peekMessages("16craigd", "Craggz123", 100, "");
    
    return $c_downloaded_sms;
  }
  public function send_sms_message($p_message, $p_number)
  {
    date_default_timezone_set("Europe/London");
    $m_message = '{"hashid":"'.$p_number.'","id":"ccl","message":"'.$p_message.'","timestamp":"'.date("d/m/Y").' '.date("H:i:s").'"}';
    $this->c_obj_soap_client_handle->sendMessage("16craigd", "Craggz123", "7817814149", $m_message, false, "SMS");
  }

}
