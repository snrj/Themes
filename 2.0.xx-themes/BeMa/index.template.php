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
 * @package BeMa
 * @version 1.0
 * @theme BeMa
 * @author Snrj - http://smf.konusal.com
 * Copyright 2018 BeMa
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
	<link href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/mui.min.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/responsive.css?fin20" />';
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';
	echo '
	<script src="', $settings['theme_url'], '/scripts/mui.min.js"></script>
	<script src="', $settings['theme_url'], '/scripts/jquery.min.js"></script>
	<script src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script>
	$(document).ready(function(){
		$("input[type=button]").attr("class", "mui-btn mui-btn--small mui-btn--raised");
		$(".button_submit").attr("class", "mui-btn mui-btn--small mui-btn--raised mui-btn--primary");
		$(".table_grid").addClass("mui-table");
	});
	</script>
	<script><!-- // --><![CDATA[
		var alpha_themeId = "', $settings['theme_id'], '";
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

	echo'',template_menu(),' <header id="header">
		<div class="mui-appbar mui--appbar-line-height">
        <div class="mui-container-fluid">
          <a class="sidedrawer-toggle mui--visible-xs-inline-block mui--visible-sm-inline-block js-show-sidedrawer">☰</a>
          <a class="sidedrawer-toggle mui--hidden-xs mui--hidden-sm js-hide-sidedrawer">☰</a>
		
			<div class="search_form mui--pull-right">
				<form id="search_form" class="mui-form--inline" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
				<div class="mui-textfield">
					<input type="text" name="search" value="" class="input_text" />
				</div>	
					<input type="submit" name="submit" value="', $txt['search'], '" class="button_submit" />
					<input type="hidden" name="advanced" value="0" />';
	if (!empty($context['current_topic']))
		echo '
					<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
	elseif (!empty($context['current_board']))
		echo '
					<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

	echo '</form></div>
		</div> 
		</div></header>';

	echo '
	<div id="wrapper">
		<div class="mui-panel middletext">
				<span class="mui--text-title mui--visible-xs-inline-block mui--visible-sm-inline-block">	
		  ', empty($settings['site_slogan']) ? '<img id="smflogo" src="' . $settings['images_url'] . '/smflogo.png" alt="Simple Machines Forum" title="Simple Machines Forum" />' : '<span id="siteslogan" class="floatright">' . $settings['site_slogan'] . '</span>', '
		  </span>
				<h1 class="forumtitle">
					<a href="', $scripturl, '">', empty($context['header_logo_url_html_safe']) ? $context['forum_name'] : '<img src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '</a>
				</h1>';
						if (!empty($settings['enable_news']))
							echo '
								<div class="enable_news">', $txt['news'], ':', $context['random_news_line'], '</div>';
					echo'			
					<br class="clear">
				',theme_linktree(),'
		</div>
		<div id="content_section">
			<div id="main_content_section">';
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

		echo '
			</div>
		</div>
	</div>';
	echo '
	<footer id="footer">
		<ul class="reset">
			<li class="floatright">',$txt['themecop'], '</li>
			<li class="copyright">', theme_copyright(), '</li>
		</ul>';
	if ($context['show_load_time'])
		echo '
		<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';
	echo '
	</footer>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
</body></html>';
}

function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree,$scripturl,$txt;
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="navigate_section">
		<ul>';
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];
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

function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '<div id="sidedrawer" class="mui--no-user-select">
      <div id="sidedrawer-brand" class="mui--appbar-line-height">
        <span class="mui--text-title">	
		  ', empty($settings['site_slogan']) ? '<img id="smflogo1" src="' . $settings['images_url'] . '/smflogo.png" alt="Simple Machines Forum" title="Simple Machines Forum" />' : '<span id="siteslogan" class="floatright">' . $settings['site_slogan'] . '</span>', '
		  </span>
      </div>
      <div class="mui-divider"></div>
		<div id="main_menu">
			<ul class="dropmenu" id="menu_nav">';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		
		echo '',(!empty($button['sub_buttons'])?'<strong><span class="generic_icons '. $act. '"></span> '. $button['title']. '<span class="mui-caret"></span></strong>':'<li id="button_'.$act. '">
					<a class="'.( $button['active_button'] ? 'active ' : ''). '" href="'. $button['href']. '"'.( isset($button['target']) ? ' target="' . $button['target'] . '"' : ''). '>
						<span class="generic_icons '. $act. '"></span> '.$button['title']. '
					</a>' ),'
				';
		if (!empty($button['sub_buttons']))
		{
			echo '
					<ul><li>
					<a class="'.( $button['active_button'] ? 'active ' : ''). '" href="'. $button['href']. '"'.( isset($button['target']) ? ' target="' . $button['target'] . '"' : ''). '> '.$button['title']. '
					</a></li>';

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
	echo '<li class="mui-divider"></li>';
			if ($context['user']['is_logged'])
			{
				echo '	
					
					<li><a href="', $scripturl, '?action=unread"><span class="generic_icons im_on"></span> ', $txt['unread_since_visit'], '</a></li>
					<li><a href="', $scripturl, '?action=unreadreplies"><span class="generic_icons replied"></span> ', $txt['show_unread_replies'], '</a></li>';
			}		
				echo '	
					<li><a href="', $scripturl, '?action=recent"><span class="generic_icons reply"></span> ', $txt['recent_posts'], '</a></li>			
					<li><a href="', $scripturl, '?action=stats" title="', $txt['more_stats'], '"><span class="generic_icons stats"></span> ', $txt['forum_stats'], '</a></li>					
					',$context['show_calendar'] ? '<li><a href="'. $scripturl. '?action=calendar' . '"><span class="generic_icons calendar"></span> '. $context['calendar_only_today'] ? $txt['calendar_today'] : $txt['calendar_upcoming']. '</a></li>': '', '				
					<li><a href="' . $scripturl . '?action=who"><span class="generic_icons people"></span> '.$txt['online_users']. '</a></li>';
	echo '
			</ul>
		</div>  </div>';
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
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span><i class="generic_icons '.$key. '"></i> ' . $txt[$value['text']] . '</span></a></li>';
	}
	if (empty($buttons))
		return;
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>',
				implode('', $buttons), '
			</ul>
		</div>';
}

?>