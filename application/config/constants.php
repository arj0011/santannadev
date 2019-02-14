<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

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
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('FROM_EMAIL', 'gsproject.gr@gmail.com');
define('SUPPORT_EMAIL', 'gsproject.gr@gmail.com');
define('SITE_NAME', 'Santanna');
define('DEFAULT_USER_IMG', 'default-148.png');
define('DEFAULT_USER_IMG_PATH', 'assets/img/default-148.png');
define('DEFAULT_NO_IMG', 'noimagefound.jpg');
define('DEFAULT_NO_IMG_PATH', 'assets/img/no_image.jpg');
define('DEFAULT_LOGO', 'assets/img/santanna.png');
define('EDIT_ICON', 'assets/img/edit1.png');
define('DELETE_ICON', 'assets/img/delete.png');
define('ACTIVE_ICON', 'assets/img/active.png');
define('INACTIVE_ICON', 'assets/img/inactive.png');
define('VIEW_ICON', 'assets/img/eye.png');
define('PASSWORD_ICON', 'assets/img/key.png');

/* IOS push notification gateway url */
define('APNS_GATEWAY_URL', 'ssl://gateway.push.apple.com:2195');
define('IPINFODBKEY', 'b2e0bb044541b5d37eda7d925f78e2aa33e8d9969d7b8af8e5c094364d9fe41a');

define('CERTIFICATE_PATH_IOS', APPPATH . "/libraries/Santana-Mykonos.pem");
define('IOS_PASSPHRASE', '123');
define('ADMIN_ID', '1');
define('API_ACCESS_KEY_ANDROID', 'AAAA4nL44qo:APA91bFdpXaqAZSL3xomh_IYpxARxu4gun3QODgSrouOZcBJ8z6H4Pcb65tUfBl4UE-2jB_W4jryKv4ACNBNtRInn0_z50BPSfPwMSpAynZSrr7jHeHsEB1fCTVi03w6Cx3KWUQ7qV8_');
define('GCM_URL', 'https://android.googleapis.com/gcm/send');
define('FCM_URL', 'https://fcm.googleapis.com/fcm/send');
define('DEFAULT_DATE', 'd/m/Y');
define('DEFAULT_DATE_TIME', 'd/m/Y h:i A');
/* Messages constants */
define('GENERAL_ERROR', 'Some error occured, please try again.');
define('EMAIL_SEND_FAILED', 'Failed to sending a mail');
define('NO_CHANGES', 'We didn`t find any changes');
define('BLOCK_USER', 'Your profile has been blocked. Please contact to our support team');
define('DEACTIVATE_USER', 'Currently your profile is deactivated. Please contact to our support team');
define('CURRENCY', '<i class="fa fa-eur" aria-hidden="true"></i>');
define('CARTCURRENCY', 'eur');
switch (ENVIRONMENT){
	case 'development':
		/*define('BRAINTREEENVIRONMENTS','sandbox'); 
		define('BRAINTREEMERCHANTID','z52bf8rhk62vz33d'); 
		define('BRAINTREEPUBLICKEY','7t7vxzvjpmfyhw37'); 
		define('BRAINTREEPRIVATEKEY','19a75fce2033a53fb673a84cc870e0a8');*/

		define('BRAINTREEENVIRONMENTS','sandbox'); 
		define('BRAINTREEMERCHANTID','342qy8wsmhfb6hzq'); 
		define('BRAINTREEPUBLICKEY','4kxs822xr3yh6hnc'); 
		define('BRAINTREEPRIVATEKEY','969be5ecce89a4a01e3a7a862d7d4a48');

		break;
	default :
		define('BRAINTREEENVIRONMENTS','sandbox'); 
		define('BRAINTREEMERCHANTID','z52bf8rhk62vz33d'); 
		define('BRAINTREEPUBLICKEY','7t7vxzvjpmfyhw37'); 
		define('BRAINTREEPRIVATEKEY','19a75fce2033a53fb673a84cc870e0a8');
		break;
}

/* Database tables */
define('ADMIN', 'company');
define('COMPANY_INFO', 'company_info');
define('USERS', 'users');
define('USERS_DEVICE_HISTORY', 'users_device_history');
define('COUNTRY', 'countries');
define('NEWS_CATEGORY', 'news_category');
define('NEWS_TITLE_IMAGE', 'news_title_image');
define('NEWS', 'news');
define('LANGUAGE', 'languages');
define('SERVICE_CATEGORY', 'service_category');
define('RESTAURANT', 'restaurant');
define('FEEDBACK_OF_RESTAURANT', 'feedback_of_restaurant');
define('COMPANY_CITY', 'company_city');
define('CONTACTS', 'contacts');
define('NOTIFICATIONS', 'notifications');
define('OFFER', 'offer');
define('MENUS', 'menus');
define('MENU_CATEGORY', 'menu_category');
define('MENU_SUBCATEGORY', 'menu_subcategory');
define('CONTACTS_EMAIL', 'contacts_email');
define('STORE_SENT_MESSAGE', 'restaurant_sent_message');
define('CMS', 'cms');
define('GROUPS', 'groups');
define('GROUPS_USER', 'groups_user');
define('BOOKING', 'booking');
define('LOYALTY', 'loyalty');
define('USER_OFFERS', 'user_offers');
define('USER_LOYALTY', 'user_loyalty');
define('USER_SCANE_HISTORY', 'user_scane_history');
define('USER_NOTIFICATION', 'users_notifications');
define('AGENTS', 'agents');
define('ADMIN_NOTIFICATION', 'admin_notification');
define('USER_NEWS', 'user_news');
define('EMAIL_CRON', 'email_cron');
define('OFFER_HISTORY', 'offer_history');
define('ROLE', 'role');
define('SPECIAL_REQUEST', 'special_request');
define('STORE', 'store');
define('POINTCONFIG', 'point_config');
define('USERPOINTSWALLET', 'user_points_wallet');
define('USERWALLETHISTORY', 'user_wallet_history');
define('BOOKINGCART', 'booking_cart');
define('CARTPAYMENT', 'cart_payment');

/*Location*/
define('IMAGENAME', 'Location.jpg');
define('LOCATIONWIDTH', '750');
define('LOCATIONHEIGHT', '600');