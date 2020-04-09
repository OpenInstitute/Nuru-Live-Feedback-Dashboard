<?php
	$configTime 	= '2019-03-01';
	
	$s_title 		= "Nuru";
	$s_title_short 	= "Nuru";
	$s_site 		= "Nuru.live";
	$s_site_mail 	= "Nuru.live";

	$adminConfig = array(		
		'SITE_ALIAS' 	  		=> "",
		'SITE_FOLDER' 	  		=> "/dashboard/",
		'SITE_TITLE_LONG'  		=> $s_title,	
		'SITE_TITLE_SHORT' 		=> $s_title_short,
		'SITE_DOMAIN_URI'  		=> $s_site,
		'SITE_DOMAIN_URI_MX'  	=> $s_site_mail,
		'SITE_MAIL_SENDER' 		=> $s_title,
		'SITE_MAIL_TO_BASIC' 	=> "webmaster@gmail.com",	/*"info@".$s_site,*/
		'SITE_MAIL_FROM_BASIC' 	=> "noreply@".$s_site,
		'SITE_LOGO' 			=> "#",
		'SITE_LOGO_B' 			=> "#",
		'SITE_LOGO_C' 			=> "#",
		'SITE_FAVICON' 			=> "#",
		
		'COLOR_BG_MAIN' 		=> "#B4A985",
		'COLOR_BG_SITE' 		=> "#B4A985",
		'COLOR_BG_HEADER' 		=> "#FFF",
		'UPLOAD_MAX_SIZE' 		=> "5000000",
		'GALLTHMB_WIDTH' 		=> "500",
		'GALLTHMB_HEIGHT' 		=> "375",
		'GALLIMG_WIDTH' 		=> "1600",
		'GALLIMG_HEIGHT' 		=> "1125",
		
		'SOCIAL_ID_FACEBOOK' 	    => "#",
        'SOCIAL_ID_FACEBOOK_WIDGET' => "#",
		'SOCIAL_ID_TWITTER' 	    => "#",
		'SOCIAL_ID_TWITTER_WIDGET'  => "#",		
		
		'SOCIAL_ID_YOUTUBE' 	    => "#",
		'SOCIAL_ID_INSTAGRAM' 		=> "#",
		'SOCIAL_ID_LINKEDIN' 		=> "#",
		'SOCIAL_ID_GOOGLE' 			=> "#",
		'SOCIAL_ID_ADDTHIS' 		=> "ra-50f0c76b1a12dd47",
		'SOCIAL_ID_GITHUB' 			=> "#",
		
		'RECAPTCHA_KEY' 			=> "6LfLCZgUAAAAAPRFxsjMBpW3HAMEAlnxiri5YWrQ",
		
		'_lists_date_format' 	=> "%b %e %Y",
		'_lists_time_format' 	=> "%l:%i %p",
		'MySQLDateFormat' 		=> "%m/%d/%Y",
		'PHPDateFormat' 		=> "M j, Y", /*'l M d, Y'*/
		'PHPDateTimeFormat' 	=> "m/d/Y, h:i a"
		
		,'ADM_STYLE_BG' 		=> '#00C0CC'
		,'DB_PREFIX' 			=> 'uawb_'
	);
	
	if($_SERVER['HTTP_HOST'] == "localhost" or $_SERVER['HTTP_HOST'] == "10.0.2.2"){
		$adminConfig['SITE_FOLDER'] = "/dashboard/";
	}
