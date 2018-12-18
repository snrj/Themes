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
 * @package Aydogdu
 * @version 1.0
 * @theme Aydogdu
 * @author Snrj - http://smf.konusal.com
 * Copyright 2018 Aydogdu
 *
 */
function template_init()
{
	global $context, $settings, $options, $txt;
	$settings['use_default_images'] = 'never';
	$settings['doctype'] = 'html';
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
	echo '<!DOCTYPE html>
<html lang="', $txt['lang_dictionary'],'" ', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />';
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/responsive.css?fin20" />';
	echo !empty($settings['forum_width']) ? '<style>#header,#wrapper{width: ' . $settings['forum_width'] . ';}</style>' : '', '';
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';
	echo '	
	<script src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script><!-- // --><![CDATA[
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
	echo'
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

	echo'<div class="kapsa"><span></span><div class="duzelt"><div class="masthead"></div></div></div>
		<div id="header"><div id="top_section">
			<div class="user">';

if ($context['user']['is_logged'])
	{
	if (!empty($context['user']['avatar']))
		echo '<img class="avatar" src="', $context['user']['avatar']['href'], '" alt="*"/>';
		else
		echo '<img class="avatar"  src="'.$settings['images_url'].'/noavatar.png" alt="*"/>';
		echo '', $txt['hello_member_ndt'], ' <a href="', $scripturl, '?action=profile">', $context['user']['name'], '</a>';
	}
	elseif (!empty($context['show_login_bar']))
	{
		echo '<div class="buttonlist">
				<ul>
					<li>
						<a class="button" id="giris"><i class="generic_icons login"></i> <span>',$txt['login'],'</span></a> 
					</li>	
					<li>
						<a class="button" href="' . $scripturl . '?action=register"><i class="generic_icons register"></i> <span>',$txt['register'],'</span></a>
					</li>	
				</ul>
			</div>
			<div id="giriss" class="modal">
			<div class="modal-content">
			  <div class="modal-header">
				',$txt['hello_guest'] . $txt['guest_title'],'				
				<span class="close">&times;</span>
			  </div>
			  <div class="modal-body">
				<script src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
				<form id="guest_form" action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
					<dl class="settings">
					<dt>', $txt['username'], ':</dt>
					<dd><input type="text" name="user" class="input_text" /></dd>
					<dt>', $txt['password'], ':</dt>
					<dd><input type="password" name="passwrd" class="input_password" /></dd>
					</dl>
					<div class="centertext">
					<select name="cookielength">
						<option value="60">', $txt['one_hour'], '</option>
						<option value="1440">', $txt['one_day'], '</option>
						<option value="10080">', $txt['one_week'], '</option>
						<option value="43200">', $txt['one_month'], '</option>
						<option value="-1" selected="selected">', $txt['forever'], '</option>
					</select><br />
					<input type="submit" value="', $txt['login'], '" class="button_submit" /><br />
					', $txt['quick_login_dec'], '</div>';

		if (!empty($modSettings['enableOpenID']))
			echo '
					<br /><input type="text" name="openid_identifier" id="openid_url" class="input_text openid_login" />';

		echo '
					<input type="hidden" name="hash_passwrd" value="" /><input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				</form> </div>
				</div>
			</div>			
			<script><!-- // --><![CDATA[
			var modal = document.getElementById(\'giriss\');
			var btn = document.getElementById("giris");
			var span = document.getElementsByClassName("close")[0];
			btn.onclick = function() {
				modal.style.display = "block";
			}
			span.onclick = function() {
				modal.style.display = "none";
			}
			window.onclick = function(event) {
				if (event.target == modal) {
					modal.style.display = "none";
				}
			}
		// ]]></script>';
	}

	echo '
			</div>';
	// the upshrink image, right-floated
	echo '
			<img id="upshrink" src="', $settings['images_url'], '/upshrink.png" alt="*" title="', $txt['upshrink_description'], '" style="display: none;" />
			<div class="normaltext floatright">
				<form id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
					<input type="text" name="search" value="" class="input_text" />&nbsp;
					<input type="submit" name="submit" value="', $txt['search'], '" class="button_submit" />
					<input type="hidden" name="advanced" value="0" />';

	// Search within current topic?
	if (!empty($context['current_topic']))
		echo '
					<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
	// If we're on a certain board, limit it to this board ;).
	elseif (!empty($context['current_board']))
		echo '
					<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

	echo '</form></div>';

	echo '
			
		</div>
			',template_menu(),'

			<div id="upper_section" class="middletext information"', empty($options['collapse_header']) ? '' : ' style="display: none;"', '>
				<h1 class="forumtitle">
					<a href="', $scripturl, '">', empty($context['header_logo_url_html_safe']) ? $context['forum_name'] : '<img src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '</a>
				</h1>';
		echo '
				', empty($settings['site_slogan']) ? '<div class="floatright logotext">' .$txt['logotext']. '</div>' : '<div class="floatright logotext">' . $settings['site_slogan'] . '</div>', '
			
			</div>
		</div>';

	echo '
	<div id="wrapper">
		<div id="upper_section1">
			<div id="inner_wrap">
				<div id="inner_section">
					<div class="user">
						', $context['current_time'], '
					</div>
					<div class="news">';
						if (!empty($settings['enable_news']))
							echo '<p>', $txt['news'], ':', $context['random_news_line'], '</p>';
					echo'				
					</div>
					<hr class="clear">';
		theme_linktree();
		echo '</div>
			</div>
		</div>
		<div id="content_section">
			<div id="main_content_section">';

	// Custom banners and shoutboxes should be placed here, before the linktree.

	// Show the navigation tree.
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

		echo '
			</div>
		</div>
	</div>';

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '<div class="footer">
			<ul class="reset">
				<li class="floatright" style="text-align: right;">
				',$txt['themecop'], '
				</li>
				<li class="copyright">', theme_copyright(), '</li>
			</ul>';
			if ($context['show_load_time'])
			echo '
			<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';
	echo '
	</div>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
		<script><!-- // --><![CDATA[
			var oMainHeaderToggle = new smc_Toggle({
				bToggleEnabled: true,
				bCurrentlyCollapsed: ', empty($options['collapse_header']) ? 'false' : 'true', ',
				aSwappableContainers: [
					\'upper_section\'
				],
				aSwapImages: [
					{
						sId: \'upshrink\',
						srcExpanded: smf_images_url + \'/upshrink.png\',
						altExpanded: ', JavaScriptEscape($txt['upshrink_description']), ',
						srcCollapsed: smf_images_url + \'/upshrink2.png\',
						altCollapsed: ', JavaScriptEscape($txt['upshrink_description']), '
					}
				],
				oThemeOptions: {
					bUseThemeSettings: ', $context['user']['is_guest'] ? 'false' : 'true', ',
					sOptionName: \'collapse_header\',
					sSessionVar: ', JavaScriptEscape($context['session_var']), ',
					sSessionId: ', JavaScriptEscape($context['session_id']), '
				},
				oCookieOptions: {
					bUseCookie: ', $context['user']['is_guest'] ? 'true' : 'false', ',
					sCookieName: \'upshrink\'
				}
			});
		// ]]></script>
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree,$scripturl,$txt;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="navigate_section">
		<ul>';
			if ($context['user']['is_logged'])
			{
				echo '	
					<li class="unread_links">
					<a href="', $scripturl, '?action=unread">', $txt['unread_since_visit'], '</a>
					<a href="', $scripturl, '?action=unreadreplies">', $txt['show_unread_replies'], '</a>
					</li>';
			}
	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo ' &#187;';

		echo '
			</li>';
	}
	echo '
		</ul>
	</div>';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '<div id="main_menu">
		<nav>
        <label for="abc" class="toggle"><span class="icon"></span></label>
        <input type="checkbox" id="abc" />
            <ul class="menu">';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '', '" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						<span class="generic_icons ', $act, '"></span> ', $button['title'], '
					</a>';
		if (!empty($button['sub_buttons']))
		{
			echo '
					<ul>';

			foreach ($button['sub_buttons'] as $childbutton)
			{
				echo '
						<li>
							<a href="', $childbutton['href'], '"', isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', '>
								<span', isset($childbutton['is_last']) ? ' class="last"' : '', '>', $childbutton['title'], !empty($childbutton['sub_buttons']) ? '...' : '', '</span>
							</a>';
				// 3rd level menus :)
				if (!empty($childbutton['sub_buttons']))
				{
					echo '
							<ul>';

					foreach ($childbutton['sub_buttons'] as $grandchildbutton)
						echo '
								<li>
									<a href="', $grandchildbutton['href'], '"', isset($grandchildbutton['target']) ? ' target="' . $grandchildbutton['target'] . '"' : '', '>
										<span', isset($grandchildbutton['is_last']) ? ' class="last"' : '', '>', $grandchildbutton['title'], '</span>
									</a>
								</li>';

					echo '
							</ul>';
				}

				echo '
						</li>';
			}
				echo '
					</ul>';
		}
		echo '
				</li>';
	}

	echo '
			 </ul>
        </nav></div>';
}
// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '> <i class="generic_icons '.$key. '"></i> <span> ' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>',
				implode('', $buttons), '
			</ul>
		</div>';
}

?>