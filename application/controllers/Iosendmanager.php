<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 7-9-2015 14:56
 *
 */

require 'PHPMailer/PHPMailerAutoload.php';
 
class Iosendmanager extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        $this->load->model('User_model');
        
        $this->load->model('Sendmanager_model');
        $this->load->model('Messages_model');
        
        $this->lang->load('mailermain', $this->config->item('language'));
        $this->lang->load('mailersendmanager', $this->config->item('language'));
        
        //$this->output->enable_profiler(false);
    }
    
    public function addnewmessage($MessageId)
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $this->load->helper('string');
        $StatID = random_string('alnum', 50).'_'.time();
        
        // X - Pobranie wiadomości o ID
        // X - Umieszczenie jej w wysłanych
        // X - Pobranie ID adresów e-mail
        // X - Umieszczenie adresów e-mail
        
        $IdOfNewSenderMassage = $this->Sendmanager_model->CopyMessage($MessageId,$StatID);
        
        redirect('sendmanager/sender');
    }
    
    public function cronmanager()
    {
		//CopyMessage($MessageId,$StatID,$IsFromCron = false)
        //*/5 * * * * php index.php iosendmanager cronmanager 
		
		$ResultDB = $this->Sendmanager_model->StartSendingFromCron();
		
		$this->load->helper('string');
		
		foreach($ResultDB->result() as $row)
        {
			// Dodawanie wiadomości do wysyłanych
			$StatID = random_string('alnum', 50).'_'.time();
			
			$IdOfNewSenderMassage = $this->Sendmanager_model->CopyMessage($row->message_id,$StatID,true);
			
			// Usunięcia wiadomości z do wysłania (CRON)
			$this->Sendmanager_model->DeleteMessageCron($row->message_id);
		}
		
		$CronTable = $this->System_model->GetConfigInt();
		
        for($i=0;$i<$CronTable['cron_howmany'];$i++)
		{
			$this->makesend('yes');
		}
    }
    
    public function sender($Action='',$SubAction='')
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
		$_SESSION['msg_break_many'] = 1;
		
        $SystemLang['SystemHead'] = $this->lang->line('sendmanager_2consoletitle');
		
        $this->load->view('head',$SystemLang);
        
        if($Action == 'delete')
        {
            $this->Sendmanager_model->DeleteMessage($SubAction);
            
            $SystemLang['MessageWasDeleted'] = true;
        }
        
        $ConfigTableTo = $this->Messages_model->MessageTo();
		$ConfigTableFrom = $this->Messages_model->MessageFrom();
        
        $ResultDB = $this->Sendmanager_model->SelectMessagesCount();
        
        foreach($ResultDB->result() as $row)
        {
            $SystemLang['HowManyMessagesToSend'] = $row->HowMany;
        }
        
        $ResultDB = $this->Sendmanager_model->SelectMessages();
        
        $ExistOne = false;
        
        $SystemLang['TableContent'] .= '<table class="DataReader">
        <tr>
        <th>'.$this->lang->line('sendmanager_2id').'</th>
		<th>'.$this->lang->line('sendmanager_2topic').'</th>
		<th>'.$this->lang->line('sendmanager_2to').'</th>
		<th>'.$this->lang->line('sendmanager_2from').'</th>
        <th style="width: 26px;"></th>
        </tr>';
        
		//'.$this->lang->line('sendmanager_2delete').'
		
        $TableToJavaScript = null;
        
        foreach($ResultDB->result() as $row)
        {
            $ExistOne = true;
            
            $TableToJavaScript[] = $row->message_id;
            
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
            
            $SystemLang['TableContent'] .= '
            <tr>
            <td class="'.$RowClass.'">'.$row->message_id.'</td>
    		<td class="'.$RowClass.'">'.$row->message_title.'</td>
    		<td class="'.$RowClass.'">';
            
            if($ConfigTableTo[$row->message_to] == "")
            {
                $SystemLang['TableContent'] .= $this->lang->line('sendmanager_none');
            }
            else
            {
                $SystemLang['TableContent'] .= $ConfigTableTo[$row->message_to];
            }
            
            $SystemLang['TableContent'] .= '</td>
    		<td class="'.$RowClass.'">';
            
            if($ConfigTableFrom[$row->message_from] == "")
            {
                $SystemLang['TableContent'] .= $this->lang->line('sendmanager_none');
            }
            else
            {
                $SystemLang['TableContent'] .= $ConfigTableFrom[$row->message_from];
            }
            
            $SystemLang['TableContent'] .= '</td>
            <td class="'.$RowClass.'"><a href="JavaScript:DeteleInfo(\''.base_url('sendmanager/sender/delete/'.$row->message_id).'\',\''.$this->lang->line('sendmanager_2realydelete').'\')"><img src="'.base_url('library/delete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('sendmanager_5deletebutton').'"></a></td>
            </tr>';
        }
        
        if($ExistOne == false)
        {
            // Brak wiadomości    
            $SystemLang['TableContent'] .= '
            <tr>
            <td colspan="5" style="color: #000000; text-align: center; padding: 10px; font-weight: bold;">'.$this->lang->line('sendmanager_3nomsg').'</td>
            </tr>';
        }
        
        $SystemLang['TableContent'] .= '</table>';
        
        /*if($ExistOne == true)
        {
            $SystemLang['TableJS'] = '<script>';
            $SystemLang['TableJS'] .= 'var TableWithIds;';
            
            for($i=0;$i<count($TableToJavaScript);$i++)
            {
                $SystemLang['TableJS'] .= 'TableWithIds['.$i.'] = '.$TableToJavaScript[$i].';';
            }
            
            $SystemLang['TableJS'] .= '</script>';
        }*/
        
        $this->load->view('sendmanager/sender',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function GetMessageSerwer($SerwerId)
    {
        //
        // All Users
        //
        
	    $ResultDBSendServer = $this->Sendmanager_model->SelectsenderData($SerwerId);
	    
	    foreach($ResultDBSendServer->result() as $rowSender)
	    {
    		$TableSender['send_name'] = $rowSender->send_name;
    		$TableSender['send_name_account'] = $rowSender->send_name_account;
    		$TableSender['send_organization'] = $rowSender->send_organization;
    		$TableSender['send_from'] = $rowSender->send_from;
    		$TableSender['send_reply'] = $rowSender->send_reply;
    		$TableSender['send_smtp_serwer'] = $rowSender->send_smtp_serwer;
    		$TableSender['send_auth'] = $rowSender->send_auth;
    		$TableSender['send_break_every'] = $rowSender->send_break_every;
    		$TableSender['send_break_time'] = $rowSender->send_break_time;
    		$TableSender['send_login'] = $rowSender->send_login;
    		$TableSender['send_pswd'] = $rowSender->send_pswd;
    		$TableSender['send_user'] = $rowSender->send_user;
    		$TableSender['send_port'] = $rowSender->send_port;
            $TableSender['send_access'] = $rowSender->send_access;
	    }
	    
	    return $TableSender;
    }
    
    public function GetMessageContentTest($EmailMessageId)
    {
        //
        // All Users
        //
        
        // Treść i tytuł maila 
        $ResultDBMessage = $this->Sendmanager_model->SelectMessageToSend($EmailMessageId);
        
        foreach($ResultDBMessage->result() as $rowMessage)
        {
            $MessageTable['MsgTitle'] = $rowMessage->message_title;
            $MessageTable['MsgHtml'] = $rowMessage->message_html;
            $MessageTable['MsgText'] = $rowMessage->message_text;
            $MessageTable['MsgSign'] = $rowMessage->message_sing;
        }
        
        // Wybieranie podpisu
       if($MessageTable['MsgSign'] != 0)
       {
           $ResultDBMessageSign = $this->Sendmanager_model->SelectSignToSend($MsgSign);
        
           foreach($ResultDBMessageSign->result() as $rowSign)
           {
               $SignHtml = $rowSign->signatures_html;
               $SignText = $rowSign->signatures_text;
           }
       }
       
       $MessageTable['MsgHtml'] = str_replace('</body>', $SignHtml.'</body>', $MessageTable['MsgHtml']);
       
       if($MessageTable['MsgText'] != "")
       {
            $MessageTable['MsgText'] = str_replace('</body>', $SignText.'</body>', $MessageTable['MsgText']);
       }
       
       return $MessageTable;
    }
    
    public function GetMessageContent($EmailMessageId,$EmailToSendId,$TableEmail)
    {
        //
        // All Users
        //
        
        // Treść i tytuł maila 
        $ResultDBMessage = $this->Sendmanager_model->SelectMessageToSend($EmailMessageId);
        
        foreach($ResultDBMessage->result() as $rowMessage)
        {
            $MessageTable['MsgTitle'] = $rowMessage->message_title;
            $MessageTable['MsgHtml'] = $rowMessage->message_html;
            $MessageTable['MsgText'] = $rowMessage->message_text;
            $MessageTable['MsgSign'] = $rowMessage->message_sing;
        }
        
        // Podmiana w tytule i w treści znaczników
        
        $MessageTable['MsgTitle'] = str_replace('[email_email]', $TableEmail['EmailContentemail_email'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_title]', $TableEmail['EmailContentemail_title'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_name]', $TableEmail['EmailContentemail_name'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_lastname]', $TableEmail['EmailContentemail_lastname'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_initial]', $TableEmail['EmailContentemail_initial'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_address1]', $TableEmail['EmailContentemail_address1'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_address2]', $TableEmail['EmailContentemail_address2'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_city]', $TableEmail['EmailContentemail_city'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_state]', $TableEmail['EmailContentemail_state'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_postcode]', $TableEmail['EmailContentemail_postcode'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_country]', $TableEmail['EmailContentemail_country'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_firm]', $TableEmail['EmailContentemail_firm'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_jobtitle]', $TableEmail['EmailContentemail_jobtitle'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_network]', $TableEmail['EmailContentemail_network'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_phone]', $TableEmail['EmailContentemail_phone'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_fax]', $TableEmail['EmailContentemail_fax'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_field1]', $TableEmail['EmailContentemail_field1'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_field2]', $TableEmail['EmailContentemail_field2'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_field3]', $TableEmail['EmailContentemail_field3'], $MessageTable['MsgTitle']);
        $MessageTable['MsgTitle'] = str_replace('[email_field4]', $TableEmail['EmailContentemail_field4'], $MessageTable['MsgTitle']);
        
        $MessageTable['MsgHtml'] = str_replace('[email_email]', $TableEmail['EmailContentemail_email'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_title]', $TableEmail['EmailContentemail_title'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_name]', $TableEmail['EmailContentemail_name'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_lastname]', $TableEmail['EmailContentemail_lastname'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_initial]', $TableEmail['EmailContentemail_initial'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_address1]', $TableEmail['EmailContentemail_address1'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_address2]', $TableEmail['EmailContentemail_address2'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_city]', $TableEmail['EmailContentemail_city'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_state]', $TableEmail['EmailContentemail_state'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_postcode]', $TableEmail['EmailContentemail_postcode'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_country]', $TableEmail['EmailContentemail_country'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_firm]', $TableEmail['EmailContentemail_firm'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_jobtitle]', $TableEmail['EmailContentemail_jobtitle'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_network]', $TableEmail['EmailContentemail_network'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_phone]', $TableEmail['EmailContentemail_phone'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_fax]', $TableEmail['EmailContentemail_fax'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_field1]', $TableEmail['EmailContentemail_field1'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_field2]', $TableEmail['EmailContentemail_field2'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_field3]', $TableEmail['EmailContentemail_field3'], $MessageTable['MsgHtml']);
        $MessageTable['MsgHtml'] = str_replace('[email_field4]', $TableEmail['EmailContentemail_field4'], $MessageTable['MsgHtml']);
        
        $MessageTable['MsgText'] = str_replace('[email_email]', $TableEmail['EmailContentemail_email'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_title]', $TableEmail['EmailContentemail_title'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_name]', $TableEmail['EmailContentemail_name'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_lastname]', $TableEmail['EmailContentemail_lastname'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_initial]', $TableEmail['EmailContentemail_initial'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_address1]', $TableEmail['EmailContentemail_address1'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_address2]', $TableEmail['EmailContentemail_address2'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_city]', $TableEmail['EmailContentemail_city'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_state]', $TableEmail['EmailContentemail_state'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_postcode]', $TableEmail['EmailContentemail_postcode'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_country]', $TableEmail['EmailContentemail_country'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_firm]', $TableEmail['EmailContentemail_firm'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_jobtitle]', $TableEmail['EmailContentemail_jobtitle'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_network]', $TableEmail['EmailContentemail_network'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_phone]', $TableEmail['EmailContentemail_phone'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_fax]', $TableEmail['EmailContentemail_fax'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_field1]', $TableEmail['EmailContentemail_field1'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_field2]', $TableEmail['EmailContentemail_field2'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_field3]', $TableEmail['EmailContentemail_field3'], $MessageTable['MsgText']);
        $MessageTable['MsgText'] = str_replace('[email_field4]', $TableEmail['EmailContentemail_field4'], $MessageTable['MsgText']);
           
       // Wybieranie podpisu
       if($MessageTable['MsgSign'] != 0)
       {
           $ResultDBMessageSign = $this->Sendmanager_model->SelectSignToSend($MsgSign);
        
           foreach($ResultDBMessageSign->result() as $rowSign)
           {
               $SignHtml = $rowSign->signatures_html;
               $SignText = $rowSign->signatures_text;
           }
       }
    
       $MessageTable['MsgHtml'] = str_replace('</body>', $SignHtml.'</body>', $MessageTable['MsgHtml']);
       
       if($MessageTable['MsgText'] != "")
       {
            $MessageTable['MsgText'] = str_replace('</body>', $SignText.'</body>', $MessageTable['MsgText']);
       }
    
       // Dodawanie znaczników śledzenia
       $SpyChar = '<img src="'.base_url('getimage/pic_'.$EmailMessageId.'_'.$EmailToSendId.'.jpg').'" width="1" height="1" />';
                           
       //$MessageTable['MsgHtml'] = str_replace('</body>', $SpyChar.'</body>', $MessageTable['MsgHtml']);
       $MessageTable['MsgHtml'] .= $SpyChar;
       
       return $MessageTable;
    }
    
    public function GetEmailFromGroupContent($EmailId)
    {
        //
        // All Users
        //
        
	    $ResultDBSendEmail = $this->Sendmanager_model->SelectEmailId($EmailId);
	                    
	    foreach($ResultDBSendEmail->result() as $rowEmail)
	    {
    		//echo '<strong>'.$rowEmail->email_email.'</strong> - message was send';
            //$TableEmail['EmailToSend_id'] = $rowEmail->email_id;
    		$TableEmail['EmailToSend'] = $rowEmail->email_email;
    		$TableEmail['EmailContentemail_email'] = $rowEmail->email_email;
    		$TableEmail['EmailContentemail_title'] = $rowEmail->email_title;
    		$TableEmail['EmailContentemail_name'] = $rowEmail->email_name;
    		$TableEmail['EmailContentemail_lastname'] = $rowEmail->email_lastname;
    		$TableEmail['EmailContentemail_initial'] = $rowEmail->email_initial;
    		$TableEmail['EmailContentemail_address1'] = $rowEmail->email_address1;
    		$TableEmail['EmailContentemail_address2'] = $rowEmail->email_address2;
    		$TableEmail['EmailContentemail_city'] = $rowEmail->email_city;
    		$TableEmail['EmailContentemail_state'] = $rowEmail->email_state;
    		$TableEmail['EmailContentemail_postcode'] = $rowEmail->email_postcode;
    		$TableEmail['EmailContentemail_country'] = $rowEmail->email_country;
    		$TableEmail['EmailContentemail_firm'] = $rowEmail->email_firm;
    		$TableEmail['EmailContentemail_jobtitle'] = $rowEmail->email_jobtitle;
    		$TableEmail['EmailContentemail_network'] = $rowEmail->email_network;
    		$TableEmail['EmailContentemail_phone'] = $rowEmail->email_phone;
    		$TableEmail['EmailContentemail_fax'] = $rowEmail->email_fax;
    		$TableEmail['EmailContentemail_field1'] = $rowEmail->email_field1;
    		$TableEmail['EmailContentemail_field2'] = $rowEmail->email_field2;
    		$TableEmail['EmailContentemail_field3'] = $rowEmail->email_field3;
    		$TableEmail['EmailContentemail_field4'] = $rowEmail->email_field4;
    	}
    	
    	return $TableEmail;
    }
    
    public function makesendtest($MessageId)
    {        
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        if($this->input->get('emailsendtest') == "")
        {
            echo $this->lang->line('sendmanager_errnullfield');
        }
        else
        {
            $this->load->helper('email');
            
            if(valid_email($this->input->get('emailsendtest')))
            {
                //echo $MessageId;
                // Wybranie serwera wysyłąnych wiadomości
                $ResultDB = $this->Sendmanager_model->SelectMessageTestSend($MessageId);
	                
                foreach($ResultDB->result() as $row)
                {
                    //echo $row->message_from;
                    $TableSender = $this->GetMessageSerwer($row->message_from);
                }
                
                // Wybieranie i przygotowywanie wiadomości
                $EmailContent = $this->GetMessageContentTest($MessageId);
                
                // Wysyłanie wiadomości
                $ResultSendMsg = $this->SendEmailReady($TableSender, $EmailContent, $MessageId, $this->input->get('emailsendtest'));
                
                if($ResultSendMsg == "")
                {
                    echo '<strong>'.$this->input->get('emailsendtest').'</strong>'.$this->lang->line('sendmanager_msqsendtrue');
                }
                else
                {
                    echo $ResultSendMsg;
                }
            }
            else
            {
                echo $this->lang->line('sendmanager_errtestnotvalid');
            }
        }
    }
    
    public function DebugThis($Vaiable)
    {
        echo '<pre>';
        print_r($Vaiable);
        echo '</pre>';
        exit();
    }
    
    public function makesend($Cron = '')
    {		
        // Sprawdź czy wiadomość istnieje
		if($Cron == 'yes')
		{
			$ResultDB = $this->Sendmanager_model->SelectMessagesOneCron();
		}
		else
		{
			$ResultDB = $this->Sendmanager_model->SelectMessagesOne();
        }
		
        $ExistOne = false;
        
        foreach($ResultDB->result() as $row)
        {
            $IdOfEmail = $row->message_id;
        }
        
		$BackComunicat = null;
		
        if($IdOfEmail != "")
        {
			// Sprawdzenie czy w mailingu pozostały jeszcze puste maile
			$ResultAreAny = $this->Sendmanager_model->UpdateSendEmail($IdOfEmail);
            
            if($ResultAreAny == 'no')
            {
				//$BackComunicat .= 'nomail|||';
				
				// Stworzenie logów z wysłanej wiadomości
				$this->Sendmanager_model->GetStatsToFile($IdOfEmail);
				
				// Zakończenie wysyłania przez Cron
				if($Cron == 'yes')
				{
					$this->Sendmanager_model->UpdateStatusByCron($IdOfEmail);
				}
				
				// Komunikat - koniec wysyłania
				echo '[send_end]';
            }
			else
			{
				//
				// Doliczenie do przerwy
				//
				$_SESSION['msg_break_many'] = $_SESSION['msg_break_many'] + 1;
				
				// Przerwa w sysyłaniu
				//$MakeBreakCount = null;
				//$BreakTime = 0;
								
				$ResultDB = $this->Sendmanager_model->SelecEmailMessage($IdOfEmail);
				
				$IsMessageZero = true;
				
				foreach($ResultDB->result() as $row)
				{                
					// Wybieranie jednego maila na który idzie wysyłka (Zwraaca adres e-mail)
					$EmailToSend = $this->Sendmanager_model->SelectEmailById($row->email_email_id);
					
					// Sprawdzanie wykluczeń
					$IsExclusion = $this->Sendmanager_model->CheckExclusion($EmailToSend);
					
					if($IsExclusion == 0)
					{
						// Wybranie serwera wysyłanych wiadomości
						$ResultDB = $this->Sendmanager_model->SelectMessageTestSend($IdOfEmail);
							
						foreach($ResultDB->result() as $rowMessage)
						{
							//echo $row->message_from;
							$TableSender = $this->GetMessageSerwer($rowMessage->message_from);
							
							// Wywołaj przerwę w wysyłąniu
							if($_SESSION['msg_break_many'] == $TableSender['send_break_every'])
							{
								//$MakeBreakCount = true;
								$BreakTime = $TableSender['send_break_time'];
					
								$_SESSION['msg_break_many'] = 1;
								
								echo '[break_time]|'.$BreakTime;
								
								$_SESSION['msg_break_many'] = 1;
								
								$BreakForEach = true;
								
								
								//$BackComunicat .= 'break|||'.$BreakTime.'|||';
								//$BackComunicat .= 'fdsfdasfdasf|||';
							}
							
							if($BreakForEach)
							{
								break;
							}
						}
						
						if($BreakForEach)
						{
							break;
						}
						
						// Wybieranie użytkownika z grupy
						$TableEmail = $this->GetEmailFromGroupContent($row->email_email_id);
						
						// Wybieranie i przygotowywanie wiadomości
						$EmailContent = $this->GetMessageContent($IdOfEmail,$row->email_email_id,$TableEmail);
						
						// Wysyłanie wiadomości
						$ResultSendMsg = $this->SendEmailReady($TableSender, $EmailContent, $IdOfEmail, $EmailToSend);
						
						if($ResultSendMsg == "")
						{
							$ExceptionNow = $this->lang->line('sendmanager_3msgstatus');
							echo '<strong>'.$this->lang->line('sendmanager_3msgtoispost').' '.$EmailToSend.'</strong>'.$this->lang->line('sendmanager_msqsendtrue');
							//$BackComunicat .= 'send|||'.$EmailToSend.'|||';
						}
						else
						{
							$ExceptionNow = $ResultSendMsg;
							echo $ExceptionNow;
							//$BackComunicat .= 'error|||'.$ExceptionNow.'|||'.$EmailToSend.'|||';
						}
					}
					else
					{
						$ExceptionNow = $this->lang->line('sendmanager_4addressexcluted');
						echo '<strong>'.$this->lang->line('sendmanager_4exclusion1').' '.$EmailToSend.'</strong>'.$this->lang->line('sendmanager_4exclusion2');
						//$BackComunicat .= 'exception|||'.$EmailToSend.'|||';
					}
					
					// Odznaczenie w tabli komunikatu o wysyłce maila
					$this->Sendmanager_model->UpdateEmailId($row->email_id, $ExceptionNow);
					
					$IsMessageZero = false;
				}
			}
        }
    }
	
    public function SendEmailReady($TableSender,$EmailContent,$MessageId,$EmailSendTo)
    {
        //
        // All Users
        //
        
        // Konfiguracja biblioteki e-mail
		//if(!method_exists($mail, 'Send'))
		if($mail == null)
		{
			//require 'PHPMailer/PHPMailerAutoload.php';
			$mail = new PHPMailer();
		}
		
        $ExceptionNow = $this->lang->line('sendmanager_3msgstatus');
        
        $mail->SMTPDebug = 3;
        $mail->SMTPDebug = 0;
        
        if($TableSender['send_access'] == 'tls')
        {
            $mail->SMTPSecure = "tls"; 
        }
        else
        {
            $mail->isSMTP();
        }
        
        $mail->Host = $TableSender['send_smtp_serwer'];
        
        if($TableSender['send_auth'] == 'y')
        {
            $mail->SMTPAuth = true;
            $mail->Username = $TableSender['send_login'];
            $mail->Password = $TableSender['send_pswd'];
        }
        
        $mail->Port = $TableSender['send_port'];
        $mail->CharSet = 'UTF-8';
        
        $mail->FromName = $TableSender['send_name'];
        $mail->From = $TableSender['send_from'];
        
        if($TableSender['send_reply'] != "")
        {
            $mail->AddReplyTo($TableSender['send_reply']);
        }
        
        $mail->addAddress($EmailSendTo);
        
        $mail->isHTML(true);
        
        // Dodanie załączników
        $ResultDBAttach = $this->Sendmanager_model->SelectAttachToSend($MessageId);
        
        foreach($ResultDBAttach->result() as $rowAttach)
        {
            if(file_exists('attachment/mess'.$MessageId.'/'.$rowAttach->attachment_file))
            {
                $mail->AddAttachment('attachment/mess'.$MessageId.'/'.$rowAttach->attachment_file);
            }
        }
        
        //
        // Licencja
        //
        if($this->System_model->CheckLicenseExistsNoAlert() == 'yes')
        {
            $DontShow = true;
        }
        
        if(!$DontShow)
        {
            if(strpos($EmailContent['MsgHtml'], '</body>') !== false) 
            {
                $EmailContent['MsgHtml'] = str_replace('</body>', $this->lang->line('licensewarning').'</body>', $EmailContent['MsgHtml']);
            }
            else
            {
                $EmailContent['MsgHtml'] = $EmailContent['MsgHtml'].$this->lang->line('licensewarning');
            }
            
            if($EmailContent['MsgText'] != "")
            {
                $EmailContent['MsgText'] = $EmailContent['MsgText'].$this->lang->line('licensewarning');
            }
        }

        // Temat i treść HTML + TXT po przetworzeniu
        $mail->Subject = $EmailContent['MsgTitle'];
        $mail->Body    = $EmailContent['MsgHtml'];
        
        if($EmailContent['MsgText'] != "")
        {
            $mail->AltBody = $EmailContent['MsgText'];
        }
        
        $MessageCom = null;
        
        // wysłanie maila
        if(!$mail->Send())
        {
            $MessageCom = $mail->ErrorInfo;
        }
        
        return $MessageCom;
    }
    
    public function SendMyMail()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
    }
}

?>