<?php
/*
  Contains sql queries
*/
class WrapperSQL
{
  public function __construct(){}

  public function __destruct(){}

  // Store messages sql query
  public function store_message()
  {
    $m_sql_query_string  = "INSERT INTO message (source, destination, received, bearer, message_hash, message_id, switch1, switch2, switch3, switch4, fan, heater, keypad, message)";
    $m_sql_query_string .= "VALUES (";
    $m_sql_query_string .= ":source, ";
    $m_sql_query_string .= ":destination, ";
    $m_sql_query_string .= ":received, ";
    $m_sql_query_string .= ":bearer, ";
    $m_sql_query_string .= ":message_hash, ";
    $m_sql_query_string .= ":message_id, ";
    $m_sql_query_string .= ":switch1, ";
    $m_sql_query_string .= ":switch2, ";
    $m_sql_query_string .= ":switch3, ";
    $m_sql_query_string .= ":switch4, ";
    $m_sql_query_string .= ":fan, ";
    $m_sql_query_string .= ":heater, ";
    $m_sql_query_string .= ":keypad, ";
    $m_sql_query_string .= ":message);";
    return $m_sql_query_string;
  }

  // Get messages sql query
  public function get_message()
  {
    $m_sql_query_string  = "SELECT DISTINCT * FROM message ";
    $m_sql_query_string .= "ORDER BY received DESC;";
    return $m_sql_query_string;
  }

  // Check if a message is a duplicate in database query
  public function check_dupe_message($p_message_hash)
  {
    $m_sql_query_string  = "SELECT DISTINCT * FROM message ";
    $m_sql_query_string .= "WHERE ";
    $m_sql_query_string .= "message_hash = '$p_message_hash'";
    return $m_sql_query_string;
  }




}