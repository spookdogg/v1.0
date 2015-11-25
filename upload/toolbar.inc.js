//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2007 Jonathon Freeman                                 //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the MIT License.                                   //
//                                                                           //
//***************************************************************************//

// Handles single-parameter BB codes.
function bbcode(code)
{
	var msgbox = document.theform.message;
	
	inserttext = prompt("Enter text to be formatted:\n[" + code + "]blah[/" + code + "]", "");
	if((inserttext != null) && (inserttext != ""))
	{
		var code = "[" + code + "]" + inserttext + "[/" + code + "]";
		if(document.selection)
		{
			msgbox.focus();
			document.selection.createRange().text = code;
		}
		else if(msgbox.selectionStart || msgbox.selectionStart == "0")
		{
			var start = msgbox.selectionStart;
			var end = msgbox.selectionEnd;
			msgbox.value = msgbox.value.substring(0, start) + code + msgbox.value.substring(end, msgbox.value.length);
			msgbox.selectionStart = msgbox.selectionEnd = start + code.length;
			msgbox.focus();
		}
		else
		{
			msgbox.value = msgbox.value + code;
		}
	}
}

// Handles two-parameter BB codes.
function bbcode2(code, option)
{
	var msgbox = document.theform.message;
	
	inserttext = prompt("Enter the text to be formatted:\n[" + code + "=" + option + "]blah[/" + code + "]", "");
	if((inserttext != null) && (inserttext != ""))
	{
		var code = "[" + code + "=" + option + "]" + inserttext + "[/" + code + "]";
		if(document.selection)
		{
			msgbox.focus();
			document.selection.createRange().text = code;
		}
		else if(msgbox.selectionStart || msgbox.selectionStart == "0")
		{
			var start = msgbox.selectionStart;
			var end = msgbox.selectionEnd;
			msgbox.value = msgbox.value.substring(0, start) + code + msgbox.value.substring(end, msgbox.value.length);
			msgbox.selectionStart = msgbox.selectionEnd = start + code.length;
		}
		else
		{
			msgbox.value = msgbox.value + code;
		}
	}

	document.theform.tsize.selectedIndex = 0;
	document.theform.tfont.selectedIndex = 0;
	document.theform.tcolor.selectedIndex = 0;
	msgbox.focus();
}

// Handles list.
function makelist()
{
	var msgbox = document.theform.message;
	
	var type = prompt("What type of list do you want? Enter '1' for a numbered list, 'a' for an alphabetical list, and leave blank for a bulleted list.", '');
	if((type == '1') || (type == 'a'))
	{
		var list = '[list="'+ type + '"]\n';
	}
	else
	{
		var list = '[list]\n';
	}
	
	do
	{
		var item = prompt('Enter a list item. Leave blank or press Cancel to end the list.', '');
		if((item != '') && (item != null))
		{
			list += '[*]' + item + '\n';
		}
	} while((item != '') && (item != null));
	list = list + '[/list]';
	
	if(document.selection)
	{
		msgbox.focus();
		document.selection.createRange().text = list;
	}
	else if(msgbox.selectionStart || msgbox.selectionStart == "0")
	{
		var start = msgbox.selectionStart;
		var end = msgbox.selectionEnd;
		msgbox.value = msgbox.value.substring(0, start) + list + msgbox.value.substring(end, msgbox.value.length);
		msgbox.selectionStart = msgbox.selectionEnd = start + list.length;
	}
	else
	{
		msgbox.value = msgbox.value + list;
	}
	
	msgbox.focus();
}