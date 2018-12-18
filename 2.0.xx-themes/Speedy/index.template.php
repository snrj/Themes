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
 * @package Speedy
 * @version 1.0
 * @theme Speedy
 * @author Snrj - http://smf.konusal.com
 * Copyright 2017 Speedy
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
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css?fin20" />	
	<script type="text/javascript" async="" src="', $settings['theme_url'], '/scripts/jquery.min.js?fin20"></script>	
	<script type="text/javascript" async="" src="', $settings['theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript" async="" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
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
		template_menu();
		echo '
		<div id="header">
			<a href="', $scripturl, '">
				', empty($context['header_logo_url_html_safe']) ? '<img id="smflogo" src="' . $settings['images_url'] . '/smflogo.png?fin20" width="88" height="37" alt="' . $context['forum_name'] . '"/>' : '<img src="' . $context['header_logo_url_html_safe'] . '?fin20" alt="' . $context['forum_name'] . '" />', '
			</a>
				<form id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
					<div class="input-group">
						<input type="text" name="search" class="form-control" placeholder="', $txt['search'], '">
						<div class="input-group-btn">
						  <button class="btn btn-default" type="submit">
							<i class="fa fa-search fa-fw"></i>
						  </button>
						  <input type="hidden" name="advanced" value="0" />
						</div>
					</div>
				</form>
		</div>
		<div id="wrapper">
			<div id="content_section">
				<div id="main_content_section">
					<h1 class="forumtitle">
						<a href="', $scripturl, '">', $context['forum_name'], '</a>
					</h1>
					<p id="siteslogan">', empty($settings['site_slogan']) ? $txt['site_slogan']: $settings['site_slogan'], '</p>';
					if (!empty($settings['enable_news']))
					echo '<p class="alert alert-info">', $txt['news'], ':', $context['random_news_line'], '</p>';

						theme_linktree();
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

			echo '
				</div>
			</div>
		</div>';
		echo '
			<div class="footer">
					<div class="row">
						<div class="col-md-6">
							<div class="copyright">
								', theme_copyright(), '
							</div>
						</div>
						<div class="col-sm-6">
							<div class="design">
								 ',$txt['themecop'];
								if ($context['show_load_time'])
								echo '
								<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';
								echo '	
							</div>
						</div>
					</div>
			</div>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '<div id="giris" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm">
				 <div class="modal-content">
				  <div class="modal-header">
					',$txt['hello_guest'] . $txt['guest_title'],'				
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				  </div>
				  <div class="modal-body">
					<script type="text/javascript" async="" src="', $settings['theme_url'], '/scripts/sha1.js?fin20"></script>
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
			</div>	
</body></html>';
}

function giris()
{
	global $context, $settings, $modSettings, $scripturl, $txt;

	if ($context['user']['is_logged'])
	{

			if (!empty($context['user']['avatar']))
				echo '<img class="avatar" src="', $context['user']['avatar']['href'], '" alt="', $context['user']['name'], '"/>';
			else
				echo '<img class="avatar"  src="'.$settings['images_url'].'/noavatar.png" alt="', $context['user']['name'], '"/>';
			echo '', $txt['hello_member_ndt'], ' <a href="', $scripturl, '?action=profile">', $context['user']['name'], '</a> ';
	
		if ($context['in_maintenance'] && $context['user']['is_admin'])
			echo '
					', $txt['maintain_mode_on'], '';
		if (!empty($context['unapproved_members']))
			echo '
					', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve"><i class="fa fa-approve fa-fw"></i> ', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a> ', $txt['approve_members_waiting'], '';

		if (!empty($context['open_mod_reports']) && $context['show_open_reports'])
			echo '
					<a href="', $scripturl, '?action=moderate;area=reports"><i class="fa fa-warning fa-fw"></i> ', sprintf($txt['mod_reports_waiting'], $context['open_mod_reports']), '</a>';

	}
	elseif (!empty($context['show_login_bar']))
	{
		echo '<div class="btn-group">
				
						<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#giris"><i class="fa fa-login fa-fw"></i> ',$txt['login'],'</a> 
						<a class="btn btn-default btn-sm" href="' . $scripturl . '?action=register"><span><i class="fa fa-register"></i> ',$txt['register'],'</span></a>
			</div>';
	}
	
}
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree,$scripturl,$txt;

	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="navigate_section">
		<ul>';
			if ($context['user']['is_logged'])
			{
				echo '	
					<li class="unread_links">
					<a href="', $scripturl, '?action=unread"><i class="fa fa-comment fa-fw"></i>  ', $txt['unread_since_visit'], '</a>
					<a href="', $scripturl, '?action=unreadreplies"><i class="fa fa-comments fa-fw"></i>  ', $txt['show_unread_replies'], '</a>
					</li>';
			}
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
					<li class="linktree">';

		echo $settings['linktree_link'] && isset($tree['url']) ? '<a href="' . $tree['url'] . '">' . ($link_num == 0 ? '<i class="fa fa-home fa-2x"></i>' : $tree['name']) . '</a>' : $tree['name'];

		echo '
					</li>';
	}
	echo '
		</ul>
	</div>';

	$shown_linktree = true;
}
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '<div class="nav-side-menu">
    <div class="brand">',giris(),'</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
        <div class="menu-list">
		<ul id="menu-content" class="menu-content collapse out">';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '" class="', $button['active_button'] ? 'collapsed ' : '', '"
				', $button['sub_buttons'] ? 'data-toggle="collapse" data-target="#'. $act.'" class="collapsed" ' : '', '>
					
					<a href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						<i class="fa fa-', $act, ' fa-lg"></i> ', $button['title'], '
					</a>', $button['sub_buttons'] ? '<span class="arrow"></span>' : '', '';
		if (!empty($button['sub_buttons']))
		{
			echo '
					<ul class="sub-menu collapse"  id="', $act, '">';

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

     </div>
</div>';
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
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><i class="fa fa-'.$key.' fa-fw"></i><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	if (empty($buttons))
		return;

	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul class="nav nav-pills">',
				implode('', $buttons), '
			</ul>
		</div>';
}


?>