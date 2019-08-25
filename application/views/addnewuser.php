<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 7-9-2015 11:21
 *
 */
 
echo '<h1><img src="'.base_url('library/huseradd.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$SystemHead.'</h1>';

echo '<div class="BorderDiv">';
if($UserRegistered)
{
    echo '<div class="goodAction">'.$this->lang->line('mailing_8vuseradded').'<br />
    '.$this->lang->line('mailing_8vlogin').' <strong>'.$UserName.'</strong><br />
    '.$this->lang->line('mailing_8vpass').' <strong>'.$UserPassword.'</strong></div>';
}

echo form_open('adduser');

echo $this->lang->line('mailing_8flogin').' <br /> '.form_input(array('name' => 'user_email', 'id' => 'user_email', 'style' => 'width:100%',  'value' => $Fuser_email)).'<br />';
echo form_error('user_email','<div class="error">', '</div>');

echo '<br />'.$this->lang->line('mailing_8fnameandsurname').' <br /> '.form_input(array('name' => 'user_name_and_lastname', 'id' => 'user_name_and_lastname', 'style' => 'width:100%',  'value' => $Fuser_name_and_lastname)).'<br />';
echo form_error('user_name_and_lastname','<div class="error">', '</div>');

echo '<br />';

echo form_hidden('formlogin','yes');
$SubmitButton = array('name' => 'register_school', 'value' => $this->lang->line('mailing_8faddsubmitbutton'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);

echo form_close();
echo '</div>';

?>