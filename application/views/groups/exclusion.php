<?php

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 22-9-2015 18:16
 */


echo '<h1><img src="'.base_url('library/hexclusion.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('groupv_exch5').'</h1>';

if($EmailDeleted)
{
    echo '<div class="goodAction">'.$this->lang->line('groupv_wasdeleted5').'</div>';	
}

echo '<a href="'.base_url('exclusion-add-email').'" class="AddButton"><img src="'.base_url('library/emailadd.png').'" width="24" height="24" style="vertical-align: middle"> '.$this->lang->line('groupv_addnew5').'</a>';

echo '<div class="BorderDiv">';

echo '<div class="pageBreak">'.$this->pagination->create_links().'</div>';

echo $TableContent;

echo '<div class="pageBreak">'.$this->pagination->create_links().'</div>';

echo '</div>';

?>