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
 * @package Dokuzharf
 * @version 1.0
 * @theme Dokuzharf
 * @author Snrj - http://smf.konusal.com
 * Copyright 2016 Dokuzharf
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
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/bootstrap.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css?fin20" />
	' , ($context['right_to_left']) ? '<link rel="stylesheet" type="text/css" href="'. $settings['theme_url']. '/css/rtl.css" />' : '' , '';
	echo '
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/jquery.min.js"></script>	
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
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

	echo '
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
	echo  '<div class="container">
				<div class="row">
			<div class="col-sm-6"><h1 class="forumtitle text-left">
				<a href="', $scripturl, '">', empty($context['header_logo_url_html_safe']) ? $context['forum_name'] : '<img src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '</a>
			</h1></div>';
	echo '<div class="text-right col-sm-6">', empty($settings['site_slogan']) ? '<img class="smflogo" src="' . $settings['images_url'] . '/smflogo.png" alt="Simple Machines Forum" title="Simple Machines Forum" />' : '<h2 class="text-right">' . $settings['site_slogan'] . '</h2>', '</div></div>';
	echo'<div class="top-section"><div class="col-sm-6">';
	if ($context['user']['is_logged'])
	{
		if (!empty($context['user']['avatar']))
			echo '<img class="img-circle" src="', $context['user']['avatar']['href'], '" alt="*"/>';
		else
			echo '<img class="img-circle"  src="'.$settings['images_url'].'/default_avatar.png" alt="*"/>';
		echo '<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">', $txt['hello_member_ndt'], ' ', $context['user']['name'], '
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
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

		echo '<li class="dropdown-header"><span class="generic_icons history"></span> ', $context['current_time'], '</li> </ul>
</div>';
	}
	elseif (!empty($context['show_login_bar']))
	{
		echo '<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#giris">',$txt['login'],'</button> <a class="btn btn-info btn-sm" href="' . $scripturl . '?action=register">',$txt['register'],'</a>
		<div id="giris" class="modal fade " role="dialog">
		  <div class="modal-dialog modal-sm">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">',$txt['hello_guest'] . $txt['guest_title'],'</h4>
			  </div>
			  <div class="modal-body">
				<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
				<form class="form-horizontal" id="guest_form" action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
					<input type="text" name="user"  class="form-control" placeholder="',$txt['username'],'"/>
					<input type="password" name="passwrd"  class="form-control" placeholder="',$txt['password'],'"/>
					<select name="cookielength" class="form-control">
						<option value="60">', $txt['one_hour'], '</option>
						<option value="1440">', $txt['one_day'], '</option>
						<option value="10080">', $txt['one_week'], '</option>
						<option value="43200">', $txt['one_month'], '</option>
						<option value="-1" selected="selected">', $txt['forever'], '</option>
					</select><br/>
					<div class="form-group text-center"><input type="submit" class="btn btn-success" value="', $txt['login'], '"  />
					<p class="text-center">', $txt['quick_login_dec'], '</p></div>';
		if (!empty($modSettings['enableOpenID']))
			echo '
					<br /><input type="text" name="openid_identifier" id="openid_url" size="25" class="input_text openid_login" />';
		echo '
					<input type="hidden" name="hash_passwrd" value="" /><input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				</form> </div>
				</div>
			</div></div>';
	}
	echo '</div><div class="col-sm-6 text-right">
				<form class="form-inline" id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '"><div class="form-group form-group-sm">
					<input class="form-control" type="text" name="search" value="" placeholder="', $txt['search'], '" />&nbsp;
					<input type="submit" name="submit" value="', $txt['search'], '" class="btn btn-info btn-sm" />
					<input  type="hidden" name="advanced" value="0" />';
	if (!empty($context['current_topic']))
		echo '
					<input class="form-control" type="hidden" name="topic" value="', $context['current_topic'], '" />';
	elseif (!empty($context['current_board']))
		echo '
					<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';
	echo '</div></form>';
	echo '</div></div>';
	if (!empty($settings['enable_news']))
		echo '<div class="top-section">
				<span class="generic_icons news"></span>
				', $context['random_news_line'], '</div>';
	echo'<br class="clear" /><div class="cols-sm-12">';
	template_menu();
	theme_linktree();
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
		echo '</div><div class="footer">
		<div class="dropup pull-right">
		  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			',$txt['smfmenu'],'
			<span class="caret"></span>
		  </button>
		<ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu2">';
	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li>
					<a class="', $button['active_button'] ? 'active ' : '', 'firstlevel" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						<span class="generic_icons ', $act, '"></span> <span class="', isset($button['is_last']) ? 'last ' : '', 'firstlevel">', $button['title'], '</span>
					</a></li>';		
	}		
		echo '<li><a href="' . $scripturl . '?action=.xml;type=rss"><span class="generic_icons details"></span> <span>' . $txt['rss'] . '</span></a></li>
		
		</ul></div>	
			',$txt['themeauthor'].theme_copyright(), '';
	if ($context['show_load_time'])
		echo '
		<p class="small">', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';
	echo '</div></div>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	if(empty($settings['dsocial']))
	{
	echo'<div class="dsocial">
	<a href="#" id="scrlTop"><span class="togle ust"></span></a>';
	if(empty($settings['dyoutube']))
	echo'', empty($settings['dyoutubehref']) ? '<span class="dokuzharfsocial youtube"></span>' : '<a href="'.$settings['dyoutubehref'].'" title="Youtube" target="_blank"><span class="dokuzharfsocial youtube"></span></a>', '';
	if(empty($settings['dfacebook']))
	echo'', empty($settings['dfacebookhref']) ? '<span class="dokuzharfsocial facebook"></span>' : '<a href="'.$settings['dfacebookhref'].'" title="Facebook" target="_blank"><span class="dokuzharfsocial facebook"></span></a>', '';
	if(empty($settings['dtwiter']))
	echo'', empty($settings['dtwiterhref']) ? '<span class="dokuzharfsocial twiter"></span>' : '<a href="'.$settings['dtwiterhref'].'" title="Twiter" target="_blank"><span class="dokuzharfsocial twiter"></span></a>', '';
	if(empty($settings['dgoogle']))
	echo'', empty($settings['dgooglehref']) ? '<span class="dokuzharfsocial google"></span>' : '<a href="'.$settings['dgooglehref'].'" title="Google" target="_blank"><span class="dokuzharfsocial google"></span></a>', '';
	if(empty($settings['dinstagram']))
	echo'', empty($settings['dinstagramhref']) ? '<span class="dokuzharfsocial instagram"></span>' : '<a href="'.$settings['dinstagramhref'].'" title="instagram" target="_blank"><span class="dokuzharfsocial instagram"></span></a>', '';
	if(empty($settings['dskype']))
	echo'', empty($settings['dskypehref']) ? '<span class="dokuzharfsocial skype"></span>' : '<a href="'.$settings['dskypehref'].'" title="Skype" target="_blank"><span class="dokuzharfsocial skype"></span></a>', '';
	if(empty($settings['dgithub']))
	echo'', empty($settings['dgithubhref']) ? '<span class="dokuzharfsocial github"></span>' : '<a href="'.$settings['dgithubhref'].'" title="Github" target="_blank"><span class="dokuzharfsocial github"></span></a>', '';
	echo'
	<a href="#" id="scrlBotm"><span class="togle alt"></span></a>
	</div>
	<script type="text/javascript"><!-- // --><![CDATA[	
	$(function () { $(\'#scrlBotm\').click(function () { $(\'html, body\').animate({ scrollTop: $(document).height() }, 1500); return false; }); $(\'#scrlTop\').click(function () { $(\'html, body\').animate({ scrollTop: \'0px\' }, 1500); return false; }); }); 
	// ]]></script>';
	}
	echo '
</body></html>';
}
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;
	echo '<ol class="breadcrumb">';
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="active"' : '', '>';
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '">' . $tree['name'] . '</a>' : '' . $tree['name'] . '';
		echo '
			</li>';
	}
	echo '</ol>';

	$shown_linktree = true;
}
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#primary_nav_wrap">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
    </div>
    <div class="collapse navbar-collapse" id="primary_nav_wrap">
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
    </div>
  </div>
</nav>';
}
function template_button_strip($button_strip, $direction = 'right', $strip_options = array())
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
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="btn btn-default button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}
	if (empty($buttons))
		return;
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);
	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul class="pagination">',
				implode('', $buttons), '
			</ul>
		</div>';
}
?>