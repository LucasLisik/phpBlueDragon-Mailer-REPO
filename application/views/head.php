<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 19-8-2015 20:08
 *
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    
    $ResultDB = $this->System_model->GetSystemConfig();

	foreach($ResultDB->result() as $row)
	{
		$ConfigTable[$row->config_name] = $row->config_value;
	}
    
    ?>
    <?php
    if($IsIndex)
    {
        echo '<title>'.$ConfigTable['title'].'</title>';
    }
    else
    {
        echo '<title>'.$SystemHead.' - '.$ConfigTable['title'].'</title>';
    }
  	?>
    <meta http-equiv="Description" content="<?php echo $ConfigTable['description']; ?>" />
    <meta http-equiv="Keywords" content="<?php echo $ConfigTable['keywords']; ?>" />
  	<meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>library/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>library/datetimepicker-master/jquery.datetimepicker.css" />
    <script src="<?php echo base_url(); ?>library/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>library/jquery.numeric.js"></script>
	<script src="<?php echo base_url(); ?>library/datetimepicker-master/jquery.datetimepicker.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>library/jquery-ui/jquery-ui.structure.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>library/jquery-ui/jquery-ui.theme.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>library/jquery-ui/jquery-ui.css" />
    <script src="<?php echo base_url(); ?>library/jquery-ui/jquery-ui.js"></script>
    <script>
    jQuery(document).ready(function() {
        jQuery('.tabs .tab-links a').on('click', function(e)  {
            var currentAttrValue = jQuery(this).attr('href');
     
            // Show/Hide Tabs
            jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
     
            // Change/remove current tab to active
            jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
     
            e.preventDefault();
        });
    });
    </script>
	<script type="text/javascript">
	$(document).ready(function() {
	// Tooltip only Text
	$('.masterTooltip').hover(function(){
		// Hover over code
		var title = $(this).attr('title');
		$(this).data('tipText', title).removeAttr('title');
		$('<p class="tooltip"></p>')
		.text(title)
		.appendTo('body')
		.fadeIn('slow');
	}, function() {
		// Hover out code
		$(this).attr('title', $(this).data('tipText'));
		$('.tooltip').remove();
	}).mousemove(function(e) {
		var mousex = e.pageX + 20; //Get X coordinates
		var mousey = e.pageY + 10; //Get Y coordinates
		$('.tooltip')
		.css({ top: mousey, left: mousex })
	});
	});
	</script>
    <script src="<?php echo base_url(); ?>library/ckeditor/ckeditor.js"></script>
    <link rel="Shortcut icon" href="<?php echo base_url('favicon.ico'); ?>" />
    <script language="JavaScript">
    function DeteleInfo(URL,Comunicate)
    {
    	if(confirm(Comunicate))
    	{
    		window.location = URL;
    	}
    }
    </script>
    <script>
    $(function() {
    $( "#tabs" ).tabs(
    {
        activate:function(event,ui)
        {                                                       
            if(ui.newPanel.attr('id') == "tabs-3")
            {
                CheckIsEmpty();
            }                                              
        }       
    }
    );
    });
    </script>
    <script>
    // Attachment
    $(function() 
    {
        $("#dialogAttachment").dialog(
        { 
        modal: true, 
        height: 520, 
        width: 700, 
        resizable: false, 
        autoOpen: false, 
        show: {effect: "blind", duration: 100}, 
        hide: {effect: "blind", duration: 100},
        buttons: {Ok: function() {$( this ).dialog( "close" );}}
        });
        
        $("#openerAttachment").click(function() {$("#dialogAttachment").dialog("open");});
    });
    
    // Configuration of SMTP account
    CheckVariable = false;
    CheckVariableFields = true;
    SendCheckVariable = "no";
    
    $(function() 
    {
        $("#dialogCheckConfiguration").dialog(
        { 
        modal: true, 
        height: 250, 
        width: 400, 
        resizable: false, 
        autoOpen: false, 
        show: {effect: "blind", duration: 100}, 
        hide: {effect: "blind", duration: 100},
        buttons: {Ok: function() {$( this ).dialog( "close" );}}
        });
        
        $("#openerCheckConfiguration").click(function()
        {            
            if($('#send_smtp_serwer').val() == "")
            {
                CheckVariableFields = false;
            }
            
            if($('#send_port').val() == "")
            {
                CheckVariableFields = false;
            }
            
            if($('[name="send_auth"]').is(':checked'))
            {                
                SendCheckVariable = "yes";
                
                if($('#send_login').val() == "")
                {
                    CheckVariableFields = false;
                }
                
                if($('#send_pswd').val() == "")
                {
                    CheckVariableFields = false;
                }
            }
            else
            {
                SendCheckVariable = "no";
            }
            
            CheckVariable = CheckVariableFields;
        
            if(CheckVariable == true)
            {
                //$("#dialogCheckConfiguration").dialog("open");
                    $.post("<?php echo base_url('smtpcheck.php'); ?>", { 
                        send_smtp_serwer: $('#send_smtp_serwer').val(), 
                        send_port: $('#send_port').val(),
                        send_auth: SendCheckVariable,
                        send_login: $('#send_login').val(),
                        send_pswd: $('#send_pswd').val(),
                        send_access: $('input[name=send_access]:checked').val()
                        })
                      .done(function( data ) 
                      {
                        if(data == "LOGIN")
                        {
                            alert("<?php echo $this->lang->line('sends4_error1'); ?>");
                        }
                        else if(data == "OK")
                        {
                            alert("<?php echo $this->lang->line('sends4_error2'); ?>");
                        }
                        else if(data == "NO")
                        {
                            alert("<?php echo $this->lang->line('sends4_error3'); ?>");
                        }
                        else
                        {
                            alert(data);
                        }
                        
                      });
            }
            else
            {
                alert("<?php echo $this->lang->line('sends4_nullfieldstotest'); ?>");
            }
        }
        );
    });
    
    
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>library/jqsimplemenu/js/jqsimplemenu.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>library/jqsimplemenu/css/jqsimplemenu.css" type="text/css" media="screen" />
    <script type="text/javascript">
    $(document).ready(function() {
        $('.menu').jqsimplemenu();
    });
    </script>
</head>
<body>
<?php
	if($_SESSION['user_id'] != "")
	{
	?>
	<div class="TopMenu" style="width: 100%; height: 40px;">
	    <div style="width: 980px; height: 40px; margin-left: auto; margin-right: auto; padding-top: 0px; text-align: left;">
	    
        <div style="padding-top: 5px;"> 
        <ul class="menu" style="text-align: left; z-index: 999;">
		<li style="padding-top: 0px; padding-bottom: 0px;"><a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('library/mailIcon.png'); ?>" width="27" style="padding-top: 0px; padding-bottom: 0px;" /></a></li>
	      <li style="padding-top: 5px; padding-bottom: 5px;"><a href="<?php echo base_url(); ?>" style="color: #666666;"><?php echo $this->lang->line('main_mainpage'); ?></a></li>
	      <li style="padding-top: 5px; padding-bottom: 5px;"><a href="<?php echo base_url('groups'); ?>" style="color: #666666;"><?php echo $this->lang->line('main_groups'); ?></a>
		  <ul>
		      <li><a href="<?php echo base_url('groups'); ?>"><?php echo $this->lang->line('main_groupsbrowse'); ?></a></li>
		      <li><a href="<?php echo base_url('export-group-wizard'); ?>"><?php echo $this->lang->line('main_groupsexport'); ?></a></li>
		      <li><a href="<?php echo base_url('import-group-wizard'); ?>"><?php echo $this->lang->line('main_groupsimport'); ?></a></li>
              <li><a href="<?php echo base_url('exclusion'); ?>"><?php echo $this->lang->line('main_groupexclusion'); ?></a></li>
		  </ul>
	      </li>
	      <li style="padding-top: 5px; padding-bottom: 5px;"><a href="<?php echo base_url('messages/draft'); ?>" style="color: #666666;"><?php echo $this->lang->line('main_msg'); ?></a>
		  <ul>
					<?php
					 echo '<li><a href="'.base_url('new-message').'">'.$this->lang->line('main_msgnew').'</a></li>';
					echo '<li><a href="'.base_url('messages/draft').'">'.$this->lang->line('main_msgdraft').'</a></li>';
					   echo '<li><a href="'.base_url('messages/send').'">'.$this->lang->line('main_msgsend').'</a></li>';
					   //echo '<li><a href="'.base_url('messages/time').'">Zaplanowane</a></li>';
				       echo '<li><a href="'.base_url('timetable').'">'.$this->lang->line('main_msgtime').'</a></li>';
				       ?>
			  </ul>
	      </li>
	      <li style="padding-top: 5px; padding-bottom: 5px;">
	      <?php echo '<a href="'.base_url('sends').'" style="color: #666666;">'.$this->lang->line('main_accounts').'</a>'; ?>
	      </li>
	      <li style="padding-top: 5px; padding-bottom: 5px;"><?php echo '<a href="'.base_url('sendmanager/sender').'" style="color: #666666;">'.$this->lang->line('main_console').'</a>'; ?></li>
	      <li style="padding-top: 5px; padding-bottom: 5px;"><a href="#" style="color: #666666;"><?php echo $this->lang->line('main_other'); ?></a>
		  <ul>
		      <?php echo '<li><a href="'.base_url('signatures').'">'.$this->lang->line('main_othersign').'</a></li>'; ?>
		      <?php echo '<li><a href="'.base_url('users').'">'.$this->lang->line('main_otherusers').'</a></li>'; ?>
		      <?php echo '<li><a href="'.base_url('options').'">'.$this->lang->line('main_otheroptions').'</a></li>'; ?>
		      <?php echo '<li><a href="'.base_url('logs').'">'.$this->lang->line('main_otherlogs').'</a></li>'; ?>
		  </ul>
	      </li>
	      <li style="padding-top: 5px; padding-bottom: 5px;"><a href="<?php echo base_url('profile'); ?>" style="color: #666666;"><?php echo $this->lang->line('main_profile'); ?></a>
			<ul>
			<li><?php echo '<a href="'.base_url('logout').'">'.$this->lang->line('main_profilelogout').'</a>'; ?></li>
			<li><?php echo '<a href="'.base_url('change-password').'">'.$this->lang->line('main_profilechangepass').'</a>'; ?></li>
			<li><?php echo '<a href="'.base_url('profile').'">'.$this->lang->line('main_profileprofile').'</a>'; ?></li>
			</ul>
	      </li>
	      <li style="padding-top: 5px; padding-bottom: 5px;"><?php echo '<a href="'.base_url('about').'" style="color: #666666;">'.$this->lang->line('main_help').'</a>'; ?>
		<ul>
		    <li><?php echo '<a href="'.base_url('about').'">'.$this->lang->line('main_helpabout').'</a>'; ?></li>
            <li><?php echo '<a href="'.base_url('about-rights').'">'.$this->lang->line('main_helpcopyright').'</a>'; ?></li>
		</ul>
	      </li>
	  </ul>
	    </div>
        
     </div>
	</div>

	<?php
    //<div style="clear: both;"></div>
    ?>

    <?php 
    //background-image: url('<?php echo base_url('library/view/top3.png'); ');
    ?>
	<div style="width: 100%; height: 30px; background-color: #E7E7E7; bnorder-top: solid 1px #D0D0D0; border-bottom: solid 1px #D0D0D0;">
	    <div style="width: 980px; height: 30px; margin-left: auto; margin-right: auto; font-weight: bold; padding-top: 7px; font-family: 'Trebuchet MS'; color: #555E5E;">
	    <?php
        echo $Location;
        //Program &gt; Wiadomości &gt; Nowa wiadomość
        //#4C4A49
        ?>
	    </div>
	</div>

	<?php
    /*
    <div style="clear: both;"></div>

	<div style="width: 100%; height: 4px; background-image: url('<?php echo base_url('library/view/bgtop.png'); ?>');">
	</div>
    */
    ?>
	<?php
	}
?>

<div style="width: 980px; margin-left: auto; margin-right: auto; padding-top: 10px;">

<div style="clear: both;"></div>
<div>

<?php
/*
if($this->System_model->CheckLicenseExistsNoAlert() == 'yes')
{
    $DontShow = true;
}

if(!$DontShow)
{
    ?><div class="goodAction" style="border: solid 1px #336699; padding: 5px; border-radius: 5px;"><?php echo $this->lang->line('licensewarning'); ?></div><?php
}
*/
?>