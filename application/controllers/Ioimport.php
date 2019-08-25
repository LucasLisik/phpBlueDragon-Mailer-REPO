<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 4-9-2015 22:38
 *
 */

set_time_limit(60 * 5);

class Ioimport extends CI_Controller
{
	private $IsLicensed = false;
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        $this->load->model('User_model');
        
        $this->load->model('Import_model');
        $this->load->model('Groups_model');
        
        $this->lang->load('mailermain', $this->config->item('language'));
        $this->lang->load('mailerimport', $this->config->item('language'));
        
		$IsLicenseExists = $this->System_model->CheckLicenseExists();
		
		if($IsLicenseExists == 'yes')
		{
			$this->IsLicensed = true;
		}
		
        //$this->output->enable_profiler(true);
    }
    
    public function group($GroupId)
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
        $SystemLang['GroupId'] = $GroupId;
        
        $SystemLang['SystemHead'] = $this->lang->line('import_grouph1');
        $SystemLang['Location'] = $this->lang->line('import_groupsub1');
        
        $this->load->helper('string');
        
		if($this->IsLicensed)
		{
			if($this->input->post('addfile') == 'yes')
			{
				$RandomString = random_string('alnum', 5);
				 
				$config['upload_path'] = './import';
				$config['allowed_types'] = 'csv|txt';
				$config['max_size']	= '100000';
				$config['remove_spaces'] = true;
				$config['overwrite'] = false;
				$config['file_name'] = time().'_'.$RandomString.'.csv';
				
				$SystemLang['FileName'] = $config['file_name'];
				
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('uploadfileattachement'))
				{
					$SystemLang['UploadError'] = array('error' => $this->upload->display_errors());
					$SystemLang['data'] = $this->upload->data();
				}
				else
				{
					$data = $this->upload->data();
					
					$SystemLang['ImportFileSuccess'] = true;
				}
			}
		}
        
        $this->load->view('head',$SystemLang);
        
		if($this->IsLicensed)
		{
			$this->load->view('import/group',$SystemLang);
        }
		else
		{
			$this->load->view('licensefail');
		}
		
        $this->load->view('foot');
    }
    
    public function isnull($str)
    {
        if($str == $this->input->post('nullfield'))
		{
			$this->form_validation->set_message('isnull', $this->lang->line('import_emailempty2'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
    }
    
    public function groupimport($GroupId,$FileName)
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('login');
            exit();    
        }
        
		$FileName = basename($FileName);
		
        $ResultDB = $this->Import_model->SelectOneGroup($GroupId);
        
        foreach($ResultDB->result() as $row)
        {
            $IsMultiEmail = $row->groups_multi;
        }
                
        // X - Sprawdzanie czy e-mail jest null
        // X - czy e-mail jest poprawny
        // X - czy data jest ppoprawna
        // X - czy jest możliwość dodawania dwóch maili
		if($this->IsLicensed)
		{
			if($this->input->post('formsubmit') == 'yes')
			{
				$this->load->helper('email');
				
				$this->form_validation->set_rules('email_email', $this->lang->line('import_email3'), 'required|callback_isnull');

				if($this->form_validation->run() != FALSE)
				{
					$SystemLang['GroupImported'] = true;
				 
					$ResultDB = $this->Import_model->ImportGroupStructure();

					$TableFields = null;
					
					foreach($ResultDB->result() as $row)
					{
						$TableFields[] = $row->Field;
					}
					
					if(($GetFile = fopen('import/'.$FileName,"r")) !== FALSE) 
					{
						$Line = 0;
						
						while(($data = fgetcsv($GetFile, 0, ',')) !== FALSE)  
						{
							$LineInform = $this->lang->line('import_line3').' <strong>'.$Line.'</strong>: ';
							$Line++;
							
							$TableDataFile = null;
							
							//
							// Import do bazy
							//
							$TableValueFields = null;
							$ThisEmail = null;
							
							$NotValidRow = false;
									 
								$TableToImport = null;
								
								if($this->input->post('email_email') != $this->input->post('nullfield'))
								{
									$ThisEmail = $data[$this->input->post('email_email')];
									
									if(!valid_email($data[$this->input->post('email_email')]))
									{
										$NotValidRow = true;
									}
									else
									{
										$ResultDBOne = $this->Import_model->SelectEmail($GroupId,$this->input->post('email_email'));
											
										$IsEmail = 0;
										
										foreach($ResultDBOne->result() as $rowOne)
										{
											$IsEmail = $rowOne->HowMany;    
										}
										
										if($IsEmail > 0)
										{
											if($IsMultiEmail != 'y')
											{
												$NotValidRow = true;
											}
											else
											{
												$TableToImport['email_email'] = $data[$this->input->post('email_email')];
											}
										}
										else
										{
											$TableToImport['email_email'] = $data[$this->input->post('email_email')];   
										}
									}
								}
								
								$DateNotValid = false;
								
								if($this->input->post('email_date') != $this->input->post('nullfield'))
								{
									if($this->validateDate($data[$this->input->post('email_date')]))
									{
										$TableToImport['email_date'] = $data[$this->input->post('email_date')];
									}
									else
									{
										$NotValidRow = true;
										$DateNotValid = true;
									}
								}
								
								if($this->input->post('email_title') != $this->input->post('nullfield'))
								{
									$TableToImport['email_title'] = $data[$this->input->post('email_title')];
								}
								
								if($this->input->post('email_name') != $this->input->post('nullfield'))
								{
									$TableToImport['email_name'] = $data[$this->input->post('email_name')];
								}
								
								if($this->input->post('email_lastname') != $this->input->post('nullfield'))
								{
									$TableToImport['email_lastname'] = $data[$this->input->post('email_lastname')];
								}
								
								if($this->input->post('email_initial') != $this->input->post('nullfield'))
								{
									$TableToImport['email_initial'] = $data[$this->input->post('email_initial')];
								}
								
								if($this->input->post('email_address1') != $this->input->post('nullfield'))
								{
									$TableToImport['email_address1'] = $data[$this->input->post('email_address1')];
								}
								
								if($this->input->post('email_address2') != $this->input->post('nullfield'))
								{
									$TableToImport['email_address2'] = $data[$this->input->post('email_address2')];
								}
								
								if($this->input->post('email_city') != $this->input->post('nullfield'))
								{
									$TableToImport['email_city'] = $data[$this->input->post('email_city')];
								}
								
								if($this->input->post('email_state') != $this->input->post('nullfield'))
								{
									$TableToImport['email_state'] = $data[$this->input->post('email_state')];
								}
								
								if($this->input->post('email_postcode') != $this->input->post('nullfield'))
								{
									$TableToImport['email_postcode'] = $data[$this->input->post('email_postcode')];
								}
								
								if($this->input->post('email_country') != $this->input->post('nullfield'))
								{
									$TableToImport['email_country'] = $data[$this->input->post('email_country')];
								}
								
								if($this->input->post('email_firm') != $this->input->post('nullfield'))
								{
									$TableToImport['email_firm'] = $data[$this->input->post('email_firm')];
								}
								
								if($this->input->post('email_jobtitle') != $this->input->post('nullfield'))
								{
									$TableToImport['email_jobtitle'] = $data[$this->input->post('email_jobtitle')];
								}
								
								if($this->input->post('email_network') != $this->input->post('nullfield'))
								{
									$TableToImport['email_network'] = $data[$this->input->post('email_network')];
								}
								
								if($this->input->post('email_phone') != $this->input->post('nullfield'))
								{
									$TableToImport['email_phone'] = $data[$this->input->post('email_phone')];
								}
								
								if($this->input->post('email_fax') != $this->input->post('nullfield'))
								{
									$TableToImport['email_fax'] = $data[$this->input->post('email_fax')];
								}
								
								if($this->input->post('email_field1') != $this->input->post('nullfield'))
								{
									$TableToImport['email_field1'] = $data[$this->input->post('email_field1')];
								}
								
								if($this->input->post('email_field2') != $this->input->post('nullfield'))
								{
									$TableToImport['email_field2'] = $data[$this->input->post('email_field2')];
								}
								
								if($this->input->post('email_field3') != $this->input->post('nullfield'))
								{
									$TableToImport['email_field3'] = $data[$this->input->post('email_field3')];
								}
								
								if($this->input->post('email_field4') != $this->input->post('nullfield'))
								{
									$TableToImport['email_field4'] = $data[$this->input->post('email_field4')];
								}
								
								
								
								/*if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id')
								{
									echo $this->input->post($TableFields[$i]).'-'.$this->input->post('nullfield').'<br />';
									
									//if($this->input->post($TableFields[$i]) != "" OR $this->input->post($TableFields[$i]) != $this->input->post('nullfield'))
									if($this->input->post($TableFields[$i]) != $this->input->post('nullfield'))
									{
										if($this->input->post('email_email'))
										{
											if(!valid_email($this->input->post('email_email')))
											{
												$NotValidRow = true;
												echo 'E-mail - brak walidacji<br />';
											}
											
											$ResultDBOne = $this->Import_model->SelectEmail($GroupId,$this->input->post('email_email'));
											
											$IsEmail = 0;
											
											foreach($ResultDBOne->result() as $rowOne)
											{
												$IsEmail = $rowOne->HowMany;    
											}
											
											if($IsEmail > 0)
											{
												if($IsMultiEmail != 'y')
												{
													$NotValidRow = true;
													echo 'Taki e-mail już istnieje<br />';
												}
											}
										}
										elseif($this->input->post('email_date'))
										{
											if(!$this->validateDate($this->input->post('email_date')))
											{
												$NotValidRow = true;
												echo 'Data jest w niepoprawnym formacie<br />';
											}
										}
										else
										{
											$TableValueFields[$TableFields[$i]] = $TableDataFile[$this->input->post($TableFields[$i])];
										}
									}
								}
								*/
							
							if(!$NotValidRow)
							{
								$this->Import_model->ImportOneEmail($GroupId,$TableToImport);   
							}
							else
							{
								if($DateNotValid)
								{
									$ReportTable[] = $LineInform.$this->lang->line('import_dataerror3');
								}
								else
								{
									$ReportTable[] = $LineInform.$this->lang->line('import_emailerror3').$ThisEmail.'.';
								}
							}
						}
						
						fclose ($GetFile);
					}
					
					$this->Groups_model->CountEmailAndUpdateGroup($GroupId);
				}
			}
		}
        
        $SystemLang['ReportShow'] = $ReportTable;
        $SystemLang['GroupId'] = $GroupId;
        $SystemLang['FileName'] = $FileName;
        
        $SystemLang['SystemHead'] = $this->lang->line('import_importgrouph3');
        $SystemLang['Location'] = $this->lang->line('import_importgroupsub3');
        
        $this->load->view('head',$SystemLang);
        
		if($this->IsLicensed)
		{
			$this->load->view('import/groupimport',$SystemLang);
        }
		else
		{
			$this->load->view('licensefail');
		}
        
        $this->load->view('foot');
    }
    
    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d;
    }
}

?>