<?php

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 19-8-2015 19:29
 *
 */


class System_model extends CI_Model 
{
	private $ThisFileLicense = false;
	
    public function __construct()
    {
        parent::__construct();
		
		if($this->CheckLicenseExists() == 'yes')
		{
			$this->ThisFileLicense = true;
		}
		
		//$ReadyUrl = $this->GetMyUrl($_SERVER['SERVER_NAME']).'nuPDrev4AYtHHqqi0wZ6MipxhNk0Zv7c';
        //echo $_SERVER['SERVER_NAME'];
		//echo password_hash($ReadyUrl, PASSWORD_DEFAULT);
    }
    
	private function GetMyUrl($Url) 
	{
		$ResultUrl = parse_url($Url);
		return $ResultUrl['scheme']."://".$ResultUrl['host'];
	}

    public function CheckLicenseExists()
    {
        if(file_exists('phpbluedragon.php'))
        {
            //yes
			//echo 'exists';
			$ContentLicenseFileTemp = @file('phpbluedragon.php');
			
            $ContentLicenseFile = $ContentLicenseFileTemp[2];
            //echo $ContentLicenseFile;
			//$ReadyUrl = $this->GetMyUrl($_SERVER['SERVER_NAME']);
            
            if(isset($_SERVER['HTTPS']))
            {
                $HostName = 'https://';
            }
            else
            {
                $HostName = 'http://';
            }
            
			$ReadyUrl = $this->GetMyUrl($HostName.$_SERVER['SERVER_NAME']);
			//echo $ReadyUrl;
			if(password_verify($ReadyUrl.'nuPDrev4AYtHHqqi0wZ6MipxhNk0Zv7c',trim($ContentLicenseFile)))
			{
				$ValToReturn = 'yes';
			}
			else
			{
				$ValToReturn = 'no';
			}
			
        }
        else
        {
            $ValToReturn = 'no';
        }
        
        //echo $ValToReturn;
        
		//$ValToReturn = 'yes';
		
        return $ValToReturn;
    }
    
    public function CheckLicenseExistsNoAlert()
    {
        if(file_exists('phpbluedragonlic.php'))
        {
			$ContentLicenseFileTemp = file('phpbluedragonlic.php');
            
            $ContentLicenseFile = $ContentLicenseFileTemp[2];
            
            if(isset($_SERVER['HTTPS']))
            {
                $HostName = 'https://';
            }
            else
            {
                $HostName = 'http://';
            }
            
			$ReadyUrl = $this->GetMyUrl($HostName.$_SERVER['SERVER_NAME']);
                       
			if(password_verify($ReadyUrl.'933Gfiu8Ney1zhzphZ9yGDyLn3BWCuI7_lic',trim($ContentLicenseFile)))
			{
				$ValToReturn = 'yes';
			}
			else
			{
				$ValToReturn = 'no';
			}
        }
        else
        {
            $ValToReturn = 'no';
        }
		
        return $ValToReturn;
    }
    
    public function WriteLog($Message)
    {
		if($this->ThisFileLicense)
		{
			$Message = htmlspecialchars($Message);
			
			$QueryResult = $this->db->query('INSERT INTO 
			{PREFIXDB}log
			(
			log_user_id,
			log_what,
			log_time,
			log_ip
			)
			VALUES
			(
			"'.$this->db->escape_str($_SESSION['user_id']).'",
			"'.$this->db->escape_str($Message).'",
			"'.$this->db->escape_str(date('Y-m-d H:i:s')).'",
			"'.$this->db->escape_str($_SERVER['REMOTE_ADDR']).'"
			)
			');
		}
    }
    
    public function GetSystemConfig()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}config
        ');
        
        return $QueryResult;
    }
    
	public function GetSystemConfigInt()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}config_int
        ');
        
        return $QueryResult;
    }
	
    public function GetConfig()
    {
        $ResultDB = $this->System_model->GetSystemConfig();
    
    	foreach($ResultDB->result() as $row)
    	{
    		$ConfigTable[$row->config_name] = $row->config_value;
    	}
        
        return $ConfigTable;
    }
	
	public function GetConfigInt()
    {
        $ResultDB = $this->System_model->GetSystemConfigInt();
    
    	foreach($ResultDB->result() as $row)
    	{
    		$ConfigTable[$row->config_name] = $row->config_value;
    	}
        
        return $ConfigTable;
    }
    
    public function SelectEmailContent($WhatEmail)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}email
        WHERE
        email_what = "'.$this->db->escape_str($WhatEmail).'"
		');
        		
		return $QueryResult;
    }
    
	public function InsertDate()
	{
		$QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}date
        SET
        date_value = "'.$this->db->escape_str(date('Y-m-d H:i:s')).'"
        WHERE
        date_id = "1" 
        ');
	}
	
    /*
    public function PageSelect($Id)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        link_pages 
        WHERE
        page_id = "'.$this->db->escape_str($Id).'"
        ');
        
        return $QueryResult;
    }
    
    public function GetUserRights()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        link_user 
        WHERE
        user_id = "'.$this->db->escape_str($_SESSION['user_id']).'"
        ');
        
        foreach($QueryResult->result() as $row)
        {
            $ConfigArray['user_package'] = $row->user_package;
            $ConfigArray['user_root'] = $row->user_root;
        }
        
        return $ConfigArray;
    }
    */
}

?>