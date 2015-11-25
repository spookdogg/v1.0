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

	// Reverse magic quotes if they are enabled.
	if(get_magic_quotes_gpc())
	{
		$_REQUEST = dmq($_REQUEST);
	}

	// Set errors accordingly.
	error_reporting(E_ALL ^ E_NOTICE);

	// Start the session.
	ini_set('arg_separator.output', '&amp;');
	session_name('s');
	session_start();

	// Don't screw with our URLs, you crazy PHP you.
	output_reset_rewrite_vars();

	// Add the session ID to all local URLs (if it hasn't been saved to a cookie).
	if(SID)
	{
		output_add_rewrite_var('s', stripslashes(session_id()));
	}

	// Are they specifying a step?
	if($_REQUEST['step'] == 1)
	{
		// Yes, so set the step.
		$_SESSION['step'] = 1;
	}

	// Are they specifying an install type?
	if(isset($_REQUEST['setup']))
	{
		// Yes, so set the install type.
		$_SESSION['setup'] = $_REQUEST['setup'];
	}

	// Are they wanting to install or upgrade?
	if($_SESSION['setup'] == 'install')
	{
		require('./includes/install.inc.php');
	}
	else if($_SESSION['setup'] == 'upgrade')
	{
		require('./includes/upgrade.inc.php');
	}

	// Header
	PrintHeader();
?>

<h1>Setup Type</h1>
<p>Which type of setup do you want to perform?</p>
<div align="center" style="font-size: 1.25em;"><a href="index.php?setup=install">Fresh Installation</a> | <a href="index.php?setup=upgrade">Upgrade From V0.15a</a></div>

<?php
	// Footer
	PrintFooter();

// *************************************************************************** \\

function dmq($given)
{
	return is_array($given) ? array_map('dmq', $given) : stripslashes($given);
}

// *************************************************************************** \\

// Connects the database server and selects the database.
function InitDatabase()
{
	global $dbConn;

	// Load the database information.
	require('../includes/dbinfo.inc.php');

	// Load the database driver.
	if(!@include("../includes/{$aDBInfo['type']}.inc.php"))
	{
		die('<span style="font-family: verdana, arial, helvetica, sans-serif; font-size: 13px;">Please upload a database driver!</span>');
	}

	// Create a new database connection.
	$dbConn = new DBConnection($aDBInfo);

	// Make sure we connected.
	if(!$dbConn->objConnection || !$dbConn->objSelect)
	{
		$strError = $dbConn->geterror();
	}

	// Return database information.
	return array($aDBInfo, $strError);
}

// *************************************************************************** \\

// Tells the user when there's a problem with the database.
function DatabaseError()
{
	global $dbConn;
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
	<p>Please try again by pressing the <a href="javascript:window.location=window.location;">refresh</a> button in your browser.</p>
	<p><b>Error</b>: <?php echo($dbConn->geterror()); ?>.</p>
</body>
</html>
<?php
	// We're done.
	exit;
}

// *************************************************************************** \\

// Executes a given file as semicolon-terminated SQL statements.
function ExecuteSQL($filename)
{
	global $dbConn;

	// Get contents of the file.
	$filedata = file_get_contents($filename);
	if($filedata === FALSE)
	{
		return FALSE;
	}

	// Explode statements into elements of an array.
	$aSQL = explode(';', $filedata);

	// Execute each of the queries.
	foreach($aSQL as $strSQL)
	{
		if(trim($strSQL))
		{
			if(!$dbConn->query(trim($strSQL)))
			{
				return FALSE;
			}
		}
	}

	return TRUE;
}

// *************************************************************************** \\

function PrintHeader()
{
	$strType = ($_SESSION['setup'] == 'upgrade') ? 'Upgrade' : 'Install';
	$strStep = $_SESSION['step'] ? "Step {$_SESSION['step']}" : 'Welcome';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link href="style.css" rel="stylesheet" type="text/css" />
	<title>OvBB <?php echo($strType); ?> :: <?php echo($strStep); ?></title>
</head>

<body>

<?php
}

// *************************************************************************** \\

function PrintFooter()
{
?>

<hr style="margin-top: 2em;" /><div>Having problems? Click <a href="http://www.ovbb.org/forums/forumdisplay.php?forumid=3" target="_blank">here</a> to visit the OvBB Support Forum.</div>
</body>
</html>

<?php
	exit;
}
?>
