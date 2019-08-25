<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 21-8-2015 13:00
 *
 */

class Iomessages extends CI_Controller
{
	private $IsLicensed = false;
	
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('System_model');
        $this->load->model('User_model');
        $this->load->model('Messages_model');
        
        $this->lang->load('mailermain', $this->config->item('language'));
        $this->lang->load('mailermsg', $this->config->item('language'));
        $this->lang->load('mailermailing', $this->config->item('language'));
        
		$IsLicenseExists = $this->System_model->CheckLicenseExists();
		
		if($IsLicenseExists == 'yes')
		{
			$this->IsLicensed = true;
		}
		
        //$this->output->enable_profiler(true);
    }
    
    public function index($Type,$Page="",$Action="",$SubAction="")
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }

        //echo '---'.$Action;
        
        if($Type == 'draft')
        {
            $SystemLang['SystemHead'] = $this->lang->line('msg_drafts');
            $SystemLang['Location'] = $this->lang->line('msg_draftspath');
            $SystemLang['FileName'] = 'hdraft';
        }
        elseif($Type == 'send')
        {
            $SystemLang['SystemHead'] = $this->lang->line('msg_send');
            $SystemLang['Location'] = $this->lang->line('msg_sendpath');
            $SystemLang['FileName'] = 'hsend';
        }
        
        
		
        if($Action == 'delete-message')
        {
			// Usuwanie w przypadku gdyby już była w trakcie
			$ResultDBIn = $this->Messages_model->CountMessageSendNow($SubAction);
				
			$HowMany = 0;
			
			foreach($ResultDBIn->result() as $rowIn)
			{
				$HowMany = $rowIn->HowMany;
			}
			
			$IsSendNow = null;
			
			if($HowMany != 0)
			{
				$this->Messages_model->DeleteFromSender($SubAction);
			}
			
			// Usuwanie wiadomości
            $this->Messages_model->DeleteMessage($SubAction);
            
            $SystemLang['MesageWasDeleted'] = true;
            
            $this->System_model->WriteLog($this->lang->line('msg_2deletemessagelog'));
        }
        
		if($Page == '')
        {
            $Page = 0;
        }
		
        $this->load->view('head',$SystemLang);
        
		$ResultDB = $this->Messages_model->MessagesCount($Type);

        foreach($ResultDB->result() as $row)
		{
            $HowManyRows = $row->HowMany;
        }
 
        $this->load->library('pagination');

        $config['base_url'] = base_url('messages/'.$Type.'/');
		$config['total_rows'] = $HowManyRows;
		$config['per_page'] = 30;
		$config['num_links'] = 15;
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
		
        $ConfigTableUsers = $this->User_model->UsersList();
        $ConfigTableTo = $this->Messages_model->MessageTo();
		$ConfigTableFrom = $this->Messages_model->MessageFrom();
		
        $ResultDB = $this->Messages_model->SelectMessages($Type,$Page);

        $SystemLang['Type'] = $Type;
		
        $SystemLang['TableContent'] .= '<table class="DataReader">
        <tr>
        <th>'.$this->lang->line('msg_id').'</th>
        <th>'.$this->lang->line('msg_to').'</th>
    	<th>'.$this->lang->line('msg_topic').'</th>
    	<th>'.$this->lang->line('msg_from').'</th>
    	<th>'.$this->lang->line('msg_date').'</th>';
    
    	if($Type == 'send')
    	{
    		//$SystemLang['TableContent'] .= '<th>'.$this->lang->line('msg_datesend').'</th>';
			$SystemLang['TableContent'] .= '<th>'.$this->lang->line('msg_5status').'</th>';
    	}
    
    	if($Type == 'time')
    	{
    		$SystemLang['TableContent'] .= '<th>'.$this->lang->line('msg_makesend').'</th>';
    	}
		
		if($Type == 'send')
		{
			$SystemLang['TableContent'] .= '<th></th>';
		}
		
        $SystemLang['TableContent'] .= '<th></th>
        <th></th>
        <th></th>
        </tr>';
        
        foreach($ResultDB->result() as $row)
        {
            if($ConfigTableUsers[$row->message_user] == "")
            {
                $User = $this->lang->line('msg_nouser');
            }
            else
            {
                $User = $ConfigTableUsers[$row->message_user];
            }
			
			if($ConfigTableTo[$row->message_to] == "")
            {
                $MessageTo = $this->lang->line('msg_nogroup');
            }
            else
            {
                $MessageTo = $ConfigTableTo[$row->message_to];
            }
            
			if($ConfigTableFrom[$row->message_from] == "")
            {
                $MessageFrom = $this->lang->line('msg_noreceiver');
            }
            else
            {
                $MessageFrom = $ConfigTableFrom[$row->message_from];
            }
			
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
            <td class="'.$RowClass.'">'.$MessageTo.'</td>
			<td class="'.$RowClass.'">'.$row->message_title.'</td>
			<td class="'.$RowClass.'">'.$MessageFrom.'</td>
			<td class="'.$RowClass.'">'.$row->message_date.'</td>';
			
			if($Type == 'send')
			{
				//$SystemLang['TableContent'] .= '<td class="'.$RowClass.'">'.$row->message_end_date.'</td>';
				
				$ResultDBIn = $this->Messages_model->CountMessageSendNow($row->message_id);
				
				$HowMany = 0;
				
				foreach($ResultDBIn->result() as $rowIn)
				{
					$HowMany = $rowIn->HowMany;
				}
				
				$IsSendNow = null;
				
				if($HowMany != 0)
				{
					$IsSendNow = 'yes';
				}
				
				if($IsSendNow == 'yes')
				{
					$SystemLang['TableContent'] .= '<td class="'.$RowClass.'">'.$this->lang->line('msg_5sendnow').'</td>';
				}
				else
				{
					$SystemLang['TableContent'] .= '<td class="'.$RowClass.'">'.$this->lang->line('msg_5ended').'</td>';
					
				}
			}
			
			if($Type == 'time')
			{
				$SystemLang['TableContent'] .= '<td class="'.$RowClass.'">'.$row->message_planned_date.'</td>';
			}
			
            $SystemLang['TableContent'] .= '<td class="'.$RowClass.'"><img src="'.base_url('library/user.png').'" width="24" height="24" class="masterTooltip" title="'.$User.'"></td>';
            
            if($Type == 'send')
			{
                $SystemLang['TableContent'] .= '<td class="'.$RowClass.'"><a href="'.base_url('stat-message/'.$row->message_id).'"><img src="'.base_url('library/stats.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('msg_statistics').'"></a></td>';
            }
            else
            {
                $SystemLang['TableContent'] .= '<td class="'.$RowClass.'"><a href="'.base_url('edit-message/'.$row->message_id).'"><img src="'.base_url('library/edit.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('msg_edit').'"></a></td>';
            }
              
			if($Type == 'send')
			{
				$SystemLang['TableContent'] .= '<td class="'.$RowClass.'"><a href="'.base_url('report-message/'.$row->message_id).'"><img src="'.base_url('library/report.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('msg_41raport').'"></a></td>';
			}
			
            $SystemLang['TableContent'] .= '<td class="'.$RowClass.'"><a href="JavaScript:DeteleInfo(\''.base_url('messages/'.$Type.'/'.$Page.'/delete-message/'.$row->message_id).'\',\''.$this->lang->line('msg_deletec').'\')"><img src="'.base_url('library/delete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('msg_delete').'"></a></td>
            </tr>';
        }
        
        $SystemLang['TableContent'] .= '</table>';
        
        $this->System_model->WriteLog($this->lang->line('msg_browsemessages'));
        
        $this->load->view('messages/index',$SystemLang);
        
        $this->load->view('foot');
    }
	
	public function newmessage()
	{
		if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $this->load->model('Mailing_model');
        
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
        
        if($HowManyGroups == 0)
        {
            $SystemLang['ErrorIs1'] = '<a href="'.base_url('groups').'">'.$this->lang->line('mailing_12errorgroup').'</a>';
            $WasError = true;
        }
        
        if($HowManySend == 0)
        {
            $SystemLang['ErrorIs2'] = '<a href="'.base_url('sends').'">'.$this->lang->line('mailing_12errorsend').'</a>';
            $WasError = true;
        }
        
        if($WasError)
        {
            $SystemLang['SystemHead'] = $this->lang->line('mailing2_titlepage');
      		$SystemLang['Location'] = $this->lang->line('mailing2_locationpage');
            
            $SystemLang['NewMessage'] = 'new';
            
            $this->load->view('head',$SystemLang);
    		
    		$this->load->view('messages/empty',$SystemLang);
            
            $this->load->view('foot');
        }  
        else
        {  
            $this->System_model->WriteLog($this->lang->line('msg_2addnewmessagelog'));
            
            $this->load->helper('string');
            $StatID = random_string('alnum', 50).'_'.time();
            
            $InsertId = $this->Messages_model->CreateMessage($StatID);
            
            //echo $InsertId;
            mkdir('attachment/mess'.$InsertId, 0777);
            mkdir('uploads/mess'.$InsertId, 0777);
            
            redirect('edit-message/'.$InsertId.'/new');
        }
	}
    
    public function editmessage($MessageId,$NewMessage='')
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }

        $this->load->model('Mailing_model');
        
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
        
        if($HowManyGroups == 0)
        {
            $SystemLang['ErrorIs1'] = '<a href="'.base_url('groups').'">'.$this->lang->line('mailing_12errorgroup').'</a>';
            $WasError = true;
        }
        
        if($HowManySend == 0)
        {
            $SystemLang['ErrorIs2'] = '<a href="'.base_url('sends').'">'.$this->lang->line('mailing_12errorsend').'</a>';
            $WasError = true;
        }
        
        if($WasError)
        {
            $SystemLang['SystemHead'] = $this->lang->line('mailing2_titlepage');
      		$SystemLang['Location'] = $this->lang->line('mailing2_locationpage2');
            
            $SystemLang['NewMessage'] = 'edit';
            
            $this->load->view('head',$SystemLang);
    		
    		$this->load->view('messages/empty',$SystemLang);
            
            $this->load->view('foot');
        }  
        else
        {  
            
            $SystemLang['MessageEditId'] = $MessageId;
            $SystemLang['NewMessage'] = $NewMessage;
            
            if($this->input->post('formsubmit') == 'yes')
            {
                $this->form_validation->set_rules('message_to', $this->lang->line('msg_to'), 'required');
                $this->form_validation->set_rules('message_from', $this->lang->line('msg_from'), 'required');
                $this->form_validation->set_rules('message_title', $this->lang->line('msg_topic'), 'required');
                $this->form_validation->set_rules('message_html', $this->lang->line('msg_content'), 'required');
                
    			if($this->form_validation->run() != FALSE)
    			{
                    $this->System_model->WriteLog($this->lang->line('msg_2editnewmessagelog'));
                    
                    $ResultDB = $this->Messages_model->SelectOneMessage($MessageId);
                    
                    $AreMessage = false;
                    
                    
                    foreach($ResultDB->result() as $row)
                    {
                        $AreMessage = true;   
                    }
                
                    if($AreMessage)
                    {
                        $this->Messages_model->UpdateMessage($MessageId);
                    }
                    else
                    {
                        $this->Messages_model->AddMessage();
                    }
                    
                    $SystemLang['MessageAdded'] = true;
                    
                    if($this->input->post('send_message_button_now') == 'yes')
                    {
                        redirect('sendmanager/add/'.$MessageId);    
                    }
                }
                
                $SystemLang['Vmessage_to'] = $this->input->post('message_to');
                $SystemLang['Vmessage_from'] = $this->input->post('message_from');
                $SystemLang['Vmessage_title'] = $this->input->post('message_title');
                $SystemLang['Vmessage_html'] = $this->input->post('message_html');
                $SystemLang['Vmessage_sign'] = $this->input->post('message_sign');
                $SystemLang['Vmessage_text'] = $this->input->post('message_text');
            }
            
            if($SystemLang['Vmessage_title'] == "")
            {
                $ResultDB = $this->Messages_model->SelectOneMessage($MessageId);
    
                foreach($ResultDB->result() as $row)
                {
                    $SystemLang['Vmessage_to'] = $row->message_to;
                    $SystemLang['Vmessage_from'] = $row->message_from;
                    $SystemLang['Vmessage_title'] = $row->message_title;
                    $SystemLang['Vmessage_html'] = $row->message_html;
                    $SystemLang['Vmessage_sign'] = $row->message_sign;
                    $SystemLang['Vmessage_text'] = $row->message_text;
                }
            }
            
            if($NewMessage == 'new')
            {
                $SystemLang['SystemHead'] = $this->lang->line('msg_newmessage');
        		$SystemLang['Location'] = $this->lang->line('msg_newmessagepath');
            }
            else
            {
                $SystemLang['SystemHead'] = $this->lang->line('msg_editmessage');
        		$SystemLang['Location'] = $this->lang->line('msg_editmessagerpath');
            }
            
            $this->load->view('head',$SystemLang);
    		
    		$this->load->view('messages/editmessage',$SystemLang);
            
            $this->load->view('foot');
        }
    }
	
	public function statmessage($MailingId, $Page='')
	{
		if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
		
		$SystemLang['SystemHead'] = $this->lang->line('msg_4statistics');
    	$SystemLang['Location'] = $this->lang->line('msg_4statpath');
			
		$this->load->view('head',$SystemLang);
		
		$this->load->model('Track_model');
		
		$ResultDB = $this->Track_model->GetMessageInfo($MailingId);
		
		foreach($ResultDB->result() as $row)
        {
			$GroupFrom = $row->message_to;
		}
		
		$ResultDB = $this->Track_model->GetListInfo($GroupFrom);
		
		foreach($ResultDB->result() as $row)
        {
			$TableEmails[$row->email_id] = $row->email_email;
		}
		
		if($Page == '')
        {
            $Page = 0;
        }
		       
		$ResultDB = $this->Track_model->TrackCount($MailingId);

        foreach($ResultDB->result() as $row)
		{
            $HowManyRows = $row->HowMany;
        }
 
        $this->load->library('pagination');

        $config['base_url'] = base_url('stat-message/'.$MailingId.'/');
		$config['total_rows'] = $HowManyRows;
		$config['per_page'] = 30;
		$config['num_links'] = 15;
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
		
        $ResultDB = $this->Track_model->TracSelect($MailingId,$Page);
		
        $SystemLang['TableContent'] .= '<table class="DataReader">
        <tr>
        <th>'.$this->lang->line('msg_4statid').'</th>
        <th>'.$this->lang->line('msg_4statemail').'</th>
    	<th>'.$this->lang->line('msg_4statdate').'</th>
    	<th>'.$this->lang->line('msg_4statip').'</th>
		</tr>';
        
        foreach($ResultDB->result() as $row)
        {
            if($TableEmails[$row->track_email_id] == "")
            {
                $User = $this->lang->line('msg_4noaddress');
            }
            else
            {
                $User = $TableEmails[$row->track_email_id];
            }
			
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
            <td class="'.$RowClass.'">'.$row->track_id.'</td>
            <td class="'.$RowClass.'">'.$User.'</td>
			<td class="'.$RowClass.'">'.$row->track_date.'</td>
			<td class="'.$RowClass.'">'.$row->track_ip.'</td>
            </tr>';
        }
        
        $SystemLang['TableContent'] .= '</table>';
        
        $this->System_model->WriteLog($this->lang->line('msg_browsemessages'));
		
		if($this->IsLicensed)
		{
			$this->load->view('messages/statmessage',$SystemLang);
        }
		else
		{
			$this->load->view('licensefail');
		}
        
        $this->load->view('foot');
	}
	
	public function reportmessage($Id)
	{
		if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
		
		$SystemLang['SystemHead'] = $this->lang->line('msg_41hraport');
    	$SystemLang['Location'] = $this->lang->line('msg_41location');
			
		$this->load->view('head',$SystemLang);
		
		$this->System_model->WriteLog($this->lang->line('msg_41logfromsend'));
		
		$ResultDB = $this->Messages_model->SelectReport($Id);
        
        foreach($ResultDB->result() as $row)
        {
			$SystemLang['TableContent'] = $row->report_message;
		}
		
		$this->load->view('messages/reportmessage',$SystemLang);
        
        $this->load->view('foot');
	}
    
}
        
?>