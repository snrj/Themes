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
function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings,$memberContext;
	echo '<div class="boardindextable col-sm-9">';
	foreach ($context['categories'] as $category)
	{
		echo '<div class="catmain">
			<div class="cat_bar" id="c', $category['id'], '">
			<h3 class="catbg">
				', $category['name'], '
			</h3>
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
						<h2><a class="subject" href="', $board['href'], '" id="b', $board['id'], '" >', $board['name'], '</a></h2>
						<p>', $board['description'] , '</p>
						<p>', comma_format($board['posts']), ' ', $board['is_redirect'] ? $txt['redirects'] : $txt['posts'], ' 
						', $board['is_redirect'] ? '' : comma_format($board['topics']) . ' ' . $txt['board_topics'], '';
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
					echo '<span id="board_', $board['id'], '_children" class="childhover">
							  <button class="dropbtn">', $txt['parent_boards'], '</button>
							  <span class="childhover-content">', implode($children), '</span>
						</span>';
				}
						
					echo'	</p>';
				if ($board['can_approve_posts'] && ($board['unapproved_posts'] || $board['unapproved_topics']))
					echo '
						<a href="', $scripturl, '?action=moderate;area=postmod;sa=', ($board['unapproved_topics'] > 0 ? 'topics' : 'posts'), ';brd=', $board['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', sprintf($txt['unapproved_posts'], $board['unapproved_topics'], $board['unapproved_posts']), '" class="moderation_link"><span class="generic_icons error"></span></a>';
				if (!empty($board['moderators']))
					echo '
						<p class="moderators"><span class="generic_icons warning_watch"></span> ', implode($board['link_moderators']), '</p>';
			
					echo '
					</div>';
					echo '
					<div class="lastpost">';
				if (!empty($board['last_post']['id'])){
							loadMemberData($board['last_post']['member']['id']);
							loadMemberContext($board['last_post']['member']['id']);
							if($memberContext[$board['last_post']['member']['id']]['avatar']['image'])
				echo'', $memberContext[$board['last_post']['member']['id']]['avatar']['image'],'';
				else
				echo'<img class="avatar" src="'.$settings['images_url'].'/theme/default_avatar.png" alt="*" />';
					echo '
						<p> ', $txt['by'], ' ', $board['last_post']['member']['link'] , '<br />
						<strong>', $txt['last_post'], '</strong> ', $board['last_post']['link'], '<br />
						', $txt['on'], ' ', $board['last_post']['time'],'
						</p>';
						}
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
      </div><div class="col-sm-3">';
	template_info_center();
	echo '</div>';
}

function template_info_center()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings,$memberContext;
	if (!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
	{
		echo '
			<div class="cat_bar">
				<h4 class="catbg">
						<a href="', $scripturl, '?action=recent">
						<span class="generic_icons replies"></span>						
						', $txt['recent_posts'], '</a>
				</h4>
			</div>
			<div id="recent_posts_content">';
		if (!empty($context['latest_posts']))
		{
			echo '<ul class="sonileti">';

		foreach ($context['latest_posts'] as $post){
		loadMemberData($post['poster']['id']);
		loadMemberContext($post['poster']['id']);
			echo '<li>';
					if($memberContext[$post['poster']['id']]['avatar']['image'])
				echo'', $memberContext[$post['poster']['id']]['avatar']['image'],'';
				else
				echo'<img class="avatar" src="'.$settings['images_url'].'/theme/default_avatar.png" alt="*" />';
						echo'<strong>', $post['link'], '</strong><br/>
						', $post['poster']['link'], '<br/>
						', $post['time'], '
					</li>';
		}			
		echo '
				</ul>';
		}
		echo '
			</div>';
	}

	// Show information about events, birthdays, and holidays on the calendar.
	if ($context['show_calendar'])
	{
		echo '
			<div class="caption-side">
				<h4 class="catbg">
						<a href="', $scripturl, '?action=calendar' . '"><span class="generic_icons calendar"></span></a>
						', $context['calendar_only_today'] ? $txt['calendar_today'] : $txt['calendar_upcoming'], '
				</h4>
			</div>
			<p class="inline">';

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
			</p>';
	}

	if ($settings['show_stats_index'])
	{
		echo '
			<div class="cat_bar">
				<h4 class="catbg">
						<a href="', $scripturl, '?action=stats"><span class="generic_icons stats"></span>
						', $txt['forum_stats'], '</a>
				</h4>
			</div>
			<dl class="forumstats">
				<dt>', $txt['posts_made'], '</dt><dd>', $context['common_stats']['total_posts'], '</dd>
				<dt>', $txt['topics'], '</dt><dd> ', $context['common_stats']['total_topics'], ' </dd>
				<dt> ', $txt['members'], '</dt><dd> ', $context['common_stats']['total_members'], '</dd>
				', !empty($settings['show_latest_member']) ?'<dt>'. $txt['latest_member'] . '</dt><dd> ' . $context['common_stats']['latest_member']['link'] . '</dd>' : '', '
				</dl>
				<a class="small" href="', $scripturl, '?action=recent">', $txt['recent_view'], '</a>', $context['show_stats'] ? '<br class="clearfix"/>
				<a class="small" href="' . $scripturl . '?action=stats">' . $txt['more_stats'] . '</a>' : '', '
			';
	}

	echo '
			<div class="cat_bar">
				<h4 class="catbg">
						', $context['show_who'] ? '<a href="' . $scripturl . '?action=who' . '"><span class="generic_icons members"></span> '.$txt['online_users'].'</a>' : '<span class="generic_icons members"></span>'.$txt['online_users'].' ','
				</h4>
			</div>
			<dl class="forumstats">
				<dt>',$txt['guests'],'</dt><dd> ', comma_format($context['num_guests']), '</dd>
				<dt>', $txt['user'],'</dt><dd> ' . comma_format($context['num_users_online']), '</dd>';
	$bracketList = array();
	if ($context['show_buddies'])
		$bracketList[] = '<dt>' . $txt['buddy'].'</dt><dd> '.comma_format($context['num_buddies']) . '</dd>' ;
	if (!empty($context['num_spiders']))
		$bracketList[] = '<dt> ' .$txt['spider'].'</dt><dd> '.comma_format($context['num_spiders']) . '</dd>';
	if (!empty($context['num_users_hidden']))
		$bracketList[] = '<dt> ' . $txt['hidden'].'</dt><dd> '.comma_format($context['num_users_hidden']) . '</dd>';

	if (!empty($bracketList))
		echo ' ' . implode($bracketList) . '';

	echo '
				<dt>', $txt['most_online_today'], '</dt><dd>', comma_format($modSettings['mostOnlineToday']), '</dd>
				<dt>', $txt['most_online_ever'], '</dt><dd>', comma_format($modSettings['mostOnline']), '</dd>';
	echo '</dl>';

	if (!empty($context['users_online']))
	{
		echo '<p class="smalltext">', sprintf($txt['users_active'], $modSettings['lastActive']), '</p>
		<ul class="saymem smalltext">';
			foreach ($context['users_online'] as $saymem)
			{
			if($saymem['group']== 1)
			{
			loadMemberData($saymem['id']);
			loadMemberContext($saymem['id']);
			echo '<li class="saymemstaff">';
					if($memberContext[$saymem['id']]['avatar']['image'])
				echo'', $memberContext[$saymem['id']]['avatar']['image'],'';
				else
				echo'<img class="avatar" src="'.$settings['images_url'].'/theme/default_avatar.png" alt="*" />';
			echo'',$saymem['link'],'  </li>  ';
			}
			}
			foreach ($context['users_online'] as $saymem)
			{
			if($saymem['group']!= 1)
			echo'<li class="saymember">',$saymem['link'],'  </li>  ';
			}
		echo '</ul>';
		if (!empty($settings['show_group_key']) && !empty($context['membergroups']))
			echo '
				<hr />[' . implode(']&nbsp;&nbsp;[', $context['membergroups']) . ']';
	}

	// If they are logged in, but statistical information is off... show a personal message bar.
	if ($context['user']['is_logged'] && !$settings['show_stats_index'])
	{
		echo '
			<div class="cat_bar">
				<h4 class="catbg">
						', $context['allow_pm'] ? '<a href="' . $scripturl . '?action=pm">' : '', '<img class="icon" src="', $settings['images_url'], '/message_sm.gif" alt="', $txt['personal_message'], '" />', $context['allow_pm'] ? '</a>' : '', '
						<span>', $txt['personal_message'], '</span>
				</h4>
			</div>
			<p class="pminfo">
				<strong><a href="', $scripturl, '?action=pm">', $txt['personal_message'], '</a></strong>
				<span class="small">
					', $txt['you_have'], ' ', comma_format($context['user']['messages']), ' ', $context['user']['messages'] == 1 ? $txt['message_lowercase'] : $txt['msg_alert_messages'], '.... ', $txt['click'], ' <a href="', $scripturl, '?action=pm">', $txt['here'], '</a> ', $txt['to_view'], '
				</span>
			</p>';
	}

}
?>