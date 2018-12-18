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
	global $context, $settings, $options, $txt, $scripturl, $modSettings;
	if ($context['report_sent'])
	{
		echo '
			<div class="alert alert-danger">
				', $txt['report_sent'], '
			</div>';
	}
	echo '
			<a id="msg', $context['first_message'], '"></a>', $context['first_new_message'] ? '<a id="new"></a>' : '';
	if ($context['is_poll'])
	{
		echo '
			<div class="panel panel-default">
				<div class="panel-heading">
						<span class="text-left"><span class="generic_icons poll"></span> ', $context['poll']['question'], '</span>
				</div>
				<div class="panel-body">';
		if ($context['poll']['show_results'] || !$context['allow_vote'])
		{
			echo '<div class="poll-section">';
			foreach ($context['poll']['options'] as $option)
			{
				echo '
						<strong>', $option['option'], '</strong><span class="pull-right">', $option['votes'], ' </span>
						<div class="progress progress-bar-striped  active">';
				$color_class = array('info','success','warning','danger');
				$color = $color_class[array_rand($color_class)];
				if ($context['allow_poll_view'])
					echo ' <div class="progress-bar progress-bar-',$color,'" style="width:', $option['percent'], '%;"></div>';
				echo '
						</div>';
			}
			echo '
					</div>';
			if ($context['allow_poll_view'])
				echo '
						<p><strong>', $txt['poll_total_voters'], ':</strong> ', $context['poll']['total_votes'], '</p>';
		}
		else
		{
			echo '
						<form class="form-horizontal" action="', $scripturl, '?action=vote;topic=', $context['current_topic'], '.', $context['start'], ';poll=', $context['poll']['id'], '" method="post" accept-charset="', $context['character_set'], '">';
			if ($context['poll']['allowed_warning'])
				echo '
							<p class="alert alert-warning">', $context['poll']['allowed_warning'], '</p>';
			foreach ($context['poll']['options'] as $option)
				echo '
								<div class="checkbox">
								<label for="', $option['id'], '">
								', $option['vote_button'], '
								', $option['option'], '
								</label>
								</div>';
			echo '
							<div class="pull-right">
								<input type="submit" value="', $txt['poll_vote'], '" class=" btn btn-success" />
								<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
							</div>
						</form>';
		}
		if (!empty($context['poll']['expire_time']))
			echo '
						<p><strong>', ($context['poll']['is_expired'] ? $txt['poll_expired_on'] : $txt['poll_expires_on']), ':</strong> ', $context['poll']['expire_time'], '</p>';
		echo '
					</div>
				</div>
			<div class="pagesection">';
		$poll_buttons = array(
			'poll' => array('test' => 'allow_return_vote', 'text' => 'poll_return_vote', 'image' => 'poll_options.gif', 'lang' => true, 'url' => $scripturl . '?topic=' . $context['current_topic'] . '.' . $context['start']),
			'results' => array('test' => 'show_view_results_button', 'text' => 'poll_results', 'image' => 'poll_results.gif', 'lang' => true, 'url' => $scripturl . '?topic=' . $context['current_topic'] . '.' . $context['start'] . ';viewresults'),
			'stats' => array('test' => 'allow_change_vote', 'text' => 'poll_change_vote', 'image' => 'poll_change_vote.gif', 'lang' => true, 'url' => $scripturl . '?action=vote;topic=' . $context['current_topic'] . '.' . $context['start'] . ';poll=' . $context['poll']['id'] . ';' . $context['session_var'] . '=' . $context['session_id']),
			'lock' => array('test' => 'allow_lock_poll', 'text' => (!$context['poll']['is_locked'] ? 'poll_lock' : 'poll_unlock'), 'image' => 'poll_lock.gif', 'lang' => true, 'url' => $scripturl . '?action=lockvoting;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
			'quick_edit_button' => array('test' => 'allow_edit_poll', 'text' => 'poll_edit', 'image' => 'poll_edit.gif', 'lang' => true, 'url' => $scripturl . '?action=editpoll;topic=' . $context['current_topic'] . '.' . $context['start']),
			'remove_button' => array('test' => 'can_remove_poll', 'text' => 'poll_remove', 'image' => 'admin_remove_poll.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['poll_remove_warn'] . '\');"', 'url' => $scripturl . '?action=removepoll;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		);
		template_button_strip($poll_buttons);
		echo '
			</div>';
	}
	if (!empty($context['linked_calendar_events']))
	{
		echo '
			<div class="alert alert-warning">
					<h3>', $txt['calendar_linked_events'], '</h3>
						<ul class="list-group">';
		foreach ($context['linked_calendar_events'] as $event)
			echo '
							<li class="list-group-item">
								', ($event['can_edit'] ? '<a href="' . $event['modify_href'] . '"><span class="generic_icons regcenter"></span> </a> ' : ''), '<strong>', $event['title'], '</strong>: ', $event['start_date'], ($event['start_date'] != $event['end_date'] ? ' - ' . $event['end_date'] : ''), '
							</li>';
		echo '
						</ul>
			</div>';
	}
	// Build the normal button array.
	$normal_buttons = array(
		'sort_up' => array((allowedTo('post_new')) ? 1 : 'test' => 'snrj', 'text' => 'new_topic', 'image' => 'new_topic.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0'),
		'reply_button' => array('test' => 'can_reply', 'text' => 'reply', 'image' => 'reply.gif', 'lang' => true, 'url' => $scripturl . '?action=post;topic=' . $context['current_topic'] . '.' . $context['start'] . ';last_msg=' . $context['topic_last_message'], 'active' => true),
		'poll' => array('test' => 'can_add_poll', 'text' => 'add_poll', 'image' => 'add_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=editpoll;add;topic=' . $context['current_topic'] . '.' . $context['start']),
		'notify_button' => array('test' => 'can_mark_notify', 'text' => $context['is_marked_notify'] ? 'unnotify' : 'notify', 'image' => ($context['is_marked_notify'] ? 'un' : '') . 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_topic'] : $txt['notification_enable_topic']) . '\');"', 'url' => $scripturl . '?action=notify;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'unread_button' => array('test' => 'can_mark_unread', 'text' => 'mark_unread', 'image' => 'markunread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=topic;t=' . $context['mark_unread_time'] . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'send' => array('test' => 'can_send_topic', 'text' => 'send_topic', 'image' => 'sendtopic.gif', 'lang' => true, 'url' => $scripturl . '?action=emailuser;sa=sendtopic;topic=' . $context['current_topic'] . '.0'),
		'languages' => array('text' => 'print', 'image' => 'print.gif', 'lang' => true, 'custom' => 'rel="new_win nofollow"', 'url' => $scripturl . '?action=printpage;topic=' . $context['current_topic'] . '.0'),
	);

	// Allow adding new buttons easily.
	call_integration_hook('integrate_display_buttons', array(&$normal_buttons));
	$veli=$context['page_index'];
	$veli=str_replace('[<strong>','<li class="active" ><a href="#">',$veli);
	$veli=str_replace('</strong>]','</a ></li>',$veli);
	$veli=str_replace('<a class="navPages"','<li><a class="navPages"',$veli);
	$veli=str_replace('</a>','</a></li>',$veli);
	$veli=str_replace('<span','<li><span',$veli);
	$veli=str_replace('</span>','</li>',$veli);
	// Show the page index... "Pages: [1]".
		echo '
	<div class="pagelinks pull-left"><ul class="pagination pagination-sm">',$veli,'</ul></div>
		', template_button_strip($normal_buttons, 'right'), '';

	// Show the topic information - icon, subject, etc.
	echo '
			<div class="media">
				<div class="cat_bar">
					<h1 class="catbg">
						', $context['subject'], ' <span class="small pull-right">(', $txt['read'], ' ', $context['num_views'], ' ', $txt['times'], ')</span>
					</h1>
				</div>';

	if (!empty($settings['display_who_viewing']))
	{
		echo '
				<p id="whoisviewing" class="small">';

		// Show just numbers...?
		if ($settings['display_who_viewing'] == 1)
				echo count($context['view_members']), ' ', count($context['view_members']) == 1 ? $txt['who_member'] : $txt['members'];
		// Or show the actual people viewing the topic?
		else
			echo empty($context['view_members_list']) ? '0 ' . $txt['members'] : implode(', ', $context['view_members_list']) . ((empty($context['view_num_hidden']) || $context['can_moderate_forum']) ? '' : ' (+ ' . $context['view_num_hidden'] . ' ' . $txt['hidden'] . ')');

		// Now show how many guests are here too.
		echo $txt['who_and'], $context['view_num_guests'], ' ', $context['view_num_guests'] == 1 ? $txt['guest'] : $txt['guests'], $txt['who_viewing_topic'], '
				</p>';
	}

	echo '
				<form action="', $scripturl, '?action=quickmod2;topic=', $context['current_topic'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '" name="quickModForm" id="quickModForm" style="margin: 0;" onsubmit="return oQuickModify.bInEditMode ? oQuickModify.modifySave(\'' . $context['session_id'] . '\', \'' . $context['session_var'] . '\') : false">';

	$ignoredMsgs = array();
	$removableMessageIDs = array();
	$alternate = false;

	// Get all the messages...
	while ($message = $context['get_message']())
	{
		$ignoring = false;
		$alternate = !$alternate;
		if ($message['can_remove'])
			$removableMessageIDs[] = $message['id'];

		// Are we ignoring this message?
		if (!empty($message['is_ignored']))
		{
			$ignoring = true;
			$ignoredMsgs[] = $message['id'];
		}

		// Show the message anchor and a "new" anchor if this message is new.
		if ($message['id'] != $context['first_message'])
			echo '
				<a id="msg', $message['id'], '"></a>', $message['first_new'] ? '<a id="new"></a>' : '';


		// Show information about the poster of this message.
		echo '
						<div class="media-left media-top"><ul class="reset">';
						
						// Show avatars, images, etc.?
			if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']) && !empty($message['member']['avatar']['image']))
				echo '
								<li class="avatar1', $message['member']['online']['is_online'], '">
									<a href="', $scripturl, '?action=profile;u=', $message['member']['id'], '">
										', $message['member']['avatar']['image'], '
									</a>
								</li>';
			else
				echo '
								<li class="avatar1', $message['member']['online']['is_online'], '">
									<a href="', $scripturl, '?action=profile;u=', $message['member']['id'], '">
										<img src="' . $settings['images_url'] . '/theme/default_avatar.png" alt="', $message['member']['id'], '" />
									</a>
								</li>';
			echo'</ul><div class="centertext"><h4>', $message['member']['link'], '</h4>
			
					<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal', $message['member']['id'], '">',$txt['more'],'</button></div>
					<div class="modal fade" id="myModal', $message['member']['id'], '" role="dialog">
				<div class="modal-dialog modal-sm">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">';
						echo'	<ul class="small list-group" id="msg_', $message['id'], '_extra_info">
						<li class="list-group-item">',$txt['pkullan'],'<span class="pull-right">';
		echo '', $message['member']['link'], '
							</span></li>';	
		if (!empty($message['member']['title']))
			echo '
								<li class="title list-group-item">',$txt['pkullanb'],'<span class="pull-right">', $message['member']['title'], '</span></li>';
		if (!empty($message['member']['group']))
			echo '
								<li class="membergroup list-group-item">',$txt['pkullany'],'<span class="pull-right">', $message['member']['group'], '</span></li>';
		if (!$message['member']['is_guest'])
		{
			if ((empty($settings['hide_post_group']) || $message['member']['group'] == '') && $message['member']['post_group'] != '')
				echo '
								<li class="postgroup list-group-item">',$txt['pkullang'],'<span class="pull-right">', $message['member']['post_group'], '</span></li>';
			echo '
								<li class="stars list-group-item">',$txt['pkullanr'],'<span class="pull-right">', $message['member']['group_stars'], '</span></li>';
			if (!isset($context['disabled_fields']['posts']))
				echo '
								<li class="postcount list-group-item"><span class="label label-default">', $txt['member_postcount'], ':</span> <span class="pull-right">', $message['member']['posts'], '</span></li>';
			if ($modSettings['karmaMode'] == '1')
				echo '
								<li class="karma list-group-item"><span class="label label-warning">', $modSettings['karmaLabel'], '</span> <span class="pull-right">', $message['member']['karma']['good'] - $message['member']['karma']['bad'], '</span></li>';
			elseif ($modSettings['karmaMode'] == '2')
				echo '
								<li class="karma list-group-item"><span class="label label-warning">', $modSettings['karmaLabel'], '</span><span class="pull-right"> +', $message['member']['karma']['good'], '/-', $message['member']['karma']['bad'], '</span></li>';
			if ($message['member']['karma']['allow'])
				echo '
								<li class="karma_allow list-group-item">
									<a href="', $scripturl, '?action=modifykarma;sa=applaud;uid=', $message['member']['id'], ';topic=', $context['current_topic'], '.' . $context['start'], ';m=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $modSettings['karmaApplaudLabel'], '</a>
									<a href="', $scripturl, '?action=modifykarma;sa=smite;uid=', $message['member']['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';m=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $modSettings['karmaSmiteLabel'], '</a>
								</li>';
			if (!empty($settings['show_gender']) && $message['member']['gender']['image'] != '' && !isset($context['disabled_fields']['gender']))
				echo '
								<li class="gender list-group-item"><span class="label label-info">', $txt['gender'], ': </span><span class="pull-right">', $message['member']['gender']['image'], '</span></li>';
			if (!empty($settings['show_blurb']) && $message['member']['blurb'] != '')
				echo '
								<li class="blurb list-group-item">',$txt['pkullank'],'<span class="pull-right">', $message['member']['blurb'], '</span></li>';
			if (!empty($message['member']['custom_fields']))
			{
				$shown = false;
				foreach ($message['member']['custom_fields'] as $custom)
				{
					if ($custom['placement'] != 1 || empty($custom['value']))
						continue;
					if (empty($shown))
					{
						$shown = true;
						echo '
								<li class="im_icons list-group-item">
									<ul class="pagination" >';
					}
					echo '
										<li>', $custom['value'], '</li>';
				}
				if ($shown)
					echo '
									</ul>
								</li>';
			}
			if ($message['member']['has_messenger'] && $message['member']['can_view_profile'])
				echo '
								<li class="im_icons list-group-item">
									<ul class="pagination" >
										', !empty($message['member']['icq']['link']) ? '<li>' . $message['member']['icq']['link'] . '</li>' : '', '
										', !empty($message['member']['msn']['link']) ? '<li>' . $message['member']['msn']['link'] . '</li>' : '', '
										', !empty($message['member']['aim']['link']) ? '<li>' . $message['member']['aim']['link'] . '</li>' : '', '
										', !empty($message['member']['yim']['link']) ? '<li>' . $message['member']['yim']['link'] . '</li>' : '', '
									</ul>
								</li>';
			if ($settings['show_profile_buttons'])
			{
				echo '
								<li class="profile list-group-item">
									<ul class="pagination">';
				if ($message['member']['can_view_profile'])
					echo '
										<li><a href="', $message['member']['href'], '">', ($settings['use_image_buttons'] ? '<span class="generic_icons members"></span>' : $txt['view_profile']), '</a></li>';
				if ($message['member']['website']['url'] != '' && !isset($context['disabled_fields']['website']))
					echo '
										<li><a href="', $message['member']['website']['url'], '" title="' . $message['member']['website']['title'] . '" target="_blank" class="new_win">', ($settings['use_image_buttons'] ? '<span class="generic_icons www"></span>' : $txt['www']), '</a></li>';
				if (in_array($message['member']['show_email'], array('yes', 'yes_permission_override', 'no_through_forum')))
					echo '
										<li><a href="', $scripturl, '?action=emailuser;sa=email;msg=', $message['id'], '" rel="nofollow">', ($settings['use_image_buttons'] ? '<span class="generic_icons mail"></span>' : $txt['email']), '</a></li>';
				if ($context['can_send_pm'])
					echo '
										<li><a href="', $scripturl, '?action=pm;sa=send;u=', $message['member']['id'], '" title="', $message['member']['online']['is_online'] ? $txt['pm_online'] : $txt['pm_offline'], '">', $settings['use_image_buttons'] ? '<span class="generic_icons mail_new"></span>' : ($message['member']['online']['is_online'] ? $txt['pm_online'] : $txt['pm_offline']), '</a></li>';
				echo '
									</ul>
								</li>';
			}
			if (!empty($message['member']['custom_fields']))
			{
				foreach ($message['member']['custom_fields'] as $custom)
					if (empty($custom['placement']) || empty($custom['value']))
						echo '
								<li class="custom list-group-item">', $custom['title'], ': ', $custom['value'], '</li>';
			}
			if ($message['member']['can_see_warning'])
				echo '
								<li class="warning list-group-item">', $context['can_issue_warning'] ? '<a href="' . $scripturl . '?action=profile;area=issuewarning;u=' . $message['member']['id'] . '">' : '', '<span class="generic_icons warning_', $message['member']['warning_status'], '"></span>', $context['can_issue_warning'] ? '</a>' : '', '<span class="warn_', $message['member']['warning_status'], '">', $txt['warn_' . $message['member']['warning_status']], '</span></li>';
		}
		elseif (!empty($message['member']['email']) && in_array($message['member']['show_email'], array('yes', 'yes_permission_override', 'no_through_forum')))
			echo '
								<li class="email list-group-item"><a href="', $scripturl, '?action=emailuser;sa=email;msg=', $message['id'], '" rel="nofollow">', ($settings['use_image_buttons'] ? '<span class="generic_icons email"></span>' : $txt['email']), '</a></li>';
		echo '
							</ul>
						</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				</div>
			  </div>
			</div>
						<div class="media-body">
									<div class="alert alert-warning">
									<div id="msg_', $message['id'], '_quick_mod"></div>
									<span class="small">
									<a href="', $message['href'], '" rel="nofollow">', !empty($message['counter']) ? ' #' . $message['counter'] : '', '  </a>
									<img src="', $message['icon_url'] . '" alt="*"/></span>
									<span class="small">', $message['time'], '</span>
									';

		// If this is the first post, (#0) just say when it was posted - otherwise give the reply #.
		if ($message['can_approve'] || $context['can_reply'] || $message['can_modify'] || $message['can_remove'] || $context['can_split'] || $context['can_restore_msg'])
			echo '
								<div class="btn-group  pull-right">';

		// Maybe we can approve it, maybe we should?
		if ($message['can_approve'])
			echo '
									<a class="btn btn-default btn-xs" href="', $scripturl, '?action=moderate;area=postmod;sa=approve;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '"><span class="generic_icons approve"></span> ', $txt['approve'], '</a>';

		// Can they reply? Have they turned on quick reply?
		if ($context['can_quote'] && !empty($options['display_quick_reply']))
			echo '
									<a class="btn btn-default btn-xs" href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';last_msg=', $context['topic_last_message'], '" onclick="return oQuickReply.quote(', $message['id'], ');"><span class="generic_icons quote"></span> ', $txt['quote'], '</a>';

		// So... quick reply is off, but they *can* reply?
		elseif ($context['can_quote'])
			echo '
									<a class="btn btn-default btn-xs" href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';last_msg=', $context['topic_last_message'], '"><span class="generic_icons quote"></span> ', $txt['quote'], '</a>';

		// Can the user modify the contents of this post?
		if ($message['can_modify'])
			echo '
									<a class="btn btn-default btn-xs" href="', $scripturl, '?action=post;msg=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], '"><span class="generic_icons modify_button"></span> ', $txt['modify'], '</a>';

		// How about... even... remove it entirely?!
		if ($message['can_remove'])
			echo '
									<a class="btn btn-default btn-xs" href="', $scripturl, '?action=deletemsg;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['remove_message'], '?\');"><span class="generic_icons remove_button"></span> ', $txt['remove'], '</a>';

		// What about splitting it off the rest of the topic?
		if ($context['can_split'] && !empty($context['real_num_replies']))
			echo '
									<a class="btn btn-default btn-xs" href="', $scripturl, '?action=splittopics;topic=', $context['current_topic'], '.0;at=', $message['id'], '"><span class="generic_icons split_button"></span> ', $txt['split'], '</a>';

		// Can we restore topics?
		if ($context['can_restore_msg'])
			echo '
									<a class="btn btn-default btn-xs" href="', $scripturl, '?action=restoretopic;msgs=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '"><span class="generic_icons restore_button"></span> ', $txt['restore_message'], '</a>';

		// Show a checkbox for quick moderation?
		if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $message['can_remove'])
			echo '
									<li class="inline_mod_check" style="display: none;" id="in_topic_mod_check_', $message['id'], '"></li>';

		if ($message['can_approve'] || $context['can_reply'] || $message['can_modify'] || $message['can_remove'] || $context['can_split'] || $context['can_restore_msg'])
			echo '
								</div>';

		echo '
							</div>';

		// Ignoring this user? Hide the post.
		if ($ignoring)
			echo '
							<div id="msg_', $message['id'], '_ignored_prompt">
								', $txt['ignoring_user'], '
								<a href="#" id="msg_', $message['id'], '_ignored_link" style="display: none;">', $txt['show_ignore_user_post'], '</a>
							</div>';

		// Show the post itself, finally!
		echo '
							<div class="well well-lg">';

		if (!$message['approved'] && $message['member']['id'] != 0 && $message['member']['id'] == $context['user']['id'])
			echo '
								<div class="approve_post">
									', $txt['post_awaiting_approval'], '
								</div>';
		echo '
								<div class="inner" id="msg_', $message['id'], '"', '>', $message['body'], '</div>
							</div>';



		// Assuming there are attachments...
		if (!empty($message['attachment']))
		{
			echo '
							<div id="msg_', $message['id'], '_footer" class="attachments small alert alert-info">
								<div style="overflow: ', $context['browser']['is_firefox'] ? 'visible' : 'auto', ';">';

			$last_approved_state = 1;
			foreach ($message['attachment'] as $attachment)
			{
				// Show a special box for unapproved attachments...
				if ($attachment['is_approved'] != $last_approved_state)
				{
					$last_approved_state = 0;
					echo '
									<fieldset>
										<legend>', $txt['attach_awaiting_approve'];

					if ($context['can_approve'])
						echo '&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=all;mid=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve_all'], '</a>]';

					echo '</legend>';
				}

				if ($attachment['is_image'])
				{
					if ($attachment['thumbnail']['has_thumb'])
						echo '
										<a href="', $attachment['href'], ';image" id="link_', $attachment['id'], '" onclick="', $attachment['thumbnail']['javascript'], '"><img src="', $attachment['thumbnail']['href'], '" alt="" id="thumb_', $attachment['id'], '" /></a><br />';
					else
						echo '
										<img src="' . $attachment['href'] . ';image" alt="" width="' . $attachment['width'] . '" height="' . $attachment['height'] . '"/><br />';
				}
				echo '
										<a href="' . $attachment['href'] . '"><img src="' . $settings['images_url'] . '/icons/clip.gif" align="middle" alt="*" />&nbsp;' . $attachment['name'] . '</a> ';

				if (!$attachment['is_approved'] && $context['can_approve'])
					echo '
										[<a href="', $scripturl, '?action=attachapprove;sa=approve;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve'], '</a>]&nbsp;|&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=reject;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['delete'], '</a>] ';
				echo '
										(', $attachment['size'], ($attachment['is_image'] ? ', ' . $attachment['real_width'] . 'x' . $attachment['real_height'] . ' - ' . $txt['attach_viewed'] : ' - ' . $txt['attach_downloaded']) . ' ' . $attachment['downloads'] . ' ' . $txt['attach_times'] . '.)<br />';
			}

			// If we had unapproved attachments clean up.
			if ($last_approved_state == 0)
				echo '
									</fieldset>';

			echo '
								</div>
							</div>';
		}

		echo '
						
						<div class="alert alert-warning col-sm-12">
							<div class="small col-sm-6">';

		// Show "� Last Edit: Time by Person �" if this post was edited.
		if ($settings['show_modify'] && !empty($message['modified']['name']))
			echo '
								<span>', $txt['last_edit'], ': ', $message['modified']['time'], ' ', $txt['by'], ' ', $message['modified']['name'], '</span>';

		echo '
							</div>
							<div class="small col-sm-6 text-right">';
		// Maybe they want to report this post to the moderator(s)?
		if ($context['can_report_moderator'])
			echo '
								<a href="', $scripturl, '?action=reporttm;topic=', $context['current_topic'], '.', $message['counter'], ';msg=', $message['id'], '" title="', $txt['report_to_mod'], '"><span class="generic_icons warning_moderate"></span></a>';
		if ($context['can_issue_warning'] && !$message['is_message_author'] && !$message['member']['is_guest'])
			echo '
								<a href="', $scripturl, '?action=profile;area=issuewarning;u=', $message['member']['id'], ';msg=', $message['id'], '"><span class="generic_icons warning_moderate"></span></a>';
		
		if ($context['can_moderate_forum'] && !empty($message['member']['ip']))
			echo '
								<a href="', $scripturl, '?action=', !empty($message['member']['is_guest']) ? 'trackip' : 'profile;area=tracking;sa=ip;u=' . $message['member']['id'], ';searchip=', $message['member']['ip'], '" title="', $message['member']['ip'], '"><span class="generic_icons server"></span></a> <a href="', $scripturl, '?action=helpadmin;help=see_admin_ip" onclick="return reqWin(this.href);" class="help"><span class="generic_icons help"></span></a>';
		elseif ($message['can_see_ip'])
			echo '
								<a href="', $scripturl, '?action=helpadmin;help=see_member_ip" onclick="return reqWin(this.href);" class="help" title="', $message['member']['ip'], '"><span class="generic_icons server"></span></a>';
		elseif (!$context['user']['is_guest'])
			echo '
								<a href="', $scripturl, '?action=helpadmin;help=see_member_ip" onclick="return reqWin(this.href);" class="help" title="', $txt['logged'], '"><span class="generic_icons server"></span></a>';
		else
			echo '<span class="generic_icons server"></span>', $txt['logged'];

		echo '
							</div>';

		// Are there any custom profile fields for above the signature?
		if (!empty($message['member']['custom_fields']))
		{
			$shown = false;
			foreach ($message['member']['custom_fields'] as $custom)
			{
				if ($custom['placement'] != 2 || empty($custom['value']))
					continue;
				if (empty($shown))
				{
					$shown = true;
					echo '
							<div class="custom_fields_above_signature">
								<ul class="reset nolist">';
				}
				echo '
									<li>', $custom['value'], '</li>';
			}
			if ($shown)
				echo '
								</ul>
							</div>';
		}

		// Show the member's signature?
		if (!empty($message['member']['signature']) && empty($options['show_no_signatures']) && $context['signature_enabled'])
			echo '
							<div class="signature" id="msg_', $message['id'], '_signature">', $message['member']['signature'], '</div>';

		echo '
						</div></div>
				<hr/>';
	}

	echo '
				</form>
			</div>';

		echo '
		', template_button_strip($normal_buttons, 'right'), '
		<div class="pagelinks pull-left"><ul class="pagination pagination-sm"> ', $veli,'</ul></div><hr class="clear" />';


	$mod_buttons = array(
		'move' => array('test' => 'can_move', 'text' => 'move_topic', 'image' => 'admin_move.gif', 'lang' => true, 'url' => $scripturl . '?action=movetopic;topic=' . $context['current_topic'] . '.0'),
		'delete' => array('test' => 'can_delete', 'text' => 'remove_topic', 'image' => 'admin_rem.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['are_sure_remove_topic'] . '\');"', 'url' => $scripturl . '?action=removetopic2;topic=' . $context['current_topic'] . '.0;' . $context['session_var'] . '=' . $context['session_id']),
		'lock' => array('test' => 'can_lock', 'text' => empty($context['is_locked']) ? 'set_lock' : 'set_unlock', 'image' => 'admin_lock.gif', 'lang' => true, 'url' => $scripturl . '?action=lock;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'sticky' => array('test' => 'can_sticky', 'text' => empty($context['is_sticky']) ? 'set_sticky' : 'set_nonsticky', 'image' => 'admin_sticky.gif', 'lang' => true, 'url' => $scripturl . '?action=sticky;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'merge' => array('test' => 'can_merge', 'text' => 'merge', 'image' => 'merge.gif', 'lang' => true, 'url' => $scripturl . '?action=mergetopics;board=' . $context['current_board'] . '.0;from=' . $context['current_topic']),
		'calendar' => array('test' => 'calendar_post', 'text' => 'calendar_link', 'image' => 'linktocal.gif', 'lang' => true, 'url' => $scripturl . '?action=post;calendar;msg=' . $context['topic_first_message'] . ';topic=' . $context['current_topic'] . '.0'),
	);

	// Restore topic. eh?  No monkey business.
	if ($context['can_restore_topic'])
		$mod_buttons[] = array('text' => 'restore_topic', 'image' => '', 'lang' => true, 'url' => $scripturl . '?action=restoretopic;topics=' . $context['current_topic'] . ';' . $context['session_var'] . '=' . $context['session_id']);

	// Allow adding new mod buttons easily.
	call_integration_hook('integrate_mod_buttons', array(&$mod_buttons));

	echo '
			<div id="moderationbuttons">', template_button_strip($mod_buttons, 'bottom', array('id' => 'moderationbuttons_strip')), '</div>';
	// Show the jumpto box, or actually...let Javascript do it.
	
	// Show the lower breadcrumbs.
	theme_linktree();


	if ($context['can_reply'] && !empty($options['display_quick_reply']))
	{
		echo '
			<a id="quickreply"></a>
			<div class="tborder" id="quickreplybox">
				<div class="cat_bar">
					<h3 class="catbg">
						<span class="ie6_header floatleft"><a href="javascript:oQuickReply.swap();">
							<img src="', $settings['images_url'], '/', $options['display_quick_reply'] == 2 ? 'collapse' : 'expand', '.gif" alt="+" id="quickReplyExpand" class="icon" />
						</a>
						<a href="javascript:oQuickReply.swap();">', $txt['quick_reply'], '</a>
						</span>
					</h3>
				</div>
				<div id="quickReplyOptions"', $options['display_quick_reply'] == 2 ? '' : ' style="display: none"', '>
					<span class="upperframe"><span></span></span>
					<div class="roundframe">
						<p class="smalltext lefttext">', $txt['quick_reply_desc'], '</p>
						', $context['is_locked'] ? '<p class="alert smalltext">' . $txt['quick_reply_warning'] . '</p>' : '',
						$context['oldTopicError'] ? '<p class="alert smalltext">' . sprintf($txt['error_old_topic'], $modSettings['oldTopicDays']) . '</p>' : '', '
						', $context['can_reply_approved'] ? '' : '<em>' . $txt['wait_for_approval'] . '</em>', '
						', !$context['can_reply_approved'] && $context['require_verification'] ? '<br />' : '', '
						<form action="', $scripturl, '?board=', $context['current_board'], ';action=post2" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify" onsubmit="submitonce(this);" style="margin: 0;">
							<input type="hidden" name="topic" value="', $context['current_topic'], '" />
							<input type="hidden" name="subject" value="', $context['response_prefix'], $context['subject'], '" />
							<input type="hidden" name="icon" value="xx" />
							<input type="hidden" name="from_qr" value="1" />
							<input type="hidden" name="notify" value="', $context['is_marked_notify'] || !empty($options['auto_notify']) ? '1' : '0', '" />
							<input type="hidden" name="not_approved" value="', !$context['can_reply_approved'], '" />
							<input type="hidden" name="goback" value="', empty($options['return_to_post']) ? '0' : '1', '" />
							<input type="hidden" name="last_msg" value="', $context['topic_last_message'], '" />
							<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
							<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />';

			// Guests just need more.
			if ($context['user']['is_guest'])
				echo '
							<strong>', $txt['name'], ':</strong> <input type="text" name="guestname" value="', $context['name'], '" size="25" class="input_text" tabindex="', $context['tabindex']++, '" />
							<strong>', $txt['email'], ':</strong> <input type="text" name="email" value="', $context['email'], '" size="25" class="input_text" tabindex="', $context['tabindex']++, '" /><br />';

			// Is visual verification enabled?
			if ($context['require_verification'])
				echo '
							<strong>', $txt['verification'], ':</strong>', template_control_verification($context['visual_verification_id'], 'quick_reply'), '<br />';

			echo '
							<div class="quickReplyContent">
								<textarea cols="600" rows="7" name="message" tabindex="', $context['tabindex']++, '"></textarea>
							</div>
							<div class="righttext padding">
								<input type="submit" name="post" value="', $txt['post'], '" onclick="return submitThisOnce(this);" accesskey="s" tabindex="', $context['tabindex']++, '" class="button_submit" />
								<input type="submit" name="preview" value="', $txt['preview'], '" onclick="return submitThisOnce(this);" accesskey="p" tabindex="', $context['tabindex']++, '" class="button_submit" />';

			if ($context['show_spellchecking'])
				echo '
								<input type="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'postmodify\', \'message\');" tabindex="', $context['tabindex']++, '" class="button_submit" />';

			echo '
							</div>
						</form>
					</div>
					<span class="lowerframe"><span></span></span>
				</div>
			</div>';
	}
	else
		echo '
		<br class="clear" />';
	echo '
			<div class="plainbox" id="display_jump_to">&nbsp;</div>';
		if ($context['show_spellchecking'])
		echo '
			<form action="', $scripturl, '?action=spellcheck" method="post" accept-charset="', $context['character_set'], '" name="spell_form" id="spell_form" target="spellWindow"><input type="hidden" name="spellstring" value="" /></form>
				<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/spellcheck.js"></script>';

	echo '
				<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/topic.js"></script>
				<script type="text/javascript"><!-- // --><![CDATA[';

	if (!empty($options['display_quick_reply']))
		echo '
					var oQuickReply = new QuickReply({
						bDefaultCollapsed: ', !empty($options['display_quick_reply']) && $options['display_quick_reply'] == 2 ? 'false' : 'true', ',
						iTopicId: ', $context['current_topic'], ',
						iStart: ', $context['start'], ',
						sScriptUrl: smf_scripturl,
						sImagesUrl: "', $settings['images_url'], '",
						sContainerId: "quickReplyOptions",
						sImageId: "quickReplyExpand",
						sImageCollapsed: "collapse.gif",
						sImageExpanded: "expand.gif",
						sJumpAnchor: "quickreply"
					});';

	if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $context['can_remove_post'])
		echo '
					var oInTopicModeration = new InTopicModeration({
						sSelf: \'oInTopicModeration\',
						sCheckboxContainerMask: \'in_topic_mod_check_\',
						aMessageIds: [\'', implode('\', \'', $removableMessageIDs), '\'],
						sSessionId: \'', $context['session_id'], '\',
						sSessionVar: \'', $context['session_var'], '\',
						sButtonStrip: \'moderationbuttons\',
						sButtonStripDisplay: \'moderationbuttons_strip\',
						bUseImageButton: false,
						bCanRemove: ', $context['can_remove_post'] ? 'true' : 'false', ',
						sRemoveButtonLabel: \'', $txt['quickmod_delete_selected'], '\',
						sRemoveButtonImage: \'delete_selected.gif\',
						sRemoveButtonConfirm: \'', $txt['quickmod_confirm'], '\',
						bCanRestore: ', $context['can_restore_msg'] ? 'true' : 'false', ',
						sRestoreButtonLabel: \'', $txt['quick_mod_restore'], '\',
						sRestoreButtonImage: \'restore_selected.gif\',
						sRestoreButtonConfirm: \'', $txt['quickmod_confirm'], '\',
						sFormId: \'quickModForm\'
					});';

	echo '
					if (\'XMLHttpRequest\' in window)
					{
						var oQuickModify = new QuickModify({
							sScriptUrl: smf_scripturl,
							bShowModify: ', $settings['show_modify'] ? 'true' : 'false', ',
							iTopicId: ', $context['current_topic'], ',
							sTemplateBodyEdit: ', JavaScriptEscape('
								<div id="quick_edit_body_container" style="width: 90%">
									<div id="error_box" style="padding: 4px;" class="error"></div>
									<textarea class="editor" name="message" rows="12" style="' . ($context['browser']['is_ie8'] ? 'width: 635px; max-width: 100%; min-width: 100%' : 'width: 100%') . '; margin-bottom: 10px;" tabindex="' . $context['tabindex']++ . '">%body%</textarea><br />
									<input type="hidden" name="' . $context['session_var'] . '" value="' . $context['session_id'] . '" />
									<input type="hidden" name="topic" value="' . $context['current_topic'] . '" />
									<input type="hidden" name="msg" value="%msg_id%" />
									<div class="righttext">
										<input type="submit" name="post" value="' . $txt['save'] . '" tabindex="' . $context['tabindex']++ . '" onclick="return oQuickModify.modifySave(\'' . $context['session_id'] . '\', \'' . $context['session_var'] . '\');" accesskey="s" class="button_submit" />&nbsp;&nbsp;' . ($context['show_spellchecking'] ? '<input type="button" value="' . $txt['spell_check'] . '" tabindex="' . $context['tabindex']++ . '" onclick="spellCheck(\'quickModForm\', \'message\');" class="button_submit" />&nbsp;&nbsp;' : '') . '<input type="submit" name="cancel" value="' . $txt['modify_cancel'] . '" tabindex="' . $context['tabindex']++ . '" onclick="return oQuickModify.modifyCancel();" class="button_submit" />
									</div>
								</div>'), ',
							sTemplateSubjectEdit: ', JavaScriptEscape('<input type="text" style="width: 90%;" name="subject" value="%subject%" size="80" maxlength="80" tabindex="' . $context['tabindex']++ . '" class="input_text" />'), ',
							sTemplateBodyNormal: ', JavaScriptEscape('%body%'), ',
							sTemplateSubjectNormal: ', JavaScriptEscape('<a href="' . $scripturl . '?topic=' . $context['current_topic'] . '.msg%msg_id%#msg%msg_id%" rel="nofollow">%subject%</a>'), ',
							sTemplateTopSubject: ', JavaScriptEscape($txt['topic'] . ': %subject% &nbsp;(' . $txt['read'] . ' ' . $context['num_views'] . ' ' . $txt['times'] . ')'), ',
							sErrorBorderStyle: ', JavaScriptEscape('1px solid red'), '
						});

						aJumpTo[aJumpTo.length] = new JumpTo({
							sContainerId: "display_jump_to",
							sJumpToTemplate: "<label class=\"smalltext\" for=\"%select_id%\">', $context['jump_to']['label'], ':<" + "/label> %dropdown_list%",
							iCurBoardId: ', $context['current_board'], ',
							iCurBoardChildLevel: ', $context['jump_to']['child_level'], ',
							sCurBoardName: "', $context['jump_to']['board_name'], '",
							sBoardChildLevelIndicator: "==",
							sBoardPrefix: "=> ",
							sCatSeparator: "-----------------------------",
							sCatPrefix: "",
							sGoButtonLabel: "', $txt['go'], '"
						});

						aIconLists[aIconLists.length] = new IconList({
							sBackReference: "aIconLists[" + aIconLists.length + "]",
							sIconIdPrefix: "msg_icon_",
							sScriptUrl: smf_scripturl,
							bShowModify: ', $settings['show_modify'] ? 'true' : 'false', ',
							iBoardId: ', $context['current_board'], ',
							iTopicId: ', $context['current_topic'], ',
							sSessionId: "', $context['session_id'], '",
							sSessionVar: "', $context['session_var'], '",
							sLabelIconList: "', $txt['message_icon'], '",
							sBoxBackground: "transparent",
							sBoxBackgroundHover: "#ffffff",
							iBoxBorderWidthHover: 1,
							sBoxBorderColorHover: "#adadad" ,
							sContainerBackground: "#ffffff",
							sContainerBorder: "1px solid #adadad",
							sItemBorder: "1px solid #ffffff",
							sItemBorderHover: "1px dotted gray",
							sItemBackground: "transparent",
							sItemBackgroundHover: "#e0e0f0"
						});
					}';

	if (!empty($ignoredMsgs))
	{
		echo '
					var aIgnoreToggles = new Array();';

		foreach ($ignoredMsgs as $msgid)
		{
			echo '
					aIgnoreToggles[', $msgid, '] = new smc_Toggle({
						bToggleEnabled: true,
						bCurrentlyCollapsed: true,
						aSwappableContainers: [
							\'msg_', $msgid, '_extra_info\',
							\'msg_', $msgid, '\',
							\'msg_', $msgid, '_footer\',
							\'msg_', $msgid, '_quick_mod\',
							\'modify_button_', $msgid, '\',
							\'msg_', $msgid, '_signature\'

						],
						aSwapLinks: [
							{
								sId: \'msg_', $msgid, '_ignored_link\',
								msgExpanded: \'\',
								msgCollapsed: ', JavaScriptEscape($txt['show_ignore_user_post']), '
							}
						]
					});';
		}
	}

	echo '
				// ]]></script>';
}

?>