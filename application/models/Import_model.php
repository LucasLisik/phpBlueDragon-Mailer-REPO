<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 4-9-2015 22:39
 *
 */
 
class Import_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function ImportGroupStructure()
    {
        $QueryResult = $this->db->query('SHOW COLUMNS FROM 
        {PREFIXDB}groups_email
        ');
        
        return $QueryResult;
    }
    
    public function SelectOneGroup($Id)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}groups
        WHERE
        groups_id = "'.$this->db->escape_str($Id).'"
        ');
        
        return $QueryResult;
    }
    
    public function SelectEmail($GroupId,$Email)
    {
        $QueryResult = $this->db->query('SELECT count(email_id) AS HowMany FROM 
        {PREFIXDB}groups_email
        WHERE
        email_groups_id = "'.$this->db->escape_str($GroupId).'"
        AND
        email_email = "'.$this->db->escape_str($Email).'"
        ');
        
        return $QueryResult;
    }
    
    public function ImportOneEmail($GroupId,$TableValueFields)
    {
        /*echo '<pre>';
        print_r($TableValueFields);
        echo '</pre>';*/
        
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}groups_email
        (
    	email_groups_id,
        email_email,
        email_date,
        email_title,	
        email_name,
        email_lastname,
        email_initial,
        email_address1,
        email_address2,
        email_city,
        email_state,
        email_postcode,
        email_country,
        email_firm,
        email_jobtitle,
        email_network,
        email_phone,
        email_fax,
        email_field1,
        email_field2,
        email_field3,
        email_field4
        )
        VALUES
        (
        "'.$this->db->escape_str($GroupId).'",
        "'.$this->db->escape_str($TableValueFields['email_email']).'",
        "'.$this->db->escape_str($TableValueFields['email_date']).'",
        "'.$this->db->escape_str($TableValueFields['email_title']).'",
        "'.$this->db->escape_str($TableValueFields['email_name']).'",
        "'.$this->db->escape_str($TableValueFields['email_lastname']).'",
        "'.$this->db->escape_str($TableValueFields['email_initial']).'",
        "'.$this->db->escape_str($TableValueFields['email_address1']).'",
        "'.$this->db->escape_str($TableValueFields['email_address2']).'",
        "'.$this->db->escape_str($TableValueFields['email_city']).'",
        "'.$this->db->escape_str($TableValueFields['email_state']).'",
        "'.$this->db->escape_str($TableValueFields['email_postcode']).'",
        "'.$this->db->escape_str($TableValueFields['email_country']).'",
        "'.$this->db->escape_str($TableValueFields['email_firm']).'",
        "'.$this->db->escape_str($TableValueFields['email_jobtitle']).'",
        "'.$this->db->escape_str($TableValueFields['email_network']).'",
        "'.$this->db->escape_str($TableValueFields['email_phone']).'",
        "'.$this->db->escape_str($TableValueFields['email_fax']).'",
        "'.$this->db->escape_str($TableValueFields['email_field1']).'",
        "'.$this->db->escape_str($TableValueFields['email_field2']).'",
        "'.$this->db->escape_str($TableValueFields['email_field3']).'",
        "'.$this->db->escape_str($TableValueFields['email_field4']).'"
        )
        ');
    }
}

?>