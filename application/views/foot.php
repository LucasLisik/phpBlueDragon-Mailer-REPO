<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 19-8-2015 20:07
 *
 */

/*
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
*/
?>
</div>
</div>
<?php
    
$ResultDB = $this->System_model->GetSystemConfig();

foreach($ResultDB->result() as $row)
{
	$ConfigTable[$row->config_name] = $row->config_value;
}

?>
<br /><br />
<div style="width: 100%;">
<div style="width: 980px; margin-left: auto; margin-right: auto; padding: 2px; text-align: left; color: #827F7F !important; font-size: 14px !important;">
<?php
//echo $ConfigTable['foot']; 
?>
Copyright &copy; 2015-<?php echo date('Y'); ?><br />
<a href="http://phpbluedragon.eu" style="color: #827F7F;" target="_blank">phpBlueDragon</a><br />
<a href="http://en.lukasz.sos.pl" style="color: #827F7F;" target="_blank">Lukas SOS</a>
</div>
</div>
</body>
</html>