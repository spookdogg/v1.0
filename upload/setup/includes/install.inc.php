<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2015-2016 Phillip                                          //
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

		case 3:
		{
			Step3();
		}

		case 4:
		{
			Step4();
		}
	}

	// Header
	PrintHeader();
?>

<h1>Install OvBB</h1>
<p>This script will help you setup a fresh installation of OvBB v1.0.</p>
<p>If you need to <i>upgrade</i> from V0.1, click <a href="index.php?setup=upgrade">here</a>. Otherwise, click <a href="index.php?step=1">here</a> to begin the installation.</p>

<?php
	// Footer
	PrintFooter();

// *************************************************************************** \\

function WriteSetting($strKey, $strName, $strFile)
{
	global $dbConn;

	// Load the default settings.
	require("./scripts/{$strFile}");

	// Serialize and sanitize it for the database.
	$strSettings = $dbConn->sanitize(serialize($$strName));
	$dbConn->query("INSERT INTO configuration(name, content) VALUES('{$strKey}', '{$strSettings}')");
}

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
<p>If this information is correct, click Next. Otherwise, you will need to edit the <code>dbinfo.inc.php</code> file in the <code>includes</code> subdirectory to reflect your database connection details before you can continue the installation.</p>
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
	echo('SQL details verified. Click <a href="index.php">here</a> to continue the installation process.');
	PrintFooter();
}

// *************************************************************************** \\

// Screen user gets to setup database.
function Step2()
{
	// Are they submitting?
	if($_REQUEST['action'] == 'setup')
	{
		$strError = Step2B();
	}

	// Header
	PrintHeader();

	// Display any errors.
	if($strError)
	{
		echo("<b>Error</b>: {$strError}<br /><br />\nIf you would like to retry, click <a href=\"index.php?action=setup\">here</a>.\n");
	}
	else
	{
?>

<h1>Database Setup</h1>
<p>The first thing that must be done is to setup your database so that OvBB can use it.</p>
<p>Click <a href="index.php?action=setup">here</a> to setup the database.</p>

<?php
	}

	// Footer
	PrintFooter();
}

// Sets up the database for the user.
function Step2B()
{
	// Connect to database.
	list($aDBInfo, $strError) = InitDatabase();

	// Return any errors.
	if($strError)
	{
		return $strError;
	}

	// Execute the installation SQL script.
	if(!$_SESSION["install.{$aDBInfo['type']}"])
	{
		if(ExecuteSQL("./includes/install.{$aDBInfo['type']}"))
		{
			// Mark us as having executed the SQL script.
			$_SESSION["install.{$aDBInfo['type']}"] = TRUE;
		}
		else
		{
			// Couldn't execute install.sql.
			return("Could not execute <code>install.{$aDBInfo['type']}</code>.<br /><b>Database says</b>: ".$dbConn->geterror());
		}
	}

	// Store the default configuration settings.
	$aSettings['avatars'] = array('aAvatars', 'avatars.inc.php');
	$aSettings['posticons'] = array('aPostIcons', 'posticons.inc.php');
	$aSettings['smilies'] = array('aSmilies', 'smilies.inc.php');
	$aSettings['usergroups'] = array('aGroup', 'usergroups.inc.php');
	$aSettings['skins'] = array('aSkins', 'skins.inc.php');
	foreach($aSettings as $strKey => $aSetting)
	{
		WriteSetting($strKey, $aSetting[0], $aSetting[1]);
	}

	// Tell the user it was a success.
	PrintHeader();
	$_SESSION['step']++;
	echo('Your database has been setup successfully. Click <a href="index.php">here</a> to continue the installation process.');
	PrintFooter();
}

// *************************************************************************** \\

// Screen user gets to setup some basic settings.
function Step3()
{
	// Are they submitting?
	if(isset($_REQUEST['submit']))
	{
		$strError = Step3B();
	}

	// Header
	PrintHeader();

	// Print out any errors.
	if($strError)
	{
		echo("<p><b>Error:</b> {$strError}</p>\n");
	}
?>

<h1>Forum Information</h1>

<p>Enter some information about your forums. This will be public and appear on most, if not all, forum pages.</p>
<blockquote><form action="index.php" method="post">
	<b>Forum Name</b>
	<div><input type="text" name="name" value="<?php echo(htmlspecialchars($_SESSION['forumname'])); ?>" /></div><br />

	<b>Forum Copyright Notice</b>
	<div><input type="text" name="copyright" value="<?php echo(htmlspecialchars($_SESSION['copyright'])); ?>" /></div><br />

	<b>Administrator's E-Mail</b>
	<div><input type="text" name="adminemail" value="<?php echo(htmlspecialchars($_SESSION['adminemail'])); ?>" /></div><br />

	<input type="submit" name="submit" value="Go" />
</form></blockquote>

<?php
	// Footer
	PrintFooter();
}

// Verifies settings given by user, and saves them.
function Step3B()
{
	global $dbConn;

	// Connect to database.
	list(, $strError) = InitDatabase();

	// Return any errors.
	if($strError)
	{
		return $strError;
	}

	// Grab the settings.
	$_SESSION['forumname'] = trim($_REQUEST['name']);
	$_SESSION['copyright'] = trim($_REQUEST['copyright']);
	$_SESSION['adminemail'] = trim($_REQUEST['adminemail']);

	// Forum name
	if($_SESSION['forumname'] == '')
	{
		return('You must specify a name for your forums.');
	}

	// Admin's e-mail
	if($_SESSION['adminemail'] == '')
	{
		return('You must specify the administrator\'s e-mail address.');
	}

	// Read in the default configuration settings.
	require('./scripts/config.inc.php');

	// Overwrite the default values with the user's settings.
	$CFG['general']['name'] = $_SESSION['forumname'];
	$CFG['general']['copyright'] = $_SESSION['copyright'];
	$CFG['general']['admin']['email'] = $_SESSION['adminemail'];
	eval("\$CFG['msg']['invalidlink'] = \"{$CFG['msg']['invalidlink']}\";");
	$CFG['version'] = '1.0';

	// Save the settings to the database.
	$strSettings = $dbConn->sanitize(serialize($CFG));
	$dbConn->query("INSERT INTO configuration(name, content) VALUES('settings', '{$strSettings}')");

	// Tell the user it was a success.
	PrintHeader();
	$_SESSION['step']++;
	echo('Forum information was successfully saved. Click <a href="index.php">here</a> to continue the installation.');
	PrintFooter();
}

// *************************************************************************** \\

function Step4()
{
	global $dbConn;

	// Connect to database.
	list(, $strError) = InitDatabase();

	// Are they submitting?
	if(isset($_REQUEST['submit']))
	{
		$strError = Step4B();
	}

	// Header
	PrintHeader();

	// Print out any errors.
	if($strError)
	{
		echo("<p><b>Error:</b> {$strError}</p>\n");
	}

	// Grab the configuration settings.
	else
	{
		$dbConn->query("SELECT content FROM configuration WHERE name='settings'");
		list($strValue) = $dbConn->getresult();
		$_SESSION['config'] = $CFG = unserialize($strValue);
	}
?>

<h1>Create Administrator's Account</h1>

<p>Your forums are basically setup now, but you should create an administrator account. This can be either the account you will typically use, or one dedicated to administrative tasks.</p>
<blockquote><form action="index.php" method="post">
	<b>Username</b>
	<div><input type="text" name="username" maxlength="<?php echo($CFG['maxlen']['username']); ?>" value="<?php echo(htmlspecialchars($_SESSION['username'])); ?>" /></div><br />

	<b>Password</b>
	<div><input type="password" name="password" maxlength="<?php echo($CFG['maxlen']['password']); ?>" value="<?php echo(htmlspecialchars($_SESSION['password'])); ?>" /></div><br />

	<b>E-Mail</b>
	<div><input type="text" name="email" maxlength="<?php echo($CFG['maxlen']['email']); ?>" value="<?php echo(htmlspecialchars($_SESSION['email'])); ?>" /></div><br />

	<input type="submit" name="submit" value="Go" />
</form></blockquote>

<?php
	// Footer
	PrintFooter();
}

// Verifies details given by user and saves them if they work.
function Step4B()
{
	global $dbConn;

	// Load the configuration settings.
	$CFG = $_SESSION['config'];

	// Grab the details.
	$_SESSION['username'] = trim($_REQUEST['username']);
	$_SESSION['password'] = trim($_REQUEST['password']);
	$_SESSION['email'] = trim($_REQUEST['email']);

	// Username
	if($_SESSION['username'] == '')
	{
		return('You must specify a username.');
	}
	else if(strlen($_SESSION['username']) > $CFG['maxlen']['username'])
	{
		return("Usernames cannot be longer than {$CFG['maxlen']['username']} characters.");
	}
	else
	{
		$strUsername = $dbConn->sanitize($_SESSION['username']);
	}

	// Password
	if($_SESSION['password'] == '')
	{
		return('You must specify a password.');
	}
	else if(strlen($_SESSION['password']) > $CFG['maxlen']['password'])
	{
		return("Passwords cannot be longer than {$CFG['maxlen']['password']} characters.");
	}
	else
	{
		$strPassword = md5($_SESSION['password']);
	}

	// E-mail
	if($_SESSION['email'] == '')
	{
		return('You must specify an e-mail address.');
	}
	else if(strlen($_SESSION['email']) > $CFG['maxlen']['email'])
	{
		return("E-mail addresses cannot be longer than {$CFG['maxlen']['email']} characters.");
	}
	else
	{
		$strEMail = $dbConn->sanitize($_SESSION['email']);
	}

	// Insert the administrator's member record.
	$dJoined = gmdate('Y-m-d');
	$dbConn->query("INSERT INTO citizen(username, passphrase, email, datejoined, usergroup, pmfolders) VALUES('{$strUsername}', '{$strPassword}', '{$strEMail}', '{$dJoined}', 3, 'a:0:{}')");

	// Update the stats.
	$iNewestMember = $dbConn->getinsertid('citizen');
	$dbConn->query("UPDATE stats SET content={$iNewestMember} WHERE name='newestmember'");
	$dbConn->query("UPDATE stats SET content=content+1 WHERE name='membercount'");

	// What is the address of their forums?
	$strForums = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/index.php')) . 'index.php';

	// Destroy the session.
	session_unset();
	session_destroy();

	// Tell the user it was a success.
	PrintHeader();
?>

<h1>Installation Successful!</h1>

<p>The installation of your forums was a complete success. <b>You should delete the <code>setup</code> directory in your forums' path before continuing, as it is now a security risk.</b></p>
<p>You can visit your forums at this address:</p>
<blockquote><a href="<?php echo($strForums); ?>"><?php echo(htmlspecialchars("{$_SERVER['HTTP_HOST']}{$strForums}")); ?></a></blockquote>
<p>If you or your users have any problems while using your new community, stop by the <a href="http://www.ovbb.org/forums/forumdisplay.php?forumid=3">OvBB Project Forums</a> to seek support.</p>

<?php
	PrintFooter();
}
?>
