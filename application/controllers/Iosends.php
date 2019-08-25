<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 22-8-2015 21:22
 *
 */

class Iosends extends CI_Controller
{
	private $IsLicensed = false;
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        $this->load->model('User_model');
        
        $this->load->model('Sends_model');
        
        $this->lang->load('mailermain', $this->config->item('language'));
        $this->lang->load('mailersends', $this->config->item('language'));
        
		$IsLicenseExists = $this->System_model->CheckLicenseExists();
		
		if($IsLicenseExists == 'yes')
		{
			$this->IsLicensed = true;
		}
		
        //$this->output->enable_profiler(true);
    }
    
    public function index($Action='',$SubAction='')
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }

        $SystemLang['SystemHead'] = $this->lang->line('sends_accountsh1');
        $SystemLang['Location'] = $this->lang->line('sends_accountssub1');
        
        $this->load->view('head',$SystemLang);
        
        if($Action == 'delete')
        {
            $this->Sends_model->DeleteSends($SubAction);
            
            $this->System_model->WriteLog($this->lang->line('sends_deletedaccount1'));
            
            $SystemLang['GroupDeleted'] = true;
        }
        
        if($this->input->post('formsubmit') == 'yes')
        {
            $this->form_validation->set_rules('send_name', $this->lang->line('sends_name1'), 'required');
			$this->form_validation->set_rules('send_from', $this->lang->line('sends_from1'), 'required|valid_email');
			$this->form_validation->set_rules('send_reply', $this->lang->line('sends_answer1'), 'required|valid_email');
			$this->form_validation->set_rules('send_smtp_serwer', $this->lang->line('sends_smtp1'), 'required');
			$this->form_validation->set_rules('send_port', $this->lang->line('sends_port1'), 'required|integer');

			if($this->form_validation->run() != FALSE)
			{
                $this->Sends_model->AddSends();
                
                $SystemLang['GroupAdded'] = true;

				$SystemLang['Vsend_name'] = '';
				$SystemLang['Vsend_name_account'] = '';
				$SystemLang['Vsend_organization'] = '';
				$SystemLang['Vsend_from'] = '';
				$SystemLang['Vsend_reply'] = '';
				$SystemLang['Vsend_smtp_serwer'] = '';
				$SystemLang['Vsend_auth'] = '';
				$SystemLang['Vsend_break_every'] = '';
				$SystemLang['Vsend_break_time'] = '';
				$SystemLang['Vsend_login'] = '';
				$SystemLang['Vsend_pswd'] = '';
				$SystemLang['Vsend_port'] = '';
                $SystemLang['Vsend_access'] = '';
            }
            else
            {
                $SystemLang['Vsend_name'] = $this->input->post('send_name');
				$SystemLang['Vsend_name_account'] = $this->input->post('send_name_account');
				$SystemLang['Vsend_organization'] = $this->input->post('send_organization');
				$SystemLang['Vsend_from'] = $this->input->post('send_from');
				$SystemLang['Vsend_reply'] = $this->input->post('send_reply');
				$SystemLang['Vsend_smtp_serwer'] = $this->input->post('send_smtp_serwer');
				$SystemLang['Vsend_auth'] = $this->input->post('send_auth');
				$SystemLang['Vsend_break_every'] = $this->input->post('send_break_every');
				$SystemLang['Vsend_break_time'] = $this->input->post('send_break_time');
				$SystemLang['Vsend_login'] = $this->input->post('send_login');
				$SystemLang['Vsend_pswd'] = $this->input->post('send_pswd');
				$SystemLang['Vsend_port'] = $this->input->post('send_port');
                $SystemLang['Vsend_access'] = $this->input->post('send_access');
            }
        }
        
        $ConfigTableUsers = $this->User_model->UsersList();
        
        $ResultDB = $this->Sends_model->SelectSends();
        
        $SystemLang['TableContent'] = '<table class="DataReader">
        <tr>
        <th>'.$this->lang->line('sends_id2').'</th>
        <th>'.$this->lang->line('sends_name2').'</th>
		<th>'.$this->lang->line('sends_from2').'</th>
		<th>'.$this->lang->line('sends_server2').'</th>
        <th style="width: 26px;"></th>
        <th style="width: 26px;"></th>
        <th style="width: 26px;"></th>
        </tr>';
        
		$CountAccounts = 0;
		
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
            
            if($ConfigTableUsers[$row->send_user] == "")
            {
                $User = $this->lang->line('sends_nouser2');
            }
            else
            {
                $User = $ConfigTableUsers[$row->send_user];
            }
            
            $SystemLang['TableContent'] .= '
            <tr>
            <td class="'.$RowClass.'">'.$row->send_id.'</td>
            <td class="'.$RowClass.'">'.$row->send_name.'</td>
			<td class="'.$RowClass.'">'.$row->send_from.'</td>
			<td class="'.$RowClass.'">'.$row->send_smtp_serwer.'</td>
            <td class="'.$RowClass.'"><img src="'.base_url('library/user.png').'" width="24" height="24" class="masterTooltip" title="'.$User.'"></td>
            <td class="'.$RowClass.'"><a href="'.base_url('edit-send/'.$row->send_id).'"><img src="'.base_url('library/edit.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('sends_edit2').'"></a></td>
            <td class="'.$RowClass.'"><a href="JavaScript:DeteleInfo(\''.base_url('sends/delete-send/'.$row->send_id).'\',\''.$this->lang->line('sends_qdelete2').'\')"><img src="'.base_url('library/delete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('sends_delete2').'"></a></td>
            </tr>';
			
			$CountAccounts++;
        }
        
        $SystemLang['TableContent'] .= '</table>';
        
        $this->System_model->WriteLog($this->lang->line('sends_browseaccounts2'));
        
		$SystemLang['LICENSE_CountAccounts'] = $CountAccounts;
		$SystemLang['LICENSE_IsLicenseExists'] = $this->IsLicensed;
		
        $this->load->view('sends/index',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function editsends($Id)
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $SystemLang['IdOfElement'] = $Id;

        $SystemLang['SystemHead'] = $this->lang->line('sends_edith3');
        $SystemLang['Location'] = $this->lang->line('sends_editsub3');
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formsubmit') == 'yes')
        {
            $this->form_validation->set_rules('send_name', $this->lang->line('sends_name1'), 'required');
			$this->form_validation->set_rules('send_from', $this->lang->line('sends_from1'), 'required|valid_email');
			$this->form_validation->set_rules('send_reply', $this->lang->line('sends_answer1'), 'required|valid_email');
			$this->form_validation->set_rules('send_smtp_serwer', $this->lang->line('sends_smtp1'), 'required');
			$this->form_validation->set_rules('send_port', $this->lang->line('sends_port1'), 'required|integer');
            
            /*
            $this->form_validation->set_rules('send_name', 'Nazwa', 'required');
			$this->form_validation->set_rules('send_from', 'Od', 'required|valid_email');
			$this->form_validation->set_rules('send_reply', 'Odpowiedz', 'required|valid_email');
			$this->form_validation->set_rules('send_smtp_serwer', 'SMTP serwer', 'required');
			$this->form_validation->set_rules('send_port', 'Port', 'required|integer');
            */
			
			if($this->form_validation->run() != FALSE)
			{
                $this->Sends_model->UpdateSends($Id);
                
                $SystemLang['GroupEdited'] = true;
                
                $this->System_model->WriteLog($this->lang->line('sends_editlog3'));
            }
            
            $SystemLang['Vsend_name'] = $this->input->post('send_name');
			$SystemLang['Vsend_name_account'] = $this->input->post('send_name_account');
			$SystemLang['Vsend_organization'] = $this->input->post('send_organization');
			$SystemLang['Vsend_from'] = $this->input->post('send_from');
			$SystemLang['Vsend_reply'] = $this->input->post('send_reply');
			$SystemLang['Vsend_smtp_serwer'] = $this->input->post('send_smtp_serwer');
			$SystemLang['Vsend_auth'] = $this->input->post('send_auth');
			$SystemLang['Vsend_break_every'] = $this->input->post('send_break_every');
			$SystemLang['Vsend_break_time'] = $this->input->post('send_break_time');
			$SystemLang['Vsend_login'] = $this->input->post('send_login');
			$SystemLang['Vsend_pswd'] = $this->input->post('send_pswd');
			$SystemLang['Vsend_port'] = $this->input->post('send_port');
            $SystemLang['Vsend_access'] = $this->input->post('send_access');
        }
        else
        {
            $ResultDB = $this->Sends_model->SelectOneSends($Id);

            foreach($ResultDB->result() as $row)
            {
                $SystemLang['Vsend_name'] = $row->send_name;
				$SystemLang['Vsend_name_account'] = $row->send_name_account;
				$SystemLang['Vsend_organization'] = $row->send_organization;
				$SystemLang['Vsend_from'] = $row->send_from;
				$SystemLang['Vsend_reply'] = $row->send_reply;
				$SystemLang['Vsend_smtp_serwer'] = $row->send_smtp_serwer;
				$SystemLang['Vsend_auth'] = $row->send_auth;
				$SystemLang['Vsend_break_every'] = $row->send_break_every;
				$SystemLang['Vsend_break_time'] = $row->send_break_time;
				$SystemLang['Vsend_login'] = $row->send_login;
				$SystemLang['Vsend_pswd'] = $row->send_pswd;
				$SystemLang['Vsend_port'] = $row->send_port;
                $SystemLang['Vsend_access'] = $row->send_access;
            }  
        }
        
        $this->load->view('sends/edit',$SystemLang);
        
        $this->load->view('foot');
    }
    
}

?>