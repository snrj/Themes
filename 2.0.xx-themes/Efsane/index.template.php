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
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css" />';
	
	// The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	// Here comes the JavaScript bits!
	echo '
	<script src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script src="', $settings['theme_url'], '/scripts/jquery-1.11.1.min.js"></script>
	<script src="', $settings['theme_url'], '/scripts/ayarlar.js"></script>
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
	// ]]></script>
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body>';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	<div id="mcolor">	
			<a class="black" href="', $scripturl, '?variant=black" title="">Black</a>
			<a class="mor" href="', $scripturl, '?variant=mor" title=""></a>
			<a class="orange" href="', $scripturl, '?variant=orange" title=""></a>
			<a class="red" href="', $scripturl, '?variant=red" title=""></a>
			<a class="green" href="', $scripturl, '?variant=green" title=""></a>
			<a class="blue" href="', $scripturl, '?variant=blue" title=""></a>
    </div>';


    echo'
	<div class="sayfaayar">
    	<div class="col-1-1">
			<header>
				<div class="col-1-5">
					<a href="',$scripturl,'"><img class="logost" src="', empty($context['header_logo_url_html_safe']) ? $settings['images_url'].'/img/logo.png' : $context['header_logo_url_html_safe'],'" alt="', empty($settings['site_slogan']) ? 'Logo' : $settings['site_slogan'],'" />
					</a>
				</div>
				<div class="giris">';
			if($context['user']['is_logged'])
			{if (!empty($context['user']['avatar']))
		{echo '<a href="', $scripturl, '?action=profile"><img class="avata" src="', $context['user']['avatar']['href'], '" alt="*" /></a>';}
		else {echo '<a href="', $scripturl, '?action=profile"><img class="avata" src="', $settings['images_url'], '/img/default.png" alt="*" /></a>';}
		echo '<ul><li>', $txt['hello_member_ndt'], ' <a href="', $scripturl, '?action=profile">', $context['user']['name'], '</a></li>
					<li><a class="current" href="', $scripturl, '?action=profile;area=forumprofile"  title="', $txt['forumprofile'], '"><img src="', $settings['images_url'], '/img/user.png" alt="user" /></a></li>
					<li><a href="', $scripturl, '?action=unread" title="', $txt['unread_since_visit'], '"><img src="', $settings['images_url'], '/img/user2.png" alt="user" /></a></li>
					<li><a href="', $scripturl, '?action=unreadreplies" title="', $txt['show_unread_replies'], '"><img src="', $settings['images_url'], '/img/user3.png" alt="user" /></a></li>';
		if ($context['in_maintenance'] && $context['user']['is_admin'])
			echo '<li class="notice">', $txt['maintain_mode_on'], '</li>';
		if (!empty($context['unapproved_members']))
			echo '<li>', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve">', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a> ', $txt['approve_members_waiting'], '</li>';
		if (!empty($context['open_mod_reports']) && $context['show_open_reports'])
			echo '<li><a href="', $scripturl, '?action=moderate;area=reports">', sprintf($txt['mod_reports_waiting'], $context['open_mod_reports']), '</a></li>';
		echo '</ul>';		
			}
			else
			{ 
				echo '
				
				
				 <div id="box-login">
					<div id="box-login-top">
						<form id="guest_form" action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
						<div class="gir"><label for="popupwindows" class="teknoromibutton">', $txt['login'], '</label>
						<div class="kayit">
								<a class="teknoromibutton" href="' , $scripturl , '?action=register">', $txt['register'] ,'</a>
							</div>
						</div>	
					  <input type="checkbox" class="teknoromiwindows" id="popupwindows" name="popupwindows"/>
					  <div class="popupwindows">
							<div class="info">', sprintf($txt['welcome_guest'], $txt['guest_title']), '</div>
							 <fieldset>
							 <input id="username" name="user" placeholder="username" class="form-text" type="text">
							 </fieldset>
							 <fieldset>
							 <input id="password" name="passwrd" placeholder="password" class="form-text" type="password">
							 </fieldset>
							 <select name="cookielength">
							 <option value="60">', $txt['one_hour'], '</option>
							 <option value="1440">', $txt['one_day'], '</option>
							 <option value="10080">', $txt['one_week'], '</option>
							 <option value="43200">', $txt['one_month'], '</option>
							 <option value="-1" selected="selected">', $txt['forever'], '</option>
							 </select>
							 <input type="submit" value="', $txt['login'], '" class="button_submit" /><br />
							 <div class="info">', $txt['quick_login_dec'], '</div>
							<input type="hidden" name="hash_passwrd" value="" /><br /><label for="popupwindows" class="teknoromibutton" id="close">', $txt['find_close'], '</label></div><input type="hidden" name="hash_passwrd" value="" /><input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					</form>
					</div>
				</div>';
			}
			echo'

				</div>
			</header>

			<div class="menuyeri">
			<nav id="cssmenu">
				<div class="gizlilogo">
					<div class="logogizli">
						<a href="',$scripturl,'">
							<img src="',$settings['images_url'],'/img/logo.png" alt="',empty($settings['site_slogan']) ? 'Logo' : $settings['site_slogan'] ,'" />
						</a>
					</div>
				</div>
			<div id="head-mobile"></div>
			<div class="button"></div>';
				 template_menu();
				echo'</nav>
			</div>
           	<div class="headeralt">';

					echo '			
					<form id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
					<input class="inputbox" type="text" name="search" value="" placeholder="', $txt['search'], '" />&nbsp;
					<input type="hidden" name="advanced" value="0" />';

					// Search within current topic?
					if (!empty($context['current_topic']))
						echo '
									<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
					// If we're on a certain board, limit it to this board ;).
					elseif (!empty($context['current_board']))
						echo '
									<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

			echo '</form>';
           	// Show the navigation tree.
			theme_linktree();
			echo '
				</div>';



	echo '<br class="clear" />';

}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
	<br class="clear" />
	<div class="altmenu">
		<a href="'.$scripturl.'">
			<img src="',$settings['images_url'],'/img/logo2.png" alt="',empty($settings['site_slogan']) ? 'Logo' : $settings['site_slogan'],'" />
		</a>
	<div class="sosyalan">
		<a href=""><img src="',$settings['images_url'],'/img/fb-ico.jpg" alt="Facebook" /></a>
		<a href=""><img src="',$settings['images_url'],'/img/insta-ico.jpg" alt="İnstegram" /></a>
		<a href=""><img src="',$settings['images_url'],'/img/gp-ico.jpg" alt="Google+" /></a>
		<a href=""><img src="',$settings['images_url'],'/img/in-ico.jpg" alt="İn" /></a>
		<a href=""><img src="',$settings['images_url'],'/img/tw-ico.jpg" alt="Twitter" /></a>
		<a href=""><img src="',$settings['images_url'],'/img/twitch-ico.jpg" alt="Twitch" /></a>
		<a href=""><img src="',$settings['images_url'],'/img/pint-ico.jpg" alt="Pint" /></a>
		<a href=""><img src="',$settings['images_url'],'/img/yt-ico.jpg" alt="Youtube" /></a>
		<a href=""><img src="',$settings['images_url'],'/img/steam-ico.jpg" alt="Steam" /></a>
	</div>
	</div>';

	echo '
	<div class="altacik">
		<div class="ortagl">', theme_copyright().$txt['efsane_copy'], '</div>
	</div>';
	// Show the load time?
	if ($context['show_load_time'])
		echo '
		<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	echo '</div>'; 
	echo '</div>';

} 
function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
</body></html>';
}


// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="navigate_section">
		<ul>';

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
	</div>
	<br class="clear" />';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '<ul>';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '', 'firstlevel" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>', $button['title'], '
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
			</ul>';
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
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
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