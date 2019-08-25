<h1><?php echo '<img src="'.base_url('library/'.$FileName.'.png').'" width="32" height="32" style="vertical-align: middle">'; ?> <?php echo $SystemHead; ?></h1>

<?php

if($Type == 'draft')
{
	echo '<a href="'.base_url('new-message').'" class="AddButton"><img src="'.base_url('library/add.png').'" width="24" height="24" style="vertical-align: middle"> '.$this->lang->line('msg_new').'</a>';
}

if($MesageWasDeleted)
{
    echo '<div class="goodAction">'.$this->lang->line('msg_wasdeleted').'</div>';	
}

echo '<div class="BorderDiv">';
echo '<div class="pageBreak">'.$this->pagination->create_links().'</div>';

echo $TableContent;

echo '<div class="pageBreak">'.$this->pagination->create_links().'</div>';
echo '</div>';

?>