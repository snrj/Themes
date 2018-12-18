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
 
/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/
function template_init()
{
	global $context, $settings, $options, $txt;
	$settings['use_default_images'] = 'never';
	$settings['doctype'] = 'xhtml';
	$settings['theme_version'] = '2.0';
	$settings['use_tabs'] = true;
	$settings['use_buttons'] = true;
	$settings['separate_sticky_lock'] = true;
	$settings['strict_doctype'] = false;
	$settings['message_index_preview'] = false;
	$settings['require_theme_strings'] = true;
}
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>';
echo '<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';
if ($context['right_to_left'])
echo '<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';
	echo '<style  type="text/css">
', !empty($settings['bodyback']) ? 'body{background:'.$settings['bodyback'].';}' : 'body{background:#fff;}', '
', !empty($settings['bodyfont']) ? 'body{font:'.$settings['bodyfont'].';}' : 'body{font:78%/130% Comic Sans MS;}', '
', !empty($settings['windowbgb']) ? '.windowbg, #preview_body{background-color:'.$settings['windowbgb'].';}' : '.windowbg, #preview_body{background-color:#f0f4f7;}', '
', !empty($settings['windowbgc']) ? '.windowbg, #preview_body{color:'.$settings['windowbgc'].';}' : '.windowbg, #preview_body{color:#000;}', '
', !empty($settings['windowbg2b']) ? '.windowbg2, #preview_body{background-color:'.$settings['windowbg2b'].';}' : '.windowbg2, #preview_body{background-color:#f0f4f7;}', '
', !empty($settings['windowbg2c']) ? '.windowbg2, #preview_body{color:'.$settings['windowbg2c'].';}' : '.windowbg2, #preview_body{color:#000;}', '
', !empty($settings['windowbg3b']) ? '.windowbg3, #preview_body{background-color:'.$settings['windowbg3b'].';}' : '.windowbg3, #preview_body{background-color:#cacdd3;}', '
', !empty($settings['windowbg3c']) ? '.windowbg3, #preview_body{color:'.$settings['windowbg3c'].';}' : '.windowbg3, #preview_body{color:#000;}', '
', !empty($settings['catfont']) ? 'h3.catbg, h3.catbg2, h3.titlebg, h4.titlebg, h4.catbg{font-size:'.$settings['catfont'].';height:31px;line-height:31px;overflow:hidden;}' : 'h3.catbg, h3.catbg2, h3.titlebg, h4.titlebg, h4.catbg{overflow:hidden;height:31px;line-height:31px;font-size:1.2em;font-weight:bold;}', '
', !empty($settings['caturl']) ? 'h3.catbg a:link, h3.catbg a:visited, h4.catbg a:link, h4.catbg a:visited, h3.catbg, .table_list tbody.header td, .table_list tbody.header td a{color:'.$settings['caturl'].';}' : 'h3.catbg a:link, h3.catbg a:visited, h4.catbg a:link, h4.catbg a:visited, h3.catbg, .table_list tbody.header td, .table_list tbody.header td a{color:#fff;}', '
', !empty($settings['cattitlea']) ? 'h3.titlebg a, h3.titlebg, h4.titlebg, h4.titlebg a{color:'.$settings['cattitlea'].';}' : 'h3.titlebg a, h3.titlebg, h4.titlebg, h4.titlebg a{color:#000;}', '
', !empty($settings['catbarc']) ? 'div.cat_bar{background:'.$settings['catbarc'].';}' : 'div.cat_bar{background: none repeat scroll 0% 0% #557EA0;}', '
', !empty($settings['catbarr']) ? 'div.cat_bar{border-radius:'.$settings['catbarr'].';}' : 'div.cat_bar{border-radius: 6px;}', '
', !empty($settings['cattitlec']) ? 'div.title_barIC{background:'.$settings['cattitlec'].';}' : 'div.title_barIC{background:linear-gradient(to bottom, #7BA7C7 0%, #FFFFFF 100%);}', '
', !empty($settings['cattitler']) ? 'div.title_barIC{border-radius:'.$settings['cattitler'].';}' : 'div.title_barIC{border-radius: 6px;}', '
', !empty($settings['menucolor']) ? '.dropmenu {background:'.$settings['menucolor'].';}' : '.dropmenu {background:linear-gradient(to bottom, #7BA7C7 0%, #FFFFFF 100%);}', '
', !empty($settings['menurad']) ? '.dropmenu {border-radius:'.$settings['menurad'].';}' : '.dropmenu {border-radius:10px;}', '
', !empty($settings['menubottom']) ? '.dropmenu {margin-bottom:'.$settings['menubottom'].';}' : '.dropmenu {margin-bottom: 10px;}', '
', !empty($settings['menuacolor']) ? '.dropmenu a{color:'.$settings['menuacolor'].';}' : '.dropmenu a{color:#000;}', '
', !empty($settings['menubcolor']) ? '.dropmenu .current a, .dropmenu li:hover > a{background:'.$settings['menubcolor'].';}' : '.dropmenu .current a, .dropmenu li:hover > a{background:#eeeeee;}', '
', !empty($settings['menusubcolor']) ? '.dropmenu ul{background:'.$settings['menusubcolor'].';}' : '.dropmenu ul{background:#eeeeee;}', '
', !empty($settings['menusubr']) ? '.dropmenu ul{border-radius:'.$settings['menusubr'].';}' : '.dropmenu ul{border-radius: 6px;}', '
', !empty($settings['headerback']) ? '#header{background:'.$settings['headerback'].';}' : '#header{background:linear-gradient(to bottom, #1F5E8C 0%, #FFFFFF 100%);}', '
', !empty($settings['headerbotttom']) ? '#header{margin-bottom:'.$settings['headerbotttom'].';}' : '#header{margin-bottom: 10px;}', '
', !empty($settings['headerrad']) ? '#header{border-radius:'.$settings['headerrad'].';}' : '#header{border-radius:6px;}', '
', !empty($settings['contectcolor']) ? '#content_section{background:'.$settings['contectcolor'].';}' : '#content_section{background:linear-gradient(to bottom, #7BA7C7 0%, #FFFFFF 100%);}', '
', !empty($settings['contectrad']) ? '#content_section{border-radius:'.$settings['contectrad'].';}' : '#content_section{border-radius: 6px;}', '
', !empty($settings['forumc']) ? 'h1.forumtitle{font-family:'.$settings['forumc'].';}' : 'h1.forumtitle{font-family:Comic Sans MS;}', '
', !empty($settings['forumb']) ? 'h1.forumtitle{font-size:'.$settings['forumb'].';}' : 'h1.forumtitle{font-size:1.8em;}', '
', !empty($settings['foruma']) ? 'h1.forumtitle a{color:'.$settings['foruma'].';}' : 'h1.forumtitle a{color:#000;}', '
', !empty($settings['subjectfont']) ? '.table_list tbody.content td.info a.subject{font-size:'.$settings['subjectfont'].';}' : '.table_list tbody.content td.info a.subject{font-size:110%;}', '
', !empty($settings['subjectcolor']) ? '.table_list tbody.content td.info a.subject{color:'.$settings['subjectcolor'].';}' : '.table_list tbody.content td.info a.subject{color:#d97b33;}', '
</style><script type="text/javascript" src="', $settings['theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';
	echo '<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';
	echo $context['html_headers'];
	echo '
</head>
<body>';
}
function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	echo !empty($settings['forum_width']) ? '
<div id="wrapper" style="width: ' . $settings['forum_width'] . '">' : '', '
<div id="header"><div class="frame"><div id="top_section"><div class="user">';
	if ($context['user']['is_logged'])
	{   if (!empty($context['user']['avatar']))
		{echo '<a href="', $scripturl, '?action=profile"><img class="avata" src="', $context['user']['avatar']['href'], '" alt="" /></a>';}
		else {echo '<a href="', $scripturl, '?action=profile"><img class="avata" src="', $settings['images_url'], '/avatar.png" alt="" /></a>';}
		echo '<ul><li>', $txt['hello_member_ndt'], ' <a href="', $scripturl, '?action=profile">', $context['user']['name'], '</a></li>
					<li><a class="current" href="', $scripturl, '?action=profile;area=forumprofile"  title="', $txt['forumprofile'], '"><img src="', $settings['images_url'], '/user.png" alt="user" /></a></li>
					<li><a href="', $scripturl, '?action=unread" title="', $txt['unread_since_visit'], '"><img src="', $settings['images_url'], '/user2.png" alt="user" /></a></li>
					<li><a href="', $scripturl, '?action=unreadreplies" title="', $txt['show_unread_replies'], '"><img src="', $settings['images_url'], '/user3.png" alt="user" /></a></li>';
		if ($context['in_maintenance'] && $context['user']['is_admin'])
			echo '<li class="notice">', $txt['maintain_mode_on'], '</li>';
		if (!empty($context['unapproved_members']))
			echo '<li>', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve">', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a> ', $txt['approve_members_waiting'], '</li>';
		if (!empty($context['open_mod_reports']) && $context['show_open_reports'])
			echo '<li><a href="', $scripturl, '?action=moderate;area=reports">', sprintf($txt['mod_reports_waiting'], $context['open_mod_reports']), '</a></li>';
		echo '</ul>';
	}
	elseif (!empty($context['show_login_bar']))
	{
		echo '<script type="text/javascript" src="', $settings['theme_url'], '/scripts/sha1.js"></script>
				<form id="guest_form" action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
				<label for="popupwindows" class="teknoromibutton">', $txt['login'], '</label>
				<label class="teknoromibutton"><a href="' . $scripturl . '?action=register">', $txt['register'], '</a></label>
                <input type="checkbox" class="teknoromiwindows" id="popupwindows" name="popupwindows"/>
                <label class="popupwindows">
					<span style="float: left; margin-top: 10px;">Username:</span><input name="user" class="form-text" type="text"/>
					<span style="float: left; margin-top: 24px;">password:</span><input name="passwrd" class="form-text" type="password"/>
					<input type="submit" value="', $txt['login'], '" class="button_submit" />';
		if (!empty($modSettings['enableOpenID']))
			echo '<br /><input type="text" name="openid_identifier" id="openid_url" size="25" class="input_text openid_login" />';
		echo '<input type="hidden" name="hash_passwrd" value="" /><input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" /><br /><label for="popupwindows" class="tekbutton" id="close">X</label>
					</label></form>';
	}
	echo '</div><div class="news normaltext">
				<form id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
					<input type="text" name="search" value="" class="input_text" />&nbsp;
					<input type="submit" name="submit" value="', $txt['search'], '" class="button_submit" />
					<input type="hidden" name="advanced" value="0" />';
	if (!empty($context['current_topic']))
		echo '<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
	elseif (!empty($context['current_board']))
		echo '<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';
	echo '</form></div></div>
          <h1 class="forumtitle">
				<a href="', $scripturl, '">', empty($context['header_logo_url_html_safe']) ? $context['forum_name'] : '<img src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '</a>
			</h1>';
	echo '', empty($settings['site_slogan']) ? '<img id="smflogo" src="' . $settings['images_url'] . '/smflogo.png" alt="Simple Machines Forum" title="Simple Machines Forum" />' : '<div id="siteslogan" class="floatright">' . $settings['site_slogan'] . '</div>', '
		<br class="clear" />';
	if (!empty($settings['enable_news']))
		echo '<p style="text-align: center;">', $context['random_news_line'], '</p>';
	echo '
	</div></div>';
template_menu();
	echo '<div id="content_section"><div class="frame"><div id="main_content_section">';
	theme_linktree();
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	echo '</div></div></div>';
	echo '
	<div id="footer_section"><div class="frame">
		<ul class="reset"><li class="copyright">', theme_copyright(), '</li><li><a href="http://smf.konusal.com" title="smf destek">smf destek</a></li></ul>';
	if ($context['show_load_time'])
		echo '<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';
	echo '</div></div>', !empty($settings['forum_width']) ? '</div>' : '';
}
function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	echo '</body></html>';
}
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;
	echo '<div class="navigate_section">
		<ul>';
	foreach ($context['linktree'] as $link_num => $tree)
	{echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];
		if ($link_num != count($context['linktree']) - 1)
			echo ' &#187;';
		echo '</li>';
	}
	echo '</ul></div>';
	$shown_linktree = true;
}
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;
	echo '<ul class="dropmenu">';
	foreach ($context['menu_buttons'] as $act => $button)
	{echo '<li id="button_', $act, '"><a class="', $button['active_button'] ? 'active ' : '', '" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '><span class="', isset($button['is_last']) ? 'last ' : '', '">', $button['title'], '</span></a>';
		if (!empty($button['sub_buttons']))
		{echo '<ul>';
			foreach ($button['sub_buttons'] as $childbutton)
			{echo '<li><a href="', $childbutton['href'], '"', isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', '><span', isset($childbutton['is_last']) ? ' class="last"' : '', '>', $childbutton['title'], !empty($childbutton['sub_buttons']) ? '...' : '', '</span></a>';
				if (!empty($childbutton['sub_buttons']))
				{echo '<ul>';
					foreach ($childbutton['sub_buttons'] as $grandchildbutton)
						echo '<li><a href="', $grandchildbutton['href'], '"', isset($grandchildbutton['target']) ? ' target="' . $grandchildbutton['target'] . '"' : '', '><span', isset($grandchildbutton['is_last']) ? ' class="last"' : '', '>', $grandchildbutton['title'], '</span></a></li>';
					echo '</ul>';
				}
				echo '</li>';
			}
				echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;
	if (!is_array($strip_options))
		$strip_options = array();
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}
	if (empty($buttons))
		return;
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);
	echo '<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '><ul>',implode('', $buttons), '</ul></div>';
}
?>