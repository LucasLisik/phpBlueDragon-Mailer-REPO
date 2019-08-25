<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 10-7-2015 21:15
 *
 */

echo '<h1 style="text-align: center; margin-top: 100px;"><img src="'.base_url('library/hpassword.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$SystemHead.'</h1>';

if($change_password)
{
    echo '<div class="goodAction" style="text-align: center;">'.$this->lang->line('mailing_2passgenerated').'</div>';
}
else
{
    echo '<div class="badAction" style="text-align: center;">'.$this->lang->line('mailing_2badgeneration').'</div>';
}

?>