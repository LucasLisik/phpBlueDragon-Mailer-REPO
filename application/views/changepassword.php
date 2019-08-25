<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 10-7-2015 19:7
 *
 */
 
echo '<h1><img src="'.base_url('library/hpassword.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$SystemHead.'</h1>';

echo '<div class="BorderDiv">';

echo '<p>'.$this->lang->line('mailing_4vbrief').'</p>';

echo form_open('change-password');

if($PswdChangedError == true)
{
    echo '<div class="badAction">'.$this->lang->line('mailing_4vbadpass').'</div>';    
}

if($PswdChanged == true)
{
    echo '<div class="goodAction">'.$this->lang->line('mailing_4vpasschanged').'</div>';
}

echo '<br />'.$this->lang->line('mailing_4fold').'<br />'.form_password(array('name' => 'user_pswd', 'id' => 'user_pswd', 'style' => 'width:100%')).'<br />';
echo form_error('user_pswd','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('mailing_4fnew').'<br />'.form_password(array('name' => 'user_pswd2', 'id' => 'user_pswd2', 'style' => 'width:100%')).'<br />';
echo form_error('user_pswd2','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('mailing_4fnew2').'<br />'.form_password(array('name' => 'user_pswd3', 'id' => 'user_pswd3', 'style' => 'width:100%')).'<br />';
echo form_error('user_pswd3','<div class="error">', '</div>');

echo '<br />';

echo form_hidden('formchange','yes');
$SubmitButton = array('name' => 'changepassword', 'value' => $this->lang->line('mailing_4fsubmitbutton'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);
echo form_close();
echo '</div>';

?>