<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 19-8-2015 20:07
 *
 */

echo '<h1 style="text-align: center; margin-top: 100px;"><img src="'.base_url('library/hlogin.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$SystemHead.'</h1>';

if($bad_data3 == true)
{
    echo '<div class="badAction" style="text-align: center;">'.$this->lang->line('mailing_ban5').'</div>';	
}

if($bad_data2 == true)
{
    echo '<div class="badAction" style="text-align: center;">'.$this->lang->line('mailing_logcom5').'<a href="'.base_url('contact').'">'.$this->lang->line('mailing_logcom25').'</a>.</div>';	
}

if($bad_data == TRUE)
{
	echo '<div class="badAction" style="text-align: center;">'.$this->lang->line('mailing_baddata5').'</div>';	
}

echo form_open('login');
echo '<div style="margin-right: auto; margin-left: auto; width: 400px;">';
echo $this->lang->line('mailing_femail5').' <br /> '.form_input(array('name' => 'user_email', 'id' => 'user_email', 'style' => 'width:400px')).'<br />';
echo form_error('user_email','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('mailing_fpass5').' <br /> '.form_password(array('name' => 'user_password', 'id' => 'user_password', 'style' => 'width:400px')).' <br />';
echo form_error('user_password','<div class="error">', '</div>');
echo '<br />';
echo form_hidden('formlogin','yes');
echo '<div style="text-align: center;">'.form_submit('zaloguj', $this->lang->line('mailing_fsubmitbutton5')).'</div>';
echo form_close();
echo '<br /><div style="text-align: center;"><a href="'.base_url('remind-password').'">'.$this->lang->line('mailing_recpass5').'</a></div>';
echo '</div>';

?>