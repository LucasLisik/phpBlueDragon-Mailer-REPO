<?php

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 15-9-2015 14:45
 *
 */
 
echo '<h1><img src="'.base_url('library/hoptions.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('mailing_9voptions').'</h1>';

echo '<div class="BorderDiv">';
echo validation_errors('<div class="badAction">','</div>');

if($content_added == true)
{
    echo '<div class="goodAction">'.$this->lang->line('mailing_9vconfigupdated').'</div>';
}

echo form_open('options');

echo $this->lang->line('mailing_9vtitle').' <br />'.form_input(array('name' => 'title', 'id' => 'title', 'style' => 'width:100%', 'value' => $Ctitle)).'<br /><br />';
echo $this->lang->line('mailing_9vbierf').' <br />'.form_input(array('name' => 'description', 'id' => 'description', 'style' => 'width:100%', 'value' => $Cdescription)).'<br /><br />';
echo $this->lang->line('mailing_9vkeywords').' <br />'.form_input(array('name' => 'keywords', 'id' => 'keywords', 'style' => 'width:100%', 'value' => $Ckeywords)).'<br /><br />';
echo $this->lang->line('mailing_9vemail').' <br />'.form_input(array('name' => 'root_email', 'id' => 'root_email', 'style' => 'width:100%', 'value' => $Croot_email)).'<br /><br />';
//echo 'Stopka: <br />'.form_input(array('name' => 'foot', 'id' => 'foot', 'style' => 'width:100%', 'value' => $Cfoot)).'<br /><br />';

for($i=1;$i<21;$i++)
{
    $Options[$i] = $i;
}

$ExtraConfig = 'style = "width: 100%;"';

echo $this->lang->line('mailing_9vcron').'<br />'.form_dropdown('cron_howmany', $Options, $Ccron_howmany, $ExtraConfig).'<br /><br />';

echo form_hidden('addpage','yes');
$SubmitButton = array('name' => 'add', 'value' => $this->lang->line('mailing_9vsubmitbutton'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);
echo form_close();
echo '</div>';

?>