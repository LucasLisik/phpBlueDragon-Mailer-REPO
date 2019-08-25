<?php

echo '<h1><img src="'.base_url('library/hsignedit.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('sign_editsignh3').'</h1>';

echo '<div class="BorderDiv">';
if($GroupEdited)
{
    echo '<div class="goodAction">'.$this->lang->line('sign_edited3').'</div>';	
}

echo form_open('edit-signature/'.$IdOfElement);
echo $this->lang->line('sign_namesign3').' <br /> '.form_input(array('name' => 'signatures_name', 'id' => 'signatures_name', 'style' => 'width:100%', 'value' => $Vsignatures_name)).'<br />';
echo form_error('signatures_name','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sign_html3').' <br /> '.form_textarea(array('name' => 'signatures_html', 'id' => 'signatures_html', 'style' => 'width:100%', 'value' => $Vsignatures_html)).'<br />';
echo form_error('signatures_html','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sign_text3').' <br /> '.form_textarea(array('name' => 'signatures_text', 'id' => 'signatures_text', 'style' => 'width:100%; height: 300px;', 'value' => $Vsignatures_text)).'<br />';
echo form_error('signatures_text','<div class="error">', '</div>');

echo '<br />';
echo form_hidden('formsubmit','yes');
$SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('sign_updatesubmit3'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);
echo form_close();
echo '</div>';

?>
<script>
CKEDITOR.replace('signatures_html');
CKEDITOR.config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];
CKEDITOR.config.removeButtons = 'Save,NewPage,Preview,Print,Templates';
CKEDITOR.config.height = 300;        // 500 pixels high.
CKEDITOR.config.skin = 'office2013';
</script>
