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

	// Which step are they on?
	switch($_SESSION['step'])
	{
		case 1:
		{
			Step1();
		}

		case 2:
		{
			Step2();
		}
	}

	// Header
	PrintHeader();
?>

<h1>Upgrade From V0.15a</h1>
<p>This script will help you upgrade to OvBB V0.16a from V0.15a.</p>
<p>If you need to perform a fresh <i>installation</i>, click <a href="index.php?setup=install">here</a>. Otherwise, click <a href="index.php?step=1">here</a> to begin the upgrade process.</p>

<?php
	// Footer
	PrintFooter();

// *************************************************************************** \\

// Screen user gets that displays their database details.
function Step1()
{
	// Are they submitting?
	if(isset($_REQUEST['submit']))
	{
		$strError = Step1B();
	}

	// Load the database information.
	require('../includes/dbinfo.inc.php');

	// Header
	PrintHeader();

	// Display any errors.
	if($strError)
	{
		echo("<p><b>Error:</b> {$strError}</p>\n");
	}
?>

<h1>Database Information</h1>

<p>Current Database connection details:</p>
<form action="index.php" method="post">
<blockquote>
	<b>Database Type</b>
	<div><?php echo(htmlspecialchars($aDBInfo['type'])); ?></div><br />

	<b>Server Address</b>
	<div><?php echo(htmlspecialchars($aDBInfo['address'])); ?></div><br />

	<b>Username</b>
	<div><?php echo(htmlspecialchars($aDBInfo['username'])); ?></div><br />

	<b>Password</b>
	<div><?php echo(htmlspecialchars($aDBInfo['password'])); ?></div><br />

	<b>Database Name</b>
	<div><?php echo(htmlspecialchars($aDBInfo['database'])); ?></div><br />

	<b>Database E-Mail</b>
	<div><?php echo(htmlspecialchars($aDBInfo['email'])); ?></div>
</blockquote>
<p>If this information is correct, click Next. Otherwise, you will need to edit the <code>dbinfo.inc.php</code> file in the <code>includes</code> subdirectory to reflect your database connection details before you can continue the upgrade process.</p>
<p><input type="submit" name="submit" value="Next" /> <input type="submit" name="refresh" value="Refresh" /></p>
</form>

<?php
	// Footer
	PrintFooter();
}

// Verifies details in dbinfo.inc.php.
function Step1B()
{
	// Connect to database.
	list($aDBInfo, $strError) = InitDatabase();

	// Return any errors.
	if($strError)
	{
		return $strError;
	}

	// Save the details; they're valid.
	$_SESSION['type'] = $aDBInfo['type'];
	$_SESSION['dbaddress'] = $aDBInfo['address'];
	$_SESSION['dbusername'] = $aDBInfo['username'];
	$_SESSION['dbpassword'] = $aDBInfo['password'];
	$_SESSION['dbname'] = $aDBInfo['database'];

	// Tell the user it was a success.
	PrintHeader();
	$_SESSION['step']++;
	echo('SQL details verified. Click <a href="index.php">here</a> to continue the upgrade process.');
	PrintFooter();
}

// *************************************************************************** \\

// Screen user gets to update database.
function Step2()
{
	// Are they submitting?
	if($_REQUEST['action'] == 'update')
	{
		$strError = Step2B();
	}

	// Header
	PrintHeader();

	// Display any errors.
	if($strError)
	{
		echo("<b>Error</b>: {$strError}<br /><br />\nIf you would like to retry, click <a href=\"index.php?action=update\">here</a>.\n");
	}
	else
	{
?>

<h1>Update Database</h1>
<p>The database must be updated so that the new version of OvBB can use it.</p>
<p>Click <a href="index.php?action=update">here</a> to update the database.</p>

<?php
	}

	// Footer
	PrintFooter();
}

// Updates the database for the user.
function Step2B()
{
	global $dbConn;

	// Connect to database.
	list($aDBInfo, $strError) = InitDatabase();

	// Return any errors.
	if($strError)
	{
		return $strError;
	}

	// Execute the upgrade SQL script.
	if(!$_SESSION["upgrade.{$aDBInfo['type']}"])
	{
		if(ExecuteSQL("./includes/upgrade.{$aDBInfo['type']}"))
		{
			// Mark us as having executed the SQL script.
			$_SESSION["upgrade.{$aDBInfo['type']}"] = TRUE;
		}
		else
		{
			// Couldn't execute install.sql.
			return("Could not execute <code>upgrade.{$aDBInfo['type']}</code>.<br /><b>Database says</b>: ".$dbConn->geterror());
		}
	}

	// Load the current configuration settings.
	$dbConn->query("SELECT content FROM configuration WHERE name='settings'");
	list($aConfig) = $dbConn->getresult();
	$aConfig = unserialize($aConfig);

	// Update settings.
	$aConfig['version'] = '0.16a';

	// Sanitize it for the database.
	$strSettings = $dbConn->sanitize(serialize($aConfig));
	$dbConn->query("DELETE FROM configuration WHERE name='settings'");
	$dbConn->query("INSERT INTO configuration(name, content) VALUES('settings', '{$strSettings}')");

	// What is the address of their forums?
	$strForums = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/index.php')) . 'index.php';

	// Delete old cookies.
	$path = substr(pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME), 0, strpos(pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME), 'setup'));
	setcookie('activeuserid', '', time(), $path);
	setcookie('activepassword', '', time(), $path);
	setcookie('s', '', time(), $path);

	// Destroy the session.
	session_unset();
	session_destroy();

	// Tell the user it was a success.
	PrintHeader();
?>

<h1>Upgrade Successful!</h1>

<p>Your forums have been successfully upgraded from V0.15a to V0.16a. <b>You should delete the <code>setup</code> directory in your forums' path before continuing, as it is now a security risk.</b></p>
<p>You can visit your upgraded forums at this address:</p>
<blockquote><a href="<?php echo($strForums); ?>"><?php echo(htmlspecialchars("{$_SERVER['HTTP_HOST']}{$strForums}")); ?></a></blockquote>
<p>If you or your users have any problems while using your upgraded community, stop by the <a href="http://www.ovbb.org/forums/forumdisplay.php?forumid=3">OvBB Project Forums</a> to seek support.</p>

<?php
	PrintFooter();
}
?>