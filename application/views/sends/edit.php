<?php

echo '<h1><img src="'.base_url('library/haccountedit.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('sends_edit4').'</h1>';

echo '<div class="BorderDiv">';

if($GroupEdited)
{
    echo '<div class="goodAction">'.$this->lang->line('sends_edited4').'</div>';	
}

echo form_open('edit-send/'.$IdOfElement);
echo $this->lang->line('sends_name4').' '.form_input(array('name' => 'send_name', 'id' => 'send_name', 'style' => 'width:100%', 'value' => $Vsend_name)).'<br />';
echo form_error('send_name','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sends_brief4').' <br /> '.form_input(array('name' => 'send_name_account', 'id' => 'send_name_account', 'style' => 'width:100%', 'value' => $Vsend_name_account)).'<br />';
echo form_error('send_name_account','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sends_organization4').' <br /> '.form_input(array('name' => 'send_organization', 'id' => 'send_organization', 'style' => 'width:100%', 'value' => $Vsend_organization)).'<br />';
echo form_error('send_organization','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sends_from4').'  '.form_input(array('name' => 'send_from', 'id' => 'send_from', 'style' => 'width:100%', 'value' => $Vsend_from)).'<br />';
echo form_error('send_from','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sends_answer4').' '.form_input(array('name' => 'send_reply', 'id' => 'send_reply', 'style' => 'width:100%', 'value' => $Vsend_reply)).'<br />';
echo form_error('send_reply','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sends_smtp4').' <br /> '.form_input(array('name' => 'send_smtp_serwer', 'id' => 'send_smtp_serwer', 'style' => 'width:100%', 'value' => $Vsend_smtp_serwer)).'<br />';
echo form_error('send_smtp_serwer','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sends_port4').' <br /> '.form_input(array('name' => 'send_port', 'id' => 'send_port', 'style' => 'width:100%', 'value' => $Vsend_port)).'<br />';
echo form_error('send_port','<div class="error">', '</div>');
if($Vsend_auth == 'y')
{
	$CheckedAuth = true;
}
echo '<br />'.$this->lang->line('sends_auth4').' <br /> '.form_checkbox('send_auth', 'y', $CheckedAuth).'<br />';
echo form_error('send_auth','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sends_login4').' <br /> '.form_input(array('name' => 'send_login', 'id' => 'send_login', 'style' => 'width:100%', 'value' => $Vsend_login)).'<br />';
echo form_error('send_login','<div class="error">', '</div>');
echo '<br />'.$this->lang->line('sends_pass4').' <br /> '.form_input(array('name' => 'send_pswd', 'id' => 'send_pswd', 'style' => 'width:100%', 'value' => $Vsend_pswd)).'<br />';
echo form_error('send_pswd','<div class="error">', '</div>');

echo '<br />'.$this->lang->line('sends4_accessmethos').' <br /> ';
    
$SetOption1 = null;
$SetOption2 = null;

if($Vsend_access == ""){$SetOption1 = true;}
if($Vsend_access == "text"){$SetOption1 = true;}
if($Vsend_access == "tls"){$SetOption2 = true;}

echo form_radio('send_access', 'text', $SetOption1).' '.$this->lang->line('sends4_cleantext');
echo form_radio('send_access', 'tls', $SetOption2).' '.$this->lang->line('sends4_tls');
echo '<br />';
    

$OptionsBreak[1] = 1;
$OptionsBreak[5] = 5;
$OptionsBreak[10] = 10;
$OptionsBreak[25] = 25;
$OptionsBreak[50] = 50;
$OptionsBreak[75] = 75;
$OptionsBreak[100] = 100;
$OptionsBreak[150] = 150;
$OptionsBreak[200] = 200;
$OptionsBreak[250] = 250;
$OptionsBreak[300] = 300;
$OptionsBreak[350] = 350;
$OptionsBreak[400] = 400;
$OptionsBreak[500] = 500;
$OptionsBreak[600] = 600;
$OptionsBreak[700] = 700;
$OptionsBreak[800] = 800;
$OptionsBreak[900] = 900;
$OptionsBreak[1000] = 1000;

$ExtraOptions = 'style = "width: 100%"';

if($Vsend_break_every == "")
{
	$Vsend_break_every = 25;
}

echo '<br />'.$this->lang->line('sends_break4').' <br /> '.form_dropdown('send_break_every', $OptionsBreak, $Vsend_break_every, $ExtraOptions).'<br />';
echo form_error('send_break_every','<div class="error">', '</div>');

$OptionsBreakTime[0] = $this->lang->line('sends_0');
$OptionsBreakTime[5] = $this->lang->line('sends_5');
$OptionsBreakTime[10] = $this->lang->line('sends_10');
$OptionsBreakTime[15] = $this->lang->line('sends_15');
$OptionsBreakTime[30] = $this->lang->line('sends_30');
$OptionsBreakTime[60] = $this->lang->line('sends_60');
$OptionsBreakTime[120] = $this->lang->line('sends_120');
$OptionsBreakTime[180] = $this->lang->line('sends_180');
$OptionsBreakTime[240] = $this->lang->line('sends_240');
$OptionsBreakTime[300] = $this->lang->line('sends_300');
$OptionsBreakTime[360] = $this->lang->line('sends_360');
$OptionsBreakTime[420] = $this->lang->line('sends_420');
$OptionsBreakTime[480] = $this->lang->line('sends_480');
$OptionsBreakTime[540] = $this->lang->line('sends_540');
$OptionsBreakTime[600] = $this->lang->line('sends_600');
$OptionsBreakTime[900] = $this->lang->line('sends_900');
$OptionsBreakTime[1800] = $this->lang->line('sends_1800');
$OptionsBreakTime[3600] = $this->lang->line('sends_3600');

if($Vsend_break_time == "")
{
		$Vsend_break_time = 5;
}
echo '<br />'.$this->lang->line('sends_timebreak4').' <br /> '.form_dropdown('send_break_time', $OptionsBreakTime, $Vsend_break_time, $ExtraOptions).'<br />';
echo form_error('send_break_time','<div class="error">', '</div>');

?>
    <div id="dialogCheckConfiguration" title="<?php echo $this->lang->line('sends4_checkconfigtitle'); ?>">  
    </div>
    <div style="text-align: right;">
    <br />
    <input  type="button" id="openerCheckConfiguration" value="<?php echo $this->lang->line('sends4_checkconfiguration'); ?>" class="support" style="width: 30%;" />
    </div>
<?php
    
echo '<br />';
echo form_hidden('formsubmit','yes');
$SubmitButton = array('name' => 'zaloguj', 'value' => $this->lang->line('sends_updateaccount4'), 'style' => 'width: 100%;');
echo form_submit($SubmitButton);
echo form_close();
echo '</div>';
    
?>
<script>
<?php
if($CheckedAuth == true)
{
	?>
	$("#send_login").prop( "disabled", false );
	$("#send_pswd").prop( "disabled", false );
	<?php
}
else
{
	?>
	$("#send_login").prop( "disabled", true );
	$("#send_pswd").prop( "disabled", true );
	<?php
}
?>
		
$("input[name='send_auth']").click(function() 
{
    if($(this).is(':checked')) 
	{
        $("#send_login").prop( "disabled", false );
		$("#send_pswd").prop( "disabled", false );
    } 
	else 
	{
        $("#send_login").prop( "disabled", true );
		$("#send_pswd").prop( "disabled", true );
    }
}); 
</script>
