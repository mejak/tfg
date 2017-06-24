<?php 
//error alerts
$lang['error_login_error'] = 'Invalid username/email or password';
$lang['error_login_disabled'] = 'Your account is disabled';
$lang['error_email_not_exist'] = 'Email address is not registered';
$lang['error_email_not_returned'] = 'Unable to get email address from Facebook. Please signup with form.';
$lang['error_invalid_request'] = 'Invalid request';
$lang['error_admin_delete'] = 'Admins can not be deleted';
$lang['error_disable_self'] = 'You cannot disable yourself';
$lang['error_something_wrong'] = 'Something goes wrong please try again.';
$lang['error_action_not_allowed'] = 'You do not have permission to perform this action';
$lang['error_send_email'] = 'Error sending email, Contact Admin';
$lang['error_no_manage_pages_found'] = 'No manage/fan pages found in the profile.';
$lang['error_perms'] = 'Please check that token has permission ';
$lang['error_app_user'] = 'If token doesn\'t have above permissions then either this Facebook user is not the administrator/developer/tester of this Facebook app or this Facebook user didn\'t granted required permissions to this Facebook app';
$lang['error_no_data_returnend'] = 'No data returned from Facebook';
$lang['error_select_file'] = 'Please select file';
$lang['error_invalid_file_type'] = 'Invalid File type';
$lang['error_app_id_zero'] = 'App ID is 0, if you have installed the script recently, then database is not imported correctly, Please recreate database';
$lang['error_no_fb_account'] = 'No facebook account is added. Please import your Facebook account.';
//php file uploading errors
$lang['errror_file_ini_size'] = "The uploaded file size exceeds the upload_max_filesize directive in php.ini۔ contact your hosting support to increase limit";
$lang['errror_file_missing_temp_folder'] = "Missing a temporary folder on server. contact your hosting support";
$lang['error_zip_extension_not_installed'] = 'PHP zip extension is not installed. Please contact your hosting support to enable this extension. It is required to update campaigner';
$lang['error_update_server_error'] = 'Failed to get response from update server. Please try again later. Sorry for inconvience. Thanks';
$lang['error_update_not_saved'] = 'Unable to save update file. Please allow write permissions(775) on script parent directory';
$lang['error_update_not_open'] = 'Unable to open update file. Please try again';
$lang['error_update_backup'] = 'Unable to take back-up and update script. Please set write permissions(set 755, if its already 755 then 775) on all script folders and sub-folders. See instructions below';
//success alerts
$lang['success_reset_password_mail_sent'] =  'An email with a link to reset password has been sent to your email address. check your mailbox';
$lang['success_password_reset'] =  'Password has been changed successfully';
$lang['success_account_deleted'] =  'Facebook account and all its data is deleted successfully';
$lang['success_pages_saved'] =  '%s page(s) added and %s page(s) updated successfully';
$lang['success_user_deleted'] =  'User has been deleted successfully';
$lang['success_user_created'] =  'New user has been created successfully';
$lang['success_user_updated'] =  'User has been updated successfully';
$lang['success_user_setting_saved'] =  'Settings has been saved successfully';
$lang['success_page_deleted'] =  'Page has been deleted successfully';
$lang['success_pages_deleted'] =  '%s page(s) has been deleted successfully';
$lang['success_campaign_created'] =  'Post has been created and scheduled successfully';
$lang['success_campaign_deleted'] =  'Post has been deleted successfully';
$lang['success_campaign_updated'] =  'Post has been updated and scheduled successfully';
$lang['success_post_updated'] =  'Post has been updated successfully';
$lang['success_post_deleted'] =  'Post has been deleted successfully from database';
$lang['success_post_deleted_facebook'] =  ' and Facebook';
$lang['success_user_disabled'] = 'User has been disabled successfully';
$lang['success_user_enabled'] = 'User has been enabled successfully';
$lang['success_app_configuration'] = 'Configuration has been updated successfully';
$lang['success_script_updated'] = 'Script updated to latest version successfully';
//modals text

$lang['modal_delete_user'] = 'All data of this user will also be deleted. Do you want to delete this user? confirm!';
$lang['modal_delete_fb_account'] = 'All pages of this Facebook account will be deleted from application. Do you want to delete this Facebook account? confirm!';
$lang['modal_delete_pages'] = 'All displaying pages will be deleted from this application. Do you want to delete all displaying pages? confirm!';
$lang['modal_delete_page'] = 'Page will be deleted from this application. Do you want to delete this page? confirm!';
$lang['modal_delete_campaign'] = 'Do you want to delete this post? confirm!';
$lang['modal_delete_post'] = 'Do you want to delete this post? confirm!';
$lang['modal_delete_post_facebook'] = 'Also Delete From Facebook';
$lang['modal_delete_campaign_facebook'] = 'Also delete all posts on Facebook from pages';
$lang['modal_delete_record'] = 'Do you want to delete this record from user account? Confirm!';

//info alerts
$lang['info_no_default_app'] = 'No Facebook app is available to import pages, Please add Facebook app in app configuration';
$lang['info_no_user_app'] = 'No Facebook app is available to import pages, Please add Facebook app in your settings';
$lang['info_can_save_pages'] = 'You can save %s more new pages';
$lang['script_already_updated'] = 'Script is already updated to latest version';
$lang['script_update_available'] = 'New update available.  New version : ';











 ?>