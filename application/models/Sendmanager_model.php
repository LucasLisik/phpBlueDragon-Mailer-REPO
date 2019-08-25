<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 7-9-2015 14:57
 *
 */
 
class Sendmanager_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
	public function CopyMessageCron($MessageId,$DateToSend)
	{
		$QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}messages
		SET
		message_type = "time",
		message_planned_date = "'.$this->db->escape_str($DateToSend).'"
        WHERE 
        message_id = "'.$this->db->escape_str($MessageId).'"
        ');
	}
	
    public function CopyMessage($MessageId,$StatID,$IsFromCron = false)
    {
        // Pobranie wiadomości
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}messages
        WHERE 
        message_id = "'.$this->db->escape_str($MessageId).'"
        ');
        
        $this->db->trans_start();
        
		if($IsFromCron)
		{
			$UserSentTo = 2;
		}
		else
		{
			$UserSentTo = $_SESSION['user_id'];
		}
		
        foreach($QueryResult->result() as $row)
        {
            $MessageSendTo = $row->message_to;
            
            // Umieszczenie wiadomości
            $QueryResult = $this->db->query('INSERT INTO
            {PREFIXDB}messages
            (
            message_view,
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
            message_sign,
            message_stat,
            message_sending
            )
            VALUES
            (
            "'.$this->db->escape_str($row->message_view).'",
            "send",
            "'.$this->db->escape_str($row->message_title).'",
            "'.$this->db->escape_str($row->message_html).'",
            "'.$this->db->escape_str($row->message_text).'",
            "'.$this->db->escape_str($row->message_from).'",
            "'.$this->db->escape_str($row->message_to).'",
            "'.$this->db->escape_str(date('Y-m-d H:i:s')).'",
            "'.$this->db->escape_str($row->message_end_date).'",
            "'.$this->db->escape_str($row->message_planned_date).'",
            "'.$this->db->escape_str($UserSentTo).'",
            "'.$this->db->escape_str($row->message_sign).'",
            "'.$this->db->escape_str($StatID).'",
            "y"
            )
            ');
        }
        
        $InsertedId = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        // Załączniki
        $this->db->query('INSERT INTO 
        {PREFIXDB}attachment (attachment_user_id,attachment_message_id,attachment_file)
        SELECT "'.$this->db->escape_str($UserSentTo).'","'.$this->db->escape_str($InsertedId).'",attachment_file
        FROM {PREFIXDB}attachment
        WHERE
        attachment_message_id = "'.$this->db->escape_str($MessageId).'"
        ');
        
        // Kopiowanie plików
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}attachment
        WHERE 
        attachment_message_id = "'.$this->db->escape_str($MessageId).'"
        ');
        
        if(!file_exists('attachment/mess'.$InsertedId))
        {
            mkdir('attachment/mess'.$InsertedId, 0777);
        }
        
        foreach($QueryResult->result() as $row)
        {
            copy('attachment/mess'.$MessageId.'/'.$row->attachment_file,'attachment/mess'.$InsertedId.'/'.$row->attachment_file);
        }
        
        // Pobranie ID adresów e-mail i umieszczenie ID adresów e-mail
        
        $this->db->query('INSERT INTO 
        {PREFIXDB}send_email (email_email_id,email_message_id)
        SELECT email_id,"'.$this->db->escape_str($InsertedId).'"
        FROM {PREFIXDB}groups_email
        WHERE
        email_groups_id = "'.$this->db->escape_str($MessageSendTo).'"
        ');
        
        return $InsertedId;
    }
    
    public function SelectMessages()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}messages
        WHERE 
        message_sending = "y"
        AND
        message_user = "'.$this->db->escape_str($_SESSION['user_id']).'"
        ORDER BY message_id ASC
        ');
        
        return $QueryResult;
    }
    
    public function SelectMessagesCount()
    {
        $QueryResult = $this->db->query('SELECT count(message_id) AS HowMany FROM 
        {PREFIXDB}messages
        WHERE 
        message_sending = "y"
        AND
        message_user = "'.$this->db->escape_str($_SESSION['user_id']).'"
        ');
        
        return $QueryResult;
    }
    
    public function SelectMessagesOne()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}messages
        WHERE 
        message_sending = "y"
        AND
        message_user = "'.$this->db->escape_str($_SESSION['user_id']).'"
        ORDER BY message_id ASC
        LIMIT 0,1
        ');
        
        return $QueryResult;
    }
	
	public function DeleteMessageCron($MessageId)
	{
		$QueryResult = $this->db->query('DELETE FROM 
        {PREFIXDB}messages
        WHERE 
		message_id = "'.$this->db->escape_str($MessageId).'"
        ');
	}
	
	public function StartSendingFromCron()
	{
		$QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}messages
        WHERE 
		message_type = "time"
		AND
		message_planned_date < "'.$this->db->escape_str(date('Y-m-d H:i:s')).'"
        ORDER BY message_id ASC
        LIMIT 0,1
        ');
       
        return $QueryResult;
	}
	
	public function SelectMessagesOneCron()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}messages
        WHERE 
        message_sending = "y"
        AND
        message_user = "2"
        ORDER BY message_id ASC
        LIMIT 0,1
        ');
        
        return $QueryResult;
    }
	
	public function UpdateStatusByCron($EmailId)
	{
		$this->db->query('UPDATE 
        {PREFIXDB}messages
		SET
		message_type = "send"
        WHERE 
		message_id = "'.$this->db->escape_str(EmailId).'"
        ');
	}
    
    public function SelecEmailMessage($MessageId)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}send_email
        WHERE
        email_message_id = "'.$this->db->escape_str($MessageId).'"
        AND
        email_send = ""
        ORDER BY email_id ASC
        LIMIT 0,1
        ');
        
        return $QueryResult;
        
    }
    
    public function SelectMessageTestSend($MessageId)
    {
    	$QueryResult = $this->db->query('SELECT * FROM 
    	{PREFIXDB}messages
    	WHERE 
    	message_id = "'.$this->db->escape_str($MessageId).'"
    	');
	            
        return $QueryResult;
    }
    
    public function DeleteMessage($Id)
    {
        // Generowanie statystyk
        $this->GetStatsToFile($Id);
        
        // Ilość wysłąnych maili
        $QueryResult = $this->db->query('SELECT count(email_id) AS HowMany FROM 
        {PREFIXDB}send_email
        WHERE 
        email_message_id = "'.$this->db->escape_str($Id).'"
        AND
        email_send != ""
        ');
        
        foreach($QueryResult->result() as $row)
        {
            $MessageSendTo = $row->HowMany;
        }
        
        // Aktualizacja wiadomości
        $QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}messages
        SET 
        message_sending = "",
        message_break = "y",
        message_howmanysend = "'.$this->db->escape_str($MessageSendTo).'"
        WHERE 
        message_id = "'.$this->db->escape_str($Id).'"
        ');
        
        // Usuwanie maili z tabeli
        $QueryResult = $this->db->query('DELETE FROM 
        {PREFIXDB}send_email
        WHERE 
        email_message_id = "'.$this->db->escape_str($Id).'"
        ');
    }
    
    public function GetStatsToFile($Id)
    {
		// Wybranie wiadomości
		$QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}messages
        WHERE 
        message_id = "'.$this->db->escape_str($Id).'"
        ');
        
        foreach($QueryResult->result() as $row)
        {
            $MessageId = $row->message_id;
            $MessageTitle = $row->message_title;
			$MessageDate = $row->message_date;
        }
		
		$ToResultString = $this->lang->line('sendmanager_7id').$MessageId.'<br />';
		$ToResultString .= $this->lang->line('sendmanager_7title').$MessageTitle.'<br />';
		$ToResultString .= $this->lang->line('sendmanager_7date').$MessageDate.'<br /><br />';
		
        // Wybranie e-mail		
		$QueryResult = $this->db->query('
        SELECT {PREFIXDB}groups_email.email_email AS EmailValue, {PREFIXDB}send_email.email_send AS EmailSendStatus
        FROM {PREFIXDB}groups_email,{PREFIXDB}send_email
        WHERE
        {PREFIXDB}groups_email.email_id = {PREFIXDB}send_email.email_email_id
		AND
		{PREFIXDB}send_email.email_message_id = "'.$this->db->escape_str($Id).'"

        ');
		
		foreach($QueryResult->result() as $row)
        {
			$ToResultString .= '<strong>'.$row->EmailValue.'</strong> - '.$row->EmailSendStatus.'<br />';
		}
		
		// Umieszczenie wiadomości w tabeli
		$this->db->query('INSERT INTO 
        {PREFIXDB}report
        (
		report_mailing_id,
		report_message
		)
		VALUES
		(
        "'.$this->db->escape_str($Id).'",
		"'.$this->db->escape_str($ToResultString).'"
		)
        ');
		
    }
    
    public function SelectSenderId($MessageId)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}messages
        WHERE 
        message_id = "'.$this->db->escape_str($MessageId).'"
        ');
        
        foreach($QueryResult->result() as $row)
        {
            $MessageSendTo = $row->message_from;
        }
        
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}send
        WHERE 
        send_id = "'.$this->db->escape_str($MessageSendTo).'"
        ');
        
        return $QueryResult;
    }
    
    public function SelectOnlySenderId($MessageId)
    {
	    $QueryResult = $this->db->query('SELECT * FROM 
	    {PREFIXDB}messages
	    WHERE 
	    message_id = "'.$this->db->escape_str($MessageId).'"
	    ');

	    return $QueryResult;
    }
    
    public function SelectsenderData($SenderId)
    {
    	$QueryResult = $this->db->query('SELECT * FROM 
    	{PREFIXDB}send
    	WHERE 
    	send_id = "'.$this->db->escape_str($SenderId).'"
    	');
	            
        return $QueryResult;
    }
    
    public function SelectEmailId($EmailId)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}groups_email
        WHERE 
        email_id = "'.$this->db->escape_str($EmailId).'"
        ');
        
        return $QueryResult;
    }
    
    public function UpdateEmailId($EmailId, $ExceptionNow)
    {
        $QueryResult = $this->db->query('UPDATE
        {PREFIXDB}send_email
        SET
        email_send = "'.$this->db->escape_str($ExceptionNow).'" 
        WHERE 
        email_id = "'.$this->db->escape_str($EmailId).'"
        ');
    }
    
    public function UpdateSendEmail($MessageId)
    {
        $QueryResult = $this->db->query('SELECT count(email_id) AS HowMany FROM
        {PREFIXDB}send_email
        WHERE
        email_send = "" 
        AND
        email_message_id = "'.$this->db->escape_str($MessageId).'"
        ');
        
        foreach($QueryResult->result() as $row)
        {
            $HowMany = $row->HowMany;
        }
        
        $AnyMsg = 'yes';
        
        if($HowMany == 0)
        {
            $QueryResult = $this->db->query('UPDATE
            {PREFIXDB}messages
            SET
            message_sending = "" 
            WHERE 
            message_id = "'.$this->db->escape_str($MessageId).'"
            ');
            
            $AnyMsg = 'no';
        }
        
        return $AnyMsg;
    }
    
    public function SelectEmailById($EmailId)
    {
        $QueryResult = $this->db->query('SELECT * FROM
        {PREFIXDB}groups_email
        WHERE
        email_id = "'.$this->db->escape_str($EmailId).'"
        ');
        
        foreach($QueryResult->result() as $row)
        {
            $MailContent = $row->email_email;
        }
        
        return $MailContent;
    }
    
    public function SelectMessageToSend($MessageId)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}messages
        WHERE 
        message_id = "'.$this->db->escape_str($MessageId).'"
        ');
        
        return $QueryResult;
    }
    
    public function SelectSignToSend($SignId)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}signatures
        WHERE 
        signatures_id = "'.$this->db->escape_str($SignId).'"
        ');
        
        return $QueryResult;
    }
    
    public function SelectAttachToSend($MessageId)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}attachment
        WHERE 
        attachment_message_id = "'.$this->db->escape_str($MessageId).'"
        ');
        
        return $QueryResult;
    }
	
	public function CheckExclusion($Email)
	{
		$QueryResult = $this->db->query('SELECT count(exclusion_id) AS HowMany FROM 
        {PREFIXDB}exclusion
        WHERE 
        exclusion_email = "'.$this->db->escape_str($Email).'"
        ');
        
		foreach($QueryResult->result() as $row)
        {
            $HowManyExclusions = $row->HowMany;
        }
		
        return $HowManyExclusions;
	}
}

?>