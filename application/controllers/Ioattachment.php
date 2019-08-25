<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://lukasz.sos.pl
 * @copyright: 31-8-2015 20:56
 */

class Ioattachment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        $this->load->model('User_model');
        
        $this->load->model('Attachment_model');
        
        $this->lang->load('mailermain', $this->config->item('language'));
        $this->lang->load('mailerattach', $this->config->item('language'));
        
        //$this->output->enable_profiler(true);
    }
    
    public function getattachement($MessageId,$Action='',$SubAction='')
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $this->load->view('headpop',$SystemLang);
		
        $SystemLang['MessageId'] = $MessageId;
        
        if($Action == 'delete')
        {
            $this->Attachment_model->DeleteAttachement($SubAction);
            
            $SystemLang['AttachmentDeleted'] = true;
        }
        
        if($this->input->post('addfile') == 'yes')
        {
            $config['upload_path'] = './attachment/mess'.$MessageId;
    		$config['allowed_types'] = 'jpg|png|gif|pdf|txt|zip|doc|docx|xls|xlsx';
    		$config['max_size']	= '100000';
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            //$config['file_name'] = time().'_'.$MyRandomString.'.txt';
            
    		$this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('uploadfileattachement'))
    		{
    			$SystemLang['UploadError'] = array('error' => $this->upload->display_errors());
    		}
    		else
    		{
    			$data = $this->upload->data();
                
                $this->Attachment_model->AddAttachement($MessageId,$data['file_name']);
                
                $SystemLang['ImportFileSuccess'] = true;
                
                $this->System_model->WriteLog($this->lang->line('attach_2addattachlog'));
            }
        }
        
        $ResultDB = $this->Attachment_model->SelectAttachment($MessageId);
        
        $AreAttachments = false;
        
        $SystemLang['TableContentAttachment'] .= '<table class="DataReader">
        <tr>
        <th>'.$this->lang->line('attach_id').'</th>
        <th>'.$this->lang->line('attach_file').'</th>
		<th style="width: 26px;"></th>
        </tr>';
        
        foreach($ResultDB->result() as $row)
        {
            $i++;
            
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
            
            //$row->attachment_id
            $SystemLang['TableContentAttachment'] .= '
            <tr>
            <td class="'.$RowClass.'">'.$i.'</td>
            <td class="'.$RowClass.'">'.$row->attachment_file.'</td>
    		<td class="'.$RowClass.'"><a href="JavaScript:DeteleInfo(\''.base_url('ioattachment/getattachement/'.$MessageId.'/delete/'.$row->attachment_id).'\',\''.$this->lang->line('attach_shuredelete').'\')"><img src="'.base_url('library/delete.png').'" width="24" height="24" class="masterTooltip" title="'.$this->lang->line('attach_deletebutton').'"></a></td>
            </tr>';
            
            $AreAttachments = true;
        }
        
        $SystemLang['TableContentAttachment'] .= '</table>';
        
        if($AreAttachments == false)
        {
            $SystemLang['TableContentAttachment'] = $this->lang->line('attach_nofiles');
        }
        
		$this->load->view('attachment/attachment',$SystemLang);
        
        $this->load->view('footpop');
    }

}

?>