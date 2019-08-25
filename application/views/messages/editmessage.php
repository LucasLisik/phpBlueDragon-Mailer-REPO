<?php

if($NewMessage == 'new')
{
    echo '<h1><img src="'.base_url('library/hnewmessage.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('msg_newmsgh').'</h1>';
}
else
{
    echo '<h1><img src="'.base_url('library/heditmessage.png').'" width="32" height="32" style="vertical-align: middle;" /> '.$this->lang->line('msg_editmsgh').'</h1>';
}

if($MessageAdded)
{
    if($NewMessage == 'new')
    {
        echo '<div class="goodAction">'.$this->lang->line('msg_wasadded2').'</div>';
    }
    else
    {
        echo '<div class="goodAction">'.$this->lang->line('msg_wasupdated2').'</div>';
    }	
}

$ConfigTableTo = $this->Messages_model->MessageTo();
$ConfigTableFrom = $this->Messages_model->MessageFrom();

$ConfigTableSign = $this->Messages_model->MessageSign();		
$ConfigTableSign[0] = $this->lang->line('msg_nosign2');

echo form_open('edit-message/'.$MessageEditId, array('id' => 'send_message_form'));

?>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1"><?php echo $this->lang->line('msg_fhtml'); ?></a></li>
    <li><a href="#tabs-2"><?php echo $this->lang->line('msg_ftext'); ?></a></li>
    <li><a href="#tabs-3"><?php echo $this->lang->line('msg_sendtest2'); ?></a></li>
  </ul>
  <div id="tabs-1" class="FullWidth">
    <?php

    echo $this->lang->line('msg_fto').' <br /> '.form_dropdown('message_to', $ConfigTableTo, $Vmessage_to).'<br />';
    echo form_error('message_to','<div class="error">', '</div>');
    echo '<br />'.$this->lang->line('msg_ffrom').' <br /> '.form_dropdown('message_from', $ConfigTableFrom, $Vmessage_from).'<br />';
    echo form_error('message_from','<div class="error">', '</div>');
    echo '<br />'.$this->lang->line('msg_ftopic').' <br /> '.form_input(array('name' => 'message_title', 'id' => 'message_title', 'style' => 'width:100%', 'value' => $Vmessage_title)).'<br />';
    echo form_error('message_title','<div class="error">', '</div>');
    echo '<br />'.$this->lang->line('msg_fcontent').' <br /> '.form_textarea(array('name' => 'message_html', 'id' => 'message_html', 'style' => 'width:100%; height: 450px;', 'value' => $Vmessage_html)).'<br />';
    echo form_error('message_html','<div class="error">', '</div>');
    echo '<br />'.$this->lang->line('msg_fsign3').' <br /> '.form_dropdown('message_sign', $ConfigTableSign, $Vmessage_sign).'<br />';
    echo form_error('message_to','<div class="error">', '</div>');
    echo '<br />';
    
    ?>
    <div id="dialogAttachment" title="Add attachment">
      <iframe style="width: 100%; height: 100%; border: 0px;" src="<?php echo base_url('ioattachment/getattachement/'.$MessageEditId); ?>"></iframe>
    </div>
     
    <input  type="button" id="openerAttachment" value="<?php echo $this->lang->line('msg_addatach'); ?>" style="width: 100%;" />
  </div>
  <div id="tabs-2">
    <?php
    echo ''.$this->lang->line('msg_ftext4').' <br /> '.form_textarea(array('name' => 'message_text', 'id' => 'message_text', 'style' => 'width:100%; height: 450px;', 'value' => $Vmessage_text)).'<br />';
    echo form_error('message_text','<div class="error">', '</div>');
    ?>
  </div>
  <div id="tabs-3">
  	<div id="messageFromQuery"></div>
    <script>
        $(function() 
        {
            $("#sendtestbutton").click(function() 
            {
                $("#ObserverSendTest").empty();
                $('#ObserverSendTest').show();
                    
                $("#ObserverSendTest").append("<div style=\"background-color: #FF9C2A; padding: 10px; font-size: 16px;\"><?php echo $this->lang->line('sendmanager_consolesend'); ?></div>");
                $("#ObserverSendTest").append("<div style=\"padding: 10px;\"><?php echo $this->lang->line('msg_3waitisending'); ?></div>");
                
                $.get("<?php echo base_url('iosendmanager/makesendtest/'.$MessageEditId); ?>", { emailsendtest: $("#message_testsend").val() }, function(response) 
                {
                    $("#ObserverSendTest").empty();
                    $('#ObserverSendTest').show();
                    
                    var logfile = response;
                    $("#ObserverSendTest").append("<div style=\"background-color: #FF9C2A; padding: 10px; font-size: 16px;\"><?php echo $this->lang->line('sendmanager_consolesend'); ?></div>");
                    $("#ObserverSendTest").append("<div style=\"padding: 10px;\">" + logfile + "</div>");
                });
            });
        });
    </script>
  	<div id="messageSendForm">
  	<?php
    
  	$UserEmail = $this->Messages_model->SelectEmailUser();
    
	if($Vmessage_testsend == "")
	{
	   $Vmessage_testsend = $UserEmail;
	}
    
    echo '<br />'.$this->lang->line('sendmanager_2youhavetosavemsg').'<br />';
  	echo '<br />'.$this->lang->line('msg_3sendtestto').' <br /> '.form_input(array('name' => 'message_testsend', 'id' => 'message_testsend', 'style' => 'width:100%', 'value' => $Vmessage_testsend)).'<br /><br />';
  	echo '<input type="button" id="sendtestbutton" name="sendtestbutton" value="'.$this->lang->line('msg_3sendtestbutton').'" style="width: 100%;" />';
    
  	?>
	
    <div id="ObserverSendTest" style="background-color: #000000; color: #ffffff; margin: 10px; border: solid 2px #FF9C2A; border-radius: 5px;">
        
    </div>
		
  	</div>
    <script>
    
    $('#ObserverSendTest').hide();
    
    function CheckIsEmpty()
    {
	   $('#messageSendForm').hide();
	
    	if($('#message_to').is(':empty'))
    	{
    		$('#messageFromQuery').html("<div class=\"error\"><?php echo $this->lang->line('msg_3fieldtoempty'); ?></div>");
    	}
    	else
    	{
    		if($('#message_from').is(':empty'))
    		{
    			$('#messageFromQuery').html("<div class=\"error\"><?php echo $this->lang->line('msg_3fieldfromempty'); ?></div>");
    		}
    		else
    		{
    			if(!$('#message_title').val())
    			{
    				//alert($('#message_title').html());
    				$('#messageFromQuery').html("<div class=\"error\"><?php echo $this->lang->line('msg_3fieldtitleempty'); ?></div>");
    			}
    			else
    			{
    				if(!$('#message_html').val())
    				{
    					$('#messageFromQuery').html("<div class=\"error\"><?php echo $this->lang->line('msg_3fieldcontentempty'); ?></div>");
    				}
    				else
    				{
    					// Send
    					$('#messageFromQuery').empty();
    					$('#messageSendForm').show();
    				}			
    			}
    		}
    	}
    }
    </script>
  </div>
</div>

<?php

if($NewMessage == 'new')
{
    $SubmitButton = $this->lang->line('msg_addnewsubmit');
}
else
{
    $SubmitButton = $this->lang->line('msg_editmsgsubmit');    
}

echo form_hidden('formsubmit','yes');
$SubmitButton = array('name' => 'zaloguj', 'value' => $SubmitButton, 'style' => 'width: 100%;');
echo '<br />'.form_submit($SubmitButton);
echo '<br /><br />';
echo '<input type="button" id="message_sendnow" name="message_sendnow" value="'.$this->lang->line('msg_saveandsendsubmit').'" style="width: 100%;" />';
echo form_close();



?>
<script>
CKEDITOR.config.extraPlugins = 'strinsert';
CKEDITOR.config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools', 'strinsert' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];
CKEDITOR.config.removeButtons = 'Save,NewPage,Preview,Print,Templates';
CKEDITOR.replace('message_html' ,
{ 
    filebrowserBrowseUrl : '<?php echo base_url(); ?>library/filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=mess<?php echo $MessageEditId; ?>/', 
    filebrowserUploadUrl : '<?php echo base_url(); ?>library/filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=mess<?php echo $MessageEditId; ?>/', 
    filebrowserImageBrowseUrl : '<?php echo base_url(); ?>library/filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=mess<?php echo $MessageEditId; ?>/' 
});
CKEDITOR.config.height = 500;        // 500 pixels high.
CKEDITOR.config.skin = 'office2013';
</script>
<script>
$('#send_message_button_now').remove();
$("#message_sendnow").click(function() 
{
    //$("#formsubmit").append('<input type="text" name="send_message_button_now" value="yes" />');
    $('<input type="hidden" name="send_message_button_now" value="yes" />').appendTo("#send_message_form");
    //$('<input>').attr('type','text').appendTo('#send_message_form');
    $("#send_message_form").submit();
});
</script>
