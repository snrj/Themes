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
 * @package SnrjForo
 * @version 1.0
 * @theme SnrjForo
 * @author Snrj - http://smf.konusal.com
 * Copyright 2016 SnrjForo
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
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/bootstrap.css" />	
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
	<script src="', $settings['theme_url'], '/scripts/jquery.min.js"></script>	
	<script src="', $settings['theme_url'], '/scripts/bootstrap.min.js"></script>
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

	echo '
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
	<div class="header">
			<div class="col-sm-6">
				', empty($context['header_logo_url_html_safe']) ? '<img class="snrjlogo" src="' . $settings['images_url'] . '/smflogo.png" alt="' . $context['forum_name'] . '" title="' . $context['forum_name'] . '" />' : '<img class="snrjlogo" src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '
			</div>
			<div class="col-sm-6">';
				arama();	
			echo'</div>
			<div class="col-sm-12">';
				template_menu();
		echo'</div>
	</div>
	<div class="container">
		<div class="row">';
			haberler();	
			theme_linktree();
	echo '
	<h1 class="forumtitle">', empty($settings['site_slogan']) ?'<a href="'. $scripturl.'">'.$context['forum_name'].'</a>' : '<a href="'.$scripturl.'">' . $settings['site_slogan'] . '</a>', '</h1>
	<div id="content_section">
		<div id="main_content_section">';
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
		</div>
	</div>';
	echo '
	</div>
</div>
	<div class="footer">
		<ul class="reset">
			<li class="copyright pull-left">', theme_copyright(), '</li>
			<li class="pull-right">', $txt['themeauthor'], '</li>
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
</body></html>';
}

function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="ullinktree">
		<ol class="breadcrumb breadcrumb-arrow">';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
					<li class="linktree">';

		echo $settings['linktree_link'] && isset($tree['url']) ? '<a href="' . $tree['url'] . '">' . ($link_num == 0 ? '<span class="generic_icons home"></span>' : $tree['name']) . '</a>' : $tree['name'];

		echo '
					</li>';
	}
	echo '
		</ol>
	</div>';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
    </div>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="nav navbar-nav">';
	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '', 'firstlevel" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						<span class="generic_icons ', $act, '"></span><span class="', isset($button['is_last']) ? 'last ' : '', 'firstlevel">', $button['title'], '</span>
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
	echo '</ul>
		',giris(),'
    </div>
  </div>
</nav>';
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
				<a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="btn btn-default btn-xs ' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span class="generic_icons '.$key.'"></span> ' . $txt[$value['text']] . '</a>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="btn-group ', !empty($direction) ? ' pull-' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			',
				implode('', $buttons), '
			
		</div>';
}
function giris()
{
	global $settings, $modSettings, $context, $txt, $scripturl;

if ($context['user']['is_logged'])
	{
	echo'<div class="dropdown pull-right">
		<button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">';
			if (!empty($context['user']['avatar']))
				echo '<img class="img-circle menuavatar" src="', $context['user']['avatar']['href'], '" alt="*"/>';
			else
				echo '<img class="img-circle menuavatar"  src="'.$settings['images_url'].'/theme/default_avatar.png" alt="*"/>';
			echo '', $txt['hello_member_ndt'], ' ', $context['user']['name'], '
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu userli">
					<li><a href="', $scripturl, '?action=profile"><span class="generic_icons members"></span> ', $txt['profile'], '</a></li>
					<li><a href="', $scripturl, '?action=profile;area=account"><span class="generic_icons members_request"></span> ', $txt['account'], '</a></li>
					<li><a href="', $scripturl, '?action=profile;area=forumprofile"><span class="generic_icons starters"></span> ', $txt['forumprofile'], '</a></li>
					<li><a href="', $scripturl, '?action=pm"><span class="generic_icons pm"></span> 
					', $txt['pm_short'], '
					', !empty($context['user']['unread_messages']) ? '<strong>'.$context['user']['unread_messages'] .'</strong>' : '', '
					</a></li>
					<li><a href="', $scripturl, '?action=unread"><span class="generic_icons replies"></span> ', $txt['unread_since_visit'], '</a></li>
					<li><a href="', $scripturl, '?action=unreadreplies"><span class="generic_icons drafts"></span>  ', $txt['show_unread_replies'], '</a></li>';
		if ($context['in_maintenance'] && $context['user']['is_admin'])
			echo '
					<li class="dropdown-header">', $txt['maintain_mode_on'], '</li>';
		if (!empty($context['unapproved_members']))
			echo '<li>
					', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve"><span class="generic_icons approve"></span> ', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a> ', $txt['approve_members_waiting'], '</li>';

		if (!empty($context['open_mod_reports']) && $context['show_open_reports'])
			echo '
					<li><a href="', $scripturl, '?action=moderate;area=reports"><span class="generic_icons warning_moderate"></span>  ', sprintf($txt['mod_reports_waiting'], $context['open_mod_reports']), '</a></li>';

		echo '<li class="dropdown-header"><span class="generic_icons history"></span> ', $context['current_time'], '</li> 
		</ul>
	</div>';
	}
	elseif (!empty($context['show_login_bar']))
	{
		echo '
		<ul class="nav navbar-nav navbar-right">
		<li><a class="btn btn-sm" data-toggle="modal" data-target="#giris"><span class="generic_icons login"></span> ',$txt['login'],'</a> 
		<li><a class="btn btn-sm" href="' . $scripturl . '?action=register"><span class="generic_icons register"></span> ',$txt['register'],'</a>
		</ul>
		<div id="giris" class="modal fade " role="dialog">
		  <div class="modal-dialog modal-sm">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">',$txt['hello_guest'] . $txt['guest_title'],'</h4>
			  </div>
			  <div class="modal-body">
				<script src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
				<form class="form-horizontal" id="guest_form" action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
					<div class="input-group">
					<span class="input-group-addon"><span class="generic_icons members"></span></span>
					<input type="text" name="user"  class="form-control" placeholder="',$txt['username'],'"/>
					</div>
					<div class="input-group">
					<span class="input-group-addon"><span class="generic_icons login"></span></span>
					<input type="password" name="passwrd"  class="form-control" placeholder="',$txt['password'],'"/>
					</div>
					<select name="cookielength" class="form-control">
						<option value="60">', $txt['one_hour'], '</option>
						<option value="1440">', $txt['one_day'], '</option>
						<option value="10080">', $txt['one_week'], '</option>
						<option value="43200">', $txt['one_month'], '</option>
						<option value="-1" selected="selected">', $txt['forever'], '</option>
					</select>';
		if (!empty($modSettings['enableOpenID']))
			echo '
					<br /><input type="text" name="openid_identifier" id="openid_url" size="25" class="form-control openid_login" />';
		echo '		<br/>
					<div class="form-group text-center"><input type="submit" class="btn btn-success" value="', $txt['login'], '"  />
					</div>
					<input type="hidden" name="hash_passwrd" value="" />
				</form> </div>
				</div>
			</div></div>';
	}
}
function arama()
{
	global $context, $txt, $scripturl;

echo'<form class="arama" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
			  <div class="input-group">
				<input type="text" name="search" class="form-control"  placeholder="', $txt['search'], '"/> 
				<div class="input-group-btn">
				  <button class="btn btn-default" type="submit">
				   <span class="generic_icons search"></span>
				  </button>
				</div>
			  </div>	
	</form>';

}
function haberler()
{
	global $context, $txt, $settings;

	if (!empty($settings['enable_news']))
		echo '<div class="haberler"><p>', $txt['news'], ': ', $context['random_news_line'], '</p></div>';

}

?>