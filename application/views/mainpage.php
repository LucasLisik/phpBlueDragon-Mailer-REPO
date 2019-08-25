<h1><?php echo '<img src="'.base_url('library/hmainpage.png').'" width="32" height="32" style="vertical-align: middle">'; ?> <?php echo $SystemHead; ?></h1>
<?php

if($ErrorIs1 != '')
{
    echo '<div class="errorStartup">'.$ErrorIs1.'</div>';
}

if($ErrorIs2 != '')
{
    echo '<div class="errorStartup">'.$ErrorIs2.'</div>';
}

echo '<div style="clear: both;"></div>';

echo '<div>';

echo '<div style="float: left; width: 320px;" class="BorderDiv">';
    echo '<div class="LogsHead">'.$this->lang->line('mailing_a1insystemis').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsMain1">'.$IsDrafts.'</div><div class="LogsMain2">'.$this->lang->line('mailing_a1drafts').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsMain1">'.$IsSended.'</div><div class="LogsMain2">'.$this->lang->line('mailing_a1send').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsMain1">'.$IsAttach.'</div><div class="LogsMain2">'.$this->lang->line('mailing_a1attach').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<br />';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsHead">'.$this->lang->line('mailing_a1groupsandaddresses').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsMain1">'.$IsGroups.'</div><div class="LogsMain2">'.$this->lang->line('mailing_a1groupsis2').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsMain1">'.$IsEmail.'</div><div class="LogsMain2">'.$this->lang->line('mailing_a1emailadd4').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<br />';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsHead">'.$this->lang->line('mailing_a1acc7').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsMain1">'.$IsSends.'</div><div class="LogsMain2">'.$this->lang->line('mailing_a1acc8').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<br />';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsHead">'.$this->lang->line('mailing_a1other').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsMain1">'.$IsExclusions.'</div><div class="LogsMain2">'.$this->lang->line('mailing_a1otheraddress').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsMain1">'.$IsLogs.'</div><div class="LogsMain2">'.$this->lang->line('mailing_a1logs').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsMain1">'.$IsSign.'</div><div class="LogsMain2">'.$this->lang->line('mailing_a1signs').'</div>';
    echo '<div style="clear: both;"></div>';
    echo '<div class="LogsMain1">'.$IsUser.'</div><div class="LogsMain2">'.$this->lang->line('mailing_a1users').'</div>';
echo '</div>';

echo '<div style="float: left; width: 10px; text-align: right;">';
echo '&nbsp;';
echo '</div>';

echo '<div style="float: left; width: 590px; text-align: right;" class="BorderDiv">';

echo '<input type="button" value="'.$this->lang->line('mailing_mmnewmessage').'" style="width: 570px; margin-bottom: 10px;" onClick="window.location=\''.base_url('new-message').'\'" />';
echo '<div style="clear: both;"></div>';
echo '<input type="button" value="'.$this->lang->line('mailing_mmgroups').'" style="width: 570px; margin-bottom: 10px;" onClick="window.location=\''.base_url('groups').'\'" />';
echo '<div style="clear: both;"></div>';
echo '<input type="button" value="'.$this->lang->line('mailing_mmext').'" style="width: 570px; margin-bottom: 10px;" onClick="window.location=\''.base_url('exclusion').'\'" />';
echo '<div style="clear: both;"></div>';
echo '<input type="button" value="'.$this->lang->line('mailing_mmaccounts').'" style="width: 570px; margin-bottom: 10px;" onClick="window.location=\''.base_url('sends').'\'" />';
echo '<div style="clear: both;"></div>';
echo '<input type="button" value="'.$this->lang->line('mailing_mmconfig').'" style="width: 570px; margin-bottom: 10px;" onClick="window.location=\''.base_url('options').'\'" />';
echo '<div style="clear: both;"></div>';
echo '<input type="button" value="'.$this->lang->line('mailing_mmchangepassword').'" style="width: 570px; margin-bottom: 10px;" onClick="window.location=\''.base_url('change-password').'\'" />';
echo '<div style="clear: both;"></div>';
echo '<input type="button" value="'.$this->lang->line('mailing_mmabout').'" style="width: 570px; margin-bottom: 10px;" onClick="window.location=\''.base_url('about').'\'" />';
echo '<div style="clear: both;"></div>';

echo '</div>';

echo '</div>';

echo '<div style="clear: both;"></div>';

?>