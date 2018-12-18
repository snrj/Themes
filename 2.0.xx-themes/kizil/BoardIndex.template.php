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
 * @package KIZIL
 * @version 1.0
 * @theme KIZIL
 * @author Snrj - http://smf.konusal.com
 * Copyright 2018 KIZIL
 *
 */

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	// Show some statistics if stat info is off.
	if (!$settings['show_stats_index'])
		echo '
	<div id="index_common_stats">
		', $txt['members'], ': ', $context['common_stats']['total_members'], ' &nbsp;&#8226;&nbsp; ', $txt['posts_made'], ': ', $context['common_stats']['total_posts'], ' &nbsp;&#8226;&nbsp; ', $txt['topics'], ': ', $context['common_stats']['total_topics'], '
		', ($settings['show_latest_member'] ? ' ' . $txt['welcome_member'] . ' <strong>' . $context['common_stats']['latest_member']['link'] . '</strong>' . $txt['newest_member'] : '') , '
	</div>';

	// Show the news fader?  (assuming there are things to show...)
	if ($settings['show_newsfader'] && !empty($context['fader_news_lines']))
	{
		echo '
	<div id="newsfader">
		<ul class="reset" id="smfFadeScroller"', empty($options['collapse_news_fader']) ? '' : ' style="display: none;"', '>';

			foreach ($context['news_lines'] as $news)
				echo '
			<li>', $news, '</li>';

	echo '
		</ul>
	</div>
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/fader.js"></script>
	<script type="text/javascript"><!-- // --><![CDATA[

		// Create a news fader object.
		var oNewsFader = new smf_NewsFader({
			sSelf: \'oNewsFader\',
			sFaderControlId: \'smfFadeScroller\',
			sItemTemplate: ', JavaScriptEscape('<strong>%1$s</strong>'), ',
			iFadeDelay: ', empty($settings['newsfader_time']) ? 5000 : $settings['newsfader_time'], '
		});

		// Create the news fader toggle.
		var smfNewsFadeToggle = new smc_Toggle({
			bToggleEnabled: true,
			bCurrentlyCollapsed: ', empty($options['collapse_news_fader']) ? 'false' : 'true', ',
			aSwappableContainers: [
				\'smfFadeScroller\'
			],
			aSwapImages: [
				{
					sId: \'newsupshrink\',
					srcExpanded: smf_images_url + \'/collapse.gif\',
					altExpanded: ', JavaScriptEscape($txt['upshrink_description']), ',
					srcCollapsed: smf_images_url + \'/expand.gif\',
					altCollapsed: ', JavaScriptEscape($txt['upshrink_description']), '
				}
			],
			oThemeOptions: {
				bUseThemeSettings: ', $context['user']['is_guest'] ? 'false' : 'true', ',
				sOptionName: \'collapse_news_fader\',
				sSessionVar: ', JavaScriptEscape($context['session_var']), ',
				sSessionId: ', JavaScriptEscape($context['session_id']), '
			},
			oCookieOptions: {
				bUseCookie: ', $context['user']['is_guest'] ? 'true' : 'false', ',
				sCookieName: \'newsupshrink\'
			}
		});
	// ]]></script>';
	}

	echo '<div class="row"><div class="col-md-8">
	<div id="boardindex_table" class="boardindex_table">';

	/* Each category in categories is made up of:
	id, href, link, name, is_collapsed (is it collapsed?), can_collapse (is it okay if it is?),
	new (is it new?), collapse_href (href to collapse/expand), collapse_image (up/down image),
	and boards. (see below.) */
	foreach ($context['categories'] as $category)
	{
		// If theres no parent boards we can see, avoid showing an empty category (unless its collapsed)
		if (empty($category['boards']) && !$category['is_collapsed'])
			continue;

		echo '
		<div class="main_container">
			<div class="cat_bar" id="category_', $category['id'], '">
				<h3 class="catbg">';
				if ($category['can_collapse'])
					echo '
										<a class="collapse" href="', $category['collapse_href'], '"><i class="fa fa-compress"></i></a>';

				if (!$context['user']['is_guest'] && !empty($category['show_unread']))
					echo '
										<a class="unreadlink floatright" href="', $scripturl, '?action=unread;c=', $category['id'], '">', $txt['view_unread_category'], '</a>';

				echo '
										', $category['link'], '
				</h3>
			</div>
			<div id="category_', $category['id'], '_boards" >';

			foreach ($category['boards'] as $board)
			{
				echo '
				<div id="board_', $board['id'], '" class="up_contain">
					<div class="board_icon">';
					if ($board['new'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '"><i class="fa fa-comment-o fa-2x"></i>';
				elseif ($board['children_new'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '"><i class="fa fa-comments-o fa-2x"></i>';
				elseif ($board['is_redirect'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '"><i class="fa fa-repeat fa-2x"></i>';
				else
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '"><i class="fa fa-comments fa-2x"></i>';
				echo '</a>
					</div>
					<div class="info">
						<a class="subject mobile_subject" href="', $board['href'], '" id="b', $board['id'], '">
							', $board['name'], '
						</a>';

				// Has it outstanding posts for approval?
				if ($board['can_approve_posts'] && ($board['unapproved_posts'] || $board['unapproved_topics']))
					echo '
						<a href="', $scripturl, '?action=moderate;area=postmod;sa=', ($board['unapproved_topics'] > 0 ? 'topics' : 'posts'), ';brd=', $board['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', sprintf($txt['unapproved_posts'], $board['unapproved_topics'], $board['unapproved_posts']), '" class="moderation_link">(!)</a>';

				echo '
						<p class="board_description">', $board['description'], '</p>';
				// Show the "Moderators: ". Each has name, href, link, and id. (but we're gonna use link_moderators.)
				if (!empty($board['link_moderators']))
					echo '
						<p class="moderators">', count($board['link_moderators']) == 1 ? $txt['moderator'] : $txt['moderators'], ': ', implode(', ', $board['link_moderators']), '</p>';
							if (!empty($board['children']))
							{
								$children = array();
								foreach ($board['children'] as $child)
								{
									if (!$child['is_redirect'])
										$child['link'] = '<a href="' . $child['href'] . '" ' . ($child['new'] ? 'class="new_posts" ' : '') . 'title="' . ($child['new'] ? $txt['new_posts'] : $txt['old_posts']) . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')">' . $child['name'] . '</a>';
									else
										$child['link'] = '<a href="' . $child['href'] . '" title="' . comma_format($child['posts']) . ' ' . $txt['redirects'] . '">' . $child['name'] . '</a>';

									if ($child['can_approve_posts'] && ($child['unapproved_posts'] || $child['unapproved_topics']))
										$child['link'] .= ' <a href="' . $scripturl . '?action=moderate;area=postmod;sa=' . ($child['unapproved_topics'] > 0 ? 'topics' : 'posts') . ';brd=' . $child['id'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" title="' . sprintf($txt['unapproved_posts'], $child['unapproved_topics'], $child['unapproved_posts']) . '" class="moderation_link">(!)</a>';
									$children[] =$child['new'] ? '<li> ' . $child['link'] . ' <i class="fa fa-comments"></i></li>' :'<li> '.$child['link'].'</li>';
								}

							echo '
								<div id="board_', $board['id'], '_children" class="dropdown">
								  <button class="btn btn-warning btn-xs dropdown-toggle" type="button" data-toggle="dropdown">', $txt['parent_boards'], '
								  <span class="caret"></span></button>
								  <ul class="dropdown-menu">
									', implode($children), '
								  </ul>
								</div>';
							}
				// Show some basic information about the number of posts, etc.
					echo '
					</div>
					<div class="board_stats">
						<p><span class="label label-warning"><i class="fa fa-reply"></i> ', comma_format($board['posts']), ' </span> &nbsp; 
						 <span class="label label-warning"><i class="fa fa-new_topic"></i>  '. comma_format($board['topics']) . ' </span>  
						</p>
					</div>
					<div class="lastpost">';

				if (!empty($board['last_post']['id']))
					echo '
						<p><i class="fa fa-profile"></i> ', $board['last_post']['member']['link'] , '<br />
						<i class="fa fa-reply"></i>  ', $board['last_post']['link'], '<br />
						<i class="fa fa-history"></i> ', $board['last_post']['time'],'
						</p>';
				echo '
					</div>';
				echo '
					</div>';
			}

		echo '
			</div>
		</div>';
	}

	echo '
	</div>';

	// Show the mark all as read button?
	if ($context['user']['is_logged'])
	{
		$mark_read_button = array(
			'markread' => array('text' => 'mark_as_read', 'image' => 'markread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=all;' . $context['session_var'] . '=' . $context['session_id']),
		);
		if ($settings['show_mark_read'] && !empty($context['categories']))
			echo '<div class="buttonlist">', template_button_strip($mark_read_button, 'right'), '</div>';
	}
	echo'</div><div class="col-md-4">';
		template_info_center();
	echo'</div></div>';
}

function template_info_center()
{
	global $context, $options, $txt,$settings;

		template_ic_block_recent();
		template_ic_block_calendar();
		template_ic_block_stats();
		template_ic_block_online();

}

/**
 * The recent posts section of the info center
 */
function template_ic_block_recent()
{
	global $context, $scripturl, $settings, $txt,$memberContext;
	if (!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
	{
	// This is the "Recent Posts" bar.
	echo '
			<div class="cat_bar">
				<h4 class="catbg">
					<a href="', $scripturl, '?action=recent"><i class="fa fa-comments"></i>', $txt['recent_posts'], '</a>
				</h4>
			</div>
			<div class="information">';
		if ($settings['number_recent_posts'] == 1)
		{
			// latest_post has link, href, time, subject, short_subject (shortened with...), and topic. (its id.)
			echo '
				<strong><a href="', $scripturl, '?action=recent">', $txt['recent_posts'], '</a></strong>
				<p id="infocenter_onepost" class="middletext">
					', $txt['recent_view'], ' &quot;', $context['latest_post']['link'], '&quot; ', $txt['recent_updated'], ' (', $context['latest_post']['time'], ')<br />
				</p>';
		}
		// Show lots of posts.
		elseif (!empty($context['latest_posts']))
		{
			echo '<ul class="sonileti reset">';

		foreach ($context['latest_posts'] as $post){
		loadMemberData($post['poster']['id']);
		loadMemberContext($post['poster']['id']);
			echo '<li>';
					if($memberContext[$post['poster']['id']]['avatar']['image'])
				echo'', $memberContext[$post['poster']['id']]['avatar']['image'],'';
				else
				echo'<img class="avatar" src="'.$settings['images_url'].'/noavatar.png" alt="*" />';
						echo'<strong>', $post['link'], '</strong><br/>
						', $post['poster']['link'], '<br/>
						', $post['time'], '
					</li>';
		}			
		echo '</ul>';
		}
	echo '
			</div>';
	}
}

/**
 * The calendar section of the info center
 */
function template_ic_block_calendar()
{
	global $context, $scripturl, $txt, $settings;
	if ($context['show_calendar'])
	{
	// Show information about events, birthdays, and holidays on the calendar.
	echo '
			<div class="cat_bar">
				<h4 class="catbg">
					<a href="', $scripturl, '?action=calendar' . '"><i class="fa fa-calendar"></i> ', $context['calendar_only_today'] ? $txt['calendar_today'] : $txt['calendar_upcoming'], '</a>
				</h4>
			</div>
			<div class="information">';

	// Holidays like "Christmas", "Chanukah", and "We Love [Unknown] Day" :P.
	if (!empty($context['calendar_holidays']))
		echo '
				<p class="inline holiday"><span>', $txt['calendar_prompt'], '</span> ', implode(', ', $context['calendar_holidays']), '</p>';

	// People's birthdays. Like mine. And yours, I guess. Kidding.
	if (!empty($context['calendar_birthdays']))
	{
		echo '
				<p class="inline">
					<span class="birthday">', $context['calendar_only_today'] ? $txt['birthdays'] : $txt['birthdays_upcoming'], '</span>';
		// Each member in calendar_birthdays has: id, name (person), age (if they have one set?), is_last. (last in list?), and is_today (birthday is today?)
		foreach ($context['calendar_birthdays'] as $member)
			echo '
					<a href="', $scripturl, '?action=profile;u=', $member['id'], '">', $member['is_today'] ? '<strong class="fix_rtl_names">' : '', $member['name'], $member['is_today'] ? '</strong>' : '', isset($member['age']) ? ' (' . $member['age'] . ')' : '', '</a>', $member['is_last'] ? '' : ', ';
		echo '
				</p>';
	}

	// Events like community get-togethers.
	if (!empty($context['calendar_events']))
	{
		echo '
				<p class="inline">
					<span class="event">', $context['calendar_only_today'] ? $txt['events'] : $txt['events_upcoming'], '</span> ';

		// Each event in calendar_events should have:
		//		title, href, is_last, can_edit (are they allowed?), modify_href, and is_today.
		foreach ($context['calendar_events'] as $event)
			echo '
					', $event['can_edit'] ? '<a href="' . $event['modify_href'] . '" title="' . $txt['calendar_edit'] . '"><span class="generic_icons calendar_modify"></span></a> ' : '', $event['href'] == '' ? '' : '<a href="' . $event['href'] . '">', $event['is_today'] ? '<strong>' . $event['title'] . '</strong>' : $event['title'], $event['href'] == '' ? '' : '</a>', $event['is_last'] ? '<br>' : ', ';
		echo '
				</p>';
	}
	echo'</div>';
	}
}

/**
 * The stats section of the info center
 */
function template_ic_block_stats()
{
	global $scripturl, $txt, $context, $settings;
	if ($settings['show_stats_index'])
	{
	// Show statistical style information...
	echo '
			<div class="cat_bar">
				<h4 class="catbg">
					<a href="', $scripturl, '?action=stats" title="', $txt['more_stats'], '"><i class="fa fa-signal"></i> ', $txt['forum_stats'], '</a>
				</h4>
			</div>
			<div class="information">
			<ul class="bbc_list">
					<li>', $txt['total_posts'], ': ', $context['common_stats']['total_posts'], '</li>
					<li>', $txt['total_topics'], ': ', $context['common_stats']['total_topics'], '</li>
					<li>', $txt['total_members'], ': ', $context['common_stats']['total_members'], '</li>',
					!empty($settings['show_latest_member']) ? '<li>' . $txt['latest_member'] . ': <strong> ' . $context['common_stats']['latest_member']['link'] . '</strong></li>' : '',
					'<li>', $txt['most_online_today'], ': ', comma_format($modSettings['mostOnlineToday']), '</li>
					<li>', (!empty($context['latest_post']) ? $txt['latest_post'] . ': <strong>&quot;' . $context['latest_post']['link'] . '&quot;<br /></strong> <span class="smalltext">' . $context['latest_post']['time'] . '</span>' : ''), '<br />
					<a class="linkbutton" href="', $scripturl, '?action=recent">', $txt['recent_view'], '</a><br />
					<a href="' . $scripturl . '?action=stats">' . $txt['more_stats'] . '</a></li>
				</ul>
			</div>';
	}		
}

/**
 * The who's online section of the admin center
 */
function template_ic_block_online()
{
	global $context, $scripturl, $txt, $modSettings, $settings;
	// "Users online" - in order of activity.
	echo '
			<div class="cat_bar">
				<h4 class="catbg">
					', $context['show_who'] ? '<a href="' . $scripturl . '?action=who">' : '', '<i class="fa fa-users"></i> ', $txt['online_users'], '', $context['show_who'] ? '</a>' : '', '
				</h4>
			</div>
			<div class="information">
			<ul class="bbc_list">';

	echo '
					<li>', comma_format($context['num_guests']), ' ', $context['num_guests'] == 1 ? $txt['guest'] : $txt['guests'], '</li>
					<li>', comma_format($context['num_users_online']), ' ', $context['num_users_online'] == 1 ? $txt['user'] : $txt['users'], '</li>';

	// Handle hidden users and buddies.
	$bracketList = array();
	if ($context['show_buddies'])
		$bracketList[] = comma_format($context['num_buddies']) . ' ' . ($context['num_buddies'] == 1 ? $txt['buddy'] : $txt['buddies']);

	if (!empty($context['num_spiders']))
		$bracketList[] = comma_format($context['num_spiders']) . ' ' . ($context['num_spiders'] == 1 ? $txt['spider'] : $txt['spiders']);

	if (!empty($context['num_users_hidden']))
		$bracketList[] = comma_format($context['num_users_hidden']) . ' ' . ($context['num_users_hidden'] == 1 ? $txt['hidden'] : $txt['hidden_s']);

	if (!empty($bracketList))
		echo '
					<li>(' . implode(', ', $bracketList) . ')</li>';

	echo '
				</ul>';

	// Assuming there ARE users online... each user in users_online has an id, username, name, group, href, and link.
	if (!empty($context['users_online']))
	{
		echo '
				<p class="inline">', sprintf($txt['users_active'], $modSettings['lastActive']), ': ', implode(', ', $context['list_users_online']), '</p>';

		// Showing membergroups?
		if (!empty($settings['show_group_key']) && !empty($context['membergroups']))
			echo '
				<p class="inline membergroups">[' . implode(',&nbsp;', $context['membergroups']) . ']</p>';
	}

	echo '
			<p class="last smalltext">
				', $txt['most_online_today'], ': <strong>', comma_format($modSettings['mostOnlineToday']), '</strong><br>
				', $txt['most_online_ever'], ': ', comma_format($modSettings['mostOnline']), ' (', timeformat($modSettings['mostDate']), ')
			</p>
	</div>';
}

?>