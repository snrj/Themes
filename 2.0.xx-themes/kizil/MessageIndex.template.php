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
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	if (!empty($context['boards']) && (!empty($options['show_children']) || $context['start'] == 0))
	{
		echo '
	<div id="board_', $context['current_board'], '_childboards" class="boardindex_table">
		<div class="cat_bar">
			<h3 class="catbg">', $txt['parent_boards'], '</h3>
		</div>';

		foreach ($context['boards'] as $board)
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
								  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">', $txt['parent_boards'], '
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
						<p><span class="label label-warning"><i class="fa fa-reply"></i> ', comma_format($board['posts']), ' </span>&nbsp; 
						 <span class="label label-warning"><i class="fa fa-new_topic"></i> '. comma_format($board['topics']) . ' </span>  
						</p>
					</div>
					<div class="lastpost">';

				if (!empty($board['last_post']['id']))
					echo '
						<p><strong>', $txt['last_post'], '</strong>  ', $txt['by'], ' ', $board['last_post']['member']['link'] , '<br />
						', $txt['in'], ' ', $board['last_post']['link'], '<br />
						', $txt['on'], ' ', $board['last_post']['time'],'
						</p>';
				echo '
					</div>';
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
							$child['link'] = '<a href="' . $child['href'] . '" ' . ($child['new'] ? 'class="board_new_posts" ' : '') . 'title="' . ($child['new'] ? $txt['new_posts'] : $txt['old_posts']) . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')">' . $child['name'] . ($child['new'] ? '</a> <a href="' . $scripturl . '?action=unread;board=' . $child['id'] . '" title="' . $txt['new_posts'] . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')"><span class="new_posts">' . $txt['new'] . '</span>' : '') . '</a>';
						else
							$child['link'] = '<a href="' . $child['href'] . '" title="' . comma_format($child['posts']) . ' ' . $txt['redirects'] . ' - ' . $child['short_description'] . '">' . $child['name'] . '</a>';

						// Has it posts awaiting approval?
						if ($child['can_approve_posts'] && ($child['unapproved_posts'] || $child['unapproved_topics']))
							$child['link'] .= ' <a href="' . $scripturl . '?action=moderate;area=postmod;sa=' . ($child['unapproved_topics'] > 0 ? 'topics' : 'posts') . ';brd=' . $child['id'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" title="' . sprintf($txt['unapproved_posts'], $child['unapproved_topics'], $child['unapproved_posts']) . '" class="moderation_link">(!)</a>';

						$children[] = $child['new'] ? '<strong>' . $child['link'] . '</strong>' : $child['link'];
					}

				echo '
					<div id="board_', $board['id'], '_children" class="children">
						<p><strong>', $txt['parent_boards'], '</strong>: ', implode($children), '</p>
					</div>';
				}

				echo '
					</div>';
			}

		echo '
			</div>';
	}
	// Create the button set...
	$normal_buttons = array(
		'new_topic' => array('test' => 'can_post_new', 'text' => 'new_topic', 'image' => 'new_topic.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0', 'active' => true),
		'post_poll' => array('test' => 'can_post_poll', 'text' => 'new_poll', 'image' => 'new_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0;poll'),
		'notify' => array('test' => 'can_mark_notify', 'text' => $context['is_marked_notify'] ? 'unnotify' : 'notify', 'image' => ($context['is_marked_notify'] ? 'un' : ''). 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_board'] : $txt['notification_enable_board']) . '\');"', 'url' => $scripturl . '?action=notifyboard;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';board=' . $context['current_board'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'markread' => array('text' => 'mark_read_short', 'image' => 'markread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0;' . $context['session_var'] . '=' . $context['session_id']),
	);

	// They can only mark read if they are logged in and it's enabled!
	if (!$context['user']['is_logged'] || !$settings['show_mark_read'])
		unset($normal_buttons['markread']);

	// Allow adding new buttons easily.
	call_integration_hook('integrate_messageindex_buttons', array(&$normal_buttons));

	if (!$context['no_topic_listing'])
	{
		echo '
	<div class="pagesection">
		<div class="pagelinks floatleft">', !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '<a class="button floatleft" href="#bot"><strong>' . $txt['go_down'] . '</strong></a>&nbsp;&nbsp;' : '', $txt['pages'], ': ', $context['page_index'], '</div>
		', template_button_strip($normal_buttons, 'right'), '
	</div>';

		// If Quick Moderation is enabled start the form.
		if (!empty($context['can_quick_mod']) && $options['display_quick_mod'] > 0 && !empty($context['topics']))
			echo '
	<form action="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '" class="clear" name="quickModForm" id="quickModForm">';

		echo '
		<div id="messageindex">';
		if (empty($options['show_board_desc']) && $context['description'] != '')
		echo '<p class="description_board">', $context['description'], '</p>';
		if (!empty($settings['display_who_viewing']))
		{
			echo '<div class="information">';
			if ($settings['display_who_viewing'] == 1)
				echo count($context['view_members']), ' ', count($context['view_members']) === 1 ? $txt['who_member'] : $txt['members'];
			else
				echo empty($context['view_members_list']) ? '0 ' . $txt['members'] : implode(', ', $context['view_members_list']) . ((empty($context['view_num_hidden']) or $context['can_moderate_forum']) ? '' : ' (+ ' . $context['view_num_hidden'] . ' ' . $txt['hidden'] . ')');
			echo $txt['who_and'], $context['view_num_guests'], ' ', $context['view_num_guests'] == 1 ? $txt['guest'] : $txt['guests'], $txt['who_viewing_board'], '
				
				</div>';
		}
			echo '
		<div class="title_bar" id="topic_header">';
		if (!empty($context['topics']))
		{
			echo '
					<div class="board_icon">&nbsp;</div>
					<div class="info"><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=subject', $context['sort_by'] == 'subject' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['subject'], $context['sort_by'] == 'subject' ? ' ' : '', '</a> / <a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=starter', $context['sort_by'] == 'starter' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['started_by'], $context['sort_by'] == 'starter' ? '' : '', '</a></div>
					<div class="board_stats centertext"><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=replies', $context['sort_by'] == 'replies' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['replies'], $context['sort_by'] == 'replies' ? '' : '', '</a> / <a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=views', $context['sort_by'] == 'views' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['views'], $context['sort_by'] == 'views' ? ' ' : '', '</a></div>
			
					<div class="lastpost"><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=last_post', $context['sort_by'] == 'last_post' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['last_post'], $context['sort_by'] == 'last_post' ? ' ' : '', '</a></div>';

			// Show a "select all" box for quick moderation?
			if (!empty($context['can_quick_mod']) && $options['display_quick_mod'] == 1)
				echo '
					<div class="moderation"><input type="checkbox" onclick="invertAll(this, this.form, \'topics[]\');" class="input_check" /></div>';
			
			// If it's on in "image" mode, don't show anything but the column.
			elseif (!empty($context['can_quick_mod']))
				echo '
					<div class="moderation">&nbsp;</div>';
		}
		// No topics.... just say, "sorry bub".
		else
			echo '<h3 class="titlebg">', $txt['msg_alert_none'], '</h3>';

		echo '</div>';

		// If this person can approve items and we have some awaiting approval tell them.
		if (!empty($context['unapproved_posts_message']))
		{
			echo '<div class="information">
						<span class="alert">!</span> ', $context['unapproved_posts_message'], '
					</div>';
		}
		echo '
			<div id="topic_container">';

		foreach ($context['topics'] as $topic)
		{
			$color_class = 'windowbg';

			// Is this topic pending approval, or does it have any posts pending approval?
			if ($context['can_approve_posts'] && $topic['unapproved_posts'])
				$color_class = (!$topic['approved'] ? 'approvetopic ' : 'approvepost ') . $color_class;

			// Sticky topics should get a different color, too.
			if ($topic['is_sticky'])
				$color_class = 'sticky ' . $color_class;
			// Locked topics get special treatment as well.
			if ($topic['is_locked'])
				$color_class = 'locked ' . $color_class;

			// Some columns require a different shade of the color class.
			$alternate_class = $color_class . '2';

			echo '
			<div class="', $color_class, '">
				<div class="board_icon">
					<img class="topicon" src="', $topic['first_post']['icon_url'], '" alt="*" />
					', $topic['is_posted_in'] ? '<i class="fa fa-profile"></i>' : '', '
				</div>
					<div class="info">
						<div ', (!empty($topic['quick_mod']['modify']) ? 'id="topic_' . $topic['first_post']['id'] . '" onmouseout="mouse_on_div = 0;" onmouseover="mouse_on_div = 1;" ondblclick="modify_topic(\'' . $topic['id'] . '\', \'' . $topic['first_post']['id'] . '\');"' : ''), '>
							', $topic['is_sticky'] ? '<strong>' : '', '<span id="msg_' . $topic['first_post']['id'] . '">', $topic['first_post']['link'], (!$context['can_approve_posts'] && !$topic['approved'] ? '&nbsp;<em>(' . $txt['awaiting_approval'] . ')</em>' : ''), '</span>', $topic['is_sticky'] ? '</strong>' : '';

			// Is this topic new? (assuming they are logged in!)
			if ($topic['new'] && $context['user']['is_logged'])
					echo '
							<a href="', $topic['new_href'], '" id="newicon' . $topic['first_post']['id'] . '"><i class="fa fa-comments"></i></a>';

		

			// Now we handle the icons
			echo '
							<span class="icons">';

			if ($topic['is_locked'])
				echo '
								<i class="fa fa-lock floatright"></i>';
			if ($topic['is_sticky'])
				echo '
								<i class="fa fa-sticky floatright"></i>';

			if ($topic['is_poll'])
				echo '
								<i class="fa fa-post_poll floatright"></i>';
			echo '
							</span>';

			echo '
							<br class="clear">
				
							<p>', $txt['started_by'], ' ', $topic['first_post']['member']['link'], '
								<small id="pages' . $topic['first_post']['id'] . '">', $topic['pages'], '</small>
							</p>
						</div></div>
					
					<div class="board_stats centertext">
						<span class="label label-warning"><i class="fa fa-reply"></i> ', $topic['replies'], '</span>
						<span class="label label-warning"><i class="fa fa-eye"></i> ', $topic['views'], ' </span>
					</div>
					<div class="lastpost"><p>
						', $topic['last_post']['time'], '<br />
						', $txt['by'], ' ', $topic['last_post']['member']['link'], '
						<a href="', $topic['last_post']['href'], '"> <i class="fa fa-fast-forward"></i></a></p>
					</div>';

	// Show the quick moderation options?
			if (!empty($context['can_quick_mod']))
			{
				echo '<div class="moderation">';
				if ($options['display_quick_mod'] == 1)
					echo '
						<input type="checkbox" name="topics[]" value="', $topic['id'], '" class="input_check" />';
				else
				{
					// Check permissions on each and show only the ones they are allowed to use.
					if ($topic['quick_mod']['remove'])
						echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=remove;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><i class="fa fa-delete" title="', $txt['remove_topic'], '"></i></a>';

					if ($topic['quick_mod']['lock'])
						echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=lock;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><i class="fa fa-lock" title="', $txt['set_lock'], '"></i></a>';

					if ($topic['quick_mod']['lock'] || $topic['quick_mod']['remove'])
						echo '<br />';

					if ($topic['quick_mod']['sticky'])
						echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=sticky;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><i class="fa fa-sticky" title="', $txt['set_sticky'], '"></i></a>';

					if ($topic['quick_mod']['move'])
						echo '<a href="', $scripturl, '?action=movetopic;board=', $context['current_board'], '.', $context['start'], ';topic=', $topic['id'], '.0"><i class="fa fa-move" title="', $txt['move_topic'], '"></i></a>';
				}
				echo '
					</div>';
			}
			echo '
				</div>';
		}
		echo '
			</div>';

		if (!empty($context['can_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
		{
			echo '<div class="righttext" id="quick_actions">
						<select class="qaction" name="qaction"', $context['can_move'] ? ' onchange="this.form.moveItTo.disabled = (this.options[this.selectedIndex].value != \'move\');"' : '', '>
							<option value="">--------</option>', $context['can_remove'] ? '
							<option value="remove">' . $txt['quick_mod_remove'] . '</option>' : '', $context['can_lock'] ? '
							<option value="lock">' . $txt['quick_mod_lock'] . '</option>' : '', $context['can_sticky'] ? '
							<option value="sticky">' . $txt['quick_mod_sticky'] . '</option>' : '', $context['can_move'] ? '
							<option value="move">' . $txt['quick_mod_move'] . ': </option>' : '', $context['can_merge'] ? '
							<option value="merge">' . $txt['quick_mod_merge'] . '</option>' : '', $context['can_restore'] ? '
							<option value="restore">' . $txt['quick_mod_restore'] . '</option>' : '', $context['can_approve'] ? '
							<option value="approve">' . $txt['quick_mod_approve'] . '</option>' : '', $context['user']['is_logged'] ? '
							<option value="markread">' . $txt['quick_mod_markread'] . '</option>' : '', '
						</select>';

			// Show a list of boards they can move the topic to.
			if ($context['can_move'])
			{
					echo '
						<select class="qaction" id="moveItTo" name="move_to" disabled="disabled">';

					foreach ($context['move_to_boards'] as $category)
					{
						echo '
							<optgroup label="', $category['name'], '">';
						foreach ($category['boards'] as $board)
								echo '
								<option value="', $board['id'], '"', $board['selected'] ? ' selected="selected"' : '', '>', $board['child_level'] > 0 ? str_repeat('==', $board['child_level'] - 1) . '=&gt;' : '', ' ', $board['name'], '</option>';
						echo '
							</optgroup>';
					}
					echo '
						</select>';
			}

			echo '
						<input type="submit" value="', $txt['quick_mod_go'], '" onclick="return document.forms.quickModForm.qaction.value != \'\' &amp;&amp; confirm(\'', $txt['quickmod_confirm'], '\');" class="button_submit qaction" />
					
				</div>';
		}

		echo '</div>
	<a id="bot"></a>';

		// Finish off the form - again.
		if (!empty($context['can_quick_mod']) && $options['display_quick_mod'] > 0 && !empty($context['topics']))
			echo '
	<input type="hidden" name="' . $context['session_var'] . '" value="' . $context['session_id'] . '" />
	</form>';

		echo '
	<div class="pagesection">
		', template_button_strip($normal_buttons, 'right'), '
		<div class="pagelinks">', !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '<a class="button floatleft" href="#top"><strong>' . $txt['go_up'] . '</strong></a>&nbsp;&nbsp;' : '', $txt['pages'], ': ', $context['page_index'], '</div>
	</div>';
	}

	// Show breadcrumbs at the bottom too.
	theme_linktree();

	echo '
	<div class="tborder" id="topic_icons">
		<div class="information">
			<p class="floatright" id="message_index_jump_to">&nbsp;</p>';
		if (empty($context['no_topic_listing']))
		echo '
			<p class="floatleft smalltext">', !empty($modSettings['enableParticipation']) && $context['user']['is_logged'] ? '
				<i class="fa fa-profile"></i> ' . $txt['participation_caption'] . '<br>' : '', '
				'. ($modSettings['pollMode'] == '1' ? '<i class="fa fa-post_poll"></i>' . $txt['poll'] : '') . '<br>
			</p>
			<p>
				<i class="fa fa-lock"></i> ' . $txt['locked_topic'] . '<br>
				<i class="fa fa-sticky"></i> ' . $txt['sticky_topic'] . '<br>
			</p>';
	echo '
			<script type="text/javascript"><!-- // --><![CDATA[
				if (typeof(window.XMLHttpRequest) != "undefined")
					aJumpTo[aJumpTo.length] = new JumpTo({
						sContainerId: "message_index_jump_to",
						sJumpToTemplate: "<label class=\"smalltext\" for=\"%select_id%\">', $context['jump_to']['label'], ':<" + "/label> %dropdown_list%",
						iCurBoardId: ', $context['current_board'], ',
						iCurBoardChildLevel: ', $context['jump_to']['child_level'], ',
						sCurBoardName: "', $context['jump_to']['board_name'], '",
						sBoardChildLevelIndicator: "==",
						sBoardPrefix: "=> ",
						sCatSeparator: "-----------------------------",
						sCatPrefix: "",
						sGoButtonLabel: "', $txt['quick_mod_go'], '"
					});
			// ]]></script>
		</div>
	</div>';

	// Javascript for inline editing.
	echo '
<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/topic.js?fin20"></script>
<script type="text/javascript"><!-- // --><![CDATA[



	// Use it to detect when we\'ve stopped editing.
	document.onclick = modify_topic_click;

	var mouse_on_div;
	function modify_topic_click()
	{
		if (in_edit_mode == 1 && mouse_on_div == 0)
			modify_topic_save("', $context['session_id'], '", "', $context['session_var'], '");
	}

	function modify_topic_keypress(oEvent)
	{
		if (typeof(oEvent.keyCode) != "undefined" && oEvent.keyCode == 13)
		{
			modify_topic_save("', $context['session_id'], '", "', $context['session_var'], '");
			if (typeof(oEvent.preventDefault) == "undefined")
				oEvent.returnValue = false;
			else
				oEvent.preventDefault();
		}
	}

	// For templating, shown when an inline edit is made.
	function modify_topic_show_edit(subject)
	{
		// Just template the subject.
		setInnerHTML(cur_subject_div, \'<input type="text" name="subject" value="\' + subject + \'" size="60" style="width: 95%;" maxlength="80" onkeypress="modify_topic_keypress(event)" class="input_text" /><input type="hidden" name="topic" value="\' + cur_topic_id + \'" /><input type="hidden" name="msg" value="\' + cur_msg_id.substr(4) + \'" />\');
	}

	// And the reverse for hiding it.
	function modify_topic_hide_edit(subject)
	{
		// Re-template the subject!
		setInnerHTML(cur_subject_div, \'<a href="', $scripturl, '?topic=\' + cur_topic_id + \'.0">\' + subject + \'<\' +\'/a>\');
	}

// ]]></script>';
}

?>