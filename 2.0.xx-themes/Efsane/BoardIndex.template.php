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

	echo '
	<div id="boardindex_table">';
bunebe();
	/* Each category in categories is made up of:
	id, href, link, name, is_collapsed (is it collapsed?), can_collapse (is it okay if it is?),
	new (is it new?), collapse_href (href to collapse/expand), collapse_image (up/down image),
	and boards. (see below.) */
	foreach ($context['categories'] as $category)
	{
		echo '<div class="blm">';
		// If theres no parent boards we can see, avoid showing an empty category (unless its collapsed)
		if (empty($category['boards']) && !$category['is_collapsed'])
			continue;

		echo '

		<table class="table_list">
			<tbody class="header" id="category_', $category['id'], '">
				<tr>
					<td colspan="5">
						<div class="cat_bar">
							<h3 class="catbg">';

		// If this category even can collapse, show a link to collapse it.
		if ($category['can_collapse'])
			echo '
								<a class="collapse" href="', $category['collapse_href'], '"><img src="', $settings['images_url'], '/collapse.gif" alt="" /></a>';

		if (!$context['user']['is_guest'] && !empty($category['show_unread']))
			echo '
								<a class="unreadlink" href="', $scripturl, '?action=unread;c=', $category['id'], '">', $txt['view_unread_category'], '</a>';

		echo '
								', $category['link'], '
							</h3>
						</div>
					</td>
				</tr>
				<tr class="baslik">
					<td></td>
					<td>',$txt['efsane_baslik'],'</td>
					<td class="stats">',$txt['efsane_bhit'],'</td>
					<td class="stats">',$txt['efsane_ista'],'</td>
					<td class="stats">',$txt['efsane_sonmesaj'],'</td>
				</tr>
			</tbody>';

		// Assuming the category hasn't been collapsed...
		if (!$category['is_collapsed'])
		{

		echo '
			<tbody class="content" id="category_', $category['id'], '_boards">';
			/* Each board in each category's boards has:
			new (is it new?), id, name, description, moderators (see below), link_moderators (just a list.),
			children (see below.), link_children (easier to use.), children_new (are they new?),
			topics (# of), posts (# of), link, href, and last_post. (see below.) */
			foreach ($category['boards'] as $board)
			{
				echo '
				<tr id="board_', $board['id'], '" class="windowbg2">
					<td class="icon windowbg"', !empty($board['children']) ? ' rowspan="2"' : '', '>
						<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '">';
				// If the board or children is new, show an indicator.
				if ($board['new'] || $board['children_new'])
				{
					if (file_exists($settings['theme_dir'] . '/images/icons/' . $board['id'] . '/' . $context['theme_variant_url'] . 'on.png'))
						$board_new_img = '/icons/' . $board['id'];
					else
						$board_new_img = '';
					echo '
							<img src="', $settings['images_url'], $board_new_img, '/', $context['theme_variant_url'], 'on', $board['new'] ? '' : '2', '.png" alt="', $txt['new_posts'], '" title="', $txt['new_posts'], '" />';
				}
				// Is it a redirection board?
				elseif ($board['is_redirect'])
				{
					if (file_exists($settings['theme_dir'] . '/images/icons/' . $board['id'] . '/' . $context['theme_variant_url'] . 'redirect.png'))
						$board_redirect_img = '/icons/' . $board['id'];
					else
						$board_redirect_img = '';
					echo '
							<img src="', $settings['images_url'], $board_redirect_img, '/', $context['theme_variant_url'], 'redirect.png" alt="*" title="*" />';
				}
				// No new posts at all! The agony!!
				else
				{
					if (file_exists($settings['theme_dir'] . '/images/icons/' . $board['id'] . '/' . $context['theme_variant_url'] . 'off.png'))
						$board_nonew_img = '/icons/' . $board['id'];
					else
						$board_nonew_img = '';
					echo '
							<img src="', $settings['images_url'], $board_nonew_img, '/', $context['theme_variant_url'], 'off.png" alt="', $txt['old_posts'], '" title="', $txt['old_posts'], '" />';
				}
				echo '
						</a>
					</td>
					<td class="info windowbg">
						<a class="subject" href="', $board['href'], '" name="b', $board['id'], '">', $board['name'], '</a>';

				// Has it outstanding posts for approval?
				if ($board['can_approve_posts'] && ($board['unapproved_posts'] || $board['unapproved_topics']))
					echo '
						<a href="', $scripturl, '?action=moderate;area=postmod;sa=', ($board['unapproved_topics'] > 0 ? 'topics' : 'posts'), ';brd=', $board['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', sprintf($txt['unapproved_posts'], $board['unapproved_topics'], $board['unapproved_posts']), '" class="moderation_link">(!)</a>';

				echo '

						<p>', $board['description'] , '</p>';

			// Show the "Child Boards: ". (there's a link_children but we're going to bold the new ones...)
				if (!empty($board['children']))
				{
					// Sort the links into an array with new boards bold so it can be imploded.
					$children = array();
					/* Each child in each board's children has:
							id, name, description, new (is it new?), topics (#), posts (#), href, link, and last_post. */
					foreach ($board['children'] as $child)
					{
						if (!$child['is_redirect'])
							$child['link'] = ($child['new'] ? '<img src="' . $settings['images_url'] . '/img/subforum_new-48.png" class="new_posts" alt="*"  />' : '<img src="'.$settings['images_url'].'/img/subforum_old-48.png" alt="*"  />').'<a href="' . $child['href'] . '" ' . ($child['new'] ? 'class="new_posts" ' : '') . 'title="' . ($child['new'] ? $txt['new_posts'] : $txt['old_posts']) . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')">' . $child['name'] . ($child['new'] ? '</a>' : '') . '</a>';
						else
							$child['link'] = '<img src="'.$settings['images_url'].'/img/subforum_link-48.png" alt="'.$txt['redirects'].'"><a href="' . $child['href'] . '" title="' . comma_format($child['posts']) . ' ' . $txt['redirects'] . '">' . $child['name'] . '</a>';

						// Has it posts awaiting approval?
						if ($child['can_approve_posts'] && ($child['unapproved_posts'] || $child['unapproved_topics']))
							$child['link'] .= ' <a href="' . $scripturl . '?action=moderate;area=postmod;sa=' . ($child['unapproved_topics'] > 0 ? 'topics' : 'posts') . ';brd=' . $child['id'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" title="' . sprintf($txt['unapproved_posts'], $child['unapproved_topics'], $child['unapproved_posts']) . '" class="moderation_link">(!)</a>';

						$children[] = $child['new'] ? '<strong>' . $child['link'] . '</strong>' : $child['link'];
					}
					echo '
					<div class="altblm">', implode(', ', $children), '</div>
						';
				}

				// Show the "Moderators: ". Each has name, href, link, and id. (but we're gonna use link_moderators.)
				if (!empty($board['moderators']))
					echo '
						<p class="moderators">', count($board['moderators']) == 1 ? $txt['moderator'] : $txt['moderators'], ': ', implode(', ', $board['link_moderators']), '</p>';

				// Show some basic information about the number of posts, etc.
					echo '
					</td>
					<td class="stats windowbg"><a class="floatright" href="', $scripturl, '?action=.xml;sa=recent;board=', $board['id'], ';limit=10;type=rss2"><img src="', $settings['images_url'], '/img/rss.gif" alt="*" /></a><a href="', $board['href'], '" title="', $board['name'], '"><img style="margin-left: 10px;" src="' . $settings['images_url'] . '/img/gozat.gif" alt="gozat"/></a><br/><a class="floatright" href="?action=.xml;sa=recent;board=', $board['id'], ';limit=10"><img src="', $settings['images_url'], '/img/rss_feedmailer.gif" alt="*" /></a>';
					if($board['posts']<15) echo'<div id="teknolojihit" style="background-position: -0px -0px; width: 60px; height: 16px"></div>'; 
					elseif($board['topics']<35) echo'<div id="teknolojihit" style="background-position: -0px -17px; width: 60px; height: 16px"></div>'; 
					elseif($board['topics']<70) echo'<div id="teknolojihit" style="background-position: -0px -33px; width: 60px; height: 16px"></div>'; 
					elseif($board['topics']<105) echo'<div id="teknolojihit" style="background-position: -0px -50px; width: 60px; height: 16px"></div>'; 
					elseif($board['topics']<151) echo'<div id="teknolojihit" style="background-position: -0px -67px; width: 60px; height: 16px"></div>'; 
					elseif($board['topics']<301) echo'<div id="teknolojihit" style="background-position: -0px -84px; width: 60px; height: 16px"></div>'; 
					else echo'<div id="teknolojihit" style="background-position: -0px -84px; width: 60px; height: 16px"></div> '; 
					echo'</td>
					<td class="stats windowbg">
						<p><span class="stats1">', comma_format($board['posts']), ' ', $board['is_redirect'] ? $txt['redirects'] : $txt['posts'], ' </span><br />
						<span class="stats2">', $board['is_redirect'] ? '' : comma_format($board['topics']) . ' ' . $txt['board_topics'], '</span>
						</p>
					</td>
					<td class="lastpost windowbg">';
					if (!empty($board['last_post']['id'])){
							loadMemberData($board['last_post']['member']['id']);
							loadMemberContext($board['last_post']['member']['id']);
							if($memberContext[$board['last_post']['member']['id']]['avatar']['image'])
							echo'<a href="' , $scripturl , '?action=profile;u=' .$board['last_post']['member']['id']. ';" class="floatleft" style="margin: 5px 9px 0em 5px;">', $memberContext[$board['last_post']['member']['id']]['avatar']['image'],'</a>';
							else
							echo'<a href="' , $scripturl , '?action=profile;u=' .$board['last_post']['member']['id']. ';" class="floatleft" style="margin: 5px 9px 0em 5px;"><img class="avatar" src="'.$settings['images_url'].'/default_avatar.png" alt="*" /></a>';
					echo '
						<p> <img style="float:left;width: 16px;height: 16px;" src="', $settings['images_url'], '/img/user.png" alt="*" />', $board['last_post']['member']['link'] , '</p>
						<p><img style="float:left;width: 16px;height: 16px;" src="', $settings['images_url'], '/img/subforum_new-48.png" alt="*" />', $board['last_post']['link'], '</p>
						<p><img style="float:left;width: 16px;height: 16px;" src="', $settings['images_url'], '/img/time.png" alt="*" />', $board['last_post']['time'],'</p>';
					}
				echo '
					</td>
				</tr>';
			}
		echo '
			</tbody>
			</table>';
		}
		echo '</div><br />';
	}
	echo '
	</div>';

	if ($context['user']['is_logged'])
	{
		echo '
	<div id="posting_icons" class="floatleft">';

		// Mark read button.
		$mark_read_button = array(
			'markread' => array('text' => 'mark_as_read', 'image' => 'markread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=all;' . $context['session_var'] . '=' . $context['session_id']),
		);

		echo '
		<ul class="reset">
			<li class="floatleft"><img src="', $settings['images_url'], '/new_some.png" alt="" /> ', $txt['new_posts'], '</li>
			<li class="floatleft"><img src="', $settings['images_url'], '/new_none.png" alt="" /> ', $txt['old_posts'], '</li>
			<li class="floatleft"><img src="', $settings['images_url'], '/new_redirect.png" alt="" /> ', $txt['redirect_board'], '</li>
		</ul>
	</div>';

		// Show the mark all as read button?
		if ($settings['show_mark_read'] && !empty($context['categories']))
			echo '<div class="mark_read">', template_button_strip($mark_read_button, 'right'), '</div>';
	}
	else
	{
		echo '
	<div id="posting_icons" class="flow_hidden">
		<ul class="reset">
			<li class="floatleft"><img src="', $settings['images_url'], '/new_none.png" alt="" /> ', $txt['old_posts'], '</li>
			<li class="floatleft"><img src="', $settings['images_url'], '/new_redirect.png" alt="" /> ', $txt['redirect_board'], '</li>
		</ul>
	</div>';
	}

	echo '<br class="clear" />';

	template_info_center();
}

function template_info_center()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;
	// Show information about events, birthdays, and holidays on the calendar.
	if ($context['show_calendar'])
	{
		echo '
		<div class="kabuktakvim">
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<a href="', $scripturl, '?action=calendar' . '"><img class="icon" src="', $settings['images_url'], '/icons/calendar.gif', '" alt="', $context['calendar_only_today'] ? $txt['calendar_today'] : $txt['calendar_upcoming'], '" /></a>
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
			</p>
			</div>
			<br class="clear" />';
	}

	// Show statistical style information...
	if ($settings['show_stats_index'])
	{
		echo '
			<div class="cat_bar">
			   <h3 class="catbg">
						<a href="', $scripturl, '?action=stats"><img class="icon" src="', $settings['images_url'], '/icons/info.gif" alt="', $txt['forum_stats'], '" /></a>
						', $txt['forum_stats'], '
				</h3>
			</div>
				<ul class="forum-static">
     <li><div class="fullimg icon-1"></div><div class="forum-konusal"><span>',$context['num_users_online'],'</span><strong><a href="' . $scripturl . '?action=who' . '">Online Üye</a></strong></div></li>
     <li><div class="fullimg icon-2"></div><div class="forum-konusal"><span>', $context['common_stats']['total_members'], '</span><strong>',$txt['efsane_uye'],'</strong></div></li>
     <li><div class="fullimg icon-3"></div><div class="forum-konusal"><span>', $context['common_stats']['total_topics'], '</span><strong>',$txt['efsane_konu'],'</strong></div></li>
     <li><div class="fullimg icon-4"></div><div class="forum-konusal"><span>', $context['common_stats']['total_posts'], ' </span><strong>',$txt['efsane_mesaj'],'</strong></div></li>
     <li class="sonla"><div class="fullimg icon-5"></div><div class="forum-konusal"><br/>',$txt['efsane_sonkayit'],'<br/> <b>' . $context['common_stats']['latest_member']['link'] . '</b><br/>',$txt['efsane_welcome'],' </div></li>
    </ul>';
	}




	// Info center collapse object.
	echo '
	<script><!-- // --><![CDATA[
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
function bunebe()
{
   global $context, $smcFunc, $txt, $scripturl, $modSettings,$user_info;
//smf.konusal.com En cok Konu acan top 10
if (($members = cache_get_data('stats_top_starters', 360)) == null)
	{
		$request = $smcFunc['db_query']('', '
			SELECT id_member_started, COUNT(*) AS hits
			FROM {db_prefix}topics' . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? '
			WHERE id_board != {int:recycle_board}' : '') . '
			GROUP BY id_member_started
			ORDER BY hits DESC
			LIMIT 10',
			array(
				'recycle_board' => $modSettings['recycle_board'],
			)
		);
		$members = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$members[$row['id_member_started']] = $row['hits'];
		$smcFunc['db_free_result']($request);

		cache_put_data('stats_top_starters', $members, 360);
	}

	if (empty($members))
		$members = array(0 => 0);
	$members_result = $smcFunc['db_query']('', '
		SELECT id_member, real_name
		FROM {db_prefix}members
		WHERE id_member IN ({array_int:member_list})
		ORDER BY FIND_IN_SET(id_member, {string:top_topic_posters})
		LIMIT 10',
		array(
			'member_list' => array_keys($members),
			'top_topic_posters' => implode(',', array_keys($members)),
		)
	);
	$context['top_starters'] = array();
	$max_num_topics = 1;
	while ($row_members = $smcFunc['db_fetch_assoc']($members_result))
	{
		$context['top_starters'][] = array(
			'name' => $row_members['real_name'],
			'id' => $row_members['id_member'],
			'num_topics' => $members[$row_members['id_member']],
			'href' => $scripturl . '?action=profile;u=' . $row_members['id_member'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row_members['id_member'] . '">' . $row_members['real_name'] . '</a>'
		);

		if ($max_num_topics < $members[$row_members['id_member']])
			$max_num_topics = $members[$row_members['id_member']];
		if (!empty($modSettings['MemberColorStats']))
			$context['MemberColor_ID_MEMBER'][$row_members['id_member']] = $row_members['id_member'];
	}
	$smcFunc['db_free_result']($members_result);

	foreach ($context['top_starters'] as $i => $topic)
	{
		$context['top_starters'][$i]['post_percent'] = round(($topic['num_topics'] * 100) / $max_num_topics);
		$context['top_starters'][$i]['num_topics'] = comma_format($context['top_starters'][$i]['num_topics']);
	}
//smf.konusal.com En cok Konu acan top 10 Bitti	
//smf.konusal.com Yeni uyeler top 10
   $members_result =  $smcFunc['db_query']('', '
      SELECT id_member, real_name, posts
      FROM {db_prefix}members
      ORDER BY id_member DESC
      LIMIT 10',
      array(
      )
   );
   $context['new_members'] = array();
   while ($row_members = $smcFunc['db_fetch_assoc']($members_result))
   {
      $context['new_members'][] = array(
         'name' => $row_members['real_name'],
         'id' => $row_members['id_member'],
         'href' => $scripturl . '?action=profile;u=' . $row_members['id_member'],
         'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row_members['id_member'] . '">' . $row_members['real_name'] . '</a>'
      );
   }
   $smcFunc['db_free_result']($members_result);
//smf.konusal.com Yeni uyeler top 10 bitti
//smf.konusal.com En cok mesaj atan top 10.
	$members_result = $smcFunc['db_query']('', '
		SELECT id_member, real_name, posts
		FROM {db_prefix}members
		WHERE posts > {int:no_posts}
		ORDER BY posts DESC
		LIMIT 10',
		array(
			'no_posts' => 0,
		)
	);
	$context['top_posters'] = array();
	$max_num_posts = 1;
	$context['MemberColor_ID_MEMBER'] = array();
	while ($row_members = $smcFunc['db_fetch_assoc']($members_result))
	{
		$context['top_posters'][] = array(
			'name' => $row_members['real_name'],
			'id' => $row_members['id_member'],
			'num_posts' => $row_members['posts'],
			'href' => $scripturl . '?action=profile;u=' . $row_members['id_member'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row_members['id_member'] . '">' . $row_members['real_name'] . '</a>'
		);

		if ($max_num_posts < $row_members['posts'])
			$max_num_posts = $row_members['posts'];
		if (!empty($modSettings['MemberColorStats']) && !empty($row_members['id_member']))
			$context['MemberColor_ID_MEMBER'][$row_members['id_member']] = $row_members['id_member'];
	}
	$smcFunc['db_free_result']($members_result);

	foreach ($context['top_posters'] as $i => $poster)
	{
		$context['top_posters'][$i]['post_percent'] = round(($poster['num_posts'] * 100) / $max_num_posts);
		$context['top_posters'][$i]['num_posts'] = comma_format($context['top_posters'][$i]['num_posts']);
	}
//smf.konusal.com En cok mesaj atan top 10 Bitti
//smf.konusal.com En cok yanitlananlar top 10.
	$topic_ids = array();
	$topic_reply_result = $smcFunc['db_query']('', '
		SELECT m.subject, t.num_replies, t.id_board, t.id_topic, b.name
		FROM {db_prefix}topics AS t
			INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
			INNER JOIN {db_prefix}boards AS b ON (b.id_board = t.id_board' . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? '
			AND b.id_board != {int:recycle_board}' : '') . ')
		WHERE {query_see_board}' . (!empty($topic_ids) ? '
			AND t.id_topic IN ({array_int:topic_list})' : ($modSettings['postmod_active'] ? '
			AND t.approved = {int:is_approved}' : '')) . '
		ORDER BY t.num_replies DESC
		LIMIT 10',
		array(
			'topic_list' => $topic_ids,
			'recycle_board' => $modSettings['recycle_board'],
			'is_approved' => 1,
		)
	);
	$context['top_topics_replies'] = array();
	$max_num_replies = 1;
	while ($row_topic_reply = $smcFunc['db_fetch_assoc']($topic_reply_result))
	{
		censorText($row_topic_reply['subject']);

		$context['top_topics_replies'][] = array(
			'id' => $row_topic_reply['id_topic'],
			'board' => array(
				'id' => $row_topic_reply['id_board'],
				'name' => $row_topic_reply['name'],
				'href' => $scripturl . '?board=' . $row_topic_reply['id_board'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row_topic_reply['id_board'] . '.0">' . $row_topic_reply['name'] . '</a>'
			),
			'subject' => $row_topic_reply['subject'],
			'num_replies' => $row_topic_reply['num_replies'],
			'href' => $scripturl . '?topic=' . $row_topic_reply['id_topic'] . '.0',
			'link' => '<a href="' . $scripturl . '?topic=' . $row_topic_reply['id_topic'] . '.0">' .$row_topic_reply['subject'] . '</a>'
		);

		if ($max_num_replies < $row_topic_reply['num_replies'])
			$max_num_replies = $row_topic_reply['num_replies'];
	}
	$smcFunc['db_free_result']($topic_reply_result);

	foreach ($context['top_topics_replies'] as $i => $topic)
	{
		$context['top_topics_replies'][$i]['post_percent'] = round(($topic['num_replies'] * 100) / $max_num_replies);
		$context['top_topics_replies'][$i]['num_replies'] = comma_format($context['top_topics_replies'][$i]['num_replies']);
	}
//smf.konusal.com En cok yanitlananlar top 10 Bitti
//smf.konusal.com En cok goruntulenenler top 10
	$topic_ids = array();
	$topic_view_result = $smcFunc['db_query']('', '
		SELECT m.subject, t.num_views, t.id_board, t.id_topic, b.name
		FROM {db_prefix}topics AS t
			INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
			INNER JOIN {db_prefix}boards AS b ON (b.id_board = t.id_board' . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? '
			AND b.id_board != {int:recycle_board}' : '') . ')
		WHERE {query_see_board}' . (!empty($topic_ids) ? '
			AND t.id_topic IN ({array_int:topic_list})' : ($modSettings['postmod_active'] ? '
			AND t.approved = {int:is_approved}' : '')) . '
		ORDER BY t.num_views DESC
		LIMIT 10',
		array(
			'topic_list' => $topic_ids,
			'recycle_board' => $modSettings['recycle_board'],
			'is_approved' => 1,
		)
	);
	$context['top_topics_views'] = array();
	$max_num_views = 1;
	while ($row_topic_views = $smcFunc['db_fetch_assoc']($topic_view_result))
	{
		censorText($row_topic_views['subject']);
		$context['top_topics_views'][] = array(
			'id' => $row_topic_views['id_topic'],
			'board' => array(
				'id' => $row_topic_views['id_board'],
				'name' => $row_topic_views['name'],
				'href' => $scripturl . '?board=' . $row_topic_views['id_board'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row_topic_views['id_board'] . '.0">' . $row_topic_views['name'] . '</a>'
			),
			'subject' => $row_topic_views['subject'],
			'num_views' => $row_topic_views['num_views'],
			'href' => $scripturl . '?topic=' . $row_topic_views['id_topic'] . '.0',
			'link' => '<a href="' . $scripturl . '?topic=' . $row_topic_views['id_topic'] . '.0">' . $row_topic_views['subject'] . '</a>'
		);

		if ($max_num_views < $row_topic_views['num_views'])
			$max_num_views = $row_topic_views['num_views'];
	}
	$smcFunc['db_free_result']($topic_view_result);

	foreach ($context['top_topics_views'] as $i => $topic)
	{
		$context['top_topics_views'][$i]['post_percent'] = round(($topic['num_views'] * 100) / $max_num_views);
		$context['top_topics_views'][$i]['num_views'] = comma_format($context['top_topics_views'][$i]['num_views']);
	}
// smf.konusal.com En cok goruntulenenler top 10 Bitti	
// smf.konusal.com rastgele konu	
 $rand = $smcFunc['db_query']('', '
	SELECT t.id_topic, m.subject, m.body
	FROM {db_prefix}topics AS t
	INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
	ORDER BY RAND()
	LIMIT {int:limit}',
	array(
		'limit' => 10,
	)
);

$context['top_topics_rand'] = array();	
while ($rrow = $smcFunc['db_fetch_assoc']($rand))
	{
		censorText($rrow['subject']);
		$context['top_topics_rand'][] = array(
			'id' => $rrow['id_topic'],
			'subject' => $rrow['subject'],
			'href' => $scripturl . '?topic=' . $rrow['id_topic'] . '.0',
			'link' => '<a href="' . $scripturl . '?topic=' . $rrow['id_topic'] . '.0">' . $rrow['subject'] . '</a>'
		);

		
	}
	$smcFunc['db_free_result']($rand);
$latestPostOptions = array(
			'number_posts' => !empty($settings['number_recent_posts']) ? $settings['number_recent_posts'] : 10,
		);
$context['latest_posts'] = cache_quick_get('boardindex-latest_posts:' . md5($user_info['query_wanna_see_board'] . $user_info['language']), 'Subs-Recent.php', 'cache_getLastPosts', array($latestPostOptions));
// smf.konusal.com rastgele konu Bitti	
//top 10 http://smf.konusal.com
	echo '<div class="cat_bar">
			   <h3 class="catbg">
				',$txt['efsane_stats'],'
			   </h3>
			</div><div class="atop10">';
			
	echo '<div class="topresgele"><label class="topresgelelink">',$txt['efsane_rasgele'],'</label><div class="topres">';			
		$say=0;foreach ($context['top_topics_rand'] as $ras)
		{$say++;echo '<p class="say'.$say.'">',$ras['link'], '</p>'; }		
	echo '</div></div>
		<div class="toporta"><section class="tab-area tabs-checked">
		<input checked="checked" name="tab" id="tab-A" type="radio">
		<input name="tab" id="tab-B" type="radio">
		<input name="tab" id="tab-C" type="radio">

		<label class="tab-link" for="tab-A">',$txt['efsane_ey'],'</label>
		<label class="tab-link orta" for="tab-B">',$txt['efsane_ec'],'</label>
		<label class="tab-link" for="tab-C">',$txt['efsane_eg'],'</label>

		<article class="tab">';
    if (!empty($context['latest_posts']))
	$say=0;foreach ($context['latest_posts'] as $post)
	{$say++;echo '<p class="say'.$say.'"><a href="',$post['href'],'">', $post['short_subject'], '</a><span>', $post['poster']['link'],'</span></p>';}	
    echo' </article><article class="tab">';
    $say=0;foreach ($context['top_topics_replies'] as $topic)
	{$say++;echo '<p class="say'.$say.'">', $topic['link'], '<span>', $topic['num_replies'], '</span></p>';}
    echo' </article><article class="tab">';
    $say=0;foreach ($context['top_topics_views'] as $topic)
	{$say++;echo '<p class="say'.$say.'">', $topic['link'], '<span>', $topic['num_views'], '</span></p>';}
    echo'</article></section></div>
	<div class="topson">	
	  <div class="tab-navigation">             
		<select id="select-box">
		<option value="1">',$txt['efsane_ek'],'</option>
		<option value="2">',$txt['efsane_ecy'],'</option>
		<option value="3">',$txt['efsane_euye'],'</option>
		</select>
	  </div>
	  <div id="tab-1" class="tab-content">';
	$say=0;foreach ($context['top_starters'] as $poster)
	{$say++;echo '<p class="say'.$say.'">', $poster['link'], '<span>', $poster['num_topics'], '</span></p>';}
	echo'</div><div id="tab-2" class="tab-content">';		
	$say=0;foreach ($context['top_posters'] as $poster)
	{$say++;echo '<p class="say'.$say.'">', $poster['link'], '<span>', $poster['num_posts'], '</span></p>'; }
	echo'</div><div id="tab-3" class="tab-content">';
	$say=0;foreach ($context['new_members'] as $poster)
	{$say++;echo '<p class="say'.$say.'">',$poster['link'], '</p>'; }
	echo'</div>
  </div>

		<script>
		$(\'.tab-content\').hide();
		$(\'#tab-1\').show();
		$(\'#select-box\').change(function () {
		   dropdown = $(\'#select-box\').val();
		  $(\'.tab-content\').hide();
		  $(\'#\' + "tab-" + dropdown).show();                                   
		});
		</script></div><br class="clear"/>';
//top 10 http://smf.konusal.com bitti
}
?>