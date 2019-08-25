<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 7-9-2015 11:15
 *
 */
 
echo '<h1><img src="'.base_url('library/husers.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$SystemHead.'</h1>';

if($UserDeleted)
{
    echo '<div class="goodAction">'.$this->lang->line('mailing_6vuserdeleted').'</div>';
}

echo '<a href="'.base_url('adduser').'" class="AddButton"><img src="'.base_url('library/useradd.png').'" width="24" height="24" style="vertical-align: middle"> '.$this->lang->line('mailing_6vadduserbutton').'</a>';

echo '<div class="BorderDiv">';
echo $BodyText;
echo '</div>';

?>