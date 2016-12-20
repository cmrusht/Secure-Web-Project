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
    $c_downloaded_sms = $this->c_obj_soap_client_handle->peekMessages("16craigd", "Craggz123", 20, "");
    
    return $c_downloaded_sms;
  }
}
