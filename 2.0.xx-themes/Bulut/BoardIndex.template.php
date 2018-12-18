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

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings,$memberContext;

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
	<script src="', $settings['default_theme_url'], '/scripts/fader.js"></script>
	<script><!-- // --><![CDATA[

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

	echo '<div class="boardindextable" id="boardindex_table">';
	foreach ($context['categories'] as $category)
	{
		echo '<div class="catmain">
      <div class="cat_bar" id="category_', $category['id'], '">
        <h3 class="catbg">';
		if ($category['can_collapse'])
			echo '
								<a class="collapse" href="', $category['collapse_href'], '">',temizle($category['collapse_image']), '</a>';
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
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '"><i class="fa fa-comments fa-2x"></i>';
				elseif ($board['children_new'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '"><i class="fa fa-comments-o fa-2x"></i>';
				elseif ($board['is_redirect'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '"><i class="fa fa-repeat fa-2x"></i>';
				else
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0'), '"><i class="fa fa-comment-o fa-2x"></i>';
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
						<a href="', $scripturl, '?action=moderate;area=postmod;sa=', ($board['unapproved_topics'] > 0 ? 'topics' : 'posts'), ';brd=', $board['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', sprintf($txt['unapproved_posts'], $board['unapproved_topics'], $board['unapproved_posts']), '" class="moderation_link"><i class="fa fa-exclamation-circle" ></i></a>';
				if (!empty($board['moderators']))
					echo '
						<p class="moderators"><i class="fa fa-exclamation-triangle"></i> ', implode($board['link_moderators']), '</p>';
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
						$children[] =$child['new'] ? '<strong><i class="fa fa-comment-o"></i> ' . $child['link'] . ' <i class="fa fa-comments-o" ></i></strong>' :'<strong><i class="fa fa-comment-o"></i> '.$child['link'].'</strong>';
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
				if (!empty($board['last_post']['id'])){
					echo '
						<p> ', $txt['by'], ' ', $board['last_post']['member']['link'] , '<br />
						<strong>', $txt['last_post'], '</strong> ', $board['last_post']['link'], '<br />
						', $txt['on'], ' ',$board['last_post']['time'],'
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
      </div>';
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
        <div class="panel-body sifir" id="upshrinkHeaderIC">
		<ul class="nav nav-tabs">';
	if (!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
	{	
		echo'<li>
				<a data-toggle="tab" href="#recent">
					<i class="fa fa-comments"></i>
					', $txt['recent_posts'], '
				</a>
			</li>';
	}
	if ($settings['show_stats_index'])
	{
		echo'<li>
				<a data-toggle="tab" href="#stats">
					<i class="fa fa-signal"></i>
					', $txt['forum_stats'], '
				</a>
			</li>';
	}
		echo'<li class="active">
				<a data-toggle="tab" href="#online">
					<i class="fa fa-users"></i>
					', $txt['online_users'], '
				</a>
			</li>';
  	if ($context['show_calendar'])
	{
		echo'<li>
				<a data-toggle="tab" href="#calendar">
					<i class="fa fa-calendar"></i>
					', $context['calendar_only_today'] ? $txt['calendar_today'] : $txt['calendar_upcoming'], '
				</a>
			</li>';
	}
echo'</ul><div class="tab-content">';
	if (!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
	{
		echo ' <div id="recent" class="tab-pane fade">
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
						<div class="tabledivcelll">', $post['link'], '</div>
						<div class="tabledivcell"> ', $post['poster']['link'], '</div>
						<div class="tabledivcell">', $post['board']['link'], '</div>
						<div class="tabledivcell">', $post['time'], '</div>
					  </div>';
		}
		echo '</div>
			</div></div>';
	}
	if ($settings['show_stats_index'])
	{
		echo ' <div id="stats" class="tab-pane fade">
			<p class="inline">
				', $context['common_stats']['total_posts'], ' ', $txt['posts_made'], ' ', $txt['in'], ' ', $context['common_stats']['total_topics'], ' ', $txt['topics'], ' ', $txt['by'], ' ', $context['common_stats']['total_members'], ' ', $txt['members'], '. ', !empty($settings['show_latest_member']) ? $txt['latest_member'] . ': <strong> ' . $context['common_stats']['latest_member']['link'] . '</strong>' : '', '<br />
				', (!empty($context['latest_post']) ? $txt['latest_post'] . ': <strong>&quot;' . $context['latest_post']['link'] . '&quot;</strong>  ( ' . $context['latest_post']['time'] . ' )<br />' : ''), '
				<a href="', $scripturl, '?action=recent">', $txt['recent_view'], '</a>', $context['show_stats'] ? '<br />
				<a href="' . $scripturl . '?action=stats">' . $txt['more_stats'] . '</a>' : '', '
			</p></div>';
	}
echo'
  <div id="online" class="tab-pane fade in active">
			<p class="inline stats">
				', $context['show_who'] ? '<a href="' . $scripturl . '?action=who">' : '', comma_format($context['num_guests']), ' ', $context['num_guests'] == 1 ? $txt['guest'] : $txt['guests'], ', ' . comma_format($context['num_users_online']), ' ', $context['num_users_online'] == 1 ? $txt['user'] : $txt['users'];
	$bracketList = array();
	if ($context['show_buddies'])
		$bracketList[] = comma_format($context['num_buddies']) . ' ' . ($context['num_buddies'] == 1 ? $txt['buddy'] : $txt['buddies']);
	if (!empty($context['num_spiders']))
		$bracketList[] = comma_format($context['num_spiders']) . ' ' . ($context['num_spiders'] == 1 ? $txt['spider'] : $txt['spiders']);
	if (!empty($context['num_users_hidden']))
		$bracketList[] = comma_format($context['num_users_hidden']) . ' ' . $txt['hidden'];

	if (!empty($bracketList))
		echo ' (' . implode(', ', $bracketList) . ')';

	echo $context['show_who'] ? '</a>' : '', '<br />';
	if (!empty($context['users_online']))
	{
		echo '
				', sprintf($txt['users_active'], $modSettings['lastActive']), ':<br />', implode(', ', $context['list_users_online']);
		if (!empty($settings['show_group_key']) && !empty($context['membergroups']))
			echo '
				<br />[' . implode(']&nbsp;&nbsp;[', $context['membergroups']) . ']';
	}
	echo '
			<br />
				', $txt['most_online_today'], ': <strong>', comma_format($modSettings['mostOnlineToday']), '</strong>.
				', $txt['most_online_ever'], ': ', comma_format($modSettings['mostOnline']), ' (', timeformat($modSettings['mostDate']), ')
			</p>';
	if ($context['user']['is_logged'] && !$settings['show_stats_index'])
	{
		echo '
						', $context['allow_pm'] ? '<a href="' . $scripturl . '?action=pm">' : '', '<i class="fa fa-pm"></i>', $context['allow_pm'] ? '</a>' : '', '
						<span>', $txt['personal_message'], '</span>	
			<p class="pminfo">
				<strong><a href="', $scripturl, '?action=pm">', $txt['personal_message'], '</a></strong>
				<span class="smalltext">
					', $txt['you_have'], ' ', comma_format($context['user']['messages']), ' ', $context['user']['messages'] == 1 ? $txt['message_lowercase'] : $txt['msg_alert_messages'], '.... ', $txt['click'], ' <a href="', $scripturl, '?action=pm">', $txt['here'], '</a> ', $txt['to_view'], '
				</span>
			</p>';
	}
echo'</div>';
	if ($context['show_calendar'])
	{
		echo '<div id="calendar" class="tab-pane fade">
			<p class="inline smalltext">';
		if (!empty($context['calendar_holidays']))
				echo '
				<span class="holiday">', $txt['calendar_prompt'], ' ', implode(', ', $context['calendar_holidays']), '</span><br />';
		if (!empty($context['calendar_birthdays']))
		{
				echo '
				<span class="birthday">', $context['calendar_only_today'] ? $txt['birthdays'] : $txt['birthdays_upcoming'], '</span> ';
		foreach ($context['calendar_birthdays'] as $member)
				echo '
				<a href="', $scripturl, '?action=profile;u=', $member['id'], '">', $member['is_today'] ? '<strong>' : '', $member['name'], $member['is_today'] ? '</strong>' : '', isset($member['age']) ? ' (' . $member['age'] . ')' : '', '</a>', $member['is_last'] ? '<br />' : ', ';
		}
		if (!empty($context['calendar_events']))
		{
			echo '
				<span class="event">', $context['calendar_only_today'] ? $txt['events'] : $txt['events_upcoming'], '</span> ';
			foreach ($context['calendar_events'] as $event)
				echo '
					', $event['can_edit'] ? '<a href="' . $event['modify_href'] . '" title="' . $txt['calendar_edit'] . '"><span class="generic_icons calendar_export"></span></a> ' : '', $event['href'] == '' ? '' : '<a href="' . $event['href'] . '">', $event['is_today'] ? '<strong>' . $event['title'] . '</strong>' : $event['title'], $event['href'] == '' ? '' : '</a>', $event['is_last'] ? '<br />' : ', ';
		}
		echo '
			</p></div>';
	}
	echo '</div></div></div>';
	echo '	<script><!-- // --><![CDATA[
		var oInfoCenterToggle = new smc_Toggle({
			bToggleEnabled: true,
			bCurrentlyCollapsed: ', empty($options['collapse_header_ic']) ? 'false' : 'true', ',
			aSwappableContainers: [
				\'upshrinkHeaderIC\'
			],
			aSwapImages: [
				{
					sId: \'upshrink_ic\',
					srcExpanded: smf_images_url + \'/collapse.gif\',
					altExpanded: ', JavaScriptEscape($txt['upshrink_description']), ',
					srcCollapsed: smf_images_url + \'/expand.gif\',
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
