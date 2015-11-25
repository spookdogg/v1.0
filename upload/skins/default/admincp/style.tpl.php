<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Style';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<script language="JavaScript" type="text/javascript">
<!--
	function update(el)
	{
		preview = document.getElementById(el.name + '_preview');
		preview.style.backgroundColor = el.value;
	}
//-->
</script>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; Style</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();

	// Display any errors.
	if($aError)
	{
		DisplayErrors($aError);
	}
	else
	{
		echo('<br />');
	}
?>

<form name="theform" action="admincp.php" method="post">
<input type="hidden" name="section" value="style" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="section">
	<td colspan="2" align="center" class="medium">Style</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Forum Skin</b>
		<div class="smaller">This is the skin/template used by the forum.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<select name="skinid">
<?php
	foreach($aSkins as $iSkinID => $aSkin)
	{
		$strSelected = ($iSkinID == $aStyles['skin']) ? ' selected="selected"' : '';
		$strSkinTitle = htmlsanitize($aSkin['title']);
		echo("\t\t\t<option value=\"{$iSkinID}\"{$strSelected}>{$strSkinTitle}</option>\n");
		unset($strSelected);
	}
?>
		</select> <input type="submit" name="editskins" value="Edit Skins" />
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Page Background Color</b>
		<div class="smaller">This is the color of the page background.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="page_bgcolor" size="10" value="<?php echo(htmlsanitize($aStyles['page_bgcolor'])); ?>" onchange="javascript:update(this);" /> <input id="page_bgcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['page_bgcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Forum Background Color</b>
		<div class="smaller">This is the color of the forum's background.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="forum_bgcolor" size="10" value="<?php echo(htmlsanitize($aStyles['forum_bgcolor'])); ?>" onchange="javascript:update(this);" /> <input id="forum_bgcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['forum_bgcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Forum Text Color</b>
		<div class="smaller">This is the color of normal forum text.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="forum_txtcolor" size="10" value="<?php echo(htmlsanitize($aStyles['forum_txtcolor'])); ?>" onchange="javascript:update(this);" /> <input id="forum_txtcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['forum_txtcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Table Border Color</b>
		<div class="smaller">This is the color of the table border color.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="table_bgcolor" size="10" value="<?php echo(htmlsanitize($aStyles['table_bgcolor'])); ?>" onchange="javascript:update(this);" /> <input id="table_bgcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['table_bgcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Heading Background Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="heading_bgcolor" size="10" value="<?php echo(htmlsanitize($aStyles['heading_bgcolor'])); ?>" onchange="javascript:update(this);" /> <input id="heading_bgcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['heading_bgcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Heading Text Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="heading_txtcolor" size="10" value="<?php echo(htmlsanitize($aStyles['heading_txtcolor'])); ?>" onchange="javascript:update(this);" /> <input id="heading_txtcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['heading_txtcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Section Background Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="section_bgcolor" size="10" value="<?php echo(htmlsanitize($aStyles['section_bgcolor'])); ?>" onchange="javascript:update(this);" /> <input id="section_bgcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['section_bgcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Section Text Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="section_txtcolor" size="10" value="<?php echo(htmlsanitize($aStyles['section_txtcolor'])); ?>" onchange="javascript:update(this);" /> <input id="section_txtcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['section_txtcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>First Alternating Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="table_cella" size="10" value="<?php echo(htmlsanitize($aStyles['table_cella'])); ?>" onchange="javascript:update(this);" /> <input id="table_cella_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['table_cella']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Second Alternating Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="table_cellb" size="10" value="<?php echo(htmlsanitize($aStyles['table_cellb'])); ?>" onchange="javascript:update(this);" /> <input id="table_cellb_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['table_cellb']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Table Width</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="table_width" size="10" value="<?php echo(htmlsanitize($aStyles['table_width'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Content Width</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="content_width" size="10" value="<?php echo(htmlsanitize($aStyles['content_width'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Time Text Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="time" size="10" value="<?php echo(htmlsanitize($aStyles['time'])); ?>" onchange="javascript:update(this);" /> <input id="time_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['time']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Error Text Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="errors" size="10" value="<?php echo(htmlsanitize($aStyles['errors'])); ?>" onchange="javascript:update(this);" /> <input id="errors_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['errors']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Credits Text Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="credits" size="10" value="<?php echo(htmlsanitize($aStyles['credits'])); ?>" onchange="javascript:update(this);" /> <input id="credits_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['credits']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Statistics Text Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="stats" size="10" value="<?php echo(htmlsanitize($aStyles['stats'])); ?>" onchange="javascript:update(this);" /> <input id="stats_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['stats']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Statistics Bold Text Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="stats_bold" size="10" value="<?php echo(htmlsanitize($aStyles['stats_bold'])); ?>" onchange="javascript:update(this);" /> <input id="stats_bold_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['stats_bold']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Previous/Next Month's Day Background Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="cal_datea_bgcolor" size="10" value="<?php echo(htmlsanitize($aStyles['cal_datea_bgcolor'])); ?>" onchange="javascript:update(this);" /> <input id="cal_datea_bgcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['cal_datea_bgcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Previous/Next Month's Day Text Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="cal_datea_txtcolor" size="10" value="<?php echo(htmlsanitize($aStyles['cal_datea_txtcolor'])); ?>" onchange="javascript:update(this);" /> <input id="cal_datea_txtcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['cal_datea_txtcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Current Month's Day Background Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="cal_dateb_bgcolor" size="10" value="<?php echo(htmlsanitize($aStyles['cal_dateb_bgcolor'])); ?>" onchange="javascript:update(this);" /> <input id="cal_dateb_bgcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['cal_dateb_bgcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Current Month's Day Text Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="cal_dateb_txtcolor" size="10" value="<?php echo(htmlsanitize($aStyles['cal_dateb_txtcolor'])); ?>" onchange="javascript:update(this);" /> <input id="cal_dateb_txtcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['cal_dateb_txtcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Current Day's Background Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="cal_today_bgcolor" size="10" value="<?php echo(htmlsanitize($aStyles['cal_today_bgcolor'])); ?>" onchange="javascript:update(this);" /> <input id="cal_today_bgcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['cal_today_bgcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Current Day's Text Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="cal_today_txtcolor" size="10" value="<?php echo(htmlsanitize($aStyles['cal_today_txtcolor'])); ?>" onchange="javascript:update(this);" /> <input id="cal_today_txtcolor_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['cal_today_txtcolor']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Normal Link Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="link_l" size="10" value="<?php echo(htmlsanitize($aStyles['link_l'])); ?>" onchange="javascript:update(this);" /> <input id="link_l_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['link_l']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Visited Link Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="link_v" size="10" value="<?php echo(htmlsanitize($aStyles['link_v'])); ?>" onchange="javascript:update(this);" /> <input id="link_v_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['link_v']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Active Link Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="link_a" size="10" value="<?php echo(htmlsanitize($aStyles['link_a'])); ?>" onchange="javascript:update(this);" /> <input id="link_a_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['link_a']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Hover Link Color</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="link_h" size="10" value="<?php echo(htmlsanitize($aStyles['link_h'])); ?>" onchange="javascript:update(this);" /> <input id="link_h_preview" style="border: black solid 1px; background-color: <?php echo($aStyles['link_h']); ?>;" type="text" size="10" disabled="disabled" /></td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>