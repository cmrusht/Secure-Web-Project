<?php

class WrapperSoap
{
  private $c_wsdl;
  private $c_obj_soap_client_handle;
  private $c_arr_downloaded_data;
  private $c_soap_client_connection_status;

  public function __construct()
  {
    $this->c_wsdl = null;
    $this->c_obj_soap_client_handle = null;
    $this->c_arr_downloaded_data = null;
    $this->c_soap_client_connection_status = false;
  }

  public function __destruct(){}

  public function set_wsdl($p_wsdl)
  {
    $this->c_wsdl = $p_wsdl;
  }

  public function get_soap_client_connection_status()
  {
    return $this->c_soap_client_connection_status;
  }

  public function get_soap_client_handle()
  {
    return $this->c_obj_soap_client_handle;
  }
  private function create_soap_connection_settings()
  {
    $m_arr_soapclient_settings = [
        'trace' => true,
        'exceptions' => true
    ];
    return $m_arr_soapclient_settings;
  }

  public function make_soap_client()
  {
    $m_soap_server_connection_status = false;

    $m_arr_soapclient_settings = $this->create_soap_connection_settings();

    if ($this->c_wsdl != null)
    {
      try
      {
        $this->c_obj_soap_client_handle = new SoapClient($this->c_wsdl, $m_arr_soapclient_settings);
        $m_soap_server_connection_status = true;
      }
      catch (SoapFault $m_obj_exception)
      {
        trigger_error($m_obj_exception);
      }
    }
    $this->c_soap_client_connection_status = $m_soap_server_connection_status;
  }
}