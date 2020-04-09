<?php
session_cache_limiter('private');
$cache_limiter = session_cache_limiter();

session_cache_expire(15);
$cache_expire = session_cache_expire();

session_start();

require_once('cls.condb.php');

class master
{
  	public static $dbconn;
	public $result, $sql, $table_prefix, $tstart,
      $executedQueries, $queryTime, $dumpSQL, $queryCode;
	
	public static $menuBundle 	 = array();
	public static $menuPortal 	 = array();
	public static $menusFull 	 = array();
	public static $menusType     = array();
	public static $menusChild    = array();
	public static $menusMom      = array();
	public static $menusSection  = array();
	public static $menusSeo      = array();		
	public static $menusTabs     = array();
	
	public static $menuToContents = array();
	public static $menuLocks 	  = array();
	public static $menuIntros     = array();
	
		
	public static $accProfiles 	 = array();
	public static $accProfilesPosts 	 = array();
	
	public static $contMain 	= array();
	public static $contMainNew = array();
	public static $contSection = array();
	
	public static $contSeo      = array();
	public static $contMenus   = array();
	public static $contFront   = array();
	
	
	public static $contMainSummary = array();
	public static $contJsonNews = array();
	public static $contJsonEvents = array();
	
	public static $listGallery = array();
	public static $listGallery_top = array();
	public static $listGallery_cat = array();
	public static $listGallery_banner = array();
	public static $listGallery_long = array();
	
	public static $listProfiles = array();
	
	public static $listResources       = array();		
	public static $listResources_side  = array();
	
	/* ------------------------------------------------------------------------------------------------------------
	@START:: DIRECTORY CAT ARRAYS
	------------------------------------------------------------------------------------------------------------ */	
	public static $directoryCatsMenu  	    = array();	
	
	
	/* ------------------------------------------------------------------------------------------------------------
	@START:: APP-DATA ARRAYS
	------------------------------------------------------------------------------------------------------------ */	
	public static $listComms     	   = array();
	public static $productCatItems 	 = array();
	public static $productItems    	= array();
	public static $unitLabels      	  = array();
	public static $unitLabelsActive    = array();
	
	public static $currRates      	   = array();
	
	public static $listRegions 		 = array();
	public static $listCounties 	    = array();
	
	 
	public function __construct() /*master*/
	{
		
		global $dbhost, $dbuser, $dbpassword, $dbname;
		$this->dbconfig['dbhost'] = DB_HOST; //$dbhost;
		$this->dbconfig['dbname'] = DB_NAME; //$dbname;
		$this->dbconfig['dbuser'] = DB_USER; //$dbuser;
		$this->dbconfig['dbpass'] = DB_PASSWORD; //$dbpassword;
	}
 
	private function destruct__ (){ //unset
		//unset ($this);
	}
 
  	public function getMicroTime() {
     list($usec, $sec) = explode(" ", microtime());
     return ((float)$usec + (float)$sec);
  	}

  	private function dbConnect() {
		$tstart = $this->getMicroTime();
		if(!isset(self::$dbconn)) {
			self::$dbconn = mysqli_connect($this->dbconfig['dbhost'], $this->dbconfig['dbuser'], $this->dbconfig['dbpass'], $this->dbconfig['dbname']) or die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
			mysqli_query(self::$dbconn, "SET SESSION sql_mode = ''");
		}
		
		if(self::$dbconn === false) {
			die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
		}
		
		$tend = $this->getMicroTime();
		$totaltime = $tend-$tstart;
		if($this->dumpSQL) {
			$this->queryCode .= sprintf("Database connection was created in %2.4f s", $totaltime)."";
		}
		$this->queryTime = $this->queryTime+$totaltime;
		
		return self::$dbconn;
  	}


  	public function dbQuery($query) {
	  
		if(empty(self::$dbconn)) { $this->dbConnect(); } //echo $query; exit;
		$tstart = $this->getMicroTime();
		
		if(!$result = mysqli_query(self::$dbconn, $query)) {
		  die("Execution of a query to the database failed. " .mysqli_error(self::$dbconn));
		}
		else {
		  $tend = $this->getMicroTime();
		  $totaltime = $tend-$tstart;
		  $this->queryTime = $this->queryTime+$totaltime;
		  $this->executedQueries = $this->executedQueries+1; //echo count($result);
		  
          $res_type = is_resource($result) ? get_resource_type($result) : gettype($result);
            
          if($res_type == 'object') {
			return $result;
		  } elseif($res_type == 'boolean') {
			return true;
		  } else {
			return false;
		  }
		}
  	}
  
	
	public function dbQueryFetch($query, $key='',  $opt_filter='') {
	  $rows = array();
	  $rs   = $this->dbQuery($query);
	  if($rs === false) { return false; }
	  
	  while ($row = mysqli_fetch_assoc($rs)) {
		$row_clean = array_map("clean_output", $row);
		if($key<>''){
			$tb_key 	= $row_clean[''.trim($key).''];
			$row_data 	= ($opt_filter <> '') ? $row_clean[''.trim($opt_filter).''] : $row_clean;
			$rows[$tb_key] = $row_data;
		} else {
	    	$rows[] = $row_clean;
		}
	  }
	  return $rows;
  	}
	 
  	public function dbQueryMulti($query) {
		foreach($query as $seq_post){
			$result = $this->dbQuery($seq_post);
		}
  	}
		
  	public function recordCount($rs) {
    	return mysqli_num_rows($rs);
  	}

  	public function fetchRow($rs, $mode='both') {
		if(($mode=='both') || ($mode == '')) {
		  return mysqli_fetch_array($rs, MYSQLI_BOTH);
		} elseif($mode=='num') {
		  return mysqli_fetch_row($rs);
		} elseif($mode=='assoc') {
		  return mysqli_fetch_assoc($rs);
		}
		else {
		  die("Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num' or 'both'.");
		}
  	}
  
 	public function affectedRows($rs) {
    	return mysqli_affected_rows(self::$dbconn);
  	}
 
	public function quote_si($value, $uselike = 0) {
		$connection = $this->dbConnect();
		if (is_array($value)) { $value = serialize($value); }
		
		$likehash = "";
		if($uselike == 1) { $likehash = "%"; }
		$value = "'$likehash" . mysqli_real_escape_string($connection, $value) . "$likehash'";
		return $value;
	}
	
  	public function insertId($rs='') {
    	return mysqli_insert_id(self::$dbconn);
  	}
 
  	public function errorNo() {
		$connection = $this->dbConnect();
		return mysqli_errno($connection);
  	}
  
	public function error() {
		$connection = $this->dbConnect();
		return mysqli_error($connection);
	}
	
  	public function freeResult($resultset) {
    	return mysqli_free_result($resultset);
  	}
 
  	public function serverVersion() {
    	return mysqli_get_server_info(self::$dbconn);
  	}
 
  	public function dbClose() {
		if(self::$dbconn) {
		  mysqli_close(self::$dbconn);
		}
  	}
  
  	public function tableStatus($tbname) {
		$sq = "SHOW TABLE STATUS LIKE ".$this->quote_si($tbname)."; ";
		$rs = current($this->dbQueryFetch($sq));
    	return $rs;
  	}
	
	
    /* MySQLi - Field Type to Text */
  	public static function fieldTypeText($type_id)
	{
		static $types;	
		if (!isset($types))
		{
			$types = array();
			$constants = get_defined_constants(true);
			foreach ($constants['mysqli'] as $c => $n) if (preg_match('/^MYSQLI_TYPE_(.*)/', $c, $m)) $types[$n] = $m[1];
		}
	
		return array_key_exists($type_id, $types)? $types[$type_id] : NULL;
	}
 
 
 	/* MySQLi - Field Flag to Text */
	public static function fieldFlagText($flags_num)
	{
		static $flags;
	
		if (!isset($flags))
		{
			$flags = array();
			$constants = get_defined_constants(true);
			foreach ($constants['mysqli'] as $c => $n) if (preg_match('/MYSQLI_(.*)_FLAG$/', $c, $m)) if (!array_key_exists($n, $flags)) $flags[$n] = $m[1];
		}
	
		$result = array();
		foreach ($flags as $n => $t) if ($flags_num & $n) $result[] = $t;
		return implode(' ', $result);
	}



 
/* end class */

}




$cndb = new master();
$cndb->dumpSQL = true; /* boolean */


function pathSlash($str){
	$out = (substr($str,-1) =='/') ? '' : '/';
	return $out;
}

$domain_folder = ($adminConfig['SITE_FOLDER'] <> "") ? $adminConfig['SITE_FOLDER'].'/' : $adminConfig['SITE_FOLDER'];
 
define('SITE_FOLDER', $adminConfig['SITE_FOLDER']  );




$domain_www_off = $_SERVER['HTTP_HOST']; 
$site_path_text = $_SERVER['DOCUMENT_ROOT'].SITE_FOLDER;

//echobr($_SERVER['HTTP_HOST'].$_SERVER['DOCUMENT_ROOT']);

if($_SERVER['HTTP_HOST'] == "localhost" or $_SERVER['HTTP_HOST'] == "10.0.2.2") { 
        
	$GLOBALS['SOCIAL_CONNECT']  = false; 
	$GLOBALS['NOTIFY_DEBUG']    = '1';
	$GLOBALS['NOTIFY_SUPPLIER'] = false;
	
	$domain_url 	 = $_SERVER['HTTP_HOST']; 	
	$stroke 		= ''; //pathSlash($_SERVER['CONTEXT_DOCUMENT_ROOT'],-1);
	$domain_root    = $_SERVER['DOCUMENT_ROOT'].$stroke.SITE_FOLDER; //$_SERVER['DOCUMENT_ROOT']
} 
else{
    
	$GLOBALS['SOCIAL_CONNECT']  = true; 
	$GLOBALS['NOTIFY_DEBUG']    = '';
	$GLOBALS['NOTIFY_SUPPLIER'] = false;
		
	$domain_url 	= $_SERVER['HTTP_HOST']; 	
	$stroke 		= pathSlash($_SERVER['DOCUMENT_ROOT'],-1);
	$domain_root    = $_SERVER['DOCUMENT_ROOT'].$stroke.SITE_FOLDER; 
}


//$GLOBALS['SOCIAL_CONNECT']  = true; 

$GLOBALS['PAGE_HAS_TABS'] 	 	    = false;
$GLOBALS['CONTENT_HAS_GALL'] 	 	 = false;
$GLOBALS['CONTENT_HAS_TABLE'] 		= false;
$GLOBALS['FORM_HAS_MASK'] 			= false;
$GLOBALS['EXISTS_MAILING_ACCOUNT']   = false;

$GLOBALS['FORM_MULTISELECT'] 		 = false;
$GLOBALS['FORM_MULTISELECT_LABEL']   = "";
$GLOBALS['FORM_JWYSWYG'] 		     = false;
$GLOBALS['CONTENT_SHOW_CALENDAR'] 	= false;

$GLOBALS['COMMITTEE_SHOW_DETAIL'] 	= false;

/*$us_admin_portal_id 					= 1; */
$GLOBALS['SYS_CONF'] 		   	= $adminConfig;
$pdb_prefix 					= $GLOBALS['SYS_CONF']['DB_PREFIX'];

$GLOBALS['FORM_KEYTAGS']		= false;


$my_page_head=''; $my_alias_h1=''; $my_alias_h2=''; $cont_alias=''; $showContent='';
		
$ref_path  	= 	$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$ref_path  	= 	substr($ref_path,0,strrpos($ref_path,"/")); 
$ref_page  	= 	substr($_SERVER['REQUEST_URI'],strripos($_SERVER['REQUEST_URI'],"/" )+1);
$ref_ip	  = 	$_SERVER['REMOTE_ADDR'];
$this_page   = 	substr($_SERVER['PHP_SELF'],strripos($_SERVER['PHP_SELF'],"/" )+1);

$ref_qrystr  = "?" . $_SERVER['QUERY_STRING'];				

define('REF_PAGE', $ref_page );
define('REF_QSTR', $ref_qrystr );


	


$domain_conf['live'] = array();
$protocol 		= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

define('SITE_DOMAIN_LIVE', 	   $protocol.$domain_url.SITE_FOLDER );	
define('SITE_PATH',   		  $domain_root);	

define('SITE_TITLE_LONG', 		$adminConfig['SITE_TITLE_LONG'] ); 
define('SITE_TITLE_SHORT', 	   $adminConfig['SITE_TITLE_SHORT'] );
define('SITE_DOMAIN_URI', 		$adminConfig['SITE_DOMAIN_URI'] ); 
define('SITE_DOMAIN_URI_MX', 	$adminConfig['SITE_DOMAIN_URI_MX'] ); 
define('DOMAIN_SLOGAN', 	  	  SITE_TITLE_SHORT); 	


define('SITE_MAIL_SENDER', 	   $adminConfig['SITE_MAIL_SENDER'] ); 
define('SITE_MAIL_TO_BASIC', 	 $adminConfig['SITE_MAIL_TO_BASIC'] ); 
define('SITE_MAIL_FROM_BASIC',  $adminConfig['SITE_MAIL_FROM_BASIC'] ); 

define('SITE_FAVICON', 			  SITE_DOMAIN_LIVE .$adminConfig['SITE_FAVICON']);
define('SITE_LOGO', 			  SITE_DOMAIN_LIVE .$adminConfig['SITE_LOGO']);
define('SITE_LOGO_B', 			  SITE_DOMAIN_LIVE .$adminConfig['SITE_LOGO_B']);
define('SITE_LOGO_C', 			  SITE_DOMAIN_LIVE .$adminConfig['SITE_LOGO_C']);
define('META_LOGO', 			  SITE_DOMAIN_LIVE .$adminConfig['SITE_LOGO']);
define('META_DESC',		     '....'); //$adminConfig['SITE_TITLE_LONG']); 
define('META_KEYS',		      $adminConfig['SITE_TITLE_LONG']); 


define('GALLTHMB_WIDTH', 		 $adminConfig['GALLTHMB_WIDTH']);	/*250*/
define('GALLTHMB_HEIGHT', 		$adminConfig['GALLTHMB_HEIGHT']);  /*160*/

define('GALLIMG_WIDTH', 		  $adminConfig['GALLIMG_WIDTH']);	/*1200*/
define('GALLIMG_HEIGHT', 		 $adminConfig['GALLIMG_HEIGHT']);	/*768*/



if(isset($_SERVER['HTTP_REFERER']))	{ 
	$ref_refer = str_replace(SITE_DOMAIN_LIVE, "", $_SERVER['HTTP_REFERER']); 
	if($ref_refer == '') { $ref_refer = 'index.php'; }
	$ref_back  = $ref_refer; 
}	else { $ref_back	= '';}

define('PAGE_PREV', "<a href=\"$ref_back\">&laquo;  back </a> ");


$GLOBALS['MODULAR_SCHOOL'] 	 	    	= false;

$GLOBALS['MODULAR_ACCOUNTS'] 	 	    = true;
$GLOBALS['MODULAR_ACCOUNTS_ROOT'] 	 	= 'includes/accounts/';
$GLOBALS['MODULAR_ACCOUNTS_POST'] 	 	= 'includes/accounts/php/accounts.post.php';
$GLOBALS['MODULAR_ACCOUNTS_PATH'] 	 	= SITE_PATH.'includes/accounts/';


$GLOBALS['MODULAR_FORUMS'] 	 	    	= true;
$GLOBALS['MODULAR_FORUMS_ROOT'] 	 	= 'includes/forum/';
$GLOBALS['MODULAR_FORUMS_POST'] 	 	= 'includes/forum/forums.post.php';
$GLOBALS['MODULAR_FORUMS_PATH'] 	 	= SITE_PATH.'includes/forum/';


$GLOBALS['MODULAR_CONTENT'] 	 	    = true;
$GLOBALS['MODULAR_CONTENT_PATH'] 	 	= SITE_PATH.'includes/content/';
$GLOBALS['MODULAR_CONTENT_URI'] 	 	= SITE_DOMAIN_LIVE.'includes/content/';


$GLOBALS['MODULAR_SOCIAL'] 	 	    	= true;
$GLOBALS['MODULAR_SOCIAL_PATH'] 	 	= SITE_PATH.'includes/social/';
$GLOBALS['MODULAR_SOCIAL_URI'] 	 		= SITE_DOMAIN_LIVE.'includes/social/';


$GLOBALS['MODULAR_GALLERY'] 	 	    = true;
$GLOBALS['MODULAR_GALLERY_PATH'] 	 	= SITE_PATH.'includes/gallery/';
$GLOBALS['MODULAR_GALLERY_URI'] 	 	= SITE_DOMAIN_LIVE.'includes/gallery/';


$GLOBALS['MODULAR_RESOURCES'] 	 	    = true;
$GLOBALS['MODULAR_RESOURCES_PATH'] 	 	= SITE_PATH.'includes/resources/';
$GLOBALS['MODULAR_RESOURCES_URI'] 	 	= SITE_DOMAIN_LIVE.'includes/resources/';


$GLOBALS['MODULAR_POLLS'] 	 	    = true;
$GLOBALS['MODULAR_POLLS_PATH'] 	 	= SITE_PATH.'includes/survey/';
$GLOBALS['MODULAR_POLLS_URI'] 	 	= SITE_DOMAIN_LIVE.'includes/survey/';
$GLOBALS['MODULAR_POLLS_POST'] 	 	= SITE_DOMAIN_LIVE.'includes/survey/poll_post.php';



$GLOBALS['MEMBER_LOGGED']				= (empty($_SESSION['sess_uawb_front']['member'])) ? false : true;


$GLOBALS['FCTY'] 	 	                = (isset($_REQUEST['fcty'])) ? $_REQUEST['fcty'] : ''; //@$_REQUEST['fcty'];
$GLOBALS['FSEC'] 	 	                = (isset($_REQUEST['fsec'])) ? $_REQUEST['fsec'] : '';

define('UPL_IMAGES',		    SITE_PATH ."assets/image/"); 
define('DISP_IMAGES', 		   SITE_DOMAIN_LIVE ."assets/image/");

define('UPL_GALLERY', 		   SITE_PATH ."assets/userposts/"); 
define('DISP_GALLERY', 		  SITE_DOMAIN_LIVE ."assets/userposts/");

define('UPL_FILES', 		     SITE_PATH ."assets/file/"); 
define('DISP_FILES', 			SITE_DOMAIN_LIVE ."assets/file/"); 

define('UPL_USERPOSTS', 	       SITE_PATH ."assets/userposts/"); 
define('DISP_USERPOSTS', 	   SITE_DOMAIN_LIVE ."assets/userposts/");

define('UPL_AVATARS', 	       SITE_PATH ."assets/userposts/avatars/"); 
define('DISP_AVATARS', 	      SITE_DOMAIN_LIVE ."assets/userposts/avatars/");


define('ERR_NO_IMAGE', 		  DISP_IMAGES ."no_image2.png");
define('ERR_NO_IMAGE_100', 	  DISP_IMAGES ."no_image.png");

define('CRUMBS_SEP', 	  		" &nbsp; / &nbsp; " );

define('COLOR_GREEN_DARK', 	  "#009538" );
define('COLOR_GREEN_FADE', 	  "#E7E2C5" );


define('CONF_LISTS_DATE',   	$adminConfig['_lists_date_format'] );
define('CONF_LISTS_TIME',   	$adminConfig['_lists_time_format'] );

define('CONF_LINK_DOWNLOAD',   'lib.php' );	//viewer.php
define('CONF_LINK_CART',       'home/' );

define('CONF_LINK_FORUM',       'online-forums' ); //'online-forums'
define('CONF_LINK_GALLERY',     'galleries' );

define('CONF_IMG_LOADER', 		'<img src="'.DISP_IMAGES .'icons/a-loader.gif" alt="loading..." />');

define('CONF_LABEL_ITEM_CAT',       'Category:' );


define('SOCIAL_ID_FACEBOOK', 	 $adminConfig['SOCIAL_ID_FACEBOOK'] ); 
define('SOCIAL_ID_TWITTER',  	  $adminConfig['SOCIAL_ID_TWITTER'] ); 
define('SOCIAL_ID_LINKEDIN', 	 $adminConfig['SOCIAL_ID_LINKEDIN'] ); 
define('SOCIAL_ID_GOOGLE',  	   $adminConfig['SOCIAL_ID_GOOGLE'] ); 
define('SOCIAL_ID_YOUTUBE',  	   $adminConfig['SOCIAL_ID_YOUTUBE'] ); 
define('SOCIAL_ID_ADDTHIS',  	   $adminConfig['SOCIAL_ID_ADDTHIS'] ); 


$sys_gallery_cats = array(
	'type' => '_cont'
	);


$thisSite =  SITE_TITLE_LONG;		
?>
