<h1><?php echo '<img src="'.base_url('library/htime.png').'" width="32" height="32" style="vertical-align: middle">'; ?> <?php echo $this->lang->line('timetable_time6'); ?></h1>

<?php

echo '<div class="BorderDiv">';

if($GroupDeleted)
{
    echo '<div class="goodAction">'.$this->lang->line('timetable_deleted6').'</div>';	
}

if($GroupAdded)
{
    echo '<div class="goodAction">'.$this->lang->line('timetable_added6').'</div>';	
}

echo $TableContent;

echo '</div>';

echo '<br /><br />';

echo '<div class="BorderDiv">';

echo '<h2>'.$this->lang->line('timetable_addtime6').'</h2>';

$ResultDB = $this->Messages_model->SelectMessageDrafts();
        
$IsTableNull = true;

foreach($ResultDB->result() as $row)
{
    $IsTableNull = false;
    
	$OptionsMessage[$row->message_id] = '(#'.$row->message_id.') - '.$row->message_title;
}

echo form_open('timetable');

if($IsTableNull == false)
{
    echo $this->lang->line('timetable_emailtopic7').' <br /> ';

    $ExtraOptions = ' style = "width: 100%;" ';

    echo form_dropdown('message_id', $OptionsMessage, $Vmessage_id, $ExtraOptions).'<br />';
    echo form_error('message_id','<div class="error">', '</div>');
    
    echo '<br />'.$this->lang->line('timetable_sendtime7').' <br /> '.form_input(array('name' => 'message_planned_date', 'id' => 'message_planned_date', 'style' => 'width:100%', 'value' => $Vmessage_planned_date)).'<br />';
    echo form_error('message_planned_date','<div class="error">', '</div>');
    
    echo '<br />';
    echo form_hidden('formsubmit','yes');
    $SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('timetable_addtimesubmit7'), 'style' => 'width: 100%;');
    echo form_submit($SubmitButton);
    echo form_close();
}
else
{
    echo $this->lang->line('timetable_3nomessage');
}
echo '</div>';

?>
<script>
jQuery('#message_planned_date').datetimepicker({
  format:'Y-m-d H:i',
});
</script>