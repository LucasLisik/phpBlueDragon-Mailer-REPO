<h1><?php echo '<img src="'.base_url('library/hsign.png').'" width="32" height="32" style="vertical-align: middle;" />'; ?> <?php echo $this->lang->line('sign_signh4'); ?></h1>

<?php

if($GroupDeleted)
{
    echo '<div class="goodAction">'.$this->lang->line('sign_signwasdeleted4').'</div>';	
}

if($GroupAdded)
{
    echo '<div class="goodAction">'.$this->lang->line('sign_signwasadded4').'</div>';	
}

echo '<div class="BorderDiv">';
echo $TableContent;
echo '</div>';

echo '<br /><br />';

echo '<div class="BorderDiv">';
echo '<h2>'.$this->lang->line('sign_addnewheader4').'</h2>';

echo form_open('signatures');
echo $this->lang->line('sign_namesign3').' <br /> '.form_input(array('name' => 'signatures_name', 'id' => 'signatures_name', 'style' => 'width:100%', 'value' => $Vsignatures_name)).'<br />';
echo form_error('signatures_name','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sign_html3').' <br /> '.form_textarea(array('name' => 'signatures_html', 'id' => 'signatures_html', 'style' => 'width:100%', 'value' => $Vsignatures_html)).'<br />';
echo form_error('signatures_html','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sign_text3').' <br /> '.form_textarea(array('name' => 'signatures_text', 'id' => 'signatures_text', 'style' => 'width:100%; height: 300px;', 'value' => $Vsignatures_text)).'<br />';
echo form_error('signatures_text','<div class="error">', '</div>');

echo '<br />';
echo form_hidden('formsubmit','yes');
$SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('sign_addsubmitbutton4'), 'style' => 'width: 100%;');
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