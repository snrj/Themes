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
 * @package DeepBlue
 * @version 1.0
 * @theme DeepBlue
 * @author Snrj - http://smf.konusal.com
 * Copyright 2016 DeepBlue
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
		<div class="cat_bar">
			<h3 class="catbg">
				<img id="newsupshrink" src="', $settings['images_url'], '/collapse.gif" alt="*" title="', $txt['upshrink_description'], '" align="bottom" style="display: none;" />
				', $txt['news'], '
			</h3>
		</div>
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
	echo '<div class="anagovde"><div class="boardindextable">';
	foreach ($context['categories'] as $category)
	{
		echo '<div class="catmain">
      <div class="cat_bar" id="category_', $category['id'], '">
        <h3 class="catbg">';
		if ($category['can_collapse'])
			echo '
								<a class="collapse floatright" href="', $category['collapse_href'], '">', $category['collapse_image'], '</a>';
		if (!$context['user']['is_guest'] && !empty($category['show_unread']))
			echo '
								<a class="floatright" href="', $scripturl, '?action=unread;c=', $category['id'], '">', $txt['view_unread_category'], '</a>';
		echo '', $category['name'], '';
		echo '</h3>
			</div>';
		echo '<div  id="category_', $category['id'], '_boards">';
			foreach ($category['boards'] as $board)
			{
					echo'<div class="boardbg"><div class="board_icon">';
				if ($board['new'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '" class="board_on">';
				elseif ($board['children_new'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '" class="board_on2">';
				elseif ($board['is_redirect'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '" class="board_redirect">';
				else
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '" class="board_off">';
				echo '</a>
					</div>
					<div class="info">
						<a class="subject" href="', $board['href'], '" id="b', $board['id'], '" >', $board['name'], '</a>
						<p>', $board['description'] , '</p>
						<p>', comma_format($board['posts']), ' ', $board['is_redirect'] ? $txt['redirects'] : $txt['posts'], ' 
						', $board['is_redirect'] ? '' : comma_format($board['topics']) . ' ' . $txt['board_topics'], '
						</p>';
				if ($board['can_approve_posts'] && ($board['unapproved_posts'] || $board['unapproved_topics']))
					echo '
						<a href="', $scripturl, '?action=moderate;area=postmod;sa=', ($board['unapproved_topics'] > 0 ? 'topics' : 'posts'), ';brd=', $board['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', sprintf($txt['unapproved_posts'], $board['unapproved_topics'], $board['unapproved_posts']), '" class="moderation_link"><span class="generic_icons error"></span></a>';
				if (!empty($board['moderators']))
					echo '
						<p class="moderators"><span class="generic_icons warning_watch"></span> ', implode($board['link_moderators']), '</p>';
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
						$children[] =$child['new'] ? '<strong><span class="generic_icons im_off"></span> ' . $child['link'] . ' <span class="generic_icons im_on"></span></strong>' :'<strong><span class="generic_icons im_off"></span> '.$child['link'].'</strong>';
					}
					echo '
					<div id="board_', $board['id'], '_children" class="children">
							<p>', implode($children), '</p>
					</div>';
				}
					echo '
					</div>';
					echo '
					<div class="lastpost">';
				if (!empty($board['last_post']['id']))
					echo '
						<p> ', $txt['by'], ' ', $board['last_post']['member']['link'] , '<br />
						<strong>', $txt['last_post'], '</strong>', $txt['in'], ' ', $board['last_post']['link'], '<br />
						', $txt['on'], ' ', $board['last_post']['time'],'
						</p>';
				echo '</div>';			
			echo '
			</div>';
			}
		echo '
      </div></div>';
	}


	if ($context['user']['is_logged'])
	{
		$mark_read_button = array(
			'markread' => array('text' => 'mark_as_read', 'image' => 'markread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=all;' . $context['session_var'] . '=' . $context['session_id']),
		);
		if ($settings['show_mark_read'] && !empty($context['categories']))
			echo '<div class="mark_read">', template_button_strip($mark_read_button, 'right'), '</div>';
	}
	echo '
      </div><img class="solicon" id="upshrink_ic" src="', $settings['images_url'], '/kapa.png" alt="*" title="', $txt['upshrink_description'], '" style="display: none;" /><div class="boardsol" id="upshrinkHeaderIC"', empty($options['collapse_header_ic']) ? '' : ' style="display: none;"', '>';
	template_info_center();
	echo '</div></div>';
}

function template_info_center()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings,$memberContext;
	// If the user is logged in, display stuff like their name, new messages, etc.
	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				', sprintf($txt['info_center_title'], $context['forum_name_html_safe']), '
			</h3>
		</div>
		<div class="title_barIC">
			<h4 class="titlebg">
				<span class="generic_icons starters"></span> ',$txt['merhaba'],'
			</h4>
		</div>
		<div class="side">';
	if ($context['user']['is_logged'])
	{
		if (!empty($context['user']['avatar']))
				echo '<p class="centertext"><img class="img-circle" src="', $context['user']['avatar']['href'], '" alt="*"/></p>';
				else
				echo '<p class="centertext"><img class="img-circle"  src="'.$settings['images_url'].'/default_avatar.png" alt="*"/></p>';
		echo '
				<ul class="reset">
					<li class="greeting">', $txt['hello_member_ndt'], ' <span>', $context['user']['name'], '</span></li>
					<li><a href="', $scripturl, '?action=unread">', $txt['unread_since_visit'], '</a></li>
					<li><a href="', $scripturl, '?action=unreadreplies">', $txt['show_unread_replies'], '</a></li>';

		// Is the forum in maintenance mode?
		if ($context['in_maintenance'] && $context['user']['is_admin'])
			echo '
					<li class="notice">', $txt['maintain_mode_on'], '</li>';

		// Are there any members waiting for approval?
		if (!empty($context['unapproved_members']))
			echo '
					<li>', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve">', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a> ', $txt['approve_members_waiting'], '</li>';

		if (!empty($context['open_mod_reports']) && $context['show_open_reports'])
			echo '
					<li><a href="', $scripturl, '?action=moderate;area=reports">', sprintf($txt['mod_reports_waiting'], $context['open_mod_reports']), '</a></li>';

		echo '
					<li>', $context['current_time'], '</li>
				</ul>';
	}
	// Otherwise they're a guest - this time ask them to either register or login - lazy bums...
	elseif (!empty($context['show_login_bar']))
	{
		echo '
				<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
				<form id="guest_form" action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
					<div class="info">', sprintf($txt['welcome_guest'], $txt['guest_title']), '</div>
					<input type="text" name="user" size="10" class="input_text" />
					<input type="password" name="passwrd" size="10" class="input_password" />
					<select name="cookielength">
						<option value="60">', $txt['one_hour'], '</option>
						<option value="1440">', $txt['one_day'], '</option>
						<option value="10080">', $txt['one_week'], '</option>
						<option value="43200">', $txt['one_month'], '</option>
						<option value="-1" selected="selected">', $txt['forever'], '</option>
					</select>
					<input type="submit" value="', $txt['login'], '" class="button_submit" /><br />
					<div class="info">', $txt['quick_login_dec'], '</div>';

		if (!empty($modSettings['enableOpenID']))
			echo '
					<br /><input type="text" name="openid_identifier" id="openid_url" size="25" class="input_text openid_login" />';

		echo '
					<input type="hidden" name="hash_passwrd" value="" /><input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				</form>';
	}
	echo '</div>
			<div class="title_barIC">
				<h4 class="titlebg">
				<span class="generic_icons search"></span>	',$txt['Fara'],'
				</h4>
			</div>
			<div class="side">
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


	// This is the "Recent Posts" bar.
	if (!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
	{
		echo '
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<a href="', $scripturl, '?action=recent">
						<span class="generic_icons replies"></span>						
						', $txt['recent_posts'], '</a>
					</span>
				</h4>
			</div>
			<div class="side">
			<div class="hslice" id="recent_posts_content">
				<div class="entry-title" style="display: none;">', $context['forum_name_html_safe'], ' - ', $txt['recent_posts'], '</div>';

		// Only show one post.
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
			echo '<ul class="sonileti">';

		foreach ($context['latest_posts'] as $post){
		loadMemberData($post['poster']['id']);
		loadMemberContext($post['poster']['id']);
			echo '<li>';
					if($memberContext[$post['poster']['id']]['avatar']['image'])
				echo'', $memberContext[$post['poster']['id']]['avatar']['image'],'';
				else
				echo'<img class="avatar" src="'.$settings['images_url'].'/default_avatar.png" alt="*" />';
						echo'<strong>', $post['link'], '</strong><br/>
						', $post['poster']['link'], '<br/>
						', $post['time'], '
					</li>';
		}			
		echo '
				</ul>';
		}
		echo '
			</div></div>';
	}
	// Show information about events, birthdays, and holidays on the calendar.
	if ($context['show_calendar'])
	{
		echo '
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<a href="', $scripturl, '?action=calendar' . '"><img class="icon" src="', $settings['images_url'], '/icons/calendar.gif', '" alt="', $context['calendar_only_today'] ? $txt['calendar_today'] : $txt['calendar_upcoming'], '" /></a>
						', $context['calendar_only_today'] ? $txt['calendar_today'] : $txt['calendar_upcoming'], '
					</span>
				</h4>
			</div>
			<div class="side">
			<p class="smalltext">';

		// Holidays like "Christmas", "Chanukah", and "We Love [Unknown] Day" :P.
		if (!empty($context['calendar_holidays']))
				echo '
				<span class="holiday">', $txt['calendar_prompt'], ' ', implode(', ', $context['calendar_holidays']), '</span><br />';

		// People's birthdays. Like mine. And yours, I guess. Kidding.
		if (!empty($context['calendar_birthdays']))
		{
				echo '
				<span class="birthday">', $context['calendar_only_today'] ? $txt['birthdays'] : $txt['birthdays_upcoming'], '</span> ';
		/* Each member in calendar_birthdays has:
				id, name (person), age (if they have one set?), is_last. (last in list?), and is_today (birthday is today?) */
		foreach ($context['calendar_birthdays'] as $member)
				echo '
				<a href="', $scripturl, '?action=profile;u=', $member['id'], '">', $member['is_today'] ? '<strong>' : '', $member['name'], $member['is_today'] ? '</strong>' : '', isset($member['age']) ? ' (' . $member['age'] . ')' : '', '</a>', $member['is_last'] ? '<br />' : ', ';
		}
		// Events like community get-togethers.
		if (!empty($context['calendar_events']))
		{
			echo '
				<span class="event">', $context['calendar_only_today'] ? $txt['events'] : $txt['events_upcoming'], '</span> ';
			/* Each event in calendar_events should have:
					title, href, is_last, can_edit (are they allowed?), modify_href, and is_today. */
			foreach ($context['calendar_events'] as $event)
				echo '
					', $event['can_edit'] ? '<a href="' . $event['modify_href'] . '" title="' . $txt['calendar_edit'] . '"><img src="' . $settings['images_url'] . '/icons/modify_small.gif" alt="*" /></a> ' : '', $event['href'] == '' ? '' : '<a href="' . $event['href'] . '">', $event['is_today'] ? '<strong>' . $event['title'] . '</strong>' : $event['title'], $event['href'] == '' ? '' : '</a>', $event['is_last'] ? '<br />' : ', ';
		}
		echo '
		</p></div>';
	}
	// Show statistical style information...
	if ($settings['show_stats_index'])
	{
		echo '
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<a href="', $scripturl, '?action=stats"><span class="generic_icons stats"></span>	</a>
						', $txt['forum_stats'], '
					</span>
				</h4>
			</div>
			<div class="side">
				<ul class="bbc_list">
					<li>', $txt['total_posts'], ': ', $context['common_stats']['total_posts'], '</li>
					<li>', $txt['total_topics'], ': ', $context['common_stats']['total_topics'], '</li>
					<li>', $txt['total_members'], ': ', $context['common_stats']['total_members'], '</li>',
					!empty($settings['show_latest_member']) ? '<li>' . $txt['latest_member'] . ': <strong> ' . $context['common_stats']['latest_member']['link'] . '</strong></li>' : '',
					'<li>', $txt['most_online_today'], ': ', comma_format($modSettings['mostOnlineToday']), '</li>
					<li>', (!empty($context['latest_post']) ? $txt['latest_post'] . ': <strong>&quot;' . $context['latest_post']['link'] . '&quot;<br /></strong> <span class="smalltext">' . $context['latest_post']['time'] . '</span>' : ''), '<br />
					<a class="linkbutton" href="', $scripturl, '?action=recent">', $txt['recent_view'], '</a><br />
					<a href="' . $scripturl . '?action=stats">' . $txt['more_stats'] . '</a></li>
				</ul></div>';
	}

	// "Users online" - in order of activity.
	echo '
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						', $context['show_who'] ? '<a href="' . $scripturl . '?action=who' . '">' : '', '<span class="generic_icons members"></span>	', $context['show_who'] ? '</a>' : '', '
						', $txt['online_users'], '
					</span>
				</h4>
			</div>
			<div class="side">
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
				', $txt['most_online_today'], ': <strong>', comma_format($modSettings['mostOnlineToday']), '</strong>.
				', $txt['most_online_ever'], ': ', comma_format($modSettings['mostOnline']), ' (', timeformat($modSettings['mostDate']), ')
			</p>';

	// If they are logged in, but statistical information is off... show a personal message bar.
	if ($context['user']['is_logged'] && !$settings['show_stats_index'])
	{
		echo '
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						', $context['allow_pm'] ? '<a href="' . $scripturl . '?action=pm">' : '', '<img class="icon" src="', $settings['images_url'], '/message_sm.gif" alt="', $txt['personal_message'], '" />', $context['allow_pm'] ? '</a>' : '', '
						<span>', $txt['personal_message'], '</span>
					</span>
				</h4>
			</div>
			<p class="pminfo">
				<strong><a href="', $scripturl, '?action=pm">', $txt['personal_message'], '</a></strong>
				<span class="smalltext">
					', $txt['you_have'], ' ', comma_format($context['user']['messages']), ' ', $context['user']['messages'] == 1 ? $txt['message_lowercase'] : $txt['msg_alert_messages'], '.... ', $txt['click'], ' <a href="', $scripturl, '?action=pm">', $txt['here'], '</a> ', $txt['to_view'], '
				</span>
			</p>';
	}
	echo '</div>';
	// Info center collapse object.
	echo '
	<script type="text/javascript"><!-- // --><![CDATA[
		var oInfoCenterToggle = new smc_Toggle({
			bToggleEnabled: true,
			bCurrentlyCollapsed: ', empty($options['collapse_header_ic']) ? 'false' : 'true', ',
			aSwappableContainers: [
				\'upshrinkHeaderIC\'
			],
			aSwapImages: [
				{
					sId: \'upshrink_ic\',
					srcExpanded: smf_images_url + \'/kapa.png\',
					altExpanded: ', JavaScriptEscape($txt['upshrink_description']), ',
					srcCollapsed: smf_images_url + \'/ac.png\',
					altCollapsed: ', JavaScriptEscape($txt['upshrink_description']), '
				}
			],
			oThemeOptions: {
				bUseThemeSettings: ', $context['user']['is_guest'] ? 'false' : 'true', ',
				sOptionName: \'collapse_header_ic\',
				sSessionVar: ', JavaScriptEscape($context['session_var']), ',
				sSessionId: ', JavaScriptEscape($context['session_id']), '
			},
			oCookieOptions: {
				bUseCookie: ', $context['user']['is_guest'] ? 'true' : 'false', ',
				sCookieName: \'upshrinkIC\'
			}
		});
	// ]]></script>';
}
?>