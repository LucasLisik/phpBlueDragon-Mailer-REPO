<h1><?php echo '<img src="'.base_url('library/hgroup.png').'" width="32" height="32" style="vertical-align: middle;" />'; ?> <?php echo $this->lang->line('groupv_groups8'); ?></h1>

<?php

if($GroupDeleted)
{
    echo '<div class="goodAction">'.$this->lang->line('groupv_groupdeleted8').'</div>';	
}

if($GroupAdded)
{
    echo '<div class="goodAction">'.$this->lang->line('groupv_groupadded8').'</div>';	
}

echo '<div class="BorderDiv">';
echo $TableContent;
echo '</div>';

echo '<br /><br />';

echo '<div class="BorderDiv">';
echo '<h2>'.$this->lang->line('groupv_addnewgrouph8').'</h2>';

echo form_open('groups');
echo $this->lang->line('groupv_namegrouplabel8').' <br /> '.form_input(array('name' => 'groups_name', 'id' => 'groups_name', 'style' => 'width:100%', 'value' => $Vgroups_name)).'<br />';
echo form_error('groups_name','<div class="error">', '</div>');
if($Vgroups_multi == 'y')
{
    $Vgroups_multi = true;
}
else
{
    $Vgroups_multi = false;
}
echo '<br />'.$this->lang->line('groupv_groupmany8').'<br /> '.form_checkbox('groups_multi', 'y', $Vgroups_multi).'<br />';
echo '<br />';
echo form_hidden('formsubmit','yes');
$SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('groupv_addgroupsubmit8'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);
echo form_close();
echo '</div>';

?>