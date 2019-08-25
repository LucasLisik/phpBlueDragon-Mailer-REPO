<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 4-9-2015 12:25
 *
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $this->lang->line('main_1popuptitle'); ?></title>
  	<meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>library/style.css" />
    <script language="JavaScript">
    function DeteleInfo(URL,Comunicate)
    {
    	if(confirm(Comunicate))
    	{
    		window.location = URL;
    	}
    }
    </script>
</head>
<body>