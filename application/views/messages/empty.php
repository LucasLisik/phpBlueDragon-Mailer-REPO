<?php

/**
 *
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://lukasz.sos.pl
 * @copyright: 27-10-2015 21:13
 */

if($NewMessage == 'new')
{
    echo '<h1><img src="'.base_url('library/hnewmessage.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('msg_newmsgh').'</h1>';
}
else
{
    echo '<h1><img src="'.base_url('library/heditmessage.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('msg_editmsgh').'</h1>';
}

if($ErrorIs1 != "")
{
    echo '<div class="badAction">'.$ErrorIs1.'</div>';
}

if($ErrorIs2 != "")
{
    echo '<div class="badAction">'.$ErrorIs2.'</div>';
}

?>