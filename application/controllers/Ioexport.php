<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 4-9-2015 15:34
 *
 */

set_time_limit(60 * 5);
 
class Ioexport extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        $this->load->model('User_model');
        
        $this->load->model('Export_model');
        
        $this->lang->load('mailermain', $this->config->item('language'));
        $this->lang->load('mailerexport', $this->config->item('language'));
        
        //$this->output->enable_profiler(true);
    }
    
    public function group($GroupId)
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        //CSV
        
        $this->load->helper('download');
        
        if($this->input->post('addfile') == 'yes')
        {
            $this->System_model->WriteLog($this->lang->line('export_2exportgrouplog'));
            
            $ResultDB = $this->Export_model->ExportGroupStructure();
            
            $TableFields = null;
        
            foreach($ResultDB->result() as $row)
            {
                $TableFields[] = $row->Field;
            }
            
            if($this->input->post('export_whole') == 'yes')
            {
                $ResultDB = $this->Export_model->ExportGroup('all', $GroupId);
                
                if($this->input->post('export_addheader') == 'yes')
                {
                    for($i=0;$i<count($TableFields);$i++)
                    {
                        if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id')
                        {
                            $DataInFile .= $TableFields[$i].',';
                        }
                    }
                    
                    $DataInFile .= "\n";
                }
                
                foreach($ResultDB->result() as $row)
                {
                    for($i=0;$i<count($TableFields);$i++)
                    {                    
                        if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id')
                        {
                            $DataInFile .= $row->$TableFields[$i].',';
                        }
                    }
                    
                    $DataInFile .= "\n";
                }
            }
            else
            {
                if($this->input->post('export_addheader') == 'yes')
                {
                    $DataInFile .= 'email_email'."\n";
                }
                
                $ResultDB = $this->Export_model->ExportGroup('email', $GroupId);
                
                foreach($ResultDB->result() as $row)
                {
                    $DataInFile .= $row->email_email."\n";
                }
            }
            
            force_download('export.csv', $DataInFile); 
        }
        
        $SystemLang['GroupId'] = $GroupId;
        
        $SystemLang['SystemHead'] = $this->lang->line('export_grouph');
        $SystemLang['Location'] = $this->lang->line('export_groupsub');

        $this->load->view('head',$SystemLang);
        
        $this->load->view('export/group',$SystemLang);
        
        $this->load->view('foot');
    }
}

?>