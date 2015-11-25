body
{
	margin: 10px;
	padding: 0px;
	font-family: verdana, arial, helvetica, sans-serif;
	color: <?php echo($CFG['style']['forum']['txtcolor']); ?>;
	background-color: <?php echo($CFG['style']['page']['bgcolor']); ?>;
}

a:link
{
	color: <?php echo($CFG['style']['l_normal']['l']); ?>;
}
a:visited
{
	color: <?php echo($CFG['style']['l_normal']['v']); ?>;
}
a:hover
{
	color: <?php echo($CFG['style']['l_normal']['h']); ?>;
}
a:active
{
	color: <?php echo($CFG['style']['l_normal']['a']); ?>;
}
a.section:link, a.section:visited, a.section:active
{
	color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;
	text-decoration: none;
}
a.section:hover
{
	color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;
	text-decoration: underline;
}
a.heading:link, a.heading:visited, a.heading:active
{
	color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;
	text-decoration: none;
}
a.heading:hover
{
	color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;
	text-decoration: underline;
}
a.underline:link, a.underline:visited, a.underline:active
{
	text-decoration: none;
}
a.underline:hover
{
	text-decoration: underline;
}

select
{
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
	background-color: #CFCFCF;
}

textarea, input
{
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: 12px;
	background-color: #CFCFCF;
}

input[type="radio"], input[type="checkbox"], input[type="image"]
{
	background-color: transparent;
}

form
{
	margin: 0;
}

hr
{
	border: 0;
	height: 1px;
	color: black;
	background-color: black;
}

.heading
{
	font-weight: bold;
	color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;
	background-color: <?php echo($CFG['style']['table']['heading']['bgcolor']); ?>;
}

.section
{
	font-weight: bold;
	color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;
	background-color: <?php echo($CFG['style']['table']['section']['bgcolor']); ?>;
}

.postbit
{
	width: 100%;
	overflow: auto;
	overflow-y: hidden;
	margin-bottom: 1em;
}

/* For Opera */
@media all and (min-width: 0px)
{
	.postbit, .php, .code, .quote
	{
		padding-bottom: 1.5em;
	}
}

/* For IE */
.postbit, .php, .code, .quote
{
	*padding-bottom: 1.5em;
}

.quote
{
	overflow: auto;
	overflow-y: hidden;
}

.php
{
	width: 100%;
	white-space: nowrap;
	overflow: auto;
	overflow-y: hidden;
}

.code
{
	width: 100%;
	margin: 0;
	white-space: nowrap;
	overflow: auto;
	overflow-y: hidden;
}

.pollbar
{
	font-size: 1px;
	height: 10px;
	padding: 0;
	margin: 0;
	background: #0071BC;
}

.smaller
{
	font-size: 10px;
}
.small
{
	font-size: 11px;
}
.medium
{
	font-size: 13px;
}