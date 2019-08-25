<?php

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 4-9-2015 12:29
 *
 */

?>

<h2 style="font-size: 15px;"><?php echo $this->lang->line('attach_addh'); ?></h2>

<?php

if($UploadError['error'] != "")
{
    echo '<div class="badAction">'.$UploadError['error'].'</div>';
}

if($ImportFileSuccess)
{
    echo '<div class="goodAction">'.$this->lang->line('attach_uploadsuccess').'</div>';
}

if($AttachmentDeleted)
{
    echo '<div class="goodAction">'.$this->lang->line('attach_wasdeleted2').'</div>';
}

echo form_open_multipart('ioattachment/getattachement/'.$MessageId);
echo $this->lang->line('attach_filesare').' <br /> '.form_upload(array('name' => 'uploadfileattachement', 'id' => 'uploadfileattachement')).'<br />';
echo form_error('uploadfileattachement','<div class="error">', '</div>');
echo '<br />';
echo form_hidden('addfile','yes');
echo form_submit('zaloguj', $this->lang->line('attach_uploadsubmit'));
echo form_close();

?>
<br />
<h2 style="font-size: 15px;"><?php echo $this->lang->line('attach_addedh3'); ?></h2>

<?php

echo $TableContentAttachment;

?>