<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<!-- https://github.com/InterNations/antwort/edit/master/templates/2columns-to-rows.html -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0">    <!-- So that mobile webkit will display zoomed in -->
    <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->

    <title>2 columns to rows template | Antwort</title>

    <style type="text/css">
      
/* Resets: see reset.css for details */
.ReadMsgBody { width: 100%; background-color: #ebebeb;}
.ExternalClass {width: 100%; background-color: #ebebeb;}
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}
body {margin:0; padding:0;}
table {border-spacing:0;}
table td {border-collapse:collapse;}
.yshortcuts a {border-bottom: none !important;}


/* Constrain email width for small screens */
@media screen and (max-width: 600px) {
    table[class="container"] {
        width: 95% !important;
    }
}

/* Give content more room on mobile */
@media screen and (max-width: 480px) {
    td[class="container-padding"] {
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
 }

      
    /* Styles for forcing columns to rows */
    @media only screen and (max-width : 600px) {

        /* force container columns to (horizontal) blocks */
        td[class="force-col"] {
            display: block;
            padding-right: 0 !important;
        }
        table[class="col-2"] {
            /* unset table align="left/right" */
            float: none !important;
            width: 100% !important;

            /* change left/right padding and margins to top/bottom ones */
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }

        /* remove bottom border for last column/row */
        table[id="last-col-2"] {
            border-bottom: none !important;
            margin-bottom: 0;
        }

        /* align images right and shrink them a bit */
        img[class="col-2-img"] {
            float: right;
            margin-left: 6px;
            max-width: 130px;
        }
    }


    </style>

</head>
<body style="margin:0; padding:10px 0;" bgcolor="#ebebeb" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">



<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#ebebeb">
  <tr>
    <td align="center" valign="top" bgcolor="#ebebeb" style="background-color: #ebebeb;">
      <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" bgcolor="#ffffff">
        <tr>
          <td class="container-padding" bgcolor="#ffffff" style="background-color: #ffffff; padding-left: 30px; padding-right: 30px; font-size: 14px; line-height: 20px; font-family: Helvetica, sans-serif; color: #333;">
          
            

<table border="0" cellpadding="0" cellspacing="0" class="columns-container">
<tr>
<td class="container-padding" bgcolor="#ffffff" style="background-color: #ffffff; padding-left: 30px; padding-right: 30px; font-size: 13px; line-height: 20px; font-family: Helvetica, sans-serif; color: #333;" align="left">

<div style="font-weight: bold; font-size: 18px; line-height: 24px; color: #D03C0F;"><br>{{{ ucwords($action) }}} to Support Ticket #{{{ $tickets->id }}}</div>
<br>
		<p><strong>Hello {{{ $to['name'] }}},</strong></p>
		<p>{{ $body }}</p>
		<p>
		@if($action == "open" && isset($tickets->message))
			<hr/>
			{{ Filter::filter(nl2br(strip_tags($tickets->message))) }}
		@elseif($action == "reply" && isset($reply->content))
			<hr/>
			{{ Filter::filter(nl2br(strip_tags($reply->content))) }}
		@endif
		<p>
		<hr/>
		<p>- <strong>{{{ Setting::get('site.name') }}}</strong></p>



          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<br>
</body>
</html>