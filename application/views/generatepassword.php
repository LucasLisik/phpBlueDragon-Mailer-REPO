<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 10-7-2015 20:43
 *
 */

echo '<h1 style="text-align: center; margin-top: 100px;"><img src="'.base_url('library/hpassword.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$SystemHead.'</h1>';

echo '<p style="text-align: center;">'.$this->lang->line('mailing_vrecinsert9').'</p>';

if($bad_data == TRUE)
{
	echo '<div class="badAction" style="text-align: center;">'.$this->lang->line('mailing_vnouser9').'</div>';
}

if($pswd_send == TRUE)
{
	echo '<div class="goodAction" style="text-align: center;">'.$this->lang->line('mailing_vsend9').'</div>';
}

echo form_open('remind-password');
echo '<div style="margin-right: auto; margin-left: auto; width: 400px;">';
echo $this->lang->line('mailing_vemail9').' <br /> '.form_input(array('name' => 'user_email', 'id' => 'user_email', 'style' => 'width:400px')).'<br />';
echo form_error('user_email','<div class="error">', '</div>');

echo '<br />';

$ValuesOfImage = array(
    'word' => $RandomString,
    'img_path' => './captcha/',
    'img_url' => base_url().'/captcha/',
    'font_path' => 'arial.ttf',
    'img_width' => 200,
    'img_height' => 30
    );

$CaptchaFile = create_captcha($ValuesOfImage);
echo '<div style="text-align: center;">'.$CaptchaFile['image'].'</div>';

echo '<br />';

echo $this->lang->line('mailing_vcaptchaw9').'<br /> '.form_input(array('name' => 'user_captcha', 'id' => 'user_captcha', 'style' => 'width:400px')).'<br />';
echo form_error('user_captcha','<div class="error">', '</div>');

echo '<br />';

echo form_hidden('formlogin','yes');
echo '<div style="text-align: center;">'.form_submit('zaloguj', $this->lang->line('mailing_vsendbuttompass9')).'</div>';
echo form_close();
echo '</div>';

?>