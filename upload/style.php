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

	// So the browser will know we're CSS.
	header('Content-type: text/css');

	// Template
	require("./skins/{$CFG['skin']}/style.tpl.php");

	// Send the page.
	exit;
?>