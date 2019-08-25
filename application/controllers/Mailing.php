<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 19-8-2015 19:28
 *
 */

class Mailing extends CI_Controller
{
	private $IsLicensed = false;
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        $this->load->model('User_model');
        $this->load->model('Mailing_model');
        //$this->load->model('Checklink_model');
        //$this->System_model->GetConfigFile();
        //$this->output->enable_profiler(true);
        
		$IsLicenseExists = $this->System_model->CheckLicenseExists();
		
		if($IsLicenseExists == 'yes')
		{
			$this->IsLicensed = true;
		}
		
        $this->lang->load('mailermain', $this->config->item('language'));
        $this->lang->load('mailermailing', $this->config->item('language'));
		
		//echo $_SERVER['SERVER_NAME'];
    }
    
    public function norights()
    {
        $SystemLang['SystemHead'] = $this->lang->line('mailing_norights1');
        
        $this->System_model->WriteLog($this->lang->line('mailing_norights2'));
        
        $this->load->view('head',$SystemLang);
        $this->load->view('norights');
        $this->load->view('foot');
    }
    
    public function ChekcLoginAttempts()
    {
        $ResultDB = $this->User_model->CheckLoginAttempt();
        
        foreach($ResultDB->result() as $row)
        {
            $HowManyLogin = $row->HowMany;   
        }
        
        if($HowManyLogin > 2)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function index()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
        }
        else
        {
            $SystemLang['SystemHead'] = $this->lang->line('mailing_12headmain');
            $SystemLang['Location'] = $this->lang->line('mailing_12subbar');        
            $this->load->view('head',$SystemLang);
            
            // Grupy
            $ResultDB = $this->Mailing_model->CountGroups();
        
            foreach($ResultDB->result() as $row)
            {
                $HowManyGroups = $row->HowMany;   
            }
            
            $SystemLang['IsGroups'] = $HowManyGroups;
            
            // Konta
            $ResultDB = $this->Mailing_model->CountSend();
        
            foreach($ResultDB->result() as $row)
            {
                $HowManySend = $row->HowMany;   
            }
            
            $SystemLang['IsSends'] = $HowManySend;
            
            if($HowManyGroups == 0)
            //if(true)
            {
                $SystemLang['ErrorIs1'] = '<a href="'.base_url('groups').'">'.$this->lang->line('mailing_12errorgroup').'</a>'; 
            }
            
            if($HowManySend == 0)
            //if(true)
            {
                $SystemLang['ErrorIs2'] = '<a href="'.base_url('sends').'">'.$this->lang->line('mailing_12errorsend').'</a>'; 
            }
            
            // Wykluczenia
            $ResultDB = $this->Mailing_model->CountExt();
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['IsExclusions'] = $row->HowMany;   
            }
            
            // Szkice
            $ResultDB = $this->Mailing_model->CountDrafts();
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['IsDrafts'] = $row->HowMany;   
            }
            
            // Wysłane
            $ResultDB = $this->Mailing_model->CountSends();
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['IsSended'] = $row->HowMany;   
            }
            
            // Ilość załączników
            $ResultDB = $this->Mailing_model->CountAttach();
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['IsAttach'] = $row->HowMany;   
            }
            
            // Ilość e-mail
            $ResultDB = $this->Mailing_model->CountEmail();
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['IsEmail'] = $row->HowMany;   
            }
            
            // Ilość logów
            $ResultDB = $this->Mailing_model->CountLogs();
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['IsLogs'] = $row->HowMany;   
            }
            
            // Ilość podpisów
            $ResultDB = $this->Mailing_model->CountSign();
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['IsSign'] = $row->HowMany;   
            }
            
            // Ilość użytkowników
            $ResultDB = $this->Mailing_model->CountUsers();
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['IsUser'] = $row->HowMany;   
            }
            
            $this->load->view('mainpage',$SystemLang);
            
            $this->load->view('foot');
        }
    }
    
    public function about()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $this->System_model->WriteLog($this->lang->line('mailing_11viewaboutlog'));
        
        $SystemLang['SystemHead'] = $this->lang->line('mailing_abouth3');
        $SystemLang['Location'] = $this->lang->line('mailing_aboutsub3');        
        $this->load->view('head',$SystemLang);
        $this->load->view('about',$SystemLang);
        $this->load->view('foot');
    }
    
    public function aboutrights()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $this->System_model->WriteLog($this->lang->line('mailing_11viewcopyrightlog'));
        
        $SystemLang['SystemHead'] = $this->lang->line('mailing_rightsh4');  
        $SystemLang['Location'] = $this->lang->line('mailing_rightssub4');  
        $this->load->view('head',$SystemLang);
        $this->load->view('aboutrights',$SystemLang);
        $this->load->view('foot');
    }
    
    public function login()
    {        
        $SystemLang['SystemHead'] = $this->lang->line('mailing_loginh5');
        
        $this->load->view('head',$SystemLang);
        
        if($this->ChekcLoginAttempts())
        {
            $this->load->view('logindenied', $SystemLang);
        }
        else
        {
            if($this->input->post('formlogin') == 'yes')
    		{
    			$this->form_validation->set_rules('user_email', $this->lang->line('mailing_email5'), 'required|valid_email');
    			$this->form_validation->set_rules('user_password', $this->lang->line('mailing_pass5'), 'required');
    
    			if($this->form_validation->run() != FALSE)
    			{
    				$TableUser = $this->User_model->CheckUser();
    
    				if($TableUser['IsAuth'] == 'yes')
    				{
    				    if($TableUser['UserActive'] == "y")
                        {
                            if($TableUser['UserBan'] == "y")
                            {
                                $SystemLang['bad_data3'] = TRUE;
                            }
                            else
                            {
                                $_SESSION['user_id'] = $TableUser['UserId'];
                                
                                $this->System_model->WriteLog($this->lang->line('mailing_loginlog5'));
                                
            					redirect();
                            }
                        }
                        else
                        {
                            $SystemLang['bad_data2'] = TRUE;
                        }
    				}
    				else
    				{
    				    $this->User_model->AddLoginAttempt();
    					$SystemLang['bad_data'] = TRUE;
    				}
    			}
    		}
        
            $this->load->view('login', $SystemLang);
        }
        
        $this->load->view('foot');
    }
    
    public function logout()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $this->System_model->WriteLog($this->lang->line('mailing_logout6'));
        session_destroy();
        redirect();
    }
    
    public function is_this_email2($str)
    {
        $ResultDB = $this->User_model->UserCheckEmail($str);
        
        foreach($ResultDB->result() as $row)
        {
            $HowManyEmail = $row->HowMany;
        }
        
        if($HowManyEmail == 0)
		{
			$this->form_validation->set_message('is_this_email2', $this->lang->line('mailing_thisaddressdosentexists7'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
    }
    
    public function is_valid_catpcha($str)
    {
        if($_SESSION['CatpchaWord'] == $str)
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('is_valid_catpcha', $this->lang->line('mailing_captchanotvalid8'));
			return FALSE;
        }
    }
    
    public function generatepassword()
    {        
        $SystemLang['SystemHead'] = $this->lang->line('mailing_recpassh9');
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formlogin') == 'yes')
		{
			$this->form_validation->set_rules('user_email', $this->lang->line('mailing_recemail9'), 'required|callback_is_this_email2');
            $this->form_validation->set_rules('user_captcha', $this->lang->line('mailing_reccaptcha9'), 'required|callback_is_valid_catpcha');
            
			if($this->form_validation->run() != FALSE)
			{
				$ResultDB = $this->User_model->UserCheckEmailSelect($this->input->post('user_email'));

				foreach($ResultDB->result() as $row)
				{
				    $UserId = $row->user_id;
                    $UserUsername = $row->user_email;
				}

                if(empty($UserUsername))
                {
                    $SystemLang['bad_data'] = true;
                }
                else
                {
                    $SystemLang['pswd_send'] = true;
                
                    $this->load->helper('string');
                    $KeyPassword = random_string('alnum', 20);
                    $KeyPassword2 = random_string('alnum', 20);
                    
                    $this->User_model->GenerateNewPassword($UserId,$KeyPassword,$KeyPassword2);
                    
                    $ResultDB = $this->User_model->SelectGenerateNewPassword($UserId,$KeyPassword,$KeyPassword2);
            
                    foreach($ResultDB->result() as $row)
        			{
        			     $UserDate = $row->password_time;
                         $UserIp = $row->password_ip;
                    }
                    
                    $ResultDB = $this->System_model->SelectEmailContent('recpassword');
            
                    foreach($ResultDB->result() as $row)
        			{
            			 $ReadyTitle = $row->email_title;
                         $ReadyContent = $row->email_content;
                    }
                    
                    $PrepareMyLink = base_url().'generate-password/'.$UserId.'/'.$KeyPassword.'/'.$KeyPassword2;
        
                    $ReadyContent = str_replace('[change_password]',$PrepareMyLink,$ReadyContent);
        
                    $ReadyTitle = str_replace('[user_date]',$UserDate,$ReadyTitle);
                    $ReadyTitle = str_replace('[user_ip]',$UserIp,$ReadyTitle);
                    
                    $ReadyContent = str_replace('[user_date]',$UserDate,$ReadyContent);
                    $ReadyContent = str_replace('[user_ip]',$UserIp,$ReadyContent);
                    
                    $ContactAddress = $this->System_model->GetConfig();
                        
                    require 'PHPMailer/PHPMailerAutoload.php';
                    $mail = new PHPMailer;
        
                    //$mail->SMTPDebug = 3;
                    $mail->SMTPDebug = 0;
                    
                    if($this->config->item('send_email_access') == 'tls')
                    {
                        $mail->SMTPSecure = "tls"; 
                    }
                    else
                    {
                        $mail->isSMTP();
                    }
                    
                    $mail->Host = $this->config->item('send_email_stmp_host');
                    $mail->SMTPAuth = true;
                    $mail->Username = $this->config->item('send_email_stmp_username');
                    $mail->Password = $this->config->item('send_email_stmp_password');
                    $mail->Port = $this->config->item('send_email_stmp_port');
                    $mail->CharSet = 'UTF-8';
                    
                    $mail->FromName = $this->config->item('send_email_user_name');
                    $mail->From = $ContactAddress['root_email'];
                    $mail->addAddress($UserUsername);
                    
                    $mail->isHTML(false);
                    
                    $mail->Subject = $ReadyTitle;
                    $mail->Body    = $ReadyContent;
    
                    if(!$mail->send())
                    {
                        $SystemLang['email_send2'] = true;
                    } 
                    else 
                    {
                        $SystemLang['email_send'] = true;
                    }
                    
                    $this->System_model->WriteLog($this->lang->line('mailing_reclog9').$UserUsername);
                }
			}
		}
        
        $this->load->helper('captcha');
        $this->load->helper('string');
        $SystemLang['RandomString'] = random_string('alnum', 6);
                        
        $_SESSION['CatpchaWord'] = $SystemLang['RandomString'];
        
        $this->load->view('generatepassword',$SystemLang);
        
        $this->load->view('foot');
    }
    
    //echo time()+30*60*60;
    public function generatenewpassword($UserId,$KeyPassword,$KeyPassword2)
    {       
        $SystemLang['SystemHead'] = $this->lang->line('mailing_2gennewh');
        
        $this->load->view('head',$SystemLang);
        
        $ResultDB = $this->User_model->CheckKeyPasswords($UserId,$KeyPassword,$KeyPassword2);

		foreach($ResultDB->result() as $row)
		{
            $HowManyIs = $row->HowMany;
          
            $SystemLang['change_password'] = false;
            
		    if($HowManyIs > 0)
            {                
                $this->load->helper('string');
                $TemporaryPassword = random_string('alnum', 10);
                        
                $this->User_model->ChangePasswordAutomat($UserId,$TemporaryPassword);
                
                $ResultDB4 = $this->System_model->SelectEmailContent('newpass');
                
                foreach($ResultDB4->result() as $row4)
    			{
        			 $ReadyTitle = $row4->email_title;
                     $ReadyContent = $row4->email_content;
                }
                
                $ResultDB5 = $this->User_model->GetUserDataById($UserId);
                
                foreach($ResultDB5->result() as $row5)
    			{
   			          $EmailOfShool = $row5->user_email;
                }
                
                $ReadyContent = str_replace('[new_password]',$TemporaryPassword,$ReadyContent);
                
                $DefUserDate = date('Y-m-d H:i:s');
                $DefUserIp = $_SERVER['REMOTE_ADDR'];
                
                $ReadyTitle = str_replace('[user_date]',$DefUserDate,$ReadyTitle);
                $ReadyTitle = str_replace('[user_ip]',$DefUserIp,$ReadyTitle);
                
                $ReadyContent = str_replace('[user_date]',$DefUserDate,$ReadyContent);
                $ReadyContent = str_replace('[user_ip]',$DefUserIp,$ReadyContent);
                
                $ContactAddress = $this->System_model->GetConfig();
                        
                require 'PHPMailer/PHPMailerAutoload.php';
                $mail = new PHPMailer;
    
                //$mail->SMTPDebug = 3;
                $mail->SMTPDebug = 0;
                
                if($this->config->item('send_email_access') == 'tls')
                {
                    $mail->SMTPSecure = "tls"; 
                }
                else
                {
                    $mail->isSMTP();
                }
                
                $mail->Host = $this->config->item('send_email_stmp_host');
                $mail->SMTPAuth = true;
                $mail->Username = $this->config->item('send_email_stmp_username');
                $mail->Password = $this->config->item('send_email_stmp_password');
                $mail->Port = $this->config->item('send_email_stmp_port');
                $mail->CharSet = 'UTF-8';
                
                $mail->FromName = $this->config->item('send_email_user_name');
                $mail->From = $ContactAddress['root_email'];
                $mail->addAddress($EmailOfShool);
                
                $mail->isHTML(false);
                
                $mail->Subject = $ReadyTitle;
                $mail->Body    = $ReadyContent;
    
                if(!$mail->send())
                {
                    $SystemLang['email_send2'] = true;
                } 
                else 
                {
                    $SystemLang['email_send'] = true;
                }
                //echo $mail->ErrorInfo;
                    
                $SystemLang['change_password'] = true;
                
                $this->System_model->WriteLog($this->lang->line('mailing_2getcomm').$EmailOfShool);
            }
        }
        
        $this->load->view('generatenewpassword',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function checkisthesame($str)
    {
        if($this->input->post('user_pswd2') == $this->input->post('user_pswd3'))
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('checkisthesame', $this->lang->line('mailing_3badrecom'));
			return FALSE;
        }
    }
    
    public function changepassword()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('zaloguj');
            exit();    
        }

        
        $SystemLang['SystemHead'] = $this->lang->line('mailing_4changepassh');
        $SystemLang['Location'] = $this->lang->line('mailing_4changesub');
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formchange') == 'yes')
		{
            $this->form_validation->set_rules('user_pswd', $this->lang->line('mailing_4old'), 'required|min_length[8]|max_length[20]');
			$this->form_validation->set_rules('user_pswd2', $this->lang->line('mailing_4new'), 'required|min_length[8]|max_length[20]');
			$this->form_validation->set_rules('user_pswd3', $this->lang->line('mailing_4new2'), 'required|min_length[8]|max_length[20]|callback_checkisthesame');

			if($this->form_validation->run() != FALSE)
			{
                $ResultDB = $this->User_model->UserGetData();
                
                $PasswordMatch = false;
                
                foreach($ResultDB->result() as $row)
                {
                    if(password_verify($this->input->post('user_pswd'), $row->user_password) == false)
                    {
                        $PasswordMatch = true;
                    }
                }
                
                if($PasswordMatch)
                {
                    $SystemLang['PswdChangedError'] = true;
                    $this->System_model->WriteLog($this->lang->line('mailing_4badpasslog'));
                }
                else
                {             
                    $this->System_model->WriteLog($this->lang->line('mailing_4successchanged'));
				    $this->User_model->UpdateUserPswd();
                    $SystemLang['PswdChanged'] = true;
                }
			}
		}
        
        $this->load->view('changepassword',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function changeprofiledata()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('zaloguj');
            exit();    
        }
        
        $SystemLang['SystemHead'] = $this->lang->line('mailing_5profileh');
        $SystemLang['Location'] = $this->lang->line('mailing_5profilesub');
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formlogin') == 'yes')
		{
            $this->form_validation->set_rules('user_street', $this->lang->line('mailing_5street'), 'required');
            $this->form_validation->set_rules('user_city', $this->lang->line('mailing_5city'), 'required');
            $this->form_validation->set_rules('user_code', $this->lang->line('mailing_5postcode'), 'required');
            $this->form_validation->set_rules('user_nip', $this->lang->line('mailing_5nip'), 'required|numeric|min_length[10]');
            $this->form_validation->set_rules('user_firm_name', $this->lang->line('mailing_5firmname'), 'required');
            $this->form_validation->set_rules('user_name_and_lastname', $this->lang->line('mailing_5nameandsurname'), 'required');

			if($this->form_validation->run() != FALSE)
			{
                $this->User_model->UpdateUserProfile();
                
                $this->System_model->WriteLog($this->lang->line('mailing_5changedatalog'));
                
                $SystemLang['ProfileUpdated'] = true;
			}

            $SystemLang['Fuser_street'] = $this->input->post('user_street');
        	$SystemLang['Fuser_city'] = $this->input->post('user_city');
        	$SystemLang['Fuser_code'] = $this->input->post('user_code');
        	$SystemLang['Fuser_nip'] = $this->input->post('user_nip');
        	$SystemLang['Fuser_firm_name'] = $this->input->post('user_firm_name');
        	$SystemLang['Fuser_name_and_lastname'] = $this->input->post('user_name_and_lastname');
        	$SystemLang['Fuser_tel'] = $this->input->post('user_tel');
        	$SystemLang['Fuser_fax'] = $this->input->post('user_fax');
		}
        else
        {
            $ResultDB = $this->User_model->UserGetData();
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['Fuser_street'] = $row->user_street;
            	$SystemLang['Fuser_city'] = $row->user_city;
            	$SystemLang['Fuser_code'] = $row->user_code;
            	$SystemLang['Fuser_nip'] = $row->user_nip;
            	$SystemLang['Fuser_firm_name'] = $row->user_firm_name;
            	$SystemLang['Fuser_name_and_lastname'] = $row->user_name_and_lastname;
            	$SystemLang['Fuser_tel'] = $row->user_tel;
            	$SystemLang['Fuser_fax'] = $row->user_fax;
            }
        }
        
        $this->load->view('changeprofiledata',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function users($Action='',$SubAction='')
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        if($Action == 'deleteuser')
        {
            if($SubAction == 1 OR $SubAction == 2)
            {
                $SystemLang['CantDeleteMainAdmin'] = true;
            }
            else
            {
                $this->User_model->DeleteUser($SubAction);
                
                $SystemLang['UserDeleted'] = true;
                
                $this->System_model->WriteLog($this->lang->line('mailing_11deleteuserlog'));
            }
        }
        
        $SystemLang['SystemHead'] = $this->lang->line('mailing_6usersh');
        $SystemLang['Location'] = $this->lang->line('mailing_6userssub');
        
        $this->load->view('head',$SystemLang);
        
        $ResultDB = $this->User_model->SelectTeam();
        
        $SystemLang['BodyText'] .= '<table class="DataReader">
        <tr>
        <th>'.$this->lang->line('mailing_6id').'</th>
        <th>'.$this->lang->line('mailing_6login').'</th>
        <th>'.$this->lang->line('mailing_6nameandsurname').'</th>
        <th style="width: 26px;"></th>
        </tr>';
                      
        foreach($ResultDB->result() as $row)
        {
            $IsUser = true;
            
            if($RowColor == 0)
        	{
        		$RowClass = 'DataReader1';
        		$RowColor = 1;
        	}
        	else
        	{
        		$RowClass = 'DataReader2';
        		$RowColor = 0;
        	}
            
            $SystemLang['BodyText'] .= '<tr>
            <td class="'.$RowClass.'">'.$row->user_id.'</td>
            <td class="'.$RowClass.'">'.$row->user_email.'</td>
            <td class="'.$RowClass.'">'.$row->user_name_and_lastname.'</td>
            <td class="'.$RowClass.'">';
            
            if($row->user_id != 1 AND $row->user_id != 2)
            {
                $SystemLang['BodyText'] .= '<a href="JavaScript:DeteleInfo(\''.base_url().'users/deleteuser/'.$row->user_id.'\',\''.$this->lang->line('mailing_6shuredelete').'\');"><img src="'.base_url('library/delete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('mailing_6delete').'"></a>';
            }
            else
            {
                $SystemLang['BodyText'] .= '<img src="'.base_url('library/nodelete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('mailing_6notdelete').'">';
            }
            
            $SystemLang['BodyText'] .= '</td>
            </tr>';
        }
        
        $SystemLang['BodyText'] .= '</table>';
        
        $this->System_model->WriteLog($this->lang->line('mailing_11broseuserslog'));
        
        if($IsUser == false)
        {
            $SystemLang['BodyText'] = $this->lang->line('mailing_6notaddedusers');
        }
        
        $this->load->view('team',$SystemLang);
        
        $this->load->view('foot');
        
    }
    
    public function is_this_email($str)
    {
        $this->load->model('User_model');
        
        $ResultDB = $this->User_model->UserCheckEmail($str);
        
        foreach($ResultDB->result() as $row)
        {
            $HowManyEmail = $row->HowMany;
        }
        
        if($HowManyEmail > 0)
		{
			$this->form_validation->set_message('is_this_email', $this->lang->line('mailing_7emailexists'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
    }
    
    public function adduser()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $SystemLang['SystemHead'] = $this->lang->line('mailing_8adduserh');
        $SystemLang['Location'] = $this->lang->line('mailing_8addusersub');
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formlogin') == 'yes')
		{
            $this->form_validation->set_rules('user_email', $this->lang->line('mailing_8email'), 'required|valid_email|callback_is_this_email');
            $this->form_validation->set_rules('user_name_and_lastname', $this->lang->line('mailing_8nameandsurname'), 'required');
            
			if($this->form_validation->run() != FALSE)
			{               
                $this->load->helper('string');
                $RandomKey = random_string('alnum', 20);
                $RandomPassword = random_string('alnum', 8);
                
                $this->User_model->RegisterUser($RandomKey,$RandomPassword);

                $SystemLang['UserRegistered'] = true;
                $SystemLang['UserName'] = $this->input->post('user_email');
                $SystemLang['UserPassword'] = $RandomPassword;
                
                $this->System_model->WriteLog($this->lang->line('mailing_11addnewuserlog'));
			}
            else
            {
                $SystemLang['Fuser_email'] = $this->input->post('user_email');
            	$SystemLang['Fuser_name_and_lastname'] = $this->input->post('user_name_and_lastname');
            }
		}
        
        $this->load->view('addnewuser',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function getimage($ImageName)
    {
        $this->load->model('Track_model');
        
		if($this->IsLicensed)
		{
			$ImageName = explode('.',$ImageName);
			
			if(count($ImageName) == 2)
			{
				//getimage/pic_'.$row->email_message_id.'_'.$row->email_email_id.'.jpg'
				$MailingIs = explode('_', $ImageName[0]);
				
				if(count($MailingIs) == 3)
				{
					$this->Track_model->AddTrack($MailingIs[1],$MailingIs[2]);
				}
				else
				{
					$this->Track_model->AddTrackNone();
				}
			}
			else
			{
				$this->Track_model->AddTrackNone();
			}
		}
        
        $CreateImage = imagecreatetruecolor(1, 1);
        $text_color = imagecolorallocate($CreateImage, 255, 255, 255);
        header('Content-Type: image/jpeg');
        imagejpeg($CreateImage);
        imagedestroy($CreateImage);
    }
    
    private function GetConfig()
    {
        $ResultDB = $this->User_model->GetSystemConfig();

		foreach($ResultDB->result() as $row)
		{
			$ConfigTable[$row->config_name] = $row->config_value;
		}
        
        return $ConfigTable;
    }
    
    private function GetConfig2()
    {
        $ResultDB = $this->User_model->GetSystemConfig2();

		foreach($ResultDB->result() as $row)
		{
			$ConfigTable[$row->config_name] = $row->config_value;
		}
        
        return $ConfigTable;
    }
    
    public function options()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $SystemLang['SystemHead'] = $this->lang->line('mailing_9optionsh');
        $SystemLang['Location'] = $this->lang->line('mailing_9optionssub');
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('addpage') == 'yes')
        {
            $this->form_validation->set_rules('title', $this->lang->line('mailing_9title'), 'required');
            $this->form_validation->set_rules('description', $this->lang->line('mailing_9brief'), 'required');
            $this->form_validation->set_rules('keywords', $this->lang->line('mailing_9keywords'), 'required');
            $this->form_validation->set_rules('root_email', $this->lang->line('mailing_9email'), 'required|valid_email');
            //$this->form_validation->set_rules('foot', 'Stopka', 'required');
            $this->form_validation->set_rules('cron_howmany', $this->lang->line('mailing_9cron'), 'required');
                        
            if($this->form_validation->run() != FALSE)
            {
                $this->User_model->UpdateConfig();
                $this->User_model->UpdateConfig2();
                
                $SystemLang['content_added'] = true;
                
                $this->System_model->WriteLog($this->lang->line('mailing_9updatedconfiglog'));
            }

            $SystemLang['Ctitle'] = $this->input->post('title');
            $SystemLang['Cdescription'] = $this->input->post('description');
            $SystemLang['Ckeywords'] = $this->input->post('keywords');
            $SystemLang['Croot_email'] = $this->input->post('root_email');
            $SystemLang['Cfoot'] = $this->input->post('foot');
            $SystemLang['Ccron_howmany'] = $this->input->post('cron_howmany');
        }
        else
        {
            $ConfigTable = $this->GetConfig();
            $ConfigTable2 = $this->GetConfig2();
            
            $SystemLang['Ctitle'] = $ConfigTable['title'];
            $SystemLang['Cdescription'] = $ConfigTable['description'];
            $SystemLang['Ckeywords'] = $ConfigTable['keywords'];
            $SystemLang['Croot_email'] = $ConfigTable['root_email'];
            $SystemLang['Cfoot'] = $ConfigTable['foot'];
            $SystemLang['Ccron_howmany'] = $ConfigTable2['cron_howmany'];
            
        }
        
        $this->load->view('options',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function logs($UserId='',$Page='')
    {        
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        if($UserId == '' OR $UserId == 'all')
        {
            $UserId = 'all';   
            $IsUserNotNull = true; 
        }
        
        $ResultDB = $this->User_model->GetUserTable();
        
        $TableUsers[0] = $this->lang->line('mailing_10notlogged');
        
        foreach($ResultDB->result() as $row)
		{
            $TableUsers[$row->user_id] = $row->user_email;
        }

        if($IsUserNotNull)
        {
            $SystemLang['HeadText'] = $this->lang->line('mailing_10logall');
            $SystemLang['SystemHead'] = $this->lang->line('mailing_10logallh');
        }
        else
        {
            $SystemLang['HeadText'] = $this->lang->line('mailing_10loguser').$TableUsers[$UserId];
            $SystemLang['SystemHead'] = $this->lang->line('mailing_10loguserh').$TableUsers[$UserId];
        }
        
        $SystemLang['Location'] = $this->lang->line('mailing_10logsub');
        
        $this->load->view('head',$SystemLang);
        
        $ResultDB = $this->User_model->LogsCount($UserId);

        foreach($ResultDB->result() as $row)
		{
            $HowManyRows = $row->HowMany;
        }
        
        //echo $HowManyRows;
        
        $this->load->library('pagination');

        $config['base_url'] = base_url('logs/'.$UserId.'/');
		$config['total_rows'] = $HowManyRows;
		$config['per_page'] = 30;
		$config['first_link'] = '&lt;&lt;';
		$config['last_link'] = '&gt;&gt;';
		$config['next_link'] = '&gt;';
		$config['prev_link'] = '&lt;';
		$config['num_links'] = 5;
        $config['full_tag_open'] = $this->lang->line('mailing_10logpage');
        $config['uri_segment'] = 3;
        $config['full_tag_open'] = '<ul class="PaginationClass PaginationClassA PaginationClassB">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="current"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_link'] = '&lt;&lt;';
        $config['last_link'] = '&gt;&gt;';

		$this->pagination->initialize($config);

        if($Page == '')
        {
            $Page = 0;
        }
        
        $ResultDB = $this->User_model->LogsSelectLimit($UserId,$Page);
        
        $SystemLang['BodyText'] .= '<table  class="DataReader">';
        $SystemLang['BodyText'] .= '<tr>
        <th>'.$this->lang->line('mailing_10id').'</th>
        <th>'.$this->lang->line('mailing_10user').'</th>
        <th>'.$this->lang->line('mailing_10brief').'</th>
        <th>'.$this->lang->line('mailing_10dateandtime').'</th>
        <th>'.$this->lang->line('mailing_10ip').'</th>
        </tr>';

        $i = 0;
        
        foreach($ResultDB->result() as $row)
		{
            if($RowColor == 0)
        	{
        		$RowClass = 'DataReader1';
        		$RowColor = 1;
        	}
        	else
        	{
        		$RowClass = 'DataReader2';
        		$RowColor = 0;
        	}
          
            $SystemLang['BodyText'] .= '<tr>
            <td class="'.$RowClass.'">'.$row->log_id.'</td>
            <td class="'.$RowClass.'">'.$TableUsers[$row->log_user_id].'</td>
            <td class="'.$RowClass.'">'.$row->log_what.'</td>
            <td class="'.$RowClass.'">'.$row->log_time.'</td>
            <td class="'.$RowClass.'">'.$row->log_ip.'</td>
            </tr>';
        }
        
        $SystemLang['BodyText'] .= '</table>';
        
        $this->System_model->WriteLog($this->lang->line('mailing_11browselogslog'));
        
		if($this->IsLicensed)
		{
			$this->load->view('logs',$SystemLang);
        }
		else
		{
			$this->load->view('licensefail');
		}
        
        $this->load->view('foot');
    }
}


?>