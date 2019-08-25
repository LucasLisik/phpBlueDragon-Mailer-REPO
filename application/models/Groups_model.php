<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 20-8-2015 11:58
 *
 */
 
class Groups_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function SelectGroups()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}groups
        ORDER BY groups_id ASC
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
    
    public function DeleteGroup($Id)
    {
        $QueryResult = $this->db->query('DELETE FROM 
        {PREFIXDB}groups
        WHERE
        groups_id = "'.$this->db->escape_str($Id).'"
        ');
    }
    
    public function AddGroup()
    {
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}groups
        (
        groups_name,
        groups_multi,
        groups_user
        )
        VALUES
        (
        "'.$this->db->escape_str($this->input->post('groups_name')).'",
        "'.$this->db->escape_str($this->input->post('groups_multi')).'",
        "'.$this->db->escape_str($_SESSION['user_id']).'"
        )
        ');
    }
    
    public function UpdateGroup($Id)
    {
        $QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}groups
        SET
        groups_name = "'.$this->db->escape_str($this->input->post('groups_name')).'",
        groups_multi = "'.$this->db->escape_str($this->input->post('groups_multi')).'"
        WHERE
        groups_id = "'.$this->db->escape_str($Id).'" 
        ');
    }
    
    public function EmailsCount($Id)
    {
        $QueryResult = $this->db->query('SELECT count(email_id) AS HowMany FROM 
        {PREFIXDB}groups_email
        WHERE
        email_groups_id = "'.$this->db->escape_str($Id).'"
        ');
        
        return $QueryResult;
    }
    
    public function EmailsSelectLimit($Id,$Page)
    {
        if(!is_numeric($Page))
        {
            exit;
        }
        
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}groups_email 
        WHERE
        email_groups_id = "'.$this->db->escape_str($Id).'"
        ORDER BY email_id ASC
        LIMIT '.$this->db->escape_str($Page).',30
        ');
        
        return $QueryResult;
    }
    
    public function ExclusionEmailsCount()
    {
        $QueryResult = $this->db->query('SELECT count(exclusion_id) AS HowMany FROM 
        {PREFIXDB}exclusion
        ');
        
        return $QueryResult;
    }
    
    public function ExclusionEmailsSelectLimit($Page)
    {
        if(!is_numeric($Page))
        {
            exit;
        }
        
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}exclusion 
        ORDER BY exclusion_id ASC
        LIMIT '.$this->db->escape_str($Page).',30
        ');
        
        return $QueryResult;
    }
    
    public function EmailStructure()
    {
        $QueryResult = $this->db->query('SHOW COLUMNS FROM 
        {PREFIXDB}groups_email
        ');
        
        return $QueryResult;
    }
    
    public function DeleteEmail($Id)
    {
        $QueryResult = $this->db->query('DELETE FROM 
        {PREFIXDB}groups_email
        WHERE
        email_id = "'.$this->db->escape_str($Id).'"
        ');
    }
    
    public function ExclusionDeleteEmail($Id)
    {
        $QueryResult = $this->db->query('DELETE FROM 
        {PREFIXDB}exclusion
        WHERE
        exclusion_id = "'.$this->db->escape_str($Id).'"
        ');
    }
    
    public function AddExclusionEmail()
    {
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}exclusion
        (
        exclusion_email
        )
        VALUES
        (
        "'.$this->db->escape_str($this->input->post('exclusion_email')).'"
        )
        ');
    }
    
    public function SelectExclusionEmail($Email)
    {
        $QueryResult = $this->db->query('SELECT count(exclusion_id) AS HowMany FROM 
        {PREFIXDB}exclusion 
        WHERE
        exclusion_email = "'.$this->db->escape_str($Email).'"
        ');
        
        return $QueryResult;
    }
    
    public function AddNewEmail($Id)
    {
        $ResultDB = $this->EmailStructure();

        $TableFields = null;
        
        foreach($ResultDB->result() as $row)
        {
            $TableFields[] = $row->Field;
        }
        
        $TableFieldsFill[] = null;
        $TableValuesFill[] = null;
        
        $z = 0;
        
        for($i=0;$i<count($TableFields);$i++)
        {                    
            if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id' AND $TableFields[$i] != 'email_date')
            {              
                $TableFieldsFill[$z] = $TableFields[$i];
                $TableValuesFill[$z] = '"'.$this->db->escape_str($this->input->post($TableFields[$i])).'"';
                $z++;
            }
        }
        
        $TableField = implode(',', $TableFieldsFill);
        $TableValue = implode(',', $TableValuesFill);
        
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}groups_email
        (
        '.$TableField.',
        email_groups_id,
        email_date
        )
        VALUES
        (
        '.$TableValue.',
        "'.$this->db->escape_str($Id).'",
        "'.$this->db->escape_str(date('Y-m-d H:i:s')).'"
        )
        ');
    }
    
    public function SelectOneEmail($EmailId)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}groups_email 
        WHERE
        email_id = "'.$this->db->escape_str($EmailId).'"
        ');
        
        return $QueryResult;
    }
    
    public function UpdateEmail($EmailId)
    {
        $ResultDB = $this->EmailStructure();

        $TableFields = null;
        
        foreach($ResultDB->result() as $row)
        {
            $TableFields[] = $row->Field;
        }
        
        $TableFieldsFill[] = null;
        
        $z = 0;
        
        for($i=0;$i<count($TableFields);$i++)
        {                    
            if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id' AND $TableFields[$i] != 'email_date')
            {              
                $TableFieldsFill[$z] = $TableFields[$i].' = '.'"'.$this->db->escape_str($this->input->post($TableFields[$i])).'"';
                $z++;
            }
        }
        
        $TableField = implode(',', $TableFieldsFill);
        
        $QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}groups_email
        SET
        '.$TableField.'
        WHERE
        email_id = "'.$this->db->escape_str($EmailId).'"
        ');
    }
    
    public function CountEmailOnGroup($Group,$StringEmail)
    {
        $QueryResult = $this->db->query('SELECT count(email_id) AS HowMany FROM 
        {PREFIXDB}groups_email 
        WHERE
        email_groups_id = "'.$this->db->escape_str($Group).'"
        AND
        email_email = "'.$this->db->escape_str($StringEmail).'"
        ');
        
        return $QueryResult;
    }
    
    public function CountEmailOnGroup2($Group,$EmailId,$StringEmail)
    {
        $QueryResult = $this->db->query('SELECT count(email_id) AS HowMany FROM 
        {PREFIXDB}groups_email 
        WHERE
        email_groups_id = "'.$this->db->escape_str($Group).'"
        AND
        email_email = "'.$this->db->escape_str($StringEmail).'"
        AND
        email_id != "'.$this->db->escape_str($EmailId).'"
        ');
        
        return $QueryResult;
    }
    
    public function CountEmailAndUpdateGroup($GroupId)
    {
        $QueryResult = $this->db->query('SELECT count(email_id) AS HowMany FROM 
        {PREFIXDB}groups_email 
        WHERE
        email_groups_id = "'.$this->db->escape_str($GroupId).'"
        ');
        
        foreach($QueryResult->result() as $row)
        {
            $HowManyInGroup = $row->HowMany;
        }
        
        $QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}groups
        SET
        groups_many = "'.$this->db->escape_str($HowManyInGroup).'"
        WHERE
        groups_id = "'.$this->db->escape_str($GroupId).'"
        ');
    }
    
    public function GroupsList()
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
}

?>