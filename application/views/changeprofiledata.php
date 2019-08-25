<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 10-7-2015 20:4
 *
 */

echo '<h1><img src="'.base_url('library/hprofile.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$SystemHead.'</h1>';

echo '<div class="BorderDiv">';

echo '<p>'.$this->lang->line('mailing_5vbrief').'</p>';

if($ProfileUpdated == true)
{
    echo '<div class="goodAction">'.$this->lang->line('mailing_5vprofilupdated').'</div>';
}

echo form_open('profile');

echo $this->lang->line('mailing_5vstreet').' <br /> '.form_input(array('name' => 'user_street', 'id' => 'user_street', 'style' => 'width:100%',  'value' => $Fuser_street)).'<br />';
echo form_error('user_street','<div class="error">', '</div>');

echo '<br />'.$this->lang->line('mailing_5vcity').' <br /> '.form_input(array('name' => 'user_city', 'id' => 'user_city', 'style' => 'width:100%',  'value' => $Fuser_city)).'<br />';
echo form_error('user_city','<div class="error">', '</div>');

echo '<br />'.$this->lang->line('mailing_5vpostcode').' <br /> '.form_input(array('name' => 'user_code', 'id' => 'user_code', 'style' => 'width:100%',  'value' => $Fuser_code)).'<br />';
echo form_error('user_code','<div class="error">', '</div>');

echo '<br />'.$this->lang->line('mailing_5vfirmname').' <br /> '.form_input(array('name' => 'user_firm_name', 'id' => 'user_firm_name', 'style' => 'width:100%',  'value' => $Fuser_firm_name)).'<br />';
echo form_error('user_firm_name','<div class="error">', '</div>');

echo '<br />'.$this->lang->line('mailing_5vnip').' <br /> '.form_input(array('name' => 'user_nip', 'id' => 'user_nip', 'style' => 'width:100%',  'value' => $Fuser_nip)).'<br />';
echo form_error('user_nip','<div class="error">', '</div>');

echo '<br />'.$this->lang->line('mailing_5vnameandsurname').' <br /> '.form_input(array('name' => 'user_name_and_lastname', 'id' => 'user_name_and_lastname', 'style' => 'width:100%',  'value' => $Fuser_name_and_lastname)).'<br />';
echo form_error('user_name_and_lastname','<div class="error">', '</div>');

echo '<br />'.$this->lang->line('mailing_5vtelephone').' <br /> '.form_input(array('name' => 'user_tel', 'id' => 'user_tel', 'style' => 'width:100%',  'value' => $Fuser_tel)).'<br />';
echo form_error('user_tel','<div class="error">', '</div>');

echo '<br />'.$this->lang->line('mailing_5vfax').' <br /> '.form_input(array('name' => 'user_fax', 'id' => 'user_fax', 'style' => 'width:100%',  'value' => $Fuser_fax)).'<br />';
echo form_error('user_fax','<div class="error">', '</div>');

echo '<br />';
    
echo form_hidden('formlogin','yes');
$SubmitButton = array('name' => 'register_school', 'value' => $this->lang->line('mailing_5vsubmitbutton'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);

echo form_close();

echo '</div>';

?>