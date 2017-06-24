<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


//current version of script,
define('SCRIPT_VERSION', '1.0');
define('UPDATE_SERVER_URL', 'http://fbcampaigner.com/index.php/update_fpm/');


// Directory path constants
define('PATH_POST_IMAGES', './post_images/');
define('PATH_CANVAS_GOOGLE_IMAGES', './google_images/');
define('FACEBOOK_SDK_SRC_DIR', APPPATH . 'libraries/facebook/Facebook/');
define('PATH_BACKUPS', './backups/');
//alert types
define('ALERT_TYPE_ERROR', 'danger');
define('ALERT_TYPE_SUCCESS', 'success');
define('ALERT_TYPE_INFO', 'info');
define('ALERT_TYPE_WARNING', 'block');
//user types
define('USER_TYPE_ADMIN', 1);
define('USER_TYPE_USER', 2);

define("DASHBOARD_POST_LIMIT", 5);
define("DASHBOARD_PAGE_LIMIT", 5);

//AJAX response type
define('AJAX_RESPONSE_TYPE_REDIRECT', 0);
define('AJAX_RESPONSE_TYPE_SUCCESS', 1);
define('AJAX_RESPONSE_TYPE_ERROR', 2);
//user account limit constants
define('INSIGHTS_ALLOWED_NO', 0);
define('INSIGHTS_ALLOWED_YES', 1);

//token app
define('TOKEN_USER_APP', 0);
define('TOKEN_DEFAULT_APP', 1);
//user status
define('USER_STATUS_INACTIVE', 0);
define('USER_STATUS_ACTIVE', 1);
//pagination constant
define('GET_COUNT', TRUE);
define('GET_RECORDS', FALSE);
//limits
define("PAGE_LIMIT", 500);
//post type
define('POST_TYPE_STATUS', 1);
define('POST_TYPE_LINK', 2);
define('POST_TYPE_PHOTO', 3);
define('POST_TYPE_VIDEO', 4);
//campaign states
define('CAMPAIGN_STATUS_INPROGRESS', 1);
define('CAMPAIGN_STATUS_COMPLETED', 2);
define('CAMPAIGN_STATUS_PAUSED', 3);
define('CAMPAIGN_STATUS_PENDING', 4);
//facebook post states
define('POST_STATUS_ERROR', 0);
define('POST_STATUS_PENDING', 1);
define('POST_STATUS_POSTED', 2);

/* End of file constants.php */
/* Location: ./application/config/constants */

