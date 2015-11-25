<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2006 Jonathon J. Freeman                              //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the MIT License.                                   //
//                                                                           //
//***************************************************************************//

	// Initialize OvBB.
	require('./includes/init.inc.php');

	// Build the CAPTCHA string (if necessary).
	if(!isset($_SESSION['randstr']))
	{
		$strRandom = '';

		// We use only those characters and numbers
		// which can't be confused and are not wide.
		$strChars = '2346789ABCDEFGHJKLNPRTXYZ';

		// Generate a random string.
		for($i = 0; $i < 7; $i++)
		{
			$strRandom .= $strChars[mt_rand(0, 24)];
		}
		$_SESSION['randstr'] = $strRandom;
	}

	// Set our foreground color to whatever the forum's text color is.
	list($fgRed, $fgGreen, $fgBlue) = sscanf(strtolower($CFG['style']['forum']['txtcolor']), '#%02x%02x%02x');

	// Set our background color to whatever the main table's cell A is.
	list($bgRed, $bgGreen, $bgBlue) = sscanf(strtolower($CFG['style']['table']['cella']), '#%02x%02x%02x');

	// Get the font.
	$font = realpath('entangled.ttf');

	// Create a 210x55 image.
	list(,,$iWidth) = imagettfbbox(40, 0, $font, "«{$_SESSION['randstr']}»");
	$image = imagecreate(210, 45);

	// Fill it with the background color.
	imagefill($image, 0, 0, imagecolorallocate($image, $bgRed, $bgGreen, $bgBlue));

	// Write the string to the image.
	imagettftext($image, 40, 0, ((208-$iWidth)/2), 40, imagecolorallocate($image, $fgRed, $fgGreen, $fgBlue), $font, "«{$_SESSION['randstr']}»");

	// Send the image data.
	header('Content-type: image/png');
	header('Last-modified: '.gmdate('D, d M Y H:i:s').' GMT');
	imagepng($image);

	// Free the resource up.
	imagedestroy($image);
?>