<?php
// GENERAL INFORMATION
// {
	// Forum name
	//
	//  This is the name of your bulletin board; it is displayed several times
	//  throughout the forum.
	//
	$CFG['general']['name'] = 'Name Of Your Forums Goes Here';

	// Copyright notice
	//
	//  Displayed at the bottom of each forum page, this text
	//  notifies your users of your copyright ownership.
	//  (The forum content does not have to be a registered copyright
	//  work; under US Federal law, you are generally given copyright
	//  ownership automatically for any work that you create.)
	//
	$CFG['general']['copyright'] = 'Copyright &copy; Your Name/Organization';

	// Administrator's e-mail adddress
	//
	//  This is the e-mail address of the bulletin board's administrator.
	//  It is displayed in various e-mails sent by the forum as well as
	//  at the bottom of the forum pages as a 'Contact Us' link.
	//
	$CFG['general']['admin']['email'] = 'you@yourdomain.com';

	// Message to be appended to "Invalid..." messages.
	$CFG['msg']['invalidlink'] = ' If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG[general][admin][email]}\">Webmaster</a>.';
// }


// SETTINGS
// {
	// Enable GZip compression?
	//
	//  This determines whether or not pages should be GZipped before being
	//  sent to users whose clients specify support for GZipped content.
	//
	//  TRUE = Enabled, FALSE = Disabled
	//
	$CFG['general']['gzip']['enabled'] = TRUE;

	// GZip compression level
	//
	//  This is the level of compression the forums will use when
	//  compressing pages (if enabled). Acceptable values are 0-9
	//  inclusive; 0 is no compression while 9 is full compression.
	//
	//  4 is the recommended compression level.
	//
	$CFG['general']['gzip']['level'] = 4;

	// Enable Quick Reply?
	//
	//  This determines whether or not the Quick Reply form is displayed
	//  at the bottom of each thread.
	//
	//  TRUE = Enabled, FALSE = Disabled
	//
	$CFG['general']['quickreply'] = TRUE;

  // Time zone offsets
	//
	// Display time zone offset (in seconds)
	//
	//  This is the UTC/GMT time zone offset used when displaying
	//  times on the forum for users who are not logged into their
	//  account or are not registered. For example, if your visitors
	//  are mostly from Central Time (US & Canada), then you might
	//  set this value to '-21600' (GMT-6).
	//
	$CFG['time']['display_offset'] = 0;
	//
	// Display Daylight Saving Time/Summer Time offset (in seconds)
	//
	//  This is the Daylight Saving Time/Summer Time offset used when
	//  displaying times on the forum for users who are not logged into
	//  their account or are not registered. For example, if your visitors
	//  are mostly residing at a location observing Daylight Saving Time
	//  or Summer Time by one hour, then you might set this value to
	//  '3600'.
	//
	$CFG['time']['dst'] = TRUE;
	$CFG['time']['dst_offset'] = 3600;
// }


// REGISTRATION
// {
	// Enable Image Verification?
	//
	//  This determines whether or not users must enter text from
	//  a dynamically-generated image when registering a new
	//  account. It can prevent automated processes from creating
	//  accounts, but it can also be annoying for users.
	//
	//  The GD graphics library is required for this feature.
	//
	$CFG['reg']['verify_img'] = TRUE;
// }


// STYLE
// {
	// Table and page styles
	$CFG['style']['page']['bgcolor'] = '#395A84';
	$CFG['style']['forum']['bgcolor'] = '#FFFFFF';
	$CFG['style']['forum']['txtcolor'] = '#000000';

	$CFG['style']['table']['bgcolor'] = '#243449';
	$CFG['style']['table']['cella'] = '#F1F1F1';
	$CFG['style']['table']['cellb'] = '#DFDFDF';

	$CFG['style']['table']['width'] = '100%';
	$CFG['style']['content_table']['width'] = '100%';

	$CFG['style']['table']['heading']['bgcolor'] = '#395A84';
	$CFG['style']['table']['heading']['txtcolor'] = '#EEEEFF';
	$CFG['style']['table']['section']['bgcolor'] = '#344969';
	$CFG['style']['table']['section']['txtcolor'] = '#EEEEFF';

	$CFG['style']['table']['timecolor'] = '#2B4362';
	$CFG['style']['errors'] = '#FF0000';

	$CFG['style']['credits'] = '#EEEEFF';
	$CFG['style']['stats'] = '#EEEEFF';
	$CFG['style']['stats_bold'] = '#FFFFFF';

	// Calendar colors
	$CFG['style']['calcolor']['datea']['bgcolor'] = '#FFFFFF';
	$CFG['style']['calcolor']['datea']['txtcolor'] = '#999999';
	$CFG['style']['calcolor']['dateb']['bgcolor'] = '#DFDFDF';
	$CFG['style']['calcolor']['dateb']['txtcolor'] = '#000000';
	$CFG['style']['calcolor']['today']['bgcolor'] = '#F1F1F1';
	$CFG['style']['calcolor']['today']['txtcolor'] = '#000000';

	// Link styles
	$CFG['style']['l_normal']['l'] = '#FF0000';
	$CFG['style']['l_normal']['v'] = '#FF0000';
	$CFG['style']['l_normal']['a'] = '#FF0000';
	$CFG['style']['l_normal']['h'] = '#FF0000';
// }


// MAX LENGTHS
// {
	// Maximum field lengths
	//
	//  These specify the maximum length in characters of various fields.
	//
	//  Note: These values are restricted to the maximum length of the
	//  database field(s) they [in]directly refer to; the maximum
	//  allowable value for each is given to its right. Some values
	//  (such as $CFG['maxlen']['folder']) are dependent on other variables.
	//
	$CFG['maxlen']['subject'] = 64;         // 255 characters
	$CFG['maxlen']['desc'] = 128;           // 255 characters
	$CFG['maxlen']['messagebody'] = 10000;  // 65536 characters
	$CFG['maxlen']['email'] = 128;          // 255 characters
	$CFG['maxlen']['pollquestion'] = 64;    // 255 characters
	$CFG['maxlen']['pollchoice'] = 64;      // (65536 / $CFG['maxlen']['pollchoices']) characters
	$CFG['maxlen']['pollchoices'] = 10;     // (65536 / $CFG['maxlen']['pollchoice']) characters
	$CFG['maxlen']['username'] = 32;        // 255 characters
	$CFG['maxlen']['password'] = 24;        // 255 characters
	$CFG['maxlen']['website'] = 128;        // 255 characters
	$CFG['maxlen']['aim'] = 16;             // 255 characters
	$CFG['maxlen']['icq'] = 24;             // 255 characters
	$CFG['maxlen']['msn'] = 128;            // 255 characters
	$CFG['maxlen']['yahoo'] = 50;           // 255 characters
	$CFG['maxlen']['bio'] = 255;            // 255 characters
	$CFG['maxlen']['location'] = 48;        // 255 characters
	$CFG['maxlen']['interests'] = 255;      // 255 characters
	$CFG['maxlen']['occupation'] = 255;     // 255 characters
	$CFG['maxlen']['signature'] = 255;      // 255 characters
	$CFG['maxlen']['folder'] = 24;          // (65536 / number of folders) characters
	$CFG['maxlen']['query'] = 64;           // 255 characters
// }


// MISCELLANEOUS
// {
	$CFG['uploads']['oktypes'] = array('bmp' => array('bmp.png', 'image/bmp'),
	                                   'gif' => array('image.png', 'image/gif'),
	                                   'jpg' => array('image.png', 'image/jpeg'),
	                                   'jpeg' => array('image.png', 'image/jpeg'),
	                                   'png' => array('image.png', 'image/png'),
	                                   'txt' => array('text.png', 'text/plain'),
	                                   'zip' => array('zip.png', 'application/zip'),
	                                   'rar' => array('rar.png', 'application/x-rar-compressed'),
	                                   'gz' => array('gzip.png', 'application/x-gzip'),
	                                   '7z' => array('7zip.png', 'application/x-7z-compressed'));
	$CFG['uploads']['maxsize'] = 204800;
	$CFG['avatars']['maxsize'] = 102400;
	$CFG['avatars']['maxdims'] = 50;
	$CFG['paths']['smilies'] = 'images/smilies/';
	$CFG['paths']['avatars'] = 'images/avatars/';
	$CFG['paths']['posticons'] = 'images/posticons/';
	$CFG['paths']['cookies'] = substr(pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME), 0, strpos(pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME), 'setup'));
	$CFG['default']['postsperpage'] = 10;
	$CFG['default']['threadsperpage'] = 40;
	$CFG['default']['threadview'] = 1000;
	$CFG['default']['weekstart'] = 0;
	$CFG['parsing']['showimages'] = TRUE;
	$CFG['showqueries'] = FALSE;
	$CFG['showerrors'] = FALSE;
	$CFG['iplogging'] = TRUE;
	$CFG['bufferoutput'] = FALSE;
	$CFG['skin'] = 1;
	$CFG['floodcheck'] = 0;
// }
?>