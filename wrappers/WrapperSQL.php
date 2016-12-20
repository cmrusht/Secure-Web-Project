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

  public function store_company_name()
  {
    $m_sql_query_string  = "INSERT INTO sq_company_name ";
    $m_sql_query_string .= "SET ";
    $m_sql_query_string .= "stock_company_symbol = :stock_company_symbol, ";
    $m_sql_query_string .= "stock_company_name = :stock_company_name;";
    return $m_sql_query_string;
  }


}