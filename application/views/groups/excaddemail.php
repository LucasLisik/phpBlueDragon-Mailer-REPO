<?php

echo '<h1><img src="'.base_url('library/hemailadd.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('groupv_addemailh4').'</h1>';

echo '<div class="BorderDiv">';

if($EmailAdded)
{
    echo '<div class="goodAction">'.$this->lang->line('groupv_emailadded4').'</div>';	
}

echo form_open('exclusion-add-email/');

echo $this->lang->line('groupv_emailfield4').' <br /> '.form_input(array('name' => 'exclusion_email', 'id' => 'exclusion_email', 'style' => 'width:100%', 'value' => $Vexclusion_email)).'<br />';
echo form_error('exclusion_email','<div class="error">', '</div>');

echo '<br />';
echo form_hidden('formsubmit','yes');
$SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('groupv_addemailsubmit4'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);
echo form_close();

echo '</div>';

?>