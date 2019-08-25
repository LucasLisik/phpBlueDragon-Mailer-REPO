<?php

echo '<h1><img src="'.base_url('library/hviewgroup.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('groupv_browsegrouph9').' '.$GroupName.'</h1>';

if($EmailDeleted)
{
    echo '<div class="goodAction">'.$this->lang->line('groupv_emaildeleted9').'</div>';	
}

echo '<a href="'.base_url('add-email/'.$IdOfContent).'" class="AddButton"><img src="'.base_url('library/emailadd.png').'" width="24" height="24" style="vertical-align: middle"> '.$this->lang->line('groupv_addemail9').'</a>';

echo '<div class="BorderDiv">';

echo '<div class="pageBreak">'.$this->pagination->create_links().'</div>';

echo $TableContent;

echo '<div class="pageBreak">'.$this->pagination->create_links().'</div>';

echo '</div>';

?>