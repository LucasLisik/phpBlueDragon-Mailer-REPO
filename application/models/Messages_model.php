<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 21-8-2015 13:01
 *
 */
 
class Messages_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function MessagesCount($Type)
	{
		$QueryResult = $this->db->query('SELECT count(message_id) AS HowMany FROM 
		{PREFIXDB}messages
		WHERE
		message_type = "'.$this->db->escape_str($Type).'"
        AND
        message_view = "y"
		');
		
        return $QueryResult;
	}
	
	public function MessageFrom()
	{
		$QueryResult = $this->db->query('SELECT send_id,send_name,send_from FROM 
		{PREFIXDB}send
		');

    	foreach($QueryResult->result() as $row)
    	{
    		$ConfigTable[$row->send_id] = $row->send_name.' ('.$row->send_from.')';
    	}
        
        return $ConfigTable;
	}
	
	public function MessageTo()
	{
		$QueryResult = $this->db->query('SELECT groups_id,groups_name FROM 
		{PREFIXDB}groups
		');

    	foreach($QueryResult->result() as $row)
    	{
    		$ConfigTable[$row->groups_id] = $row->groups_name;
    	}
        
        return $ConfigTable;
	}
	
    public function MessageSign()
    {
        $QueryResult = $this->db->query('SELECT signatures_id,signatures_name FROM 
		{PREFIXDB}signatures
		');

    	foreach($QueryResult->result() as $row)
    	{
    		$ConfigTable[$row->signatures_id] = $row->signatures_name;
    	}
        
        return $ConfigTable;
    }
    
	public function SelectMessages($Type,$Page)
	{
		if(!is_numeric($Page))
        {
            exit;
        }

		$QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}messages 
		WHERE
		message_type = "'.$this->db->escape_str($Type).'"
        AND
        message_view = "y"
		ORDER BY message_id DESC
		LIMIT '.$this->db->escape_str($Page).',30
		');

        return $QueryResult;
	}
	
	public function SelectMessagesAll($Type)
	{
		$QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}messages 
		WHERE
		message_type = "'.$this->db->escape_str($Type).'"
		AND
		message_planned_date >= "'.$this->db->escape_str(date('Y-m-d H:i:s')).'"
        AND
        message_view = "y"
		ORDER BY message_planned_date ASC
		');

        return $QueryResult;
	}
	
	public function SelectMessageDrafts()
	{
		$QueryResult = $this->db->query('SELECT message_id,message_title FROM 
		{PREFIXDB}messages 
		WHERE
		message_type = "draft"
        AND
        message_view = "y"
		ORDER BY message_id DESC
		');

        return $QueryResult;
	}
	
    public function AddMessage()
    {
        $QueryResult = $this->db->query('INSERT INTO 
			{PREFIXDB}messages
			(
			message_title,
			message_html,
			message_text,
			message_from,
			message_to,
			message_date,
			message_user,
            message_view,
            message_sign
			)
			VALUES
			(
			"'.$this->db->escape_str($this->input->post('message_title')).'",
			"'.$this->db->escape_str($this->input->post('message_html')).'",
			"'.$this->db->escape_str($this->input->post('message_text')).'",
			"'.$this->db->escape_str($this->input->post('message_from')).'",
			"'.$this->db->escape_str($this->input->post('message_to')).'",
			"'.$this->db->escape_str(date('Y-m-d H:i:s')).'",
			"'.$this->db->escape_str($_SESSION['user_id']).'",
            "y",
            "'.$this->db->escape_str($this->input->post('message_sign')).'",
			)
			');
    }
    
    public function UpdateMessage($MessageId)
    {
        $QueryResult = $this->db->query('UPDATE 
			{PREFIXDB}messages
			SET
			message_title = "'.$this->db->escape_str($this->input->post('message_title')).'",
			message_html = "'.$this->db->escape_str($this->input->post('message_html')).'",
			message_text = "'.$this->db->escape_str($this->input->post('message_text')).'",
			message_from = "'.$this->db->escape_str($this->input->post('message_from')).'",
			message_to = "'.$this->db->escape_str($this->input->post('message_to')).'",
            message_sign = "'.$this->db->escape_str($this->input->post('message_sign')).'",
            message_view = "y"
            WHERE
            message_id = "'.$this->db->escape_str($MessageId).'"
			');
    }
    
    public function DeleteMessage($MessageId)
    {
        $QueryResult = $this->db->query('DELETE FROM 
			{PREFIXDB}messages
            WHERE
            message_id = "'.$this->db->escape_str($MessageId).'"
			');
    }
    
    public function CreateMessage($StatId)
    {
        $this->db->trans_start();

        $QueryResult = $this->db->query('INSERT INTO 
		{PREFIXDB}messages
		(
		message_type,
        message_user,
        message_date,
        message_stat
		)
		VALUES
		(
		"draft",
        "'.$this->db->escape_str($_SESSION['user_id']).'",
        "'.$this->db->escape_str(date('Y-m-d H:i:s')).'",
        "'.$this->db->escape_str($StatId).'"
		)
		');
        
        $InsertedId = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $InsertedId;
    }
    
	public function CopyMessageTime($Id,$Date)
	{
		$QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}messages 
		WHERE
		message_id = "'.$this->db->escape_str($Id).'"
		');

        foreach($QueryResult->result() as $row)
        {
			$row->message_type = 'time';
			$row->message_date = date('Y-m-d H:i:s');
			$row->message_planned_date = $Date;
			
			$QueryResult = $this->db->query('INSERT INTO 
			{PREFIXDB}messages
			(
			message_type,
			message_title,
			message_html,
			message_text,
			message_from,
			message_to,
			message_date,
			message_end_date,
			message_planned_date,
			message_user,
            message_view
			)
			VALUES
			(
			"'.$this->db->escape_str($row->message_type).'",
			"'.$this->db->escape_str($row->message_title).'",
			"'.$this->db->escape_str($row->message_html).'",
			"'.$this->db->escape_str($row->message_text).'",
			"'.$this->db->escape_str($row->message_from).'",
			"'.$this->db->escape_str($row->message_to).'",
			"'.$this->db->escape_str($row->message_date).'",
			"'.$this->db->escape_str($row->message_end_date).'",
			"'.$this->db->escape_str($row->message_planned_date).'",
			"'.$this->db->escape_str($_SESSION['user_id']).'",
            "y"
			)
			');
		}
	}
	
	public function SelectOneMessage($Id)
	{
		$QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}messages 
		WHERE
		message_id = "'.$this->db->escape_str($Id).'"
		');

        return $QueryResult;
	}
	
	public function EditMessageDate($Id)
	{
		$QueryResult = $this->db->query('UPDATE 
		{PREFIXDB}messages 
		SET
		message_planned_date = "'.$this->db->escape_str($this->input->post('message_planned_date')).'"
		WHERE
		message_id = "'.$this->db->escape_str($Id).'"
		');
	}
	
	public function SelectEmailUser()
	{
		$QueryResult = $this->db->query('SELECT * FROM 
				{PREFIXDB}user 
				WHERE
				user_id = "'.$this->db->escape_str($_SESSION['user_id']).'"
		');
		
		foreach($QueryResult->result() as $row)
		{
			$UserEmail = $row->user_email;
    		}
    		
    		return $UserEmail;
	}
	
	public function SelectReport($Id)
	{
		$QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}report 
		WHERE
		report_mailing_id = "'.$this->db->escape_str($Id).'"
		');

        return $QueryResult;
	}
	
	public function CountMessageSendNow($MessageId)
	{
		$QueryResult = $this->db->query('SELECT count(email_id) AS HowMany FROM 
		{PREFIXDB}send_email 
		WHERE
		email_message_id = "'.$this->db->escape_str($MessageId).'"
		AND
		email_send = "";
		');

        return $QueryResult;
	}
	
	public function DeleteFromSender($MessageId)
	{
		$QueryResult = $this->db->query('DELETE FROM 
		{PREFIXDB}send_email 
		WHERE
		email_message_id = "'.$this->db->escape_str($MessageId).'"
		');
	}
	
}

?>