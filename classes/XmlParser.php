<?php

class XmlParser
{
    private $c_parsed_data;	          // array holds extracted data
    private $c_xml_string_to_parse;


    public function __construct()
    {
    }

    public function __destruct(){}

    public function set_xml_string_to_parse($p_xml_string_to_parse)
    {
        $this->c_xml_string_to_parse = $p_xml_string_to_parse;
    }

    public function get_parsed_data()
    {
        return $this->c_parsed_data;
    }

    public function parse_the_xml_string()
        {
            $this->c_parsed_data = $this->process_xml_string($this->c_xml_string_to_parse);
        }
    
    private function process_xml_string($p_messagerx) {
        $m_sourcemsisdn = implode(preg_split("/.*\<(sourcemsisdn)\>|\<(\/sourcemsisdn).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));
        $m_destinationmsisdn = implode(preg_split("/.*\<(destinationmsisdn)\>|\<(\/destinationmsisdn).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));
        $m_receivedtime = implode(preg_split("/.*\<(receivedtime)\>|\<(\/receivedtime).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));
        $m_bearer = implode(preg_split("/.*\<(bearer)\>|\<(\/bearer).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));
        //$m_messageref = implode(preg_split("/.*\<(messageref)\>|\<(\/messageref).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));
        $m_message = implode(preg_split("/.*\<(message)\>|\<(\/message).*/", $p_messagerx, -1, PREG_SPLIT_NO_EMPTY));

        $m_arr_message = [
            'sourcemsidn' => $m_sourcemsisdn,
            'desinationmsidn' => $m_destinationmsisdn,
            'receivedtime' => $m_receivedtime,
            'bearer' => $m_bearer,
            //'messageref' => $m_messageref,
            'message' => $m_message
        ];


      return $m_arr_message;
    }
}
