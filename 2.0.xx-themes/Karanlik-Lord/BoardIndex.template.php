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
 * @package Karanlik-Lord
 * @version 1.0
 * @theme Karanlik-Lord
 * @author Snrj - http://smf.konusal.com
 * Copyright 2016 Karanlik-Lord
 *
 */
function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;
	if (!$settings['show_stats_index'])
		echo '
	<div id="index_common_stats">
		', $txt['members'], ': ', $context['common_stats']['total_members'], ' &nbsp;&#8226;&nbsp; ', $txt['posts_made'], ': ', $context['common_stats']['total_posts'], ' &nbsp;&#8226;&nbsp; ', $txt['topics'], ': ', $context['common_stats']['total_topics'], '
		', ($settings['show_latest_member'] ? ' ' . $txt['welcome_member'] . ' <strong>' . $context['common_stats']['latest_member']['link'] . '</strong>' . $txt['newest_member'] : '') , '
	</div>';
	foreach ($context['categories'] as $category)
	{
		echo '<div class="catmain">
      <div class="cat_bar" id="category_', $category['id'], '">
        <h3 class="catbg">			
		<span class="togle ust floatright" onclick="Cat'.$category['id'].'.toggle();" ></span>';
		if (!$context['user']['is_guest'] && !empty($category['show_unread']))
			echo '
								<a class="pull-right sakla" href="', $scripturl, '?action=unread;c=', $category['id'], '">', $txt['view_unread_category'], '</a>';
		echo '', $category['name'], '';
		echo '</h3>
			</div>';
		echo '<div  id="AcKapa', $category['id'], '" class="darkblack">';
			foreach ($category['boards'] as $board)
			{
					echo'<div class="boardbg"><div class="board_icon">';
				if ($board['new'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '" class="board_on">';
				elseif ($board['children_new'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '" class="board_on2">';
				elseif ($board['is_redirect'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '" class="board_redirect">';
				else
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '" class="board_off">';
				echo '</a>
					</div>
					<div class="info">
						<a class="subject" href="', $board['href'], '" id="b', $board['id'], '" data-toggle="popover" data-trigger="hover" data-content="', $board['description'],'">', $board['name'], '</a>';
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
					</div>
					<div class="board_stats">
					<p>', comma_format($board['posts']), ' ', $board['is_redirect'] ? $txt['redirects'] : $txt['posts'], ' <br />
						', $board['is_redirect'] ? '' : comma_format($board['topics']) . ' ' . $txt['board_topics'], '
						</p></div>';
					echo '
					<div class="lastpost">';
				if (!empty($board['last_post']['id']))
					echo '
						<p><span class="generic_icons members"></span> ', $board['last_post']['member']['link'] , '<br />
						<span class="generic_icons replies"></span>  ', $board['last_post']['link'], '<br />
						<span class="generic_icons history"></span>  ', $board['last_post']['time'],'
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
	template_info_center();
}
function template_info_center()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;
	echo '<br class="clear"><div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
        <span class="togle ust floatright" onclick="upshrink_ic.toggle();" ></span>
				', sprintf($txt['info_center_title'], $context['forum_name_html_safe']), '
		</h4>
		</div>
        <div class="panel-body sifir" id="upshrinkHeaderIC">';
	if (!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
	{
	
	
		echo '<div class="cat_bar">
				<h4 class="catbg">
					<span class="ie6_header floatleft">
						<a href="', $scripturl, '?action=recent"><span class="generic_icons replies"></span></a>
						', $txt['recent_posts'], '
					</span>
				</h4>
			</div>
			<div id="recent">
				<div class="tablediv">
					<div class="tabledivbody">
					<div class="tabledivrow">
						<div class="tabledivcelll">
						',$txt['topic'],'
						</div>
						<div class="tabledivcell">
						', $txt['by'], '
						</div>
						<div class="tabledivcell">
						',$txt['board'],'
						</div>
						<div class="tabledivcell">
						',$txt['date'],'
						</div>
					</div>';

		if ($settings['number_recent_posts'] == 1)
		{
			echo '<div class="tabledivrow">
					<div class="tabledivcelll">', $txt['recent_view'], ' </div>
					<div class="tabledivcell">', $context['latest_post']['link'], '</div>
					<div class="tabledivcell">', $txt['recent_updated'], ' </div>
					<div class="tabledivcell">(', $context['latest_post']['time'], ')</div>
				  </div>';
		}
		elseif (!empty($context['latest_posts']))
		{
			foreach ($context['latest_posts'] as $post)
				echo '<div class="tabledivrow">
						<div class="tabledivcelll"><span class="generic_icons replies"></span> ', $post['link'], '</div>
						<div class="tabledivcell"> ', $post['poster']['link'], '</div>
						<div class="tabledivcell">', $post['board']['link'], '</div>
						<div class="tabledivcell">', $post['time'], '</div>
					  </div>';
		}
		echo '</div>
			</div></div>';
	}
		// Show information about events, birthdays, and holidays on the calendar.
	if ($context['show_calendar'])
	{
		echo '
			<div class="cat_bar">
				<h4 class="catbg">
					<span class="ie6_header floatleft">
						<a href="', $scripturl, '?action=calendar' . '"><span class="generic_icons calendar"></span></a>
						', $context['calendar_only_today'] ? $txt['calendar_today'] : $txt['calendar_upcoming'], '
					</span>
				</h4>
			</div>
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
			</p>';
	}

	// Show statistical style information...
	if ($settings['show_stats_index'])
	{
		echo '
			<div class="cat_bar">
				<h4 class="catbg">
					<span class="ie6_header floatleft">
						<a href="', $scripturl, '?action=stats"><span class="generic_icons stats"></span></a>
						', $txt['forum_stats'], '
					</span>
				</h4>
			</div>
			<p>
				', $context['common_stats']['total_posts'], ' ', $txt['posts_made'], ' ', $txt['in'], ' ', $context['common_stats']['total_topics'], ' ', $txt['topics'], ' ', $txt['by'], ' ', $context['common_stats']['total_members'], ' ', $txt['members'], '. ', !empty($settings['show_latest_member']) ? $txt['latest_member'] . ': <strong> ' . $context['common_stats']['latest_member']['link'] . '</strong>' : '', '<br />
				', (!empty($context['latest_post']) ? $txt['latest_post'] . ': <strong>&quot;' . $context['latest_post']['link'] . '&quot;</strong>  ( ' . $context['latest_post']['time'] . ' )<br />' : ''), '
				<a href="', $scripturl, '?action=recent">', $txt['recent_view'], '</a>', $context['show_stats'] ? '<br />
				<a href="' . $scripturl . '?action=stats">' . $txt['more_stats'] . '</a>' : '', '
			</p>';
	}

	// "Users online" - in order of activity.
	echo '
			<div class="cat_bar">
				<h4 class="catbg">
					<span class="ie6_header floatleft">
						', $context['show_who'] ? '<a href="' . $scripturl . '?action=who' . '">' : '', '<span class="generic_icons people"></span>', $context['show_who'] ? '</a>' : '', '
						', $txt['online_users'], '
					</span>
				</h4>
			</div>
			<p class="inline stats">
				', $context['show_who'] ? '<a href="' . $scripturl . '?action=who">' : '', comma_format($context['num_guests']), ' ', $context['num_guests'] == 1 ? $txt['guest'] : $txt['guests'], ', ' . comma_format($context['num_users_online']), ' ', $context['num_users_online'] == 1 ? $txt['user'] : $txt['users'];

	// Handle hidden users and buddies.
	$bracketList = array();
	if ($context['show_buddies'])
		$bracketList[] = comma_format($context['num_buddies']) . ' ' . ($context['num_buddies'] == 1 ? $txt['buddy'] : $txt['buddies']);
	if (!empty($context['num_spiders']))
		$bracketList[] = comma_format($context['num_spiders']) . ' ' . ($context['num_spiders'] == 1 ? $txt['spider'] : $txt['spiders']);
	if (!empty($context['num_users_hidden']))
		$bracketList[] = comma_format($context['num_users_hidden']) . ' ' . $txt['hidden'];

	if (!empty($bracketList))
		echo ' (' . implode(', ', $bracketList) . ')';

	echo $context['show_who'] ? '</a>' : '', '
			</p>
			<p class="inline smalltext">';

	// Assuming there ARE users online... each user in users_online has an id, username, name, group, href, and link.
	if (!empty($context['users_online']))
	{
		echo '
				', sprintf($txt['users_active'], $modSettings['lastActive']), ':<br />', implode(', ', $context['list_users_online']);

		// Showing membergroups?
		if (!empty($settings['show_group_key']) && !empty($context['membergroups']))
			echo '
				<br />[' . implode(']&nbsp;&nbsp;[', $context['membergroups']) . ']';
	}

	echo '
			</p>
			<p class="last smalltext">
				', $txt['most_online_today'], ': <strong>', comma_format($modSettings['mostOnlineToday']), '</strong>.
				', $txt['most_online_ever'], ': ', comma_format($modSettings['mostOnline']), ' (', timeformat($modSettings['mostDate']), ')
			</p>';

	// If they are logged in, but statistical information is off... show a personal message bar.
	if ($context['user']['is_logged'] && !$settings['show_stats_index'])
	{
		echo '
			<div class="cat_bar">
				<h4 class="catbg">
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
	echo '</div></div>';
	echo '<script type="text/javascript"><!-- // --><![CDATA[
	$(document).ready(function(){
    $(\'[data-toggle="popover"]\').popover();   
	});
	';
	foreach ($context['categories'] as $category)
	{
	echo '
		var Cat'.$category['id'].'=new smc_Toggle
		({
					bToggleEnabled:true,
					bCurrentlyCollapsed:false,
					aSwappableContainers:[\'AcKapa'.$category['id'].'\'],
					oCookieOptions:{bUseCookie:true,
					sCookieName:\'AcKapa'.$category['id'].'\',
					sCookieValue:\'0\'}
		});	
		';
	}
	echo'var upshrink_ic = new smc_Toggle({
			bToggleEnabled: true,
			bCurrentlyCollapsed:false,
			aSwappableContainers: [
				\'upshrinkHeaderIC\'
			],
			oCookieOptions: {
				bUseCookie:true,
				sCookieName: \'upshrinkIC\'
			}
		});// ]]></script>';
}
?>