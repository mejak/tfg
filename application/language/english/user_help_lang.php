<?php

$lang['user_faq_1_question'] = 'What is Spintax and how it works?';
$lang['user_faq_1_video_url'] = '';
$lang['user_faq_1_answer'] = 'Spintax is use to create random content, it used in script to create random posts in campaign. \n 
							 while creating the campaign, enter your post in the following format \n
							 { post1 | post2 | post3 } \n
							 when posting to a node(group, page, profile etc) in campaign, script will pick one of the post randomly and will post it to Facebook \n
							 You can use the above format for all input fields of campaign that belongs to content e.g Status, Link URL, message, title, description, capation, video url, video title, description etc. for url format will be like this \n 
							 { URL 1 | URL 2 | URL 3} \n 
							 You can Spin the whole post or certain words in posts like below \n
							 This is a post with { word1 | word 2 | word 3 } \n
							 If Spintax not working, ask admin to enable spintax in settings';

$lang['user_faq_2_question'] = 'I have error on postings';
$lang['user_faq_2_video_url'] = '';
$lang['user_faq_2_answer'] = 'Put the mouse cursor over error to see error message.';

$lang['user_faq_3_question'] = 'How Delete All Feature works in nodes?';
$lang['user_faq_3_video_url'] = '';
$lang['user_faq_3_answer'] = 'Delete All deletes all the displaying records on page. You can use search field to change displaying records';

$lang['user_faq_4_question'] = 'Error validating access token or token expired';
$lang['user_faq_4_video_url'] = '';
$lang['user_faq_4_answer'] = 'On Accounts page, click "Import Pages", before that you must be login to FB account which has this problem. Select and save the pages, this will updated the access tokens of pages';

$lang['user_faq_5_question'] = 'How to post a video?';
$lang['user_faq_5_video_url'] = '';
$lang['user_faq_5_answer'] = '1. Enter title and description of video (optional) \n
							   2. Enter youtube URL or Facebook public video URL or download URL of video. e.g \n
							      https://www.youtube.com/watch?v=9Z1tD3Pdie2 \n
							   	  https://www.facebook.com/khujleevines/videos/728183300649597/ \n
							   	  http://your-domain.com/videos/video.mp4 \n
							   	Note : Facebook video must have public privacy \n
							   	Some youtube videos are restricted by uploader for playback on other sites. so youtube URL may not work in that case';

$lang['user_faq_6_question'] = 'I can view page insights but I cannot post on Page';
$lang['user_faq_6_video_url'] = '';
$lang['user_faq_6_answer'] = 'Page admin has allowed you to read insights only';

$lang['user_faq_7_question'] = 'Why some inputs are disabled on edit post page';
$lang['user_faq_7_video_url'] = '';
$lang['user_faq_7_answer'] = 'Because Facebook graph API does not allowed to change these fields of post on edit.';

$lang['user_faq_8_question'] = 'What are limits of schedule time of post?';
$lang['user_faq_8_video_url'] = '';
$lang['user_faq_8_answer'] = 'Schedule time must be minimum 10 minutes from current time and maximum 6 months from current time, otherwise post will result in error';

$lang['user_faq_9_question'] = 'Can I backdate a post?';
$lang['user_faq_9_video_url'] = '';
$lang['user_faq_9_answer'] = 'Yes, check schedule checkbox and set schedule time in past';

$lang['user_faq_10_question'] = 'Can I change post timings/scheduling in edit post ?';
$lang['user_faq_10_video_url'] = '';
$lang['user_faq_10_answer'] = 'A post that is already published on timeline, can\'t be scheduled for future \n
							   Schedule time of a scheduled post can be changed within the limits specified above. but a scheduled post cannot be published on edit post page. it will be published on its scheduled time \n
							   A scheduled post for future time, cannot be backdated from current time. However you can backdate a published post';

$lang['user_faq_n_question'] = '';
$lang['user_faq_n_video_url'] = '';
$lang['user_faq_n_answer'] = '';





