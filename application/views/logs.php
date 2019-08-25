<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 15-9-2015 15:12
 *
 */
 
echo '<h1><img src="'.base_url('library/hlogs.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$HeadText.'</h1>';

echo '<a href="'.base_url('logs/0/').'" class="AddButton"><img src="'.base_url('library/notlogin.png').'" width="24" height="24" style="vertical-align: middle"> '.$this->lang->line('mailing_10shownotloginuserlogs').'</a>';

echo '<div class="BorderDiv">';
echo '<div class="pageBreak">'.$this->pagination->create_links().'</div>';

echo $BodyText;

echo '<div class="pageBreak">'.$this->pagination->create_links().'</div>';
echo '</div>';

?>