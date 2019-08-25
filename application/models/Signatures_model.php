<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 21-8-2015 11:12
 *
 */
 
class Signatures_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function AddSignature()
    {
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}signatures
        (
        signatures_name,
        signatures_html,
        signatures_text,
        signatures_user
        )
        VALUES
        (
        "'.$this->db->escape_str($this->input->post('signatures_name')).'",
        "'.$this->db->escape_str($this->input->post('signatures_html')).'",
        "'.$this->db->escape_str($this->input->post('signatures_text')).'",
        "'.$this->db->escape_str($_SESSION['user_id']).'"
        )
        ');
    }
    
    public function SelectSignatures()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}signatures
        ORDER BY signatures_id ASC
        ');
        
        return $QueryResult;
    }
    
    public function DeleteSignature($Id)
    {
        $QueryResult = $this->db->query('DELETE FROM 
        {PREFIXDB}signatures
        WHERE
        signatures_id = "'.$this->db->escape_str($Id).'"
        ');
    }
    
    public function UpdateSignatures($Id)
    {
        $QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}signatures
        SET
        signatures_name = "'.$this->db->escape_str($this->input->post('signatures_name')).'",
        signatures_html = "'.$this->db->escape_str($this->input->post('signatures_html')).'",
        signatures_text = "'.$this->db->escape_str($this->input->post('signatures_text')).'"
        WHERE
        signatures_id = "'.$this->db->escape_str($Id).'" 
        ');
    }
    
    public function SelectOneSignatures($Id)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}signatures
        WHERE
        signatures_id = "'.$this->db->escape_str($Id).'"
        ');
        
        return $QueryResult;
    }
}

?>