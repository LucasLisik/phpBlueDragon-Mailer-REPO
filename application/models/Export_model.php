<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 4-9-2015 15:38
 *
 */
 
class Export_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function ExportGroup($What, $GroupId)
    {
        if($What == 'all')
        {
            $QueryResult = $this->db->query('SELECT * FROM 
            {PREFIXDB}groups_email
            WHERE
            email_groups_id = "'.$this->db->escape_str($GroupId).'"
            ORDER BY email_id ASC
            ');
        }
        else
        {
            $QueryResult = $this->db->query('SELECT email_email FROM 
            {PREFIXDB}groups_email
            WHERE
            email_groups_id = "'.$this->db->escape_str($GroupId).'"
            ORDER BY email_id ASC
            ');
        }
        
        return $QueryResult;
    }
    
    public function ExportGroupStructure()
    {
        $QueryResult = $this->db->query('SHOW COLUMNS FROM 
        {PREFIXDB}groups_email
        ');
        
        return $QueryResult;
    }
}

?>