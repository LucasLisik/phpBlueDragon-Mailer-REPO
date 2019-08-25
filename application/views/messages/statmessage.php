<h1><?php echo '<img src="'.base_url('library/hstats.png').'" width="32" height="32" style="vertical-align: middle">'; ?> <?php echo $this->lang->line('msg_4statistics'); ?></h1>
<?php

echo '<div class="BorderDiv">';
echo '<div class="pageBreak">'.$this->pagination->create_links().'</div>';

echo $TableContent;

echo '<div class="pageBreak">'.$this->pagination->create_links().'</div>';
echo '</div>';

?>