<?php
/*
 * process.php
 *
 * PHP Toolkit for PayPal v0.51
 * http://www.paypal.com/pdn
 *
 * Copyright (c) 2004 PayPal Inc
 *
 * Released under Common Public License 1.0
 * http://opensource.org/licenses/cpl.php
 *
 */
header('Content-type: text/html; charset=utf-8');
//Configuration File
include_once('includes/config.inc.php');

//Global Configuration File
include_once('includes/global_config.inc.php');

//include_once('../admin/connDB.php');
//include_once('../admin/CLOUD_libreria.php');

?>

<html>
<head><title>::AREA CLIENTI CEMA NEXT - PayPal::</title></head>
<body onLoad="document.paypal_form.submit();">
<form method="post" name="paypal_form" action="<?=$paypal[url]?>">

<?php
//show paypal hidden variables

showVariables();

?>

<center><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="333333">In Processo . . . </font></center>

</form>
</body>
</html>
