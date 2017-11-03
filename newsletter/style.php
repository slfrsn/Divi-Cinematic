<?php if (!defined('ABSPATH')) exit;?>
p{
	margin: 10px 0;
	padding: 0;
}
html,
body,
#bodyCell,
p,
a,
h1,
h2,
h3,
h4,
h5,
h6{
	font-family: Helvetica;
	font-size: 15px;
	letter-spacing: normal;
	line-height: 125%;
}
h1,
h2,
h3,
h4,
h5,
h6{
	display: block;
	margin: 0;
	padding: 0;
	font-weight: bold;
}
h1{font-size: 26px;}
h2{font-size: 22px;}
h3{font-size: 20px;}
h4{font-size: 18px;}
img,
a img{
	border: 0;
	height: auto;
	outline: none;
	text-decoration: none;
	ms-interpolation-mode: bicubic;
}
body,
#bodyCell {
	height: 100%;
	margin: 0;
	padding: 0;
	width: 100%;
	color: #202020;
}
p,
a,
li,
td,
blockquote{
	mso-line-height-rule: exactly;
}
a[href^=tel],
a[href^=sms]{
	color: inherit;
	cursor: default;
	text-decoration: none;
}
p,
a,
li,
td,
body,
table,
blockquote{
	ms-text-size-adjust: 100%;
	webkit-text-size-adjust: 100%;
}
a[x-apple-data-detectors]{
	color: inherit !important;
	font-family: inherit !important;
	font-size: inherit !important;
	font-weight: inherit !important;
	line-height: inherit !important;
	text-decoration: none !important;
}
table{
	border-collapse: collapse;
	mso-table-lspace: 0pt;
	mso-table-rspace: 0pt;
}
body,
#bodyCell{
	background-color: <?=$background_colour?>;
}
#bodyCell{
	padding: 20px;
}
.container{
	max-width: 600px !important;
	-webkit-box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.4);
	-moz-box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.4);
	box-shadow: 0 0 20px 0 rgba(0, 0, 0,0.4);
}
.header {
	background-color: #ffffff;
}
.subheader{
	background-color: <?=$accent_colour?>;
	padding-top: 18px;
	padding-bottom: 18px;
}
.subheader .mcnTextContent,
.subheader .mcnTextContent p{
	color: <?=$accent_text_colour?>;
	font-family: Helvetica;
	font-size: 15px;
	line-height: 150%;
	text-align: left;
}
.subheader .mcnTextContent a,
.subheader .mcnTextContent p a{
	color: <?=$accent_colour?>;
	font-weight: normal;
	text-decoration: underline;
}
.divider {
	background-color: #ffffff;
}
.divider hr {
	height: 1px;
	color: #EAEAEA;
	background: #EAEAEA;
	font-size: 0;
	border: 0;
}
.content {
	background-color: #ffffff;
	border-bottom: 0;
	border-top: 0;
}
.content,
.content p{
	color: #222222;
	font-family: 'Helvetica Neue', Helvetica, Arial, Verdana, sans-serif;
	font-size: 15px;
	line-height: 150%;
}
.content a,
.content p a{
	color: #202020;
	font-weight: bold;
	text-decoration: none;
}
.listingImage img {
	max-width: 150px;
	width: 150px;
	height: auto;
}
.listingContent {
	padding: 0 20px;
}
.button {
	display: inline-block;
	background-color: <?=$accent_colour?>;
	mso-table-lspace: 0pt;
	mso-table-rspace: 0pt;
	-ms-text-size-adjust: 100%;
	-webkit-text-size-adjust: 100%;
	font-weight: bold;
	letter-spacing: normal;
	line-height: 100%;
	text-align: center;
	text-decoration: none;
	padding: 15px;
	mso-line-height-rule: exactly;
	-ms-text-size-adjust: 100%;
	-webkit-text-size-adjust: 100%;
}
.footer{
	background-color: <?=$accent_colour?>;
	border-bottom: 0;
	border-top: 0;
	padding-bottom: 9px;
	padding-top: 9px;
}
.footer,
.footer p{
	color: <?=$accent_text_colour?>;
	font-family: 'Helvetica Neue', Helvetica, Arial, Verdana, sans-serif;
	font-size: 15px;
	line-height: 150%;
	text-align: center;
}
.footer a,
.footer p a{
	color: <?=$accent_text_colour?>;
	font-weight: bold;
	text-decoration: none;
}
