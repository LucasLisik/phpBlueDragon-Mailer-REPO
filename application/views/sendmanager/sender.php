<?php

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 8-9-2015 13:17
 *
 */
 
echo '<h1>'.$this->lang->line('sendmanager_2consoletitle').'</h1>';

if($MessageWasDeleted)
{
    echo '<div class="goodAction">'.$this->lang->line('sendmanager_3messagewasdeleted').'</div>';
}

echo '<div class="BorderDiv">';
echo $TableContent;
echo '</div>';

//echo $TableJS;

?>
    
<div style="width: 904px; background-color: #FF9C2A; font-size: 16px; margin: 10px; margin-bottom: 0px; border-top-right-radius: 5px; border-top-left-radius: 5px;"><div style="padding:  5px; font-weight: bold; color: #ffffff;"><?php echo $this->lang->line('sendmanager_consolesend'); ?></div></div>
<div id="ObjectSender" style="width: 900px; height: 350px; overflow: auto; background-color: #000000; color: #ffffff; margin: 10px; margin-top: 0px; border: solid 2px #FF9C2A; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;"></div>

<?php
/*

Sprawdzanie czy jest jakiś mailing
	Brak mailingu - przerwij
	Jest przynajmniej jeden mailing zacznik
		Wysyłanie maila za pomocą Sender
		W zależności od odpowiedni generowanie odpowiedniego komunikatu w oknie



*/
?>

<script>

var ManyMessagesIsToRoute = <?php echo $HowManyMessagesToSend; ?>;

var TimeBreak;
var TimeBreakShow;

var myInterval;

var DivNameWithCount = 1;

function LoadMailingFile()
{	
    $.get("<?php echo base_url('iosendmanager/makesend'); ?>", function(response) 
    {
		var logfile = response;
		
		if(logfile == "")
		{
			//$("#ObjectSender").append("<div style=\"padding: 2px; padding-left: 8px;\" id=\"a\">Pusty</div>");
		}
		else if(logfile == "[send_end]")
		{
			//
			// Koniec mailingu
			//
			logfile = "<strong><?php echo $this->lang->line('sendmanager_5sendwasend'); ?></strong>";
			$("#ObjectSender").append("<div style=\"padding: 2px; padding-left: 8px;\">" + logfile + "</div>");
			$('#ObjectSender').scrollTop($('#ObjectSender')[0].scrollHeight);
			
				if(ManyMessagesIsToRoute > 1)
				{
					location.reload();
				}
		}
		else if(logfile.indexOf("[break_time]") > -1)
		{
			//
			// Przerwa w mailingu
			//
			//$("#ObjectSender").append("<div style=\"padding: 2px; padding-left: 8px;\">" + logfile + "</div>");
			var TableResultLogFile = logfile.split("|"); 
			TimeBreak = TableResultLogFile[1];
			TimeBreakShow = TableResultLogFile[1];
			DivNameWithCount++;
			$("#ObjectSender").append("<div style=\"padding: 2px; padding-left: 8px;\" id=\"CountLayer" + DivNameWithCount + "\"></div>");
			$('#ObjectSender').scrollTop($('#ObjectSender')[0].scrollHeight);
			myInterval = setInterval(function(){ setBreakInMailing() }, 1000);
		}
		else
		{
			$("#ObjectSender").append("<div style=\"padding: 2px; padding-left: 8px;\">" + logfile + "</div>");
			$('#ObjectSender').scrollTop($('#ObjectSender')[0].scrollHeight);
			LoadMailingFile();
		}
	});
}

function setBreakInMailing() 
{
    if(TimeBreak > 0)
    { 
        $("#CountLayer" + DivNameWithCount).html("<?php echo $this->lang->line('sendmanager_6timebreak1'); ?>" + TimeBreakShow.toString() + "<?php echo $this->lang->line('sendmanager_6timebreak2'); ?>" + TimeBreak.toString());
        TimeBreak--;
    }
    else
    {
        LoadMailingFile();
        clearInterval(myInterval);
    }
}

LoadMailingFile();

</script>