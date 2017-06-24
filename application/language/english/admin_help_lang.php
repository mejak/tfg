<?php 

$lang['admin_faq_1_question'] = 'How to add new Language?';
$lang['admin_faq_1_video_url'] = '';
$lang['admin_faq_1_answer'] = 'open application/language. Make a copy of "english" folder. rename it to your language name e.g. "spanish" \n
							  open all files of the new folder and translate them. Don\'t change file names. Just translate sentence on the right of "=" sign. Don\'t miss the commas \n 
							  Go to user settings and select your langauge from dropdown and save. Send developer, a copy of translated folder to included in next update.';

$lang['admin_faq_2_question'] = 'How to change/remove favicon in tab?';
$lang['admin_faq_2_video_url'] = '';
$lang['admin_faq_2_answer'] = 'Delete/replace the assets/img/favicon.ico , if you are replacing, don\'t change filename ' ;

$lang['admin_faq_3_question'] = 'Can I use single Facebook app for all users?';
$lang['admin_faq_3_video_url'] = '';
$lang['admin_faq_3_answer'] = 'First you need to get your Facebook app approved from Facebook for these permissions, manage_pages, publish_pages, publish_actions, read_insights. then you can use this app for all users ';

$lang['admin_faq_4_question'] = 'How to remove help section?';
$lang['admin_faq_4_video_url'] = '';
$lang['admin_faq_4_answer'] = 'from application/views/template.php remove help menu\n remove lines 36-38 and 70-72 \n remove application/controllers/help.php to remove help page';

$lang['admin_faq_5_question'] = 'How to enable connect with Facebook and sign in with Facebook?';
$lang['admin_faq_5_video_url'] = '';
$lang['admin_faq_5_answer'] = 'You need to fill Default APP ID and secret on configuration page and enable Facebook Login/Signup with buttons in right column';

$lang['admin_faq_6_question'] = 'How add/remove questions from help sections?';
$lang['admin_faq_6_video_url'] = '';
$lang['admin_faq_6_answer'] = 'goto application/language/yourlanguage/  \n
								admin_help_lang.php contains admin questions and user_help_lang.php contains user questions \n
								open file you want to edit. There are 3 lines for each question. Follow the format of file to add/remove a faq. \n
								If you remove a faq, re-order faq numbring in file \n
								if you are using single quote \' in question/answer, put a \\ before single quote';


$lang['admin_faq_7_question'] = 'What if change the Facebook app used in script?';
$lang['admin_faq_7_video_url'] = '';
$lang['admin_faq_7_answer'] = 'All users using that app, they will have to re-import Facebook account and pages, this will refresh the access tokens of pages for new app';

$lang['admin_faq_8_question'] = 'Error invalid parameter on advanced section of insights';
$lang['admin_faq_8_video_url'] = '';
$lang['admin_faq_8_answer'] = 'Facebook graph API has a limit of 90 days for insights if you are getting insights in a date range. Make sure your date range spans 90 days max.';

$lang['admin_faq_n_question'] = '';
$lang['admin_faq_n_video_url'] = '';
$lang['admin_faq_n_answer'] = '';






