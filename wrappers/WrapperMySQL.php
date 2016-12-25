<?php

class WrapperMySQL
{
	private $c_obj_pdo;
	private $c_obj_stmt;

	public function __construct()
	{
		$this->c_obj_pdo = null;
		$this->c_obj_stmt = null;
	}

	public function __destruct() { }

	public function connect_to_database()
	{
		$m_arr_db_connection_details = $this->get_user_database_connection_details();
		$m_user_name = $m_arr_db_connection_details['user_name'];
		$m_user_password = $m_arr_db_connection_details['user_password'];
		$m_host_details = $m_arr_db_connection_details['host_details'];

		$this->c_obj_pdo = null;
		try
		{
			$this->c_obj_pdo = new PDO($m_host_details, $m_user_name, $m_user_password);
			$this->c_obj_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $exception_object)
		{
			trigger_error('error connecting to  database');
		}
		return $this->c_obj_pdo;
	}

	public function safe_query($p_query_string, $p_arr_query_parameters = false)
	{
		$m_query_success = false;
		try
		{	
			$m_temp = array();

			$this->c_obj_stmt = $this->c_obj_pdo->prepare($p_query_string);

			// bind the parameters
			if (sizeof($p_arr_query_parameters) > 0 && $p_arr_query_parameters != NULL)
			{
				foreach ($p_arr_query_parameters as $m_param_key => $m_param_value)
				{	
					$m_temp[$m_param_key] = $m_param_value;
					$this->c_obj_stmt->bindParam($m_param_key, $m_temp[$m_param_key], PDO::PARAM_STR);
					
				}
			}
			// execute the query
			$m_query_success = $this->c_obj_stmt->execute();
		}
		catch (PDOException $exception_object)
		{
			$m_error_message  = 'PDO Exception caught. ';
			$m_error_message .= 'Error with the database access. ';
			$m_error_message .= 'SQL query: ' . $p_query_string;
			$m_error_message .= 'Error: ' . print_r($this->c_obj_stmt->errorInfo(), true) . "\n";
			$m_error_message .= $this->c_obj_stmt->errorInfo();
			// NB would usually output to file for sysadmin attention
			$m_query_success = $m_error_message;
		}
		return $m_query_success;
	}

	public function count_rows()
	{
		$m_num_rows = $this->c_obj_stmt->rowCount();
		return $m_num_rows;
	}

	public function safe_fetch_row()
	{
		$m_record_set = $this->c_obj_stmt->fetch(PDO::FETCH_NUM);
		return $m_record_set;
	}

	public function safe_fetch_array()
	{
		$m_arr_row = $this->c_obj_stmt->fetchall(PDO::FETCH_ASSOC);
		$this->c_obj_stmt->closeCursor();
		return $m_arr_row;
	}

	public function last_inserted_ID()
	{
		$m_sql_query = 'SELECT LAST_INSERT_ID()';

		$this->safe_query($m_sql_query);
		$m_arr_last_inserted_id = $this->safe_fetch_array();
		$m_last_inserted_id = $m_arr_last_inserted_id['LAST_INSERT_ID()'];
		return $m_last_inserted_id;
	}

	private function get_user_database_connection_details()
	{
		$m_rdbms = 'mysql';
		$m_host = 'localhost';
		$m_db_name = 'messagesmssoap';
		$m_host_name = $m_rdbms . ':host=' . $m_host;
		$m_port_number = ';port=' . '3306';
		$m_user_name = 'smssoap';
		$m_user_password = 'secureweb';
		$m_user_database = ';dbname=' . $m_db_name;
		$m_host_details = $m_host_name . $m_port_number . $m_user_database;

		$m_arr_db_connect_details =
			[
				'host_details' => $m_host_details,
				'user_name' => $m_user_name,
				'user_password' => $m_user_password
			];
		return $m_arr_db_connect_details;
	}

}
