<?php

class Validate
{
	public function __construct() { }

	public function __destruct() { }

	public function sanitise_string($p_string_to_sanitise)
	{
		
			$m_sanitised_string = filter_var($p_string_to_sanitise, FILTER_SANITIZE_STRING);
		return $m_sanitised_string;
	}

	public function validate_feature($p_type_to_check, $p_arr_features)
	{
		$m_checked_server_type = false;
		foreach($p_arr_features as $f_index => $f_value)
  		{
    		if ($p_type_to_check == $f_index)
			{
				$m_checked_server_type = $p_type_to_check;
			}
  		}
		return $m_checked_server_type;
	}
}