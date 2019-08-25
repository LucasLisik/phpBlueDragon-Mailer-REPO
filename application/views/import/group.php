<?php

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 4-9-2015 22:40
 *
 */
 
echo '<h1><img src="'.base_url('library/himport.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('import_importgrouph4').'</h1>';
/*echo '<pre>';
print_r($data);
echo '</pre>';*/
if($UploadError['error'] != "")
{
    echo '<div class="badAction">'.$UploadError['error'].'</div>';
}

if($ImportFileSuccess)
{
    echo '<div class="goodAction">'.$this->lang->line('import_uplsucces4').'<a href="'.base_url('import-to-group/'.$GroupId.'/'.$FileName).'">'.$this->lang->line('import_uplsuccesgotoimport4').'</a></div>';
}

if(!$ImportFileSuccess)
{
    echo '<div class="BorderDiv">';
    echo form_open_multipart('import-group/'.$GroupId);
    echo $this->lang->line('import_file4').'<br /> '.form_upload(array('name' => 'uploadfileattachement', 'id' => 'uploadfileattachement')).'<br />';
    echo form_error('uploadfileattachement','<div class="error">', '</div>');
    echo '<br />';
    echo form_hidden('addfile','yes');
    $SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('import_uplbutton4'), 'style' => 'width: 100%;');
    echo form_submit($SubmitButton);
    echo form_close();
    echo '</div>';
}

?>