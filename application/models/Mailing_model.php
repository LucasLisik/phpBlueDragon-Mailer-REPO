<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 25-9-2015 19:38
 *
 */
 
class Mailing_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function CountGroups()
    {
        $QueryResult = $this->db->query('SELECT count(groups_id) AS HowMany FROM 
		{PREFIXDB}groups
		');

        return $QueryResult;
    }
    
    public function CountSend()
    {
        $QueryResult = $this->db->query('SELECT count(send_id) AS HowMany FROM 
		{PREFIXDB}send
		');

        return $QueryResult;
    }
    
    public function CountExt()
    {
        $QueryResult = $this->db->query('SELECT count(exclusion_id) AS HowMany FROM 
		{PREFIXDB}exclusion
		');

        return $QueryResult;
    }
    
    public function CountDrafts()
    {
        $QueryResult = $this->db->query('SELECT count(message_id) AS HowMany FROM 
		{PREFIXDB}messages
        WHERE
        message_type = "draft"
		');

        return $QueryResult;
    }
    
    public function CountSends()
    {
        $QueryResult = $this->db->query('SELECT count(message_id) AS HowMany FROM 
		{PREFIXDB}messages
        WHERE
        message_type = "send"
		');

        return $QueryResult;
    }
    
    public function CountAttach()
    {
        $QueryResult = $this->db->query('SELECT count(attachment_id) AS HowMany FROM 
		{PREFIXDB}attachment
		');

        return $QueryResult;
    }
    
    public function CountEmail()
    {
        $QueryResult = $this->db->query('SELECT count(email_id) AS HowMany FROM 
		{PREFIXDB}groups_email
		');

        return $QueryResult;
    }
    
    public function CountLogs()
    {
        $QueryResult = $this->db->query('SELECT count(log_id) AS HowMany FROM 
		{PREFIXDB}log
		');

        return $QueryResult;
    }
    
    public function CountSign()
    {
        $QueryResult = $this->db->query('SELECT count(signatures_id) AS HowMany FROM 
		{PREFIXDB}signatures
		');

        return $QueryResult;   
    }
    
    public function CountUsers()
    {
        $QueryResult = $this->db->query('SELECT count(user_id) AS HowMany FROM 
		{PREFIXDB}user
		');

        return $QueryResult;  
    }
}

?>