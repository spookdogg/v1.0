//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2007 Jonathon Freeman                                 //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the MIT License.                                   //
//                                                                           //
//***************************************************************************//

// Puts the specified smilie into the message box.
function smilie(smilie)
{
	var msgbox = document.theform.message;

	if(document.selection)
	{
		msgbox.focus();
		document.selection.createRange().text = smilie;
	}
	else if(msgbox.selectionStart || msgbox.selectionStart == "0")
	{
		var start = msgbox.selectionStart;
		var end = msgbox.selectionEnd;
		msgbox.value = msgbox.value.substring(0, start) + smilie + msgbox.value.substring(end, msgbox.value.length);
		msgbox.selectionStart = msgbox.selectionEnd = start + smilie.length;
		msgbox.focus();
	}
	else
	{
		msgbox.value = msgbox.value + smilie;
	}
}