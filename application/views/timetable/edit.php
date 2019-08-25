<?php

echo '<h1><img src="'.base_url('library/hedittime.png').'" width="32" height="32" style="vertical-align: middle"> '.$this->lang->line('timetable_edit8').'</h1>';

echo '<div class="BorderDiv">';

if($GroupEdited)
{
    echo '<div class="goodAction">'.$this->lang->line('timetable_updated8').'</div>';	
}

echo form_open('edit-timetable/'.$IdOfElement);
echo $this->lang->line('timetable_datetimesend8').' <br /> '.form_input(array('name' => 'message_planned_date', 'id' => 'message_planned_date', 'style' => 'width:100%;', 'value' => $Vmessage_planned_date)).'<br />';
echo form_error('message_planned_date','<div class="error">', '</div>');

echo '<br />';
echo form_hidden('formsubmit','yes');
$SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('timetable_updatesubmit8'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);
echo form_close();
echo '</div>';

?>
<script>
jQuery('#message_planned_date').datetimepicker({
  format:'Y-m-d H:i',
});
</script>
