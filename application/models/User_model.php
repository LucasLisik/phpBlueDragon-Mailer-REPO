<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 19-8-2015 19:54
 *
 */
 
class User_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function CheckLoginAttempt()
    {
        $QueryResult = $this->db->query('SELECT count(login_id) AS HowMany FROM 
		{PREFIXDB}login
        WHERE
       	login_ip = "'.$this->db->escape_str($_SERVER['REMOTE_ADDR']).'"
        AND
        login_time > "'.$this->db->escape_str(time()-5*60).'"
		');
        		
		return $QueryResult;
    }
    
    public function CheckUser()
    {
        $Email = htmlspecialchars($this->input->post('user_email'));
        
        $QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}user
        WHERE
        user_email = "'.$this->db->escape_str($Email).'"
		');
        
        foreach($QueryResult->result() as $row)
		{
            $PasswordToVerify = $row->user_password;
            $UserId = $row->user_id;
            $UserActive = $row->user_active;
            $UserBan = $row->user_ban;
        }
        
        if(password_verify($this->input->post('user_password'), $PasswordToVerify) == false)
        {
            $TableUser['IsAuth'] = 'no';
        }
        else
        {
            //$this->CheckProjects($UserId);
        
            $TableUser['IsAuth'] = 'yes';
            $TableUser['UserId'] = $UserId;
            $TableUser['UserActive'] = $UserActive;
            $TableUser['UserBan'] = $UserBan;
        }
        
        return $TableUser;
    }
    
    public function AddLoginAttempt()
    {
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}login
        (
        login_ip,
        login_time
        )
        VALUES
        (
        "'.$this->db->escape_str($_SERVER['REMOTE_ADDR']).'",
        "'.$this->db->escape_str(time()).'"
        )
        ');
    }
    
    public function UserCheckEmailSelect($Email)
    {
        $Email = htmlspecialchars($Email);
        
        $QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}user
        WHERE
        user_email = "'.$this->db->escape_str($Email).'"
		');
        		
		return $QueryResult;
    }
    
    public function GenerateNewPassword($UserId,$KeyPassword,$KeyPassword2)
    {
        $UserId = htmlspecialchars($UserId);
        $KeyPassword = htmlspecialchars($KeyPassword);
        $KeyPassword2 = htmlspecialchars($KeyPassword2);
        
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}password
        (
        password_user_id,
        password_hash1,
        password_hash2,
        password_time,
        password_ip
        )
        VALUES
        (
        "'.$this->db->escape_str($UserId).'",
        "'.$this->db->escape_str($KeyPassword).'",
        "'.$this->db->escape_str($KeyPassword2).'",
        "'.$this->db->escape_str(date("Y-m-d H:i:s",time()+2*60*60)).'",
        "'.$this->db->escape_str($_SERVER['REMOTE_ADDR']).'"
        )
        ');
    }
    
    public function SelectGenerateNewPassword($UserId,$KeyPassword,$KeyPassword2)
    {
        $UserId = htmlspecialchars($UserId);
        $KeyPassword = htmlspecialchars($KeyPassword);
        $KeyPassword2 = htmlspecialchars($KeyPassword2);
        
        $QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}password
        WHERE
        password_user_id = "'.$this->db->escape_str($UserId).'"
        AND
        password_hash1 = "'.$this->db->escape_str($KeyPassword).'"
        AND
        password_hash2 = "'.$this->db->escape_str($KeyPassword2).'"
		');
        		
		return $QueryResult;
    }
    
    public function UserCheckEmail($Email)
    {
        $Email = htmlspecialchars($Email);
        
        $QueryResult = $this->db->query('SELECT count(user_email) AS HowMany FROM 
		{PREFIXDB}user
        WHERE
        user_email = "'.$this->db->escape_str($Email).'"
		');
        		
		return $QueryResult;
    }
    
    public function CheckKeyPasswords($UserId,$KeyPassword,$KeyPassword2)
    {
        $UserId = htmlspecialchars($UserId);
        $KeyPassword = htmlspecialchars($KeyPassword);
        $KeyPassword2 = htmlspecialchars($KeyPassword2);
        
        $QueryResult = $this->db->query('SELECT count(password_id) As HowMany FROM 
        {PREFIXDB}password 
        WHERE
        password_user_id = "'.$this->db->escape_str($UserId).'"
        AND
        password_hash1 = "'.$this->db->escape_str($KeyPassword).'"
        AND
        password_hash2 = "'.$this->db->escape_str($KeyPassword2).'"
        AND
        password_time > "'.$this->db->escape_str(date('Y-m-d H:i:s')).'"
        AND
        password_used = ""
        ');
        
        $this->db->query('UPDATE 
        {PREFIXDB}password 
        SET
        password_used = "y"
        WHERE
        password_user_id = "'.$this->db->escape_str($UserId).'"
        AND
        password_hash1 = "'.$this->db->escape_str($KeyPassword).'"
        AND
        password_hash2 = "'.$this->db->escape_str($KeyPassword2).'"
        AND
        password_time > "'.$this->db->escape_str(date('Y-m-d H:i:s')).'"
        ');
        
        return $QueryResult;
    }
    
    public function ChangePasswordAutomat($UserId,$TemporaryPassword)
    {      
        $UserId = htmlspecialchars($UserId);
        $TemporaryPassword = htmlspecialchars($TemporaryPassword);
        
        $SaltPassword = password_hash($TemporaryPassword, PASSWORD_DEFAULT);
        
        $QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}user
        SET
        user_password = "'.$this->db->escape_str($SaltPassword).'"
        WHERE
        user_id = "'.$this->db->escape_str($UserId).'"
        ');
        
    }
    
    public function GetUserDataById($Id)
    {
        $Id = htmlspecialchars($Id);
        
        $QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}user
        WHERE
        user_id = "'.$this->db->escape_str($Id).'"
		');
        		
		return $QueryResult;
    }
    
    public function UserGetData()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}user
        WHERE
        user_id = "'.$this->db->escape_str($_SESSION['user_id']).'"
		');
        		
		return $QueryResult;
    }
    
    public function UpdateUserPswd()
    {
        $SaltPassword = password_hash($this->input->post('user_pswd2'), PASSWORD_DEFAULT);
        $SaltPassword = htmlspecialchars($SaltPassword);
        
        $QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}user
        SET
        user_password = "'.$this->db->escape_str($SaltPassword).'"
        WHERE
        user_id = "'.$this->db->escape_str($_SESSION['user_id']).'"
        ');
    }
    
    public function UpdateUserProfile()
    {
        $UserStreet = htmlspecialchars($this->input->post('user_street'));
        $UserCity = htmlspecialchars($this->input->post('user_city'));
        $UserCode = htmlspecialchars($this->input->post('user_code'));
        $UserNip = htmlspecialchars($this->input->post('user_nip'));
        $UserFirmName = htmlspecialchars($this->input->post('user_firm_name'));
        $UserNameAndLastName = htmlspecialchars($this->input->post('user_name_and_lastname'));
        $UserTel = htmlspecialchars($this->input->post('user_tel'));
        $UserFax = htmlspecialchars($this->input->post('user_fax'));
        
        $QueryResult = $this->db->query('UPDATE 
        {PREFIXDB}user
        SET
        user_street = "'.$this->db->escape_str($UserStreet).'",
        user_city = "'.$this->db->escape_str($UserCity).'",
        user_code = "'.$this->db->escape_str($UserCode).'",
        user_nip = "'.$this->db->escape_str($UserNip).'",
        user_firm_name = "'.$this->db->escape_str($UserFirmName).'",
        user_name_and_lastname = "'.$this->db->escape_str($UserNameAndLastName).'",
        user_tel = "'.$this->db->escape_str($UserTel).'",
        user_fax = "'.$this->db->escape_str($UserFax).'"
        WHERE
        user_id = "'.$this->db->escape_str($_SESSION['user_id']).'"
        ');
    }
    
    public function UsersList()
    {
        $QueryResult = $this->db->query('SELECT user_id,user_email FROM 
		{PREFIXDB}user
		');

    	foreach($QueryResult->result() as $row)
    	{
    		$ConfigTable[$row->user_id] = $row->user_email;
    	}
        
        return $ConfigTable;
    }
    
    public function DeleteUser($Id)
    {
        $QueryResult = $this->db->query('DELETE FROM 
        {PREFIXDB}user
        WHERE
        user_id = "'.$this->db->escape_str($Id).'"
        ');
    }
    
    public function SelectTeam()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        {PREFIXDB}user
        ORDER BY user_id ASC
        ');
        
        return $QueryResult;
    }
    
    public function RegisterUser($Key,$Password)
    {
        $SaltPassword = password_hash($Password, PASSWORD_DEFAULT);
        
        $UserEmail = htmlspecialchars($this->input->post('user_email'));
        $UserNameAndLastName = htmlspecialchars($this->input->post('user_name_and_lastname'));
        $Key = htmlspecialchars($Key);
        
        $QueryResult = $this->db->query('INSERT INTO 
        {PREFIXDB}user
        (
        user_email,
        user_password,
        user_name_and_lastname,
        user_date_register,
        user_ip,
        user_key,
        user_active
        )
        VALUES
        (
        "'.$this->db->escape_str($UserEmail).'",
        "'.$this->db->escape_str($SaltPassword).'",
        "'.$this->db->escape_str($UserNameAndLastName).'",
        "'.$this->db->escape_str(date("Y-m-d H:i:s")).'",
        "'.$this->db->escape_str($this->input->ip_address()).'",
        "'.$this->db->escape_str($Key).'",
        "y"
        )
        ');
    }
    
    public function UpdateConfig()
    {
        $QueryResult = $this->db->query('UPDATE {PREFIXDB}config SET config_value = "'.$this->db->escape_str($this->input->post('title')).'" WHERE config_name = "title"');
        $QueryResult = $this->db->query('UPDATE {PREFIXDB}config SET config_value = "'.$this->db->escape_str($this->input->post('keywords')).'" WHERE config_name = "keywords"');
        $QueryResult = $this->db->query('UPDATE {PREFIXDB}config SET config_value = "'.$this->db->escape_str($this->input->post('description')).'" WHERE config_name = "description"');
        $QueryResult = $this->db->query('UPDATE {PREFIXDB}config SET config_value = "'.$this->db->escape_str($this->input->post('root_email')).'" WHERE config_name = "root_email"');
        //$QueryResult = $this->db->query('UPDATE mailing_config SET config_value = "'.$this->db->escape_str($this->input->post('foot')).'" WHERE config_name = "foot"');
    }
    
    public function UpdateConfig2()
    {
        $QueryResult = $this->db->query('UPDATE {PREFIXDB}config_int SET config_value = "'.$this->db->escape_str($this->input->post('cron_howmany')).'" WHERE config_name = "cron_howmany"');
    }
    
    public function GetSystemConfig()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}config
		');
        		
		return $QueryResult;
    }
    
    public function GetSystemConfig2()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}config_int
		');
        		
		return $QueryResult;
    }
    
    public function GetUserTable()
    {
        $QueryResult = $this->db->query('SELECT user_id,user_email FROM 
		{PREFIXDB}user
		');
        		
		return $QueryResult;
    }
    
    public function LogsCount($UserId)
    {
        if($UserId == 'all')
        {
            $QueryResult = $this->db->query('SELECT count(log_id) AS HowMany FROM 
            {PREFIXDB}log
            ');
        }
        else
        {
            $QueryResult = $this->db->query('SELECT count(log_id) AS HowMany FROM 
            {PREFIXDB}log
            WHERE
            log_user_id = "'.$this->db->escape_str($UserId).'"
            ');
        }
        
        return $QueryResult;
    }
    
    public function LogsSelectLimit($UserId,$Page)
    {
        if(!is_numeric($Page))
        {
            exit;
        }
 
        if($UserId == 'all')
        {
            $QueryResult = $this->db->query('SELECT * FROM 
            {PREFIXDB}log 
            ORDER BY log_id DESC
            LIMIT '.$this->db->escape_str($Page).',30
            ');
        }
        else
        {
            $QueryResult = $this->db->query('SELECT * FROM 
            {PREFIXDB}log 
            WHERE
            log_user_id = "'.$this->db->escape_str($UserId).'"
            ORDER BY log_id DESC
            LIMIT '.$this->db->escape_str($Page).',30
            ');
        }

        return $QueryResult;
    }
    
    /*
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function CheckProjects($UserId)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
        link_user 
        WHERE
        user_package_date < "'.$this->db->escape_str(date("Y-m-d H:i:s")).'"
        AND
        user_id = "'.$this->db->escape_str($UserId).'"
        ');
        
        if($QueryResult != null)
        {
            // Ilość projektów dla darmowego użytkownika
            $QueryResult3 = $this->db->query('SELECT * FROM 
            link_package 
            WHERE
            package_id = "1"
            ');
            
            foreach($QueryResult3->result() as $row3)
            {
                $HowManyProjects = $row->package_projects;
            }
                
            foreach($QueryResult->result() as $row)
            {
                // Zmiana pakietu
                $this->db->query('UPDATE 
                link_user 
                SET
                user_package_date = "0000-00-00 00:00:00",
                user_package = "0"
                WHERE
                user_id = "'.$this->db->escape_str($UserId).'"
                ');
        
                // Wybranie projektów użytkownika
                $QueryResult2 = $this->db->query('SELECT count(project_id) AS HowMany FROM 
                link_project 
                WHERE
                project_user_id = "'.$this->db->escape_str($UserId).'"
                ');
                
                $AllowedProjects = false;
                
                foreach($QueryResult2->result() as $row2)
                {
                    if($row2->HowMany > $HowManyProjects)
                    {
                        $AllowedProjects = true;
                    }
                }
                
                // Usunięcie nadmiaru projektów
                if($AllowedProjects)
                {
                    $QueryResult2 = $this->db->query('SELECT * FROM 
                    link_project 
                    WHERE
                    project_user_id = "'.$this->db->escape_str($UserId).'"
                    ORDER BY project_id
                    ');
                    
                    $TableWithsIDs = null;
                    
                    $i = 0;
                    
                    foreach($QueryResult2->result() as $row2)
                    {
                        if($i != 0)
                        {
                            $TableWithsIDs[] = $row2->project_id;
                        }
                        $i++;
                    }
                    
                    if(count($TableWithsIDs) == 1)
                    {
                        $QueryResult = $this->db->query('DELETE FROM 
                        link_project
                        WHERE
                        project_id = "'.$this->db->escape_str($TableWithsIDs[0]).'"
                        AND
                        project_user_id = "'.$this->db->escape_str($UserId).'"
                        ');
                    }
                    elseif(count($TableWithsIDs) > 1)
                    {
                        $TableFormated = null;
                        
                        for($i=0;$i<count($TableWithsIDs);$i++)
                        {
                            if(is_numeric($TableWithsIDs[$i]))
                            {
                                $TableFormated[] = 'project_id = "'.$this->db->escape_str($TableWithsIDs[$i]).'"';
                            }
                        }
                        
                        $ImplodedIDs = implode(' OR ', $TableFormated);
                        
                        $QueryResult = $this->db->query('DELETE FROM 
                        link_project
                        WHERE
                        '.$ImplodedIDs.'
                        AND
                        project_user_id = "'.$this->db->escape_str($UserId).'"
                        ');
                    }
                }
                
            }
        }
    }
    
    public function CheckUserKey($UserId,$UserKey)
    {
        $UserId = htmlspecialchars($UserId);
        $UserKey = htmlspecialchars($UserKey);
        
        $QueryResult = $this->db->query('SELECT count(user_id) AS HowMany FROM 
		link_user
        WHERE
        user_id = "'.$this->db->escape_str($UserId).'"
        AND
        user_key  = "'.$this->db->escape_str($UserKey).'"
		');
        		
		return $QueryResult;
    }
    
    public function UpdateStatus($UserId,$UserKey)
    {
        $UserId = htmlspecialchars($UserId);
        $UserKey = htmlspecialchars($UserKey);
        
        $QueryResult = $this->db->query('UPDATE 
        link_user
        SET
        user_active = "y"
        WHERE
        user_id = "'.$this->db->escape_str($UserId).'"
        AND
        user_key  = "'.$this->db->escape_str($UserKey).'"
        ');
    }
    
    public function GetUserData($Email)
    {
        $Email = htmlspecialchars($Email);
        
        $QueryResult = $this->db->query('SELECT * FROM 
		link_user
        WHERE
        user_email = "'.$this->db->escape_str($Email).'"
		');
        		
		return $QueryResult;
    }
    
    
    
    public function GetUserDataByDate($Id)
    {
        $Id = htmlspecialchars($Id);
        
        $QueryResult = $this->db->query('SELECT * FROM 
		link_user
        WHERE
        user_id = "'.$this->db->escape_str($Id).'"
        AND
        user_package_date > "'.$this->db->escape_str(date('Y-m-d H:i:s')).'"
		');
        		
		return $QueryResult;
    }
    
    public function SelectPackage($PackageId)
    {
        $PackageId = htmlspecialchars($PackageId);
        
        $QueryResult = $this->db->query('SELECT * FROM 
		link_package
        WHERE
        package_id = "'.$this->db->escape_str($PackageId).'"
		');
        		
		return $QueryResult;
    }
    
    public function SelectAllPackages()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
		link_package
        ORDER BY package_id ASC
		');
        		
		return $QueryResult;
    }
    
    public function SelectAllEmails()
    {
        $QueryResult = $this->db->query('SELECT * FROM 
		link_contact
        ORDER BY contact_id ASC
		');
        		
		return $QueryResult;
    }
    
    
    
    
    
    
    
    public function RegisterUser($RandomKey)
    {
        $SaltPassword = password_hash($this->input->post('user_password'), PASSWORD_DEFAULT);
        $SaltPassword = htmlspecialchars($SaltPassword);
        
        $UserEmail = htmlspecialchars($this->input->post('user_email'));
        $UserStreet = htmlspecialchars($this->input->post('user_street'));
        $UserCity = htmlspecialchars($this->input->post('user_city'));
        $UserCode = htmlspecialchars($this->input->post('user_code'));
        $UserNip = htmlspecialchars($this->input->post('user_nip'));
        $UserFirmName = htmlspecialchars($this->input->post('user_firm_name'));
        $UserNameAndLastName = htmlspecialchars($this->input->post('user_name_and_lastname'));
        $UserTel = htmlspecialchars($this->input->post('user_tel'));
        $UserFax = htmlspecialchars($this->input->post('user_fax'));
        $RandomKey = htmlspecialchars($RandomKey);
        
        $QueryResult = $this->db->query('INSERT INTO 
        link_user
        (
        user_email,
        user_password,
        user_street,
        user_city,
        user_code,
        user_nip,
        user_firm_name,
        user_name_and_lastname,
        user_tel,
        user_fax,
        user_date_register,
        user_ip,
        user_key
        )
        VALUES
        (
        "'.$this->db->escape_str($UserEmail).'",
        "'.$this->db->escape_str($SaltPassword).'",
        "'.$this->db->escape_str($UserStreet).'",
        "'.$this->db->escape_str($UserCity).'",
        "'.$this->db->escape_str($UserCode).'",
        "'.$this->db->escape_str($UserNip).'",
        "'.$this->db->escape_str($UserFirmName).'",
        "'.$this->db->escape_str($UserNameAndLastName).'",
        "'.$this->db->escape_str($UserTel).'",
        "'.$this->db->escape_str($UserFax).'",
        "'.$this->db->escape_str(date("Y-m-d H:i:s")).'",
        "'.$this->db->escape_str($this->input->ip_address()).'",
        "'.$this->db->escape_str($RandomKey).'"
        )
        ');
    }
    */
}
 
?>