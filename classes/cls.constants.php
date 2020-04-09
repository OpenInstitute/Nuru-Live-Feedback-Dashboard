<?php 
ini_set("display_errors", "on");
//if($_SERVER['HTTP_HOST'] == "localhost"){ ini_set("display_errors", "on"); ini_set('error_reporting', 'on'); } 

date_default_timezone_set('Africa/Nairobi');
//include('inc_pageload_hd.php');
require_once('cls.formats.php');
require_once('cls.config.php');	
//require_once('cls.sessions.php');	
require_once('cls.defines.php');
require_once('cls.select.php');
//require_once('cls.data.site.php');
//require_once('cls.data.resources.php');


require_once('cls.displays.php');
//require_once('cls.post.php');


$msge_array  = array(
		199  => "You have been logged out. Login to proceed.",
		3 => "Your password was reset. Check your email for the new password.",
		1  => "Thank you. Feedback Posted Successfully",
		2 => "Your subscription for updates has been saved.",
        5  => "Thank you. Course Request Submitted. We will get in touch.",
		7  => "Update successfull.",
		8  => "Your Online Application was received. We will contact you through details provided.",
		
		/* account alerts */
		//101 => "Welcome. ",
		106 => "Account Verified. Login using your credentials. ",		
		100 => "<b class='txtred'>Error!</b> Please enter a valid email.",				
		114 => "<b class='txtred'>Error!</b> Please confirm your login details.",
		115 => "<b class='txtred'>Error!</b> Password NOT changed. Make a new <a href='profile.php?fc=pass_new'>Forgot Password request</a>.",
		116 => "<b class='txtred'>Error!</b> Passwords Dont Match.",		
		117 => "<b class='txtred'>Error!</b> Account Registration NOT Successfull. Try again or contact the Administrator.",
		
		20 => "<b class='txtred'>Error!</b> Account with specified Email exists!",
		21 => "<b class='txtred'>Error!</b> Account does NOT exist or is not verified.",
		
		22 => "Account Sign Up: Check email for confirmation details.",
		23 => "Log in below to proceed.",
		24 => "Message sent.",
		25 => "Your contribution was posted successfull.",
		26 => "Forgot Password: Check your email for a verification message. <b>If it is not in your inbox, check in the SPAM folder</b>.",
		27 => "Success: New password saved. <a href='profile.php'>Click here to Login </a>.",
		
		// APPLICATION FORMS	
		32 => "Partner Registration: Check your email for confirmation link.",
		33 => "Listing Request: <br>Check your email for confirmation link.",
		34 => "Advert Post: <br>Check your email for confirmation link.",
		35 => "Message Pending.",
		36 => "Message Pending.",
		
		// USER POSTS	
		201 => "Your comments have been submitted.<br>Posted comments will be published once approved.",
		202 => "Check your email for account verification link.",
		203 => "Account Verified.<br>Awaiting approval from the administrator.",
		205 => "Account Verified.",
		206 => "Your details have been submitted.",
		207 => "<b class='txtred'>Error!</b> Details NOT submitted.",		
		
		// ASSEMBLY FUNCTIONS
		221 => "Disabled Process.<br>You have pending Un-surrendered Imprest(s).",
		223 => "Invalid Request.<br>Applicable to Members of County Assembly Only.",
		
		251 => "Meeting for this date already exists for this Sector!",
		
		// ADMIN NOTIFICATIONS	
		241 => "Request Processed.",
		
		401 => "The requested URL was not found on this server.",
		
		);
		

$mimetypes = array(
			"application/pdf",
			"application/msword", 
			"application/vnd.openxmlformats-officedocument.wordprocessingml.document", 		
			"application/vnd.ms-powerpoint", 
			"application/vnd.openxmlformats-officedocument.presentationml.presentation",
			"application/vnd.ms-excel",
			"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
			"text/plain", "text/csv", "text/comma-separated-values",
			"image/jpeg", "image/jpe", "image/jpg", "image/pjpeg", "image/gif", "image/png", "image/x-png", "application/zip"
			);

$uploadMime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,video/mp4,image/png,image/jpg,image/jpeg,application/zip';

$imageMime = array("jpeg", "jpe", "jpg", "pjpeg", "gif", "png", "x-png" );



$m2_data=new displays;
$m2_data->addir = $dir; 

if(strpos($_SERVER['REQUEST_URI'],'sysadm/')) //($this_page == 'home.php')
{		
	echobr('admin');
	
}


/*$ddSelect = new drop_downs;*/








/******************************************************************
@begin :: CACHE DATA FUNCTIONS
********************************************************************/



/******************************************************************
@end :: CACHE DATA FUNCTIONS
********************************************************************/

function closestDate($dates, $findate)
{
	$newDates = array();
	foreach($dates as $date) { $newDates[] = $date['ev_date']; }
	sort($newDates);
	foreach ($newDates as $a) { if ($a >= $findate) { return $a; } }
	return end($newDates);
}


function modHeader($title, $opts = ''){
	$body_class = '';
	if(is_array($opts)){
		if(!empty($opts['body'])) { $body_class = $opts['body']; }
	}
	return '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">'.$title.'</h4><a href="#close-modal" rel="modal:close" class="close-modal ">Close</a></div><div class="modal-body '.$body_class.'">';
}

function modFooter(){
	return '</div></div></div>';
}



$cms_bg_color = $GLOBALS['SYS_CONF']['ADM_STYLE_BG'];

$my_redirect = '';

$GLOBALS['TW_URL'] = '';
$GLOBALS['FB_URL'] = '';

$has_results = false;

?>
