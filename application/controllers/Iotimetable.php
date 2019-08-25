<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 24-8-2015 12:54
 *
 */

class Iotimetable extends CI_Controller
{
	private $IsLicensed = false;
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        $this->load->model('User_model');
        
		$this->load->model('Messages_model');
        $this->load->model('Timetable_model');
        
        $this->lang->load('mailermain', $this->config->item('language'));
        $this->lang->load('mailertimetable', $this->config->item('language'));
        
		$IsLicenseExists = $this->System_model->CheckLicenseExists();
		
		if($IsLicenseExists == 'yes')
		{
			$this->IsLicensed = true;
		}
		
        //$this->output->enable_profiler(true);
    }
	
	public function checkmydate($date)
	{
		
		$format = 'Y-m-d H:i';
		$dateTime = DateTime::createFromFormat($format, $date);

		if ($dateTime instanceof DateTime && $dateTime->format('Y-m-d H:i') == $date) 
		{
			$IsTrue = true;
		}
		else
		{
			$IsTrue = false;
		}
		
		if($IsTrue)
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('checkmydate', $this->lang->line('timetable_formaterror1'));
			return FALSE;
        }
	}
	
	public function checkmyactualdate($date)
	{
		if (new DateTime() < new DateTime($date)) 
		{
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('checkmyactualdate', $this->lang->line('timetable_dateerror2'));
			return FALSE;
        }
	}
	
    public function index($Action='',$SubAction='')
    {
		if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }

        $SystemLang['SystemHead'] = $this->lang->line('timetable_timeh1');
        $SystemLang['Location'] = $this->lang->line('timetable_timesub1');
        
		if($Action == 'delete')
        {
            $this->Timetable_model->DeleteTimetable($SubAction);
            
            $this->System_model->WriteLog($this->lang->line('timetable_deleted1'));
            
            $SystemLang['GroupDeleted'] = true;
        }
		
		if($this->IsLicensed)
		{
			if($this->input->post('formsubmit') == 'yes')
			{
				$this->form_validation->set_rules('message_id', $this->lang->line('timetable_topic3'), 'required');
				$this->form_validation->set_rules('message_planned_date', $this->lang->line('timetable_date3'), 'required|callback_checkmydate|callback_checkmyactualdate');

				if($this->form_validation->run() != FALSE)
				{
					//$this->System_model->InsertDate();
					
					$this->load->helper('string');
					$StatID = random_string('alnum', 50).'_'.time();
					
					// X - Pobranie wiadomości o ID
					// X - Umieszczenie jej w wysłanych
					// X - Pobranie ID adresów e-mail
					// X - Umieszczenie adresów e-mail
					
					$this->load->model('Sendmanager_model');
					
					$IdOfCopyiedMessage = $this->Sendmanager_model->CopyMessage($this->input->post('message_id'),$StatID);
					$this->Sendmanager_model->CopyMessageCron($IdOfCopyiedMessage,$this->input->post('message_planned_date'));
					
					//$this->Messages_model->CopyMessageTime($this->input->post('message_id'),$this->input->post('message_planned_date'));
					
					$SystemLang['GroupAdded'] = true;

					$SystemLang['Vmessage_id'] = '';
					$SystemLang['Vmessage_planned_date'] = '';
				}
				else
				{
					$SystemLang['Vmessage_id'] = $this->input->post('message_id');
					$SystemLang['Vmessage_planned_date'] = $this->input->post('message_planned_date');
				}
			}
		}
		
        $this->load->view('head',$SystemLang);
		
        $ConfigTableUsers = $this->User_model->UsersList();
        $ConfigTableTo = $this->Messages_model->MessageTo();
		$ConfigTableFrom = $this->Messages_model->MessageFrom();
		
        $ResultDB = $this->Messages_model->SelectMessagesAll('time');
        
        $SystemLang['TableContent'] = '<table class="DataReader">
        <tr>
        <th>'.$this->lang->line('timetable_id3').'</th>
        <th>'.$this->lang->line('timetable_to3').'</th>
		<th>'.$this->lang->line('timetable_topic3').'</th>
		<th>'.$this->lang->line('timetable_from3').'</th>
		<th>'.$this->lang->line('timetable_startsendind3').'</th>
		<th style="width: 26px;"></th>
        <th style="width: 26px;"></th>
        <th style="width: 26px;"></th>
        </tr>';
        
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
            
            if($ConfigTableUsers[$row->message_user] == "")
            {
                $User = $this->lang->line('timetable_nouser3');
            }
            else
            {
                $User = $ConfigTableUsers[$row->message_user];
            }
			
			if($ConfigTableTo[$row->message_to] == "")
            {
                $MessageTo = $this->lang->line('timetable_nogroup3');
            }
            else
            {
                $MessageTo = $ConfigTableTo[$row->message_to];
            }
            
			if($ConfigTableFrom[$row->message_from] == "")
            {
                $MessageFrom = $this->lang->line('timetable_norec3');
            }
            else
            {
                $MessageFrom = $ConfigTableFrom[$row->message_from];
            }
			
            $SystemLang['TableContent'] .= '
            <tr>
            <td class="'.$RowClass.'">'.$row->message_id.'</td>
            <td class="'.$RowClass.'">'.$MessageTo.'</td>
			<td class="'.$RowClass.'">'.$row->message_title.'</td>
			<td class="'.$RowClass.'">'.$MessageFrom.'</td>
			<td class="'.$RowClass.'">'.$row->message_planned_date.'</td>
			<td class="'.$RowClass.'"><img src="'.base_url('library/user.png').'" width="24" height="24" class="masterTooltip" title="'.$User.'"></td>
            <td class="'.$RowClass.'"><a href="'.base_url('edit-timetable/'.$row->message_id).'"><img src="'.base_url('library/edit.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('timetable_edit4').'"></a></td>
            <td class="'.$RowClass.'"><a href="JavaScript:DeteleInfo(\''.base_url('timetable/delete-timetable/'.$row->message_id).'\',\''.$this->lang->line('timetable_qdelete4').'\')"><img src="'.base_url('library/delete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('timetable_delete4').'"></a></td>
            </tr>';
        }
        
        $SystemLang['TableContent'] .= '</table>';
        
        $this->System_model->WriteLog($this->lang->line('timetable_browsetermlog4'));
        
		if($this->IsLicensed)
		{
			$this->load->view('timetable/index',$SystemLang);
        }
		else
		{
			$this->load->view('licensefail');
		}
        
        $this->load->view('foot');
	}
	
	public function edittimetable($Id)
	{
		if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $SystemLang['IdOfElement'] = $Id;

        $SystemLang['SystemHead'] = $this->lang->line('timetable_edith5');
        $SystemLang['Location'] = $this->lang->line('timetable_editsub5');
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formsubmit') == 'yes')
        {
			$this->form_validation->set_rules('message_planned_date', $this->lang->line('timetable_date5'), 'required|callback_checkmydate|callback_checkmyactualdate');

			if($this->form_validation->run() != FALSE)
			{
                $this->Messages_model->EditMessageDate($Id);
                
                $SystemLang['GroupEdited'] = true;
				
                $this->System_model->WriteLog($this->lang->line('timetable_2editdatelog'));
            }
			
			$SystemLang['Vmessage_planned_date'] = $this->input->post('message_planned_date');
        }
        else
        {
            $ResultDB = $this->Messages_model->SelectOneMessage($Id);

            foreach($ResultDB->result() as $row)
            {
                $SystemLang['Vmessage_planned_date'] = substr($row->message_planned_date,0,16);
            }  
        }
        
        $this->load->view('timetable/edit',$SystemLang);
        
        $this->load->view('foot');
	}
}

?>