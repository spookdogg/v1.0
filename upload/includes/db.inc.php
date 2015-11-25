<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2007 Jonathon Freeman                                 //
//  Copyright (c) 2007 Brian Otto                                            //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the MIT License.                                   //
//                                                                           //
//***************************************************************************//

	// Load the database information.
	require('./includes/dbinfo.inc.php');

	// Load the database driver.
	if(!@include("./includes/{$aDBInfo['type']}.inc.php"))
	{
		die('<span style="font-family: verdana, arial, helvetica, sans-serif; font-size: 13px;">Please upload a database driver!</span>');
	}

	// Create a new database connection.
	$dbConn = new DBConnection($aDBInfo);

	// Make sure we connected.
	if(!$dbConn->objConnection || !$dbConn->objSelect)
	{
		DatabaseError();
	}

// *************************************************************************** \\

// Tells the user when there's a problem with the database.
function DatabaseError()
{
	global $CFG, $dbConn, $strDBEMail;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="SHORTCUT ICON" href="favicon.ico" />
<title>Database Error</title>

<style type="text/css">
	body
	{
		color: black;
		background-color: white;
		width: 450px;
		line-height: 14px;
		padding: 15px;
		font-family: tahoma, arial, sans-serif;
		font-size: 11px;
		text-align: justify;
	}
</style>

</head>

<body>
	<div><b>There seems to be a problem with the database.</b></div>
	<p>Please try again by pressing the <a href="javascript:window.location=window.location;">refresh</a> button in your browser. An e-mail message has been dispatched to the <a href="mailto:<?php echo($aDBInfo['email']); ?>">Webmaster</a>, whom you can also contact if the problem persists. We apologize for any inconvenience.</p>
	<p><b>Error</b>: <?php echo($dbConn->geterror()); ?>.</p>

<?php
	// For testing purposes.
	if($CFG['showqueries'])
	{
		ShowQueries();
	}
?>

</body>
</html>
<?php
	// We're done.
	exit;
}
?>