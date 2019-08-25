<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 22-8-2015 21:24
 *
 */
 
class Sends_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function AddSends()
    {
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}send
        (
        send_name,
		send_name_account,
		send_organization,
		send_from,
		send_reply,
		send_smtp_serwer,
		send_auth,
		send_break_every,
		send_break_time,
		send_login,
		send_pswd,
		send_user,
		send_port,
        send_access
        )
        VALUES
        (
		"'.$this->db->escape_str($this->input->post('send_name')).'",
		"'.$this->db->escape_str($this->input->post('send_name_account')).'",
		"'.$this->db->escape_str($this->input->post('send_organization')).'",
		"'.$this->db->escape_str($this->input->post('send_from')).'",
		"'.$this->db->escape_str($this->input->post('send_reply')).'",
		"'.$this->db->escape_str($this->input->post('send_smtp_serwer')).'",
		"'.$this->db->escape_str($this->input->post('send_auth')).'",
		"'.$this->db->escape_str($this->input->post('send_break_every')).'",
		"'.$this->db->escape_str($this->input->post('send_break_time')).'",
		"'.$this->db->escape_str($this->input->post('send_login')).'",
		"'.$this->db->escape_str($this->input->post('send_pswd')).'",
        "'.$this->db->escape_str($_SESSION['user_id']).'",
		"'.$this->db->escape_str($this->input->post('send_port')).'",
        "'.$this->db->escape_str($this->input->post('send_access')).'"
        )
        ');
    }
    
    public function SelectSends()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}send
        ORDER BY send_id ASC
        ');
        
        return $QueryResult;
    }
    
    public function DeleteSends($Id)
    {
        $QueryResult = $this->db->query('DELETE FROM 
        {PREFIXDB}send
        WHERE
        send_id = "'.$this->db->escape_str($Id).'"
        ');
    }
    
    public function UpdateSends($Id)
    {
        $QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}send
        SET
        send_name = "'.$this->db->escape_str($this->input->post('send_name')).'",
		send_name_account = "'.$this->db->escape_str($this->input->post('send_name_account')).'",
		send_organization = "'.$this->db->escape_str($this->input->post('send_organization')).'",
		send_from = "'.$this->db->escape_str($this->input->post('send_from')).'",
		send_reply = "'.$this->db->escape_str($this->input->post('send_reply')).'",
		send_smtp_serwer = "'.$this->db->escape_str($this->input->post('send_smtp_serwer')).'",
		send_auth = "'.$this->db->escape_str($this->input->post('send_auth')).'",
		send_break_every = "'.$this->db->escape_str($this->input->post('send_break_every')).'",
		send_break_time = "'.$this->db->escape_str($this->input->post('send_break_time')).'",
		send_login = "'.$this->db->escape_str($this->input->post('send_login')).'",
		send_pswd = "'.$this->db->escape_str($this->input->post('send_pswd')).'",
		send_port = "'.$this->db->escape_str($this->input->post('send_port')).'",
        send_access = "'.$this->db->escape_str($this->input->post('send_access')).'"
        WHERE
        send_id = "'.$this->db->escape_str($Id).'" 
        ');
    }
    
    public function SelectOneSends($Id)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}send
        WHERE
        send_id = "'.$this->db->escape_str($Id).'"
        ');
        
        return $QueryResult;
    }
}

?>