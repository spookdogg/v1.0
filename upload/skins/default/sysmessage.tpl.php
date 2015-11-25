<?php
	// Is there a redirect?
	$strRedirect = str_replace('&', '&amp;', $strRedirect);

	// Header
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<div class="medium" style="margin: auto; background-color: <?php echo($CFG['style']['table']['cella']); ?>; border: <?php echo($CFG['style']['table']['bgcolor']); ?> solid 1px; width: 450px; margin-top: 3em; margin-bottom: 3em; padding: 10px; text-align: <?php echo($strAlign); ?>;"><?php echo($strMessage); ?></div>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>