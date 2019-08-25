<?php

echo '<h1><img src="'.base_url('library/hgroupedit.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('groupv_editgroup2').'</h1>';

echo '<div class="BorderDiv">';
if($GroupEdited)
{
    echo '<div class="goodAction">'.$this->lang->line('groupv_edited2').'</div>';	
}

echo form_open('edit-group/'.$IdOfElement);
echo $this->lang->line('groupv_namegroup2').' <br /> '.form_input(array('name' => 'groups_name', 'id' => 'groups_name', 'style' => 'width:100%', 'value' => $Vgroups_name)).'<br />';
echo form_error('groups_name','<div class="error">', '</div>');
if($Vgroups_multi == 'y')
{
    $Vgroups_multi = true;
}
else
{
    $Vgroups_multi = false;
}
echo '<br />'.$this->lang->line('groupv_allow2').'<br /> '.form_checkbox('groups_multi', 'y', $Vgroups_multi).'<br />';
echo '<br />';
echo form_hidden('formsubmit','yes');
$SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('groupv_updatesubmit2'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);
echo form_close();
echo '</div>';

?>