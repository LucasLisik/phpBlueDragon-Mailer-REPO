<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 21-8-2015 11:12
 *
 */

class Iosignatures extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        $this->load->model('User_model');
        
        $this->load->model('Signatures_model');
        
        $this->lang->load('mailermain', $this->config->item('language'));
        $this->lang->load('mailersignatures', $this->config->item('language'));
        
        //$this->output->enable_profiler(true);
    }
    
    public function index($Action='',$SubAction='')
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }

        $SystemLang['SystemHead'] = $this->lang->line('sign_signh1');
        $SystemLang['Location'] = $this->lang->line('sign_signsub1');
        
        $this->load->view('head',$SystemLang);
        
        if($Action == 'delete')
        {
            $this->Signatures_model->DeleteSignature($SubAction);
            
            $this->System_model->WriteLog($this->lang->line('sign_deleted1'));
            
            $SystemLang['GroupDeleted'] = true;
        }
        
        if($this->input->post('formsubmit') == 'yes')
        {
            $this->form_validation->set_rules('signatures_name', $this->lang->line('sign_namesign1'), 'required');

			if($this->form_validation->run() != FALSE)
			{
                $this->Signatures_model->AddSignature();
                
                $SystemLang['GroupAdded'] = true;
                
                $SystemLang['Vsignatures_name'] = '';
                $SystemLang['Vsignatures_html'] = '';
                $SystemLang['Vsignatures_text'] = '';
            }
            else
            {
                $SystemLang['Vsignatures_name'] = $this->input->post('signatures_name');
                $SystemLang['Vsignatures_html'] = $this->input->post('signatures_html');
                $SystemLang['Vsignatures_text'] = $this->input->post('signatures_text');
            }
        }
        
        $ConfigTableUsers = $this->User_model->UsersList();
        
        $ResultDB = $this->Signatures_model->SelectSignatures();
        
        $SystemLang['TableContent'] = '<table class="DataReader">
        <tr>
        <th>'.$this->lang->line('sign_id1').'</th>
        <th>'.$this->lang->line('sign_name1').'</th>
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
            
            if($ConfigTableUsers[$row->signatures_user] == "")
            {
                $User = $this->lang->line('sign_nouser1');
            }
            else
            {
                $User = $ConfigTableUsers[$row->signatures_user];
            }
            
            $SystemLang['TableContent'] .= '
            <tr>
            <td class="'.$RowClass.'">'.$row->signatures_id.'</td>
            <td class="'.$RowClass.'">'.$row->signatures_name.'</td>
            <td class="'.$RowClass.'"><img src="'.base_url('library/user.png').'" width="24" height="24" class="masterTooltip" title="'.$User.'"></td>
            <td class="'.$RowClass.'"><a href="'.base_url('edit-signature/'.$row->signatures_id).'"><img src="'.base_url('library/edit.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('sign_edit1').'"></a></td>
            <td class="'.$RowClass.'"><a href="JavaScript:DeteleInfo(\''.base_url('signatures/delete-signature/'.$row->signatures_id).'\',\''.$this->lang->line('sign_qdelete1').'\')"><img src="'.base_url('library/delete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('sign_delete1').'"></a></td>
            </tr>';
        }
        
        $SystemLang['TableContent'] .= '</table>';
        
        $this->System_model->WriteLog($this->lang->line('sign_browsesignlog1'));
        
        $this->load->view('signatures/index',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function editsignatures($Id)
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $SystemLang['IdOfElement'] = $Id;

        $SystemLang['SystemHead'] = $this->lang->line('sign_edith2');
        $SystemLang['Location'] = $this->lang->line('sign_editsub2');
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formsubmit') == 'yes')
        {
            $this->form_validation->set_rules('signatures_name', $this->lang->line('sign_signname2'), 'required');

			if($this->form_validation->run() != FALSE)
			{
                $this->Signatures_model->UpdateSignatures($Id);
                
                $SystemLang['GroupEdited'] = true;
                
                $this->System_model->WriteLog($this->lang->line('sign_editedsign2'));
            }
            
            $SystemLang['Vsignatures_name'] = $this->input->post('signatures_name');
            $SystemLang['Vsignatures_html'] = $this->input->post('signatures_html');
            $SystemLang['Vsignatures_text'] = $this->input->post('signatures_text');
        }
        else
        {
            $ResultDB = $this->Signatures_model->SelectOneSignatures($Id);

            foreach($ResultDB->result() as $row)
            {
                $SystemLang['Vsignatures_name'] = $row->signatures_name;
                $SystemLang['Vsignatures_html'] = $row->signatures_html;
                $SystemLang['Vsignatures_text'] = $row->signatures_text;
            }  
        }
        
        $this->load->view('signatures/edit',$SystemLang);
        
        $this->load->view('foot');
    }
}

?>