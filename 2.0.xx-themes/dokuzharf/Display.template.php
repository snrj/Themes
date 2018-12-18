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
			<a id="top"></a>
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
			<div class="pagesection pull-left">';
		$poll_buttons = array(
			'vote' => array('test' => 'allow_return_vote', 'text' => 'poll_return_vote', 'image' => 'poll_options.gif', 'lang' => true, 'url' => $scripturl . '?topic=' . $context['current_topic'] . '.' . $context['start']),
			'results' => array('test' => 'show_view_results_button', 'text' => 'poll_results', 'image' => 'poll_results.gif', 'lang' => true, 'url' => $scripturl . '?topic=' . $context['current_topic'] . '.' . $context['start'] . ';viewresults'),
			'change_vote' => array('test' => 'allow_change_vote', 'text' => 'poll_change_vote', 'image' => 'poll_change_vote.gif', 'lang' => true, 'url' => $scripturl . '?action=vote;topic=' . $context['current_topic'] . '.' . $context['start'] . ';poll=' . $context['poll']['id'] . ';' . $context['session_var'] . '=' . $context['session_id']),
			'lock' => array('test' => 'allow_lock_poll', 'text' => (!$context['poll']['is_locked'] ? 'poll_lock' : 'poll_unlock'), 'image' => 'poll_lock.gif', 'lang' => true, 'url' => $scripturl . '?action=lockvoting;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
			'edit' => array('test' => 'allow_edit_poll', 'text' => 'poll_edit', 'image' => 'poll_edit.gif', 'lang' => true, 'url' => $scripturl . '?action=editpoll;topic=' . $context['current_topic'] . '.' . $context['start']),
			'remove_poll' => array('test' => 'can_remove_poll', 'text' => 'poll_remove', 'image' => 'admin_remove_poll.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['poll_remove_warn'] . '\');"', 'url' => $scripturl . '?action=removepoll;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		);
		template_button_strip($poll_buttons);
		echo '
			</div><br class="clear"/>';
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
	$normal_buttons = array(
		'reply' => array('test' => 'can_reply', 'text' => 'reply', 'image' => 'reply.gif', 'lang' => true, 'url' => $scripturl . '?action=post;topic=' . $context['current_topic'] . '.' . $context['start'] . ';last_msg=' . $context['topic_last_message'], 'active' => true),
		'add_poll' => array('test' => 'can_add_poll', 'text' => 'add_poll', 'image' => 'add_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=editpoll;add;topic=' . $context['current_topic'] . '.' . $context['start']),
		'notify' => array('test' => 'can_mark_notify', 'text' => $context['is_marked_notify'] ? 'unnotify' : 'notify', 'image' => ($context['is_marked_notify'] ? 'un' : '') . 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_topic'] : $txt['notification_enable_topic']) . '\');"', 'url' => $scripturl . '?action=notify;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'mark_unread' => array('test' => 'can_mark_unread', 'text' => 'mark_unread', 'image' => 'markunread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=topic;t=' . $context['mark_unread_time'] . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'send' => array('test' => 'can_send_topic', 'text' => 'send_topic', 'image' => 'sendtopic.gif', 'lang' => true, 'url' => $scripturl . '?action=emailuser;sa=sendtopic;topic=' . $context['current_topic'] . '.0'),
		'print' => array('text' => 'print', 'image' => 'print.gif', 'lang' => true, 'custom' => 'rel="alternate nofollow"', 'url' => $scripturl . '?action=printpage;topic=' . $context['current_topic'] . '.0'),
	);
	call_integration_hook('integrate_display_buttons', array(&$normal_buttons));
	$veli=$context['page_index'];
	$veli=str_replace('[<strong>','<li class="active" ><a href="#">',$veli);
	$veli=str_replace('</strong>]','</a ></li>',$veli);
	$veli=str_replace('<a class="navPages"','<li><a class="navPages"',$veli);
	$veli=str_replace('</a>','</a></li>',$veli);
	$veli=str_replace('<span','<li><span',$veli);
	$veli=str_replace('</span>','</li>',$veli);
	echo '
			<div class="pagesection">
				', template_button_strip($normal_buttons, 'right'), '
				<div class="pagelinks pull-left"> <ul class="pagination">', $veli,'</ul>', !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '<a class="hopla" href="#lastPost"><span class="label label-success">' . $txt['go_down'] . '</span></a>' : '', '</div>
			</div>';
	echo '
			<div id="forumposts">
				<div class="drop-shadow lifted">
						<strong>', $context['subject'], '</strong><br/><span class="label label-default">', $txt['read'], ' ', $context['num_views'], ' ', $txt['times'], '</span>				
						<div class="btn-group pull-right">', $context['previous_next'], '</div>';
	if (!empty($settings['display_who_viewing']))
	{
		echo '
				<span class="label label-info">';
		if ($settings['display_who_viewing'] == 1)
				echo count($context['view_members']), ' ', count($context['view_members']) == 1 ? $txt['who_member'] : $txt['members'];
		else
			echo empty($context['view_members_list']) ? '0 ' . $txt['members'] : implode(', ', $context['view_members_list']) . ((empty($context['view_num_hidden']) || $context['can_moderate_forum']) ? '' : ' (+ ' . $context['view_num_hidden'] . ' ' . $txt['hidden'] . ')');
		echo $txt['who_and'], $context['view_num_guests'], ' ', $context['view_num_guests'] == 1 ? $txt['guest'] : $txt['guests'], $txt['who_viewing_topic'], '
				</span>';
	}
	echo '</div>
				<form action="', $scripturl, '?action=quickmod2;topic=', $context['current_topic'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '" name="quickModForm" id="quickModForm" style="margin: 0;" onsubmit="return oQuickModify.bInEditMode ? oQuickModify.modifySave(\'' . $context['session_id'] . '\', \'' . $context['session_var'] . '\') : false">';
	$ignoredMsgs = array();
	$removableMessageIDs = array();
	$alternate = false;
	while ($message = $context['get_message']())
	{
		$ignoring = false;
		$alternate = !$alternate;
		if ($message['can_remove'])
			$removableMessageIDs[] = $message['id'];
		if (!empty($message['is_ignored']))
		{
			$ignoring = true;
			$ignoredMsgs[] = $message['id'];
		}
		if ($message['id'] != $context['first_message'])
			echo '
				<a id="msg', $message['id'], '"></a>', $message['first_new'] ? '<a id="new"></a>' : '';

		echo '
				<div class="drop-shadow lifted">
					<div class="col-sm-6 posbit">';
				if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']) && !empty($message['member']['avatar']['image']))
				{echo '', $message['member']['avatar']['image'], '';}
				else{ echo'<img class="avatar" src="'.$settings['images_url'].'/default_avatar.png" alt="*" />';}
				echo '
					<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal', $message['member']['id'], '">';
					
				if (!empty($modSettings['onlineEnable']) && !$message['member']['is_guest'])
				echo '
						', $context['can_send_pm'] ? '<a href="' . $message['member']['online']['href'] . '" title="' . $message['member']['online']['label'] . '">' : '', '<img src="', $message['member']['online']['image_href'], '" alt="', $message['member']['online']['text'], '" />', $context['can_send_pm'] ? '</a>' : '';
					
					echo' ', $message['member']['name'], '</button>
					 <span class="label label-info">', $message['time'], '</span>
					</div>
					<div class="col-sm-6 small">
					', !empty($message['counter']) ? ' <a href="'. $message['href']. '" class="btn btn-default btn-xs  pull-right" rel="nofollow">#' . $message['counter'].'</a> ' : '', '
									<div id="msg_', $message['id'], '_quick_mod"></div>';
		if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $message['can_remove'])
			echo '
									<div class="inline_mod_check pull-right" style="display: none;" id="in_topic_mod_check_', $message['id'], '"></div>';									
		if ($message['can_approve'] || $context['can_reply'] || $message['can_modify'] || $message['can_remove'] || $context['can_split'] || $context['can_restore_msg'])
			echo '
								<div class="dropdown zzz pull-right">
									  <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">',$txt['ayarlar'],'
									  <span class="caret"></span></button>
									  <ul class="dropdown-menu dropdown-menu-right">';
		if ($message['can_approve'])
			echo '
									<li><a href="', $scripturl, '?action=moderate;area=postmod;sa=approve;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '"><span  class="quickbuttonss approve_button"></span> ', $txt['approve'], '</a></li>';
		if ($context['can_quote'] && !empty($options['display_quick_reply']))
			echo '
									<li><a href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';last_msg=', $context['topic_last_message'], '" onclick="return oQuickReply.quote(', $message['id'], ');"><span  class="quickbuttonss quote_button"></span> ', $txt['quote'], '</a></li>';
		elseif ($context['can_quote'])
			echo '
									<li><a href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';last_msg=', $context['topic_last_message'], '"><span  class="quickbuttonss quote_button"></span> ', $txt['quote'], '</a></li>';
		if ($message['can_modify'])
			echo '
									<li><a href="', $scripturl, '?action=post;msg=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], '"><span  class="quickbuttonss modify_button"></span> ', $txt['modify'], '</a></li>';
		if ($message['can_remove'])
			echo '
									<li><a href="', $scripturl, '?action=deletemsg;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['remove_message'], '?\');"><span  class="quickbuttonss remove_button"></span> ', $txt['remove'], '</a></li>';
		if ($context['can_split'] && !empty($context['real_num_replies']))
			echo '
									<li><a href="', $scripturl, '?action=splittopics;topic=', $context['current_topic'], '.0;at=', $message['id'], '"><span  class="quickbuttonss split_button"></span> ', $txt['split'], '</a></li>';
		if ($context['can_restore_msg'])
			echo '
									<li><a href="', $scripturl, '?action=restoretopic;msgs=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '"><span  class="quickbuttonss restore_button"></span> ', $txt['restore_message'], '</a></li>';
		
		if ($message['can_approve'] || $context['can_reply'] || $message['can_modify'] || $message['can_remove'] || $context['can_split'] || $context['can_restore_msg'])
			echo '
								</ul></div>';
						
		echo '</div>';
		echo '<div class="modal fade" id="myModal', $message['member']['id'], '" role="dialog">
				<div class="modal-dialog modal-sm">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">';
			if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']) && !empty($message['member']['avatar']['image']))
				{echo '', $message['member']['avatar']['image'], '';}
				else{ echo'<img class="avatar" src="'.$settings['images_url'].'/default_avatar.png" alt="" />';}
						
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
				  </div>
				</div>
			  </div>
						<div class="col-sm-12">';
		if ($ignoring)
			echo '
							<div id="msg_', $message['id'], '_ignored_prompt">
								', $txt['ignoring_user'], '
								<a href="#" id="msg_', $message['id'], '_ignored_link" style="display: none;">', $txt['show_ignore_user_post'], '</a>
							</div>';
		echo '
							<div class="post"><div id="subject_', $message['id'], '"></div>';

		if (!$message['approved'] && $message['member']['id'] != 0 && $message['member']['id'] == $context['user']['id'])
			echo '
								<div class="approve_post">
									', $txt['post_awaiting_approval'], '
								</div>';
		echo '
								<div class="inner" id="msg_', $message['id'], '"', '>', $message['body'], '</div>
							</div>';
		if ($message['can_modify'])
			echo '<div class="small pull-right">
							<span class="generic_icons regcenter" id="modify_button_', $message['id'], '" style="cursor: ', ($context['browser']['is_ie5'] || $context['browser']['is_ie5.5'] ? 'hand' : 'pointer'), '; " onclick="oQuickModify.modifyMsg(\'', $message['id'], '\')" ></span></div>';
		if (!empty($message['attachment']))
		{
			echo '
							<div id="msg_', $message['id'], '_footer" class="small">
								<div style="overflow: ', $context['browser']['is_firefox'] ? 'visible' : 'auto', ';">';
			$last_approved_state = 1;
			foreach ($message['attachment'] as $attachment)
			{
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
										<a href="' . $attachment['href'] . '"><span class="generic_icons attachment"></span>&nbsp;' . $attachment['name'] . '</a> ';
				if (!$attachment['is_approved'] && $context['can_approve'])
					echo '
										[<a href="', $scripturl, '?action=attachapprove;sa=approve;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve'], '</a>]&nbsp;|&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=reject;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['delete'], '</a>] ';
				echo '
										(', $attachment['size'], ($attachment['is_image'] ? ', ' . $attachment['real_width'] . 'x' . $attachment['real_height'] . ' - ' . $txt['attach_viewed'] : ' - ' . $txt['attach_downloaded']) . ' ' . $attachment['downloads'] . ' ' . $txt['attach_times'] . '.)<br />';
			}
			if ($last_approved_state == 0)
				echo '
									</fieldset>';
			echo '
								</div>
							</div>';
		}
		echo '					
						<div class="col-sm-12">
							<div class="small pull-left" id="modified_', $message['id'], '">';
		if ($settings['show_modify'] && !empty($message['modified']['name']))
			echo '
								&#171; <em>', $txt['last_edit'], ': ', $message['modified']['time'], ' ', $txt['by'], ' ', $message['modified']['name'], '</em> &#187;';
		echo '
							</div><hr/>
							<div class="small pull-right">';
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
							<div class="small">
								<ul class="list-group">';
				}
				echo '
									<li class="list-group-item">', $custom['value'], '</li>';
			}
			if ($shown)
				echo '
								</ul>
							</div>';
		}
		if (!empty($message['member']['signature']) && empty($options['show_no_signatures']) && $context['signature_enabled'])
			echo '
							<div class="small" id="msg_', $message['id'], '_signature">', $message['member']['signature'], '</div>';
		echo '
						</div></div>
				</div><br class="clear " />
				<hr/>';
	}
	echo '
				</form>
			</div>
			<a id="lastPost"></a>';
	echo '
			<div class="pagesection">
				', template_button_strip($normal_buttons, 'right'), '
				<div class="pagelinks pull-left"> <ul class="pagination">', $veli,'</ul>', !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '<a class="hopla" href="#top"><span class="label label-success">' . $txt['go_up'] . '</span></a>' : '', '</div><br class="clear"/>
			</div>';
	theme_linktree();
	$mod_buttons = array(
		'move' => array('test' => 'can_move', 'text' => 'move_topic', 'image' => 'admin_move.gif', 'lang' => true, 'url' => $scripturl . '?action=movetopic;topic=' . $context['current_topic'] . '.0'),
		'delete' => array('test' => 'can_delete', 'text' => 'remove_topic', 'image' => 'admin_rem.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['are_sure_remove_topic'] . '\');"', 'url' => $scripturl . '?action=removetopic2;topic=' . $context['current_topic'] . '.0;' . $context['session_var'] . '=' . $context['session_id']),
		'lock' => array('test' => 'can_lock', 'text' => empty($context['is_locked']) ? 'set_lock' : 'set_unlock', 'image' => 'admin_lock.gif', 'lang' => true, 'url' => $scripturl . '?action=lock;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'sticky' => array('test' => 'can_sticky', 'text' => empty($context['is_sticky']) ? 'set_sticky' : 'set_nonsticky', 'image' => 'admin_sticky.gif', 'lang' => true, 'url' => $scripturl . '?action=sticky;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'merge' => array('test' => 'can_merge', 'text' => 'merge', 'image' => 'merge.gif', 'lang' => true, 'url' => $scripturl . '?action=mergetopics;board=' . $context['current_board'] . '.0;from=' . $context['current_topic']),
		'calendar' => array('test' => 'calendar_post', 'text' => 'calendar_link', 'image' => 'linktocal.gif', 'lang' => true, 'url' => $scripturl . '?action=post;calendar;msg=' . $context['topic_first_message'] . ';topic=' . $context['current_topic'] . '.0'),
	);
	if ($context['can_restore_topic'])
		$mod_buttons[] = array('text' => 'restore_topic', 'image' => '', 'lang' => true, 'url' => $scripturl . '?action=restoretopic;topics=' . $context['current_topic'] . ';' . $context['session_var'] . '=' . $context['session_id']);
	call_integration_hook('integrate_mod_buttons', array(&$mod_buttons));
	echo '
			<div id="moderationbuttons">', template_button_strip($mod_buttons, 'bottom', array('id' => 'moderationbuttons_strip')), '</div>';
	echo '<div class="top-section">
			<div class="form-group pull-right" id="display_jump_to">&nbsp;</div></div>';
	if ($context['can_reply'] && !empty($options['display_quick_reply']))
	{
		echo '
			<a id="quickreply"></a>
			<div class="panel panel-default" id="quickreplybox">
				<div class="panel-heading"><a href="javascript:oQuickReply.swap();">
							<img src="', $settings['images_url'], '/', $options['display_quick_reply'] == 2 ? 'collapse' : 'expand', '.gif" alt="+" id="quickReplyExpand" class="icon" />
						</a>
						<a href="javascript:oQuickReply.swap();">', $txt['quick_reply'], '</a>
				</div>
				<div class="panel-body" id="quickReplyOptions"', $options['display_quick_reply'] == 2 ? '' : ' style="display: none"', '>
						<p class="smalltext lefttext">', $txt['quick_reply_desc'], '</p>
						', $context['is_locked'] ? '<p class="alert alert-danger smalltext">' . $txt['quick_reply_warning'] . '</p>' : '',
						$context['oldTopicError'] ? '<p class="alert alert-danger smalltext">' . sprintf($txt['error_old_topic'], $modSettings['oldTopicDays']) . '</p>' : '', '
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
			if ($context['user']['is_guest'])
				echo '
							<strong>', $txt['name'], ':</strong> <input type="text" name="guestname" value="', $context['name'], '" size="25" class="form-control" tabindex="', $context['tabindex']++, '" />
							<strong>', $txt['email'], ':</strong> <input type="text" name="email" value="', $context['email'], '" size="25" class="form-control" tabindex="', $context['tabindex']++, '" /><br />';
			if ($context['require_verification'])
				echo '
							<strong>', $txt['verification'], ':</strong>', template_control_verification($context['visual_verification_id'], 'quick_reply'), '<br />';
			echo '
							<div class="quickReplyContent">
								<textarea cols="600" rows="7" name="message" tabindex="', $context['tabindex']++, '"></textarea>
							</div>
							<div class="pull-right">
								<input type="submit" name="post" value="', $txt['post'], '" onclick="return submitThisOnce(this);" accesskey="s" tabindex="', $context['tabindex']++, '" class="btn btn-success" />
								<input type="submit" name="preview" value="', $txt['preview'], '" onclick="return submitThisOnce(this);" accesskey="p" tabindex="', $context['tabindex']++, '" class="btn btn-success" />';
			if ($context['show_spellchecking'])
				echo '
								<input type="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'postmodify\', \'message\');" tabindex="', $context['tabindex']++, '" class="btn btn-success" />';
			echo '
							</div>
						</form>
					</div>
				</div>';
	}	
	else
		echo '
		<br class="clear" />';
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
							sTemplateSubjectEdit: ', JavaScriptEscape('<input type="text" style="width: 90%;" name="subject" value="%subject%" size="80" maxlength="80" tabindex="' . $context['tabindex']++ . '" class="form-control" />'), ',
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