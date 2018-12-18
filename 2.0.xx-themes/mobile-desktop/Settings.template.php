<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */
 
 /**
 * @Theme mobile-desktop
 * @version 1.0
 * @author snrj <teknorom@teknoromi.com>
 * @copyright 2014 snrj
 * @license http://www.mozilla.org/MPL/MPL-1.1.html
 * 
 */

function template_options()
{
	global $context, $settings, $options, $scripturl, $txt;

	$context['theme_options'] = array(
		array(
			'id' => 'show_board_desc',
			'label' => $txt['board_desc_inside'],
			'default' => true,
		),
		array(
			'id' => 'show_children',
			'label' => $txt['show_children'],
			'default' => true,
		),
		array(
			'id' => 'use_sidebar_menu',
			'label' => $txt['use_sidebar_menu'],
			'default' => true,
		),
		array(
			'id' => 'show_no_avatars',
			'label' => $txt['show_no_avatars'],
			'default' => true,
		),
		array(
			'id' => 'show_no_signatures',
			'label' => $txt['show_no_signatures'],
			'default' => true,
		),
		array(
			'id' => 'show_no_censored',
			'label' => $txt['show_no_censored'],
			'default' => true,
		),
		array(
			'id' => 'return_to_post',
			'label' => $txt['return_to_post'],
			'default' => true,
		),
		array(
			'id' => 'no_new_reply_warning',
			'label' => $txt['no_new_reply_warning'],
			'default' => true,
		),
		array(
			'id' => 'view_newest_first',
			'label' => $txt['recent_posts_at_top'],
			'default' => true,
		),
		array(
			'id' => 'view_newest_pm_first',
			'label' => $txt['recent_pms_at_top'],
			'default' => true,
		),
		array(
			'id' => 'posts_apply_ignore_list',
			'label' => $txt['posts_apply_ignore_list'],
			'default' => false,
		),
		array(
			'id' => 'wysiwyg_default',
			'label' => $txt['wysiwyg_default'],
			'default' => false,
		),
		array(
			'id' => 'popup_messages',
			'label' => $txt['popup_messages'],
			'default' => true,
		),
		array(
			'id' => 'copy_to_outbox',
			'label' => $txt['copy_to_outbox'],
			'default' => true,
		),
		array(
			'id' => 'pm_remove_inbox_label',
			'label' => $txt['pm_remove_inbox_label'],
			'default' => true,
		),
		array(
			'id' => 'auto_notify',
			'label' => $txt['auto_notify'],
			'default' => true,
		),
		array(
			'id' => 'topics_per_page',
			'label' => $txt['topics_per_page'],
			'options' => array(
				0 => $txt['per_page_default'],
				5 => 5,
				10 => 10,
				25 => 25,
				50 => 50,
			),
			'default' => true,
		),
		array(
			'id' => 'messages_per_page',
			'label' => $txt['messages_per_page'],
			'options' => array(
				0 => $txt['per_page_default'],
				5 => 5,
				10 => 10,
				25 => 25,
				50 => 50,
			),
			'default' => true,
		),
		array(
			'id' => 'calendar_start_day',
			'label' => $txt['calendar_start_day'],
			'options' => array(
				0 => $txt['days'][0],
				1 => $txt['days'][1],
				6 => $txt['days'][6],
			),
			'default' => true,
		),
		array(
			'id' => 'display_quick_reply',
			'label' => $txt['display_quick_reply'],
			'options' => array(
				0 => $txt['display_quick_reply1'],
				1 => $txt['display_quick_reply2'],
				2 => $txt['display_quick_reply3']
			),
			'default' => true,
		),
		array(
			'id' => 'display_quick_mod',
			'label' => $txt['display_quick_mod'],
			'options' => array(
				0 => $txt['display_quick_mod_none'],
				1 => $txt['display_quick_mod_check'],
				2 => $txt['display_quick_mod_image'],
			),
			'default' => true,
		),
	);
}

function template_settings()
{
	global $context, $settings, $options, $scripturl, $txt;

	$context['theme_settings'] = array(
		array(
			'id' => 'header_logo_url',
			'label' => $txt['header_logo_url'],
			'description' => $txt['header_logo_url_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'site_slogan',
			'label' => $txt['site_slogan'],
			'description' => $txt['site_slogan_desc'],
			'type' => 'text',
		),
        array(
			'id' => 'smiley_sets_default',
			'label' => $txt['smileys_default_set_for_theme'],
			'options' => $context['smiley_sets'],
			'type' => 'text',
		),
		array(
			'id' => 'forum_width',
			'label' => $txt['forum_width'],
			'description' => $txt['forum_width_desc'],
			'type' => 'text',
			'size' => 8,
		),
	'',
		array(
			'id' => 'linktree_link',
			'label' => $txt['current_pos_text_img'],
		),
		array(
			'id' => 'show_mark_read',
			'label' => $txt['enable_mark_as_read'],
		),
		array(
			'id' => 'allow_no_censored',
			'label' => $txt['allow_no_censored'],
		),
		array(
			'id' => 'enable_news',
			'label' => $txt['enable_random_news'],
		),
	'',
		array(
			'id' => 'show_newsfader',
			'label' => $txt['news_fader'],
		),
		array(
			'id' => 'newsfader_time',
			'label' => $txt['admin_fader_delay'],
			'type' => 'number',
		),
		array(
			'id' => 'number_recent_posts',
			'label' => $txt['number_recent_posts'],
			'description' => $txt['number_recent_posts_desc'],
			'type' => 'number',
		),
		array(
			'id' => 'show_stats_index',
			'label' => $txt['show_stats_index'],
		),
		array(
			'id' => 'show_latest_member',
			'label' => $txt['latest_members'],
		),
		array(
			'id' => 'show_group_key',
			'label' => $txt['show_group_key'],
		),
		array(
			'id' => 'display_who_viewing',
			'label' => $txt['who_display_viewing'],
			'options' => array(
				0 => $txt['who_display_viewing_off'],
				1 => $txt['who_display_viewing_numbers'],
				2 => $txt['who_display_viewing_names'],
			),
			'type' => 'number',
		),
	'',
		array(
			'id' => 'show_modify',
			'label' => $txt['last_modification'],
		),
		array(
			'id' => 'show_profile_buttons',
			'label' => $txt['show_view_profile_button'],
		),
		array(
			'id' => 'show_user_images',
			'label' => $txt['user_avatars'],
		),
		array(
			'id' => 'show_blurb',
			'label' => $txt['user_text'],
		),
		array(
			'id' => 'show_gender',
			'label' => $txt['gender_images'],
		),
		array(
			'id' => 'hide_post_group',
			'label' => $txt['hide_post_group'],
			'description' => $txt['hide_post_group_desc'],
		),
	'',
		array(
			'id' => 'show_bbc',
			'label' => $txt['admin_bbc'],
		),
		array(
			'id' => 'additional_options_collapsable',
			'label' => $txt['additional_options_collapsable'],
		),
	'',
		array(
			'id' => 'bodyback',
			'label' => $txt['bodyback'],
			'description' => $txt['bodyback_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'bodyfont',
			'label' => $txt['bodyfont'],
			'description' => $txt['bodyfont_desc'],
			'type' => 'text',
		),
	'',
		array(
			'id' => 'headerback',
			'label' => $txt['headerback'],
			'description' => $txt['headerback_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'headerbotttom',
			'label' => $txt['headerbotttom'],
			'description' => $txt['headerbotttom_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'headerrad',
			'label' => $txt['headerrad'],
			'description' => $txt['headerrad_desc'],
			'type' => 'text',
		),
		'',
		array(
			'id' => 'catbarc',
			'label' => $txt['catbarc'],
			'description' => $txt['catbarc_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'catbarr',
			'label' => $txt['catbarr'],
			'description' => $txt['catbarr_desc'],
			'type' => 'text',
		),
		
		array(
			'id' => 'caturl',
			'label' => $txt['caturl'],
			'description' => $txt['caturl_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'catfont',
			'label' => $txt['catfont'],
			'description' => $txt['catfont_desc'],
			'type' => 'text',
		),
		'',
		array(
			'id' => 'subjectfont',
			'label' => $txt['subjectfont'],
			'description' => $txt['subjectfont_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'subjectcolor',
			'label' => $txt['subjectcolor'],
			'description' => $txt['subjectcolor_desc'],
			'type' => 'text',
		),
		'',
		array(
			'id' => 'cattitlec',
			'label' => $txt['cattitlec'],
			'description' => $txt['cattitlec_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'cattitler',
			'label' => $txt['cattitler'],
			'description' => $txt['cattitler_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'cattitlea',
			'label' => $txt['cattitlea'],
			'description' => $txt['cattitlea_desc'],
			'type' => 'text',
		),
		'',
		array(
			'id' => 'contectcolor',
			'label' => $txt['contectcolor'],
			'description' => $txt['contectcolor_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'contectrad',
			'label' => $txt['contectrad'],
			'description' => $txt['contectrad_desc'],
			'type' => 'text',
		),
		'',
		array(
			'id' => 'menucolor',
			'label' => $txt['menucolor'],
			'description' => $txt['menucolor_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'menurad',
			'label' => $txt['menurad'],
			'description' => $txt['menurad_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'menubottom',
			'label' => $txt['menubottom'],
			'description' => $txt['menubottom_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'menuacolor',
			'label' => $txt['menuacolor'],
			'description' => $txt['menuacolor_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'menubcolor',
			'label' => $txt['menubcolor'],
			'description' => $txt['menubcolor_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'menusubcolor',
			'label' => $txt['menusubcolor'],
			'description' => $txt['menusubcolor_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'menusubr',
			'label' => $txt['menusubr'],
			'description' => $txt['menusubr_desc'],
			'type' => 'text',
		),
		'',
		array(
			'id' => 'foruma',
			'label' => $txt['foruma'],
			'description' => $txt['foruma_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'forumb',
			'label' => $txt['forumb'],
			'description' => $txt['forumb_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'forumc',
			'label' => $txt['forumc'],
			'description' => $txt['forumc_desc'],
			'type' => 'text',
		),
		'',
		array(
			'id' => 'windowbgb',
			'label' => $txt['windowbgb'],
			'description' => $txt['windowbgb_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'windowbgc',
			'label' => $txt['windowbgc'],
			'description' => $txt['windowbgb_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'windowbg2b',
			'label' => $txt['windowbg2b'],
			'description' => $txt['windowbgb_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'windowbg2c',
			'label' => $txt['windowbg2c'],
			'description' => $txt['windowbgb_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'windowbg3b',
			'label' => $txt['windowbg3b'],
			'description' => $txt['windowbgb_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'windowbg3c',
			'label' => $txt['windowbg3c'],
			'description' => $txt['windowbgb_desc'],
			'type' => 'text',
		),
	);
}

?>