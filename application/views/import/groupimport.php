<?php

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 5-9-2015 16:42
 *
 */
 
echo '<h1><img src="'.base_url('library/himport.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('import_impgroup5').'</h1>';

echo '<div class="BorderDiv">';

if($GroupImported == true)
{
    echo '<div class="goodAction">'.$this->lang->line('import_importsuccess5').'</div>';
    
    if(count($ReportShow) > 0)
    {
        for($i=0;$i<count($ReportShow);$i++)
        {
            echo '<div class="goodAction">'.$ReportShow[$i].'</div>';
        }
    }
}
else
{
    $ResultDB = $this->Import_model->ImportGroupStructure();
    
    $TableFields = null;
    
    foreach($ResultDB->result() as $row)
    {
        $TableFields[] = $row->Field;
    }
    
    echo form_open('import-to-group/'.$GroupId.'/'.$FileName);
    
    //fgetcsv 
    
    /*
    if(($GetFile = fopen('import/'.$FileName,"r")) !== FALSE) 
    {
        while(($data = fgetcsv($GetFile, 0, ',')) !== FALSE)  
        {
            $num = count($data);
            
            for($c=0;$c<$num;$c++) 
            {
                echo $data[$c] . "<br />\n";
            }
        }
        
        fclose ($GetFile);
    }
    */
    
    $TableWithCSV = null;
    
    if(($GetFile = fopen('import/'.$FileName,"r")) !== FALSE) 
    {
        $data = fgetcsv($GetFile, 0, ',');
        
        $num = count($data);
            
        for($c=0;$c<$num;$c++) 
        {
            if($data[$c] != "")
            {
                $TableWithCSV[$c] = $data[$c];
            }
        }
        
        fclose ($GetFile);
    }
    
    $ExtraOptions = ' style = "width: 100%;" ';
    
    $TableWithCSV[] = 'Null';
    
    $NumberDefault = count($TableWithCSV);
    
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
        
        if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id')
        {
            $SpecialField = 'V'.$TableFields[$i];
            
            echo $ReadyString.': <br /> ';
            
            if($this->input->post($TableFields[$i]) == "")
            {
                $ValueField[$TableFields[$i]] = $NumberDefault-1;
            }
            else
            {
                $ValueField[$TableFields[$i]] = $this->input->post($TableFields[$i]);
            }
            
            echo form_dropdown($TableFields[$i], $TableWithCSV, $ValueField[$TableFields[$i]], $ExtraOptions);
            echo '<br />';
            echo form_error($TableFields[$i],'<div class="error">', '</div>');
        }
    }
    
    echo '<br />';
    echo form_hidden('nullfield',$NumberDefault-1);
    echo form_hidden('formsubmit','yes');
    $SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('import_importsubmit5'), 'style' => 'width: 100%;');
    echo form_submit($SubmitButton);
    echo form_close();
}

echo '</div>';

?>