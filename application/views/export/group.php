<?php

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 4-9-2015 15:37
 *
 */
 
echo '<h1><img src="'.base_url('library/hexport.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('export_grouph2').'</h1>';

echo '<div class="BorderDiv">';
echo form_open('export-group/'.$GroupId);
echo $this->lang->line('export_options').' <br /> ';
echo form_radio('export_whole', 'yes', TRUE).' '.$this->lang->line('export_alldata').'<br />';
echo form_radio('export_whole', 'no').' '.$this->lang->line('export_onlyaddress').'<br />';
echo '<br />';
echo $this->lang->line('export_add3').' <br /> ';
echo form_checkbox('export_addheader', 'yes', TRUE).' '.$this->lang->line('export_headers4').'<br />';
echo '<br />';
echo form_hidden('addfile','yes');
$SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('export_getcsvsubmit'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);
echo form_close();
echo '</div>';

// CSV

?>