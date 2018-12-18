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
 * @package Bulut
 * @version 1.0
 * @theme Bulut
 * @author Snrj - http://smf.konusal.com
 * Copyright 2017 Bulut
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
	$settings['theme_variants'] = array('arkaplan1','arkaplan2','arkaplan3','arkaplan4');
}

function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	echo '<!DOCTYPE html>
<html lang="', $txt['lang_dictionary'],'" ', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/bootstrap.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/font-awesome.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css?fin20" />
	' , ($context['right_to_left']) ? '<link rel="stylesheet" type="text/css" href="'. $settings['theme_url']. '/css/rtl.css" />' : '' , '';
	echo '
	<script src="', $settings['theme_url'], '/scripts/jquery.min.js?fin20"></script>	
	<script src="', $settings['theme_url'], '/scripts/bootstrap.min.js?fin20"></script>
	<script>
	$(document).ready(function(){
		$("input[type=button]").attr("class", "btn btn-default btn-sm");
		$(".input_text").attr("class", "form-control");
		$(".input_password").attr("class", "form-control");
		$(".button_submit").attr("class", "btn btn-primary btn-sm");
		$("#advanced_search input[type=\'text\'], #search_term_input input[type=\'text\']").removeAttr("size"); 
		$(".table_grid").addClass("table table-striped");
		$("img[alt=\'', $txt['new'], '\'], img.new_posts").replaceWith("<span class=\'label label-warning\'>', $txt['new'], '</span>");
		$("#profile_success").removeAttr("id").removeClass("windowbg").addClass("alert alert-success"); 
		$("#profile_error").removeAttr("id").removeClass("windowbg").addClass("alert alert-danger"); 
	});
	</script>	
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
	if(!empty($context['page_index']))
	{
		$context['page_index'] = snrjpages('<ul class="pagination pagination-sm">'.$context['page_index'].'</ul>');
	}
	if(!empty($context['previous_next']))
	{
		$context['previous_next'] = snrjprevious('<ul class="pager">'.$context['previous_next'].'</ul>');
	}
	echo '
	<style>html {background-image: url(' . $settings['images_url'] . '/arkaplan/snrj'. $context['theme_variant'].'.jpg);}</style>
</head>
<body>';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo'<div id="particles-js"><canvas class="particles-js-canvas-el"></canvas></div>
	<div id="wrapper">';
			template_menu();
		echo'
		<div class="container">'; 
			theme_linktree();
			giris();
			titlelogo();
		echo'
		</div>';

	echo '
	<div class="container"><div id="content_section">
		<div id="main_content_section">';

}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
		</div>
	</div></div>';

	echo '
	<div class="footer"><div class="container">
		<ul class="reset">
			<li class="floatright"><a href="http://smf.konusal.com/" title="smf destek" target="_blank"><span class="label label-success"><i class="fa fa-paint-brush"></i> Smf Destek</span></a></li>
			<li>', theme_copyright(), '</li>
			
		</ul>';

	if ($context['show_load_time'])
		echo '
		<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	echo '
	</div></div>
</div>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	<script src="', $settings['theme_url'], '/scripts/particles.min.js"></script>
</body></html>';
}

function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="ullinktree">
		<ol class="breadcrumb breadcrumb-arrow">';
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
					<li class="linktree">';

		echo $settings['linktree_link'] && isset($tree['url']) ? '<a href="' . $tree['url'] . '">' . ($link_num == 0 ? '<i class="fa fa-home fa-2x"></i>' : $tree['name']) . '</a>' : $tree['name'];

		echo '
					</li>';
	}
	echo '
		</ol>
	</div>';

	$shown_linktree = true;
}

function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<div id="main_menu"><a href="javascript:void(0);" class="icon" onclick="snrjmenu()"></a>
			<ul class="dropmenu" id="menu_nav">';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '', '" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						<i class="fa fa-'.$act.' fa-fw"></i> ', $button['title'], '
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
		</div>
		<script>
			function snrjmenu() {
				var x = document.getElementById("menu_nav");
				if (x.className === "dropmenu") {
					document.getElementById("menu_nav").style.width = "250px";
					document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
					x.className += " responsive";
				} else {
					x.className = "dropmenu";
					document.getElementById("menu_nav").style.width = "0";
				}
				
				
			}
			
		</script>';
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
function giris()
{
		global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo'<div class="user information">';

if ($context['user']['is_logged'])
	{
	echo'<div class="dropdown floatright">
		<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">';
			if (!empty($context['user']['avatar']))
				echo '<img class="avatar" src="', $context['user']['avatar']['href'], '" alt="*"/>';
			echo '', $txt['hello_member_ndt'], ' ', $context['user']['name'], '
		<span class="caret"></span></button>
		<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="', $scripturl, '?action=profile"><i class="fa fa-user fa-fw"></i> ', $txt['profile'], '</a></li>
					<li><a href="' , $scripturl , '?action=profile;area=forumprofile;"><i class="fa fa-gear fa-fw"></i>' , $txt['forumprofile'] , '</a></li>
					<li><a href="' , $scripturl , '?action=profile;area=account;"><i class="fa fa-wrench fa-fw"></i>' , $txt['account'] , '</a></li>
					<li><a href="' , $scripturl , '?action=unread;"><i class="fa fa-comment fa-fw"></i>' , $txt['unread_since_visit'] , '</a></li>
					<li><a href="' , $scripturl , '?action=unreadreplies;"><i class="fa fa-comments fa-fw"></i>' , $txt['show_unread_replies'] , '</a></li>
					<li><a href="', $scripturl, '?action=pm"><i class="fa fa-pm fa-fw"></i> 
					', $txt['pm_short'], '
					', !empty($context['user']['unread_messages']) ? '<strong>'.$context['user']['unread_messages'] .'</strong>' : '', '
					</a></li>
					<li class="divider"></li>
					<li><a href="' , $scripturl , '?action=logout;sesc=', $context['session_id'], '"><i class="fa fa-sign-out fa-fw"></i>' , $txt['logout'] , '</a></li>';
		if ($context['in_maintenance'] && $context['user']['is_admin'])
			echo '
					', $txt['maintain_mode_on'], '';
		if (!empty($context['unapproved_members']))
			echo '
					', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <li><a href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve"><i class="fa fa-approve fa-fw"></i> ', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a></li> ', $txt['approve_members_waiting'], '';

		if (!empty($context['open_mod_reports']) && $context['show_open_reports'])
			echo '
					<li><a href="', $scripturl, '?action=moderate;area=reports"><i class="fa fa-warning fa-fw"></i>  ', sprintf($txt['mod_reports_waiting'], $context['open_mod_reports']), '</a></li>';
					
		echo '
		</ul>
	</div>';
	}
	elseif (!empty($context['show_login_bar']))
	{
		echo '<div class="btn-group floatright">
				
						<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#giris"><i class="generic_icons login"></i> ',$txt['login'],'</a> 
						<a class="btn btn-default btn-sm" href="' . $scripturl . '?action=register"><span><i class="generic_icons register"></i> ',$txt['register'],'</span></a>
			</div>
			<div id="giris" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm">

				 <div class="modal-content">
				  <div class="modal-header">
					',$txt['hello_guest'] . $txt['guest_title'],'				
					<button type="button" class="close" data-dismiss="modal">&times;</button>
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
			</div>';
	}
		arkaplan();

	echo'<form id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
		  <div class="input-group">
			<input type="text" name="search" class="form-control" placeholder="', $txt['search'], '"><input type="hidden" name="advanced" value="0" />
			<div class="input-group-btn">
			  <button class="btn btn-default" type="submit" name="submit">
				<i class="fa fa-search fa-fw"></i> 
			  </button>
			</div>
		  </div>
		</form>
	</div>';

	
}
function titlelogo()
{
		global $context, $settings, $scripturl;

		echo'
		<div class="row">
			<div class="col-sm-6">
				<h1 class="forumtitle text-left">
					<a href="', $scripturl, '">', empty($context['header_logo_url_html_safe']) ? $context['forum_name'] : '<img src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '</a>
				</h1>
			</div>
			<div class="text-right col-sm-6">
				', empty($settings['site_slogan']) ? '<img class="smflogo" src="' . $settings['images_url'] . '/smflogo.png" alt="Simple Machines Forum" title="Simple Machines Forum" />' : '<h2 class="text-right">' . $settings['site_slogan'] . '</h2>', '
			</div>
		</div>';
}
function snrjpages($string)
{
	$string = str_replace(array('[',']'),array('',''), $string);
	$string = str_replace(array('<strong>','</strong>'),array('<li class="active"><a href="#">','</a></li>'), $string);
	$string = str_replace(array('<a class="navPages"','</a>'),array('<li><a class="navPages"','</a></li>'), $string);
	$string = str_replace(array('<span','</span>'),array('<li><span','</span></li>'), $string);
	return ''.$string.'';
}
function snrjprevious($string)
{
	$string = str_replace(array('<a','</a>'),array('<li><a','</a></li>'), $string);
	return ''.$string.'';
}
function temizle($string)
{
global $context,$settings;
$string= str_replace(array('<img src="' . $settings['images_url'] . '/' . $context['theme_variant_url'] .'','/>'),array('<img src="' . $settings['images_url'] . '/','/>'), $string);
return ''.$string.'';
}
function arkaplan()
{
global $context,$scripturl,$settings;
	if (empty($context['theme_settings']['disable_user_variant']))
	 echo '<div class="dropdown floatright">
			  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Background
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu dropdown-menu-right">
				<li>
					<a href="', $scripturl, '?variant=arkaplan1">
						<img src="' . $settings['images_url'] . '/arkaplan/mini/snrj_arkaplan1.jpg" class="img-thumbnail" alt="arkaplan1">
					</a>
				</li>
				<li>
					<a href="', $scripturl, '?variant=arkaplan2">
						<img src="' . $settings['images_url'] . '/arkaplan/mini/snrj_arkaplan2.jpg" class="img-thumbnail" alt="arkaplan2">
					</a>
				</li>
				<li>
					<a href="', $scripturl, '?variant=arkaplan3">
						<img src="' . $settings['images_url'] . '/arkaplan/mini/snrj_arkaplan3.jpg" class="img-thumbnail" alt="arkaplan3">
					</a>
				</li>
				<li>
					<a href="', $scripturl, '?variant=arkaplan4">
						<img src="' . $settings['images_url'] . '/arkaplan/mini/snrj_arkaplan4.jpg" class="img-thumbnail" alt="arkaplan4">
					</a>
				</li>
			  </ul>
			</div>';
}			
?>
