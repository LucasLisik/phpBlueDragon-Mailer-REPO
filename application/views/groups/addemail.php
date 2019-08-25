<?php

echo '<h1><img src="'.base_url('library/hemailadd.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('groupv_addemail1').'</h1>';

echo '<div class="BorderDiv">';

if($EmailAdded)
{
    echo '<div class="goodAction">'.$this->lang->line('groupv_added1').'</div>';	
}
   
$ResultDB = $this->Groups_model->EmailStructure();

$TableFields = null;

foreach($ResultDB->result() as $row)
{
    $TableFields[] = $row->Field;
}

echo form_open('add-email/'.$IdOfContent);

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
    
    if($TableFields[$i] != 'email_groups_id' AND $TableFields[$i] != 'email_id' AND $TableFields[$i] != 'email_date')
    {
        $SpecialField = 'V'.$TableFields[$i];
        
        echo '<br />'.$ReadyString.': <br /> '.form_input(array('name' => $TableFields[$i], 'id' => $TableFields[$i], 'style' => 'width:100%', 'value' => $$SpecialField)).'<br />';
        echo form_error($TableFields[$i],'<div class="error">', '</div>');
    }
}

echo '<br />';
echo form_hidden('formsubmit','yes');
$SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('groupv_addsubmit1'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);
echo form_close();
echo '</div>';

?>