<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 20-8-2015 11:57
 *
 */

class Iogroups extends CI_Controller
{
	private $IsLicensed = false;
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        $this->load->model('User_model');
        
        $this->load->model('Groups_model');
        
        $this->lang->load('mailermain', $this->config->item('language'));
        $this->lang->load('mailergroup', $this->config->item('language'));
        
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

        $SystemLang['SystemHead'] = $this->lang->line('group_groupsh');
        $SystemLang['Location'] = $this->lang->line('group_subbar');
        
        $this->load->view('head',$SystemLang);
        
        if($Action == 'delete')
        {
            $this->Groups_model->DeleteGroup($SubAction);
            
            $this->System_model->WriteLog($this->lang->line('group_deletedgroup'));
            
            $SystemLang['GroupDeleted'] = true;
        }
        
        if($this->input->post('formsubmit') == 'yes')
        {
            $this->form_validation->set_rules('groups_name', $this->lang->line('group_name'), 'required');

			if($this->form_validation->run() != FALSE)
			{
                $this->Groups_model->AddGroup();
                
                $SystemLang['GroupAdded'] = true;
                
                $SystemLang['Vgroups_name'] = '';
                $SystemLang['Vgroups_multi'] = '';
                
                $this->System_model->WriteLog($this->lang->line('groupv_2addgrouplog'));
            }
            else
            {
                $SystemLang['Vgroups_name'] = $this->input->post('groups_name');
                $SystemLang['Vgroups_multi'] = $this->input->post('groups_multi');
            }
        }
        
        $ConfigTableUsers = $this->User_model->UsersList();
        
        $ResultDB = $this->Groups_model->SelectGroups();
        
        $SystemLang['TableContent'] = '<table class="DataReader">
        <tr>
        <th>'.$this->lang->line('group_id').'</th>
        <th>'.$this->lang->line('group_name2').'</th>
        <th>'.$this->lang->line('group_hmaddresses').'</th>
        <th>'.$this->lang->line('group_candouble').'</th>
        <th style="width: 26px;"></th>
        <th style="width: 26px;"></th>
        <th style="width: 26px;"></th>
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
            
            if($row->groups_multi == 'y')
            {
                $GroupsMulti = $this->lang->line('group_yes');
            }
            else
            {
                $GroupsMulti = $this->lang->line('group_no');
            }
            
            if($ConfigTableUsers[$row->groups_user] == "")
            {
                $User = $this->lang->line('group_nouser3');
            }
            else
            {
                $User = $ConfigTableUsers[$row->groups_user];
            }
            
            $SystemLang['TableContent'] .= '
            <tr>
            <td class="'.$RowClass.'">'.$row->groups_id.'</td>
            <td class="'.$RowClass.'">'.$row->groups_name.'</td>
            <td class="'.$RowClass.'">'.$row->groups_many.'</td>
            <td class="'.$RowClass.'">'.$GroupsMulti.'</td>
            <td class="'.$RowClass.'"><img src="'.base_url('library/user.png').'" width="24" height="24" class="masterTooltip" title="'.$User.'"></td>
            <td class="'.$RowClass.'"><a href="'.base_url('view-group/'.$row->groups_id).'"><img src="'.base_url('library/viewgroup.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('group_seegroup').'"></a></td>
            <td class="'.$RowClass.'"><a href="'.base_url('import-group/'.$row->groups_id).'"><img src="'.base_url('library/import.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('group_import').'"></a></td>
            <td class="'.$RowClass.'"><a href="'.base_url('export-group/'.$row->groups_id).'"><img src="'.base_url('library/export.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('group_export').'"></a></td>
            <td class="'.$RowClass.'"><a href="'.base_url('edit-group/'.$row->groups_id).'"><img src="'.base_url('library/edit.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('group_edit4').'"></a></td>
            <td class="'.$RowClass.'"><a href="JavaScript:DeteleInfo(\''.base_url('groups/delete-group/'.$row->groups_id).'\',\''.$this->lang->line('group_realydelete5').'\')"><img src="'.base_url('library/delete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('group_delete5').'"></a></td>
            </tr>';
        }
        
        $SystemLang['TableContent'] .= '</table>';
               
        $this->System_model->WriteLog($this->lang->line('group_browsegroups'));
        
        $this->load->view('groups/index',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function edit($Id)
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $SystemLang['IdOfElement'] = $Id;

        $SystemLang['SystemHead'] = $this->lang->line('group_editgrouph');
        $SystemLang['Location'] = $this->lang->line('group_editgroupsub');
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formsubmit') == 'yes')
        {
            $this->form_validation->set_rules('groups_name', $this->lang->line('group_name'), 'required');

			if($this->form_validation->run() != FALSE)
			{
                $this->Groups_model->UpdateGroup($Id);
                
                $SystemLang['GroupEdited'] = true;
                
                $this->System_model->WriteLog($this->lang->line('group_editlog'));
            }
            
            $SystemLang['Vgroups_name'] = $this->input->post('groups_name');
            $SystemLang['Vgroups_multi'] = $this->input->post('groups_multi');
        }
        else
        {
            $ResultDB = $this->Groups_model->SelectOneGroup($Id);

            foreach($ResultDB->result() as $row)
            {
                $SystemLang['Vgroups_name'] = $row->groups_name;
                $SystemLang['Vgroups_multi'] = $row->groups_multi;
            }  
        }
        
        $this->load->view('groups/edit',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function importtogroup()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        if($this->input->post('groups_name') != "")
        {
            redirect('import-group/'.$this->input->post('groups_name'));
            exit();
        }
        
        $SystemLang['SystemHead'] = $this->lang->line('group_importh2');
        $SystemLang['Location'] = $this->lang->line('group_import2sub');
        
        $this->load->view('head',$SystemLang);
		
		if($this->IsLicensed)
		{
			$this->load->view('groups/import',$SystemLang);
        }
		else
		{
			$this->load->view('licensefail');
		}
		
        $this->load->view('foot');
    }
    
    public function exporttogroup()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        if($this->input->post('groups_name') != "")
        {
            redirect('export-group/'.$this->input->post('groups_name'));
            exit();
        }
        
        $SystemLang['SystemHead'] = $this->lang->line('group_export2h');
        $SystemLang['Location'] = $this->lang->line('group_export2sub');
        
        $this->load->view('head',$SystemLang);
        $this->load->view('groups/export',$SystemLang);
        $this->load->view('foot');
    }
    
    public function viewgroup($Id,$Page='',$Action='',$SubAction='')
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $SystemLang['IdOfContent'] = $Id;
        
        if($Action == 'delete')
        {            
            $this->Groups_model->DeleteEmail($SubAction);
            
            $SystemLang['EmailDeleted'] = true;
            
            $this->System_model->WriteLog($this->lang->line('group_delfromgrouplog'));
            
            $this->Groups_model->CountEmailAndUpdateGroup($Id);
        }
        
        $ResultDB = $this->Groups_model->SelectOneGroup($Id);

        foreach($ResultDB->result() as $row)
        {
            $SystemLang['GroupName'] = $row->groups_name;
        }
        
        $ResultDB = $this->Groups_model->EmailStructure();

        $TableFields = null;
        
        foreach($ResultDB->result() as $row)
        {
            $TableFields[] = $row->Field;
        }
        
        //print_r($TableFields);
        
        $SystemLang['SystemHead'] = $this->lang->line('group_bgrouph4');
        $SystemLang['Location'] = $this->lang->line('group_bgroup4sub');
        
        $this->load->view('head',$SystemLang);
        
        $ResultDB = $this->Groups_model->EmailsCount($Id);

        foreach($ResultDB->result() as $row)
		{
            $HowManyRows = $row->HowMany;
        }
        
        $this->load->library('pagination');

        $config['base_url'] = base_url('view-group/'.$Id.'/');
		$config['total_rows'] = $HowManyRows;
		$config['per_page'] = 30;
		$config['first_link'] = '&lt;&lt;';
		$config['last_link'] = '&gt;&gt;';
		$config['next_link'] = '&gt;';
		$config['prev_link'] = '&lt;';
		$config['num_links'] = 15;
        $config['full_tag_open'] = $this->lang->line('group_page');
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
        
        $ResultDB = $this->Groups_model->EmailsSelectLimit($Id,$Page);
        
        if($HowManyRows == 0)
        {
            $SystemLang['TableContent'] = $this->lang->line('groupv_3noemailsingroup');
        }
        else
        {
            $SystemLang['TableContent'] = '<div style="width: 950px; overflow-x: auto;"><table class="DataReader"><tr>';
            
            for($i=0;$i<count($TableFields);$i++)
            {
                if(substr($TableFields[$i], 0, strlen('email_')) == 'email_') 
                {
                    $ReadyString = substr($TableFields[$i], strlen('email_'));
                }
                else
                {
                    $ReadyString = $TableFields[$i];
                }
                
                $ReadyString = ucfirst($ReadyString);
                
                if($i == 1)
                {
                    $SystemLang['TableContent'] .= '<th style="width: 26px;"></th>';
                    $SystemLang['TableContent'] .= '<th style="width: 26px;"></th>';
                }
                
                if($TableFields[$i] != 'email_groups_id')
                {
                    $SystemLang['TableContent'] .= '<th>'.$ReadyString.'</th>';
                }
                //echo '1';
            }
            
            $SystemLang['TableContent'] .= '</tr>';
            
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
                
                $SystemLang['TableContent'] .= '<tr>';
                
                for($i=0;$i<count($TableFields);$i++)
                {
                    if($i == 1)
                    {
                        $SystemLang['TableContent'] .= '<td class="'.$RowClass.'"><a href="'.base_url('edit-email/'.$Id.'/'.$row->email_id).'"><img src="'.base_url('library/edit.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('group_edit5').'"></a></td>
                        <td class="'.$RowClass.'"><a href="JavaScript:DeteleInfo(\''.base_url('view-group/'.$Id.'/0/delete/'.$row->email_id).'\',\''.$this->lang->line('group_realydelete5').'\')"><img src="'.base_url('library/delete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('group_delete5').'"></a></td>';
                    }
                    
                    if($TableFields[$i] != 'email_groups_id')
                    {
                        $SystemLang['TableContent'] .= '<td class="'.$RowClass.'"><nobr>'.$row->$TableFields[$i].'</nobr></td>';
                    }
                }
                
                $SystemLang['TableContent'] .= '</tr>';
            }
            
            $SystemLang['TableContent'] .= '</table></div>';
        }
        
        $this->System_model->WriteLog($this->lang->line('groupv_2viewgrouplog'));
        
        $this->load->view('groups/viewgroup',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function checkemail($str)
    {
        $ResultDB = $this->Groups_model->SelectOneGroup($this->GroupId);
        
        foreach($ResultDB->result() as $row)
        {
            $IsDouble = $row->groups_multi;
        }
        
        if($IsDouble == 'y')
        {
            return TRUE;
        }
        else
        {
            $ResultDB = $this->Groups_model->CountEmailOnGroup($this->GroupId,$str);
        
            foreach($ResultDB->result() as $row)
            {
                $HowManyEmail = $row->HowMany;
            }
            
            if($HowManyEmail != 0)
    		{
    			$this->form_validation->set_message('checkemail', $this->lang->line('group_emailexists6'));
    			return FALSE;
    		}
    		else
    		{
    			return TRUE;
    		}
        }
    }
    
    private $GroupId;
    
    public function addemail($Id)
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $this->GroupId = $Id;
        
        // Check if the e-mail in input when the group have a not double e-mails
        
        $SystemLang['IdOfContent'] = $Id;
        
        $SystemLang['SystemHead'] = $this->lang->line('group_addh7');
        $SystemLang['Location'] = $this->lang->line('group_add7sub');
        
        $this->load->view('head',$SystemLang);
        
        $ResultDB = $this->Groups_model->EmailStructure();

        $TableFields = null;
        
        foreach($ResultDB->result() as $row)
        {
            $TableFields[] = $row->Field;
        }

        if($this->input->post('formsubmit') == 'yes')
        {
            $this->form_validation->set_rules('email_email', $this->lang->line('group_email7'), 'required|valid_email|callback_checkemail');

			if($this->form_validation->run() != FALSE)
			{             
                $this->Groups_model->AddNewEmail($Id);
                
                $SystemLang['EmailAdded'] = true;
                
                $this->System_model->WriteLog($this->lang->line('group_addlog7'));
                
                for($i=0;$i<count($TableFields);$i++)
                {                    
                    if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id' AND $TableFields[$i] != 'email_date')
                    {
                        $SpecialField = 'V'.$TableFields[$i];
                        
                        $SystemLang[$SpecialField] = '';
                    }
                }
                
                $this->Groups_model->CountEmailAndUpdateGroup($Id);
            }
            else
            {
                for($i=0;$i<count($TableFields);$i++)
                {                    
                    if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id' AND $TableFields[$i] != 'email_date')
                    {
                        $SpecialField = 'V'.$TableFields[$i];
                        
                        $SystemLang[$SpecialField] = $this->input->post($TableFields[$i]);
                    }
                }
            }
        }
        
        $this->load->view('groups/addemail',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function checkemail2($str)
    {
        $ResultDB = $this->Groups_model->SelectOneGroup($this->GroupId);
        
        foreach($ResultDB->result() as $row)
        {
            $IsDouble = $row->groups_multi;
        }
        
        if($IsDouble == 'y')
        {
            return TRUE;
        }
        else
        {
            $ResultDB = $this->Groups_model->CountEmailOnGroup2($this->GroupId,$this->GroupEmailId,$str);
        
            foreach($ResultDB->result() as $row)
            {
                $HowManyEmail = $row->HowMany;
            }
            
            if($HowManyEmail != 0)
    		{
    			$this->form_validation->set_message('checkemail2', $this->lang->line('group_emailexists8'));
    			return FALSE;
    		}
    		else
    		{
    			return TRUE;
    		}
        }
    }
    
    private $GroupEmailId;
    
    public function editemail($Id,$EmailId)
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $this->GroupId = $Id;
        $this->GroupEmailId = $EmailId;
        
        $SystemLang['IdOfContent'] = $Id;
        $SystemLang['IdOfContentEmail'] = $EmailId;
        
        $SystemLang['SystemHead'] = $this->lang->line('group_editemailh9');
        $SystemLang['Location'] = $this->lang->line('group_editemailsub9');
        
        $this->load->view('head',$SystemLang);
        
        $ResultDB = $this->Groups_model->EmailStructure();

        $TableFields = null;
        
        foreach($ResultDB->result() as $row)
        {
            $TableFields[] = $row->Field;
        }

        if($this->input->post('formsubmit') == 'yes')
        {
            $this->form_validation->set_rules('email_email', $this->lang->line('group_email9'), 'required|valid_email|callback_checkemail2');

			if($this->form_validation->run() != FALSE)
			{
                $this->Groups_model->UpdateEmail($EmailId);
                
                $SystemLang['EmailAdded'] = true;
                
                $this->System_model->WriteLog($this->lang->line('group_editemaillog9'));
            }
            
            for($i=0;$i<count($TableFields);$i++)
            {                    
                if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id' AND $TableFields[$i] != 'email_date')
                {
                    $SpecialField = 'V'.$TableFields[$i];
                    
                    $SystemLang[$SpecialField] = $this->input->post($TableFields[$i]);
                }
            }
        }
        else
        {
            $ResultDB = $this->Groups_model->SelectOneEmail($EmailId);

            foreach($ResultDB->result() as $row)
            {
                for($i=0;$i<count($TableFields);$i++)
                {                    
                    if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id' AND $TableFields[$i] != 'email_date')
                    {
                        $SpecialField = 'V'.$TableFields[$i];
                        
                        $SystemLang[$SpecialField] = $row->$TableFields[$i];
                    }
                }
            }  
        }
        
        $this->load->view('groups/editemail',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function exclusion($Page="",$Action="",$SubAction="")
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $SystemLang['SystemHead'] = $this->lang->line('group_exclusionh10');
        $SystemLang['Location'] = $this->lang->line('group_exclusionsub10');
        
        $this->load->view('head',$SystemLang);
        
        if($Action == 'delete')
        {           
            $this->Groups_model->ExclusionDeleteEmail($SubAction);
            
            $SystemLang['EmailDeleted'] = true;
            
            $this->System_model->WriteLog($this->lang->line('group_excdeleted10'));
        }
        
        $ResultDB = $this->Groups_model->ExclusionEmailsCount();

        foreach($ResultDB->result() as $row)
		{
            $HowManyRows = $row->HowMany;
        }
        
        $this->load->library('pagination');

        $config['base_url'] = base_url('exclusion/');
		$config['total_rows'] = $HowManyRows;
		$config['per_page'] = 30;
		$config['first_link'] = '&lt;&lt;';
		$config['last_link'] = '&gt;&gt;';
		$config['next_link'] = '&gt;';
		$config['prev_link'] = '&lt;';
		$config['num_links'] = 15;
        $config['full_tag_open'] = $this->lang->line('group_page10');
        $config['uri_segment'] = 2;
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
        
        $ResultDB = $this->Groups_model->ExclusionEmailsSelectLimit($Page);
        
        $SystemLang['TableContent'] = '<table class="DataReader">
        <tr>
        <th>'.$this->lang->line('group_excid10').'</th>
        <th>'.$this->lang->line('group_excemail10').'</th>
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
            
            $SystemLang['TableContent'] .= '<tr>';

            $SystemLang['TableContent'] .= '
            <td class="'.$RowClass.'">'.$row->exclusion_id.'</td>
            <td class="'.$RowClass.'">'.$row->exclusion_email.'</td>
            <td class="'.$RowClass.'"><a href="JavaScript:DeteleInfo(\''.base_url('exclusion/0/delete/'.$row->exclusion_id).'\',\''.$this->lang->line('group_excsdelete10').'\')"><img src="'.base_url('library/delete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('group_excdeletebutton').'"></a></td>';

            
            $SystemLang['TableContent'] .= '</tr>';
        }
        
        $SystemLang['TableContent'] .= '</table></div>';
        
        $this->load->view('groups/exclusion',$SystemLang);
        
        $this->load->view('foot');
    }
    
    public function exccheckemail($str)
    {
        $ResultDB = $this->Groups_model->SelectExclusionEmail($str);
        
        foreach($ResultDB->result() as $row)
        {
            $HowManyIs = $row->HowMany;
        }
        
        if($HowManyIs != 0)
		{
			$this->form_validation->set_message('exccheckemail', $this->lang->line('group_emailexists11'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
    }
    
    public function exclusionadd()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }

        $SystemLang['SystemHead'] = $this->lang->line('group_addemailh12');
        $SystemLang['Location'] = $this->lang->line('group_addemailsub12');
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formsubmit') == 'yes')
        {
            $this->form_validation->set_rules('exclusion_email', $this->lang->line('group_email12'), 'required|callback_exccheckemail');

			if($this->form_validation->run() != FALSE)
			{
                $this->Groups_model->AddExclusionEmail();
                
                $SystemLang['EmailAdded'] = true;
                
                $this->System_model->WriteLog($this->lang->line('group_addedaddress12'));
                
                $SystemLang['Vexclusion_email'] = $this->input->post('exclusion_email');
            }
            
            $SystemLang['Vexclusion_email'] = '';
        }
        
        $this->load->view('groups/excaddemail',$SystemLang);
        
        $this->load->view('foot');
    }
}

?>