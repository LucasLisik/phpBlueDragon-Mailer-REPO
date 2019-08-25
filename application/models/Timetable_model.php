<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 24-8-2015 12:55
 *
 */
 
class Timetable_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function DeleteTimetable($Id)
	{
		$QueryResult = $this->db->query('DELETE FROM 
        {PREFIXDB}messages
        WHERE
        message_id = "'.$this->db->escape_str($Id).'"
        ');
	}
	
	public function AddTimeMessage()
	{
		// Przeniesione do Messages_model
		return false;
	}
}

?>