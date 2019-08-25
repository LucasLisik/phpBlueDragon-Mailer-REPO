<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 14-9-2015 18:58
 *
 */
 
class Track_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function AddTrackNone()
    {
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}track
        (
        track_date
        )
        VALUES
        (
        "'.$this->db->escape_str(date('Y-m-d H:i:s')).'"
        )
        ');
    }
    
    public function AddTrack($MailingId,$EmailId)
    {
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}track
        (
        track_mailing_id,
        track_email_id,
        track_date,
		track_ip
        )
        VALUES
        (
        "'.$this->db->escape_str($MailingId).'",
        "'.$this->db->escape_str($EmailId).'",
        "'.$this->db->escape_str(date('Y-m-d H:i:s')).'",
		"'.$this->db->escape_str($_SERVER['REMOTE_ADDR']).'"
        )
        ');
    }
	
	public function GetMessageInfo($MessageId)
	{
		$QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}messages
		WHERE
		message_id = "'.$this->db->escape_str($MessageId).'"
		');
		
        return $QueryResult;
	}
	
	public function GetListInfo($EmailGroupId)
	{
		$QueryResult = $this->db->query('SELECT email_id, email_email FROM 
		{PREFIXDB}groups_email
		WHERE
		email_groups_id = "'.$this->db->escape_str($EmailGroupId).'"
		');
		
        return $QueryResult;
	}
	
	public function TrackCount($MailingId)
	{
		$QueryResult = $this->db->query('SELECT count(track_id) AS HowMany FROM 
		{PREFIXDB}track
		WHERE
		track_mailing_id = "'.$this->db->escape_str($MailingId).'"
		');
		
        return $QueryResult;
	}
	
	public function TracSelect($MailingId,$Page)
	{
		if(!is_numeric($Page))
        {
            exit;
        }

		$QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}track 
		WHERE
		track_mailing_id = "'.$this->db->escape_str($MailingId).'"
		ORDER BY track_id DESC
		LIMIT '.$this->db->escape_str($Page).',30
		');

        return $QueryResult;
	}
    
}

?>