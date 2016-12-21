<?php

class WrapperSQL
{
  public function __construct(){}

  public function __destruct(){}
  
  public function get_company_symbols()
  {
    $m_sql_query_string  = "SELECT stock_company_symbol, stock_company_name ";
    $m_sql_query_string .= "FROM sq_company_name ";
    $m_sql_query_string .= "ORDER BY stock_company_name;";
    return $m_sql_query_string;
  }

  public function check_company_symbol()
  {
    $m_sql_query_string  = "SELECT stock_company_symbol, stock_company_name_id ";
    $m_sql_query_string .= "FROM sq_company_name ";
    $m_sql_query_string .= "WHERE stock_company_symbol = :stock_company_symbol ";
    $m_sql_query_string .= "LIMIT 1";
    return $m_sql_query_string;
  }

  public function store_message()
  {
    $m_sql_query_string  = "INSERT INTO message (source, destination, received, bearer, message_hash, message_id, message, message_timestamp)";
    $m_sql_query_string .= "VALUES (";
    $m_sql_query_string .= ":source, ";
    $m_sql_query_string .= ":destination, ";
    $m_sql_query_string .= ":received, ";
    $m_sql_query_string .= ":bearer, ";
    $m_sql_query_string .= ":message_hash, ";
    $m_sql_query_string .= ":message_id, ";
    $m_sql_query_string .= ":message, ";
    $m_sql_query_string .= ":message_timestamp);";
    return $m_sql_query_string;
  }

  public function check_dupe_message($p_message_hash)
  {
    $m_sql_query_string  = "SELECT DISTINCT * FROM message ";
    $m_sql_query_string .= "WHERE ";
    $m_sql_query_string .= "message_hash = '$p_message_hash'";
    return $m_sql_query_string;
  }




}