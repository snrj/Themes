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
	global $context, $settings, $options, $scripturl, $modSettings, $txt;
	echo '
	<a id="top"></a>';
	if (!empty($context['boards']) && (!empty($options['show_children']) || $context['start'] == 0))
	{
		echo '
	<div class="catmain" id="board_', $context['current_board'], '_childboards">
		<div class="cat_bar">
			<h3 class="catbg">', $txt['parent_boards'], '</h3>
		</div>
		<div id="board_', $context['current_board'], '_children" class="drop-shadow lifted">';
		foreach ($context['boards'] as $board)
		{
			echo '<div class="boardbg"><div class="board_icon">';
				if ($board['new'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '" >';
				elseif ($board['children_new'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '" class="board_on2">';
				elseif ($board['is_redirect'])
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '" class="board_redirect">';
				else
					echo '	<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '" class="board_off">';
				echo '
						</a>
					</div>
					<div class="info">
						<a class="subject" href="', $board['href'], '" name="b', $board['id'], '">', $board['name'], '</a>';
			if ($board['can_approve_posts'] && ($board['unapproved_posts'] || $board['unapproved_topics']))
				echo '
						<a href="', $scripturl, '?action=moderate;area=postmod;sa=', ($board['unapproved_topics'] > 0 ? 'topics' : 'posts'), ';brd=', $board['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', sprintf($txt['unapproved_posts'], $board['unapproved_topics'], $board['unapproved_posts']), '" class="moderation_link">(!)</a>';
			echo '<p>', $board['description'] , '</p>';
			if (!empty($board['moderators']))
				echo '
						<p class="moderators">', count($board['moderators']) === 1 ? $txt['moderator'] : $txt['moderators'], ': ', implode(', ', $board['link_moderators']), '</p>';
			if (!empty($board['children']))
			{
				$children = array();
				foreach ($board['children'] as $child)
				{
					if (!$child['is_redirect'])
						$child['link'] = '<a href="' . $child['href'] . '" ' . ($child['new'] ? 'class="new_posts" ' : '') . 'title="' . ($child['new'] ? $txt['new_posts'] : $txt['old_posts']) . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')">' . $child['name'] . '</a>';
					else
						$child['link'] = '<a href="' . $child['href'] . '" title="' . comma_format($child['posts']) . ' ' . $txt['redirects'] . '">' . $child['name'] . '</a>';
					if ($child['can_approve_posts'] && ($child['unapproved_posts'] | $child['unapproved_topics']))
						$child['link'] .= ' <a href="' . $scripturl . '?action=moderate;area=postmod;sa=' . ($child['unapproved_topics'] > 0 ? 'topics' : 'posts') . ';brd=' . $child['id'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" title="' . sprintf($txt['unapproved_posts'], $child['unapproved_topics'], $child['unapproved_posts']) . '" class="moderation_link">(!)</a>';

					$children[] =$child['new'] ? '<strong><span class="generic_icons im_off"></span> ' . $child['link'] . ' <span class="generic_icons im_on"></span></strong>' :'<strong><span class="generic_icons im_off"></span> '.$child['link'].'</strong>';
				}
				echo '
					<div id="board_', $board['id'], '_children" class="children">
							<p>', implode(',',$children), '</p>
					</div>';
			}
			echo '
					</div>
					<div class="board_stats">
						<p>', comma_format($board['posts']), ' ', $board['is_redirect'] ? $txt['redirects'] : $txt['posts'], ' <br />
						', $board['is_redirect'] ? '' : comma_format($board['topics']) . ' ' . $txt['board_topics'], '
						</p>
					</div>
					<div class="lastpost">';
			if (!empty($board['last_post']['id']))
				echo '
						<p><strong>', $txt['last_post'], '</strong>  ', $txt['by'], ' ', $board['last_post']['member']['link'], '<br />
						', $txt['in'], ' ', $board['last_post']['link'], '<br />
						', $txt['on'], ' ', $board['last_post']['time'],'
						</p>';
			echo '</div></div>';
		}
		echo '
		</div>
	</div>';
	}
	if (!empty($options['show_board_desc']) && $context['description'] != '')
		echo '
	<div class="alert alert-warning">', $context['description'], '</div >';
	$normal_buttons = array(
		'new_topic' => array('test' => 'can_post_new', 'text' => 'new_topic', 'image' => 'new_topic.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0', 'active' => true),
		'post_poll' => array('test' => 'can_post_poll', 'text' => 'new_poll', 'image' => 'new_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0;poll'),
		'notify' => array('test' => 'can_mark_notify', 'text' => $context['is_marked_notify'] ? 'unnotify' : 'notify', 'image' => ($context['is_marked_notify'] ? 'un' : ''). 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_board'] : $txt['notification_enable_board']) . '\');"', 'url' => $scripturl . '?action=notifyboard;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';board=' . $context['current_board'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'markread' => array('text' => 'mark_read_short', 'image' => 'markread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0;' . $context['session_var'] . '=' . $context['session_id']),
	);
	if (!$context['user']['is_logged'] || !$settings['show_mark_read'])
		unset($normal_buttons['markread']);
	call_integration_hook('integrate_messageindex_buttons', array(&$normal_buttons));
	if (!$context['no_topic_listing'])
	{
	$veli=$context['page_index'];
	$veli=str_replace('[<strong>','<li class="active" ><a href="#">',$veli);
	$veli=str_replace('</strong>]','</a ></li>',$veli);
	$veli=str_replace('<a class="navPages"','<li><a class="navPages"',$veli);
	$veli=str_replace('</a>','</a></li>',$veli);
	$veli=str_replace('<span','<li><span',$veli);
	$veli=str_replace('</span>','</li>',$veli);
		echo '
	<div class="pagesection">
		<div class="pagelinks pull-left"><ul class="pagination">',$veli,'</ul>', !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '<a class="hopla" href="#bot"><span class="label label-success">' . $txt['go_down'] . '</span></a>' : '', '</div>
		', template_button_strip($normal_buttons, 'right'), '
	</div>';
		if (!empty($context['can_quick_mod']) && $options['display_quick_mod'] > 0 && !empty($context['topics']))
			echo '
	<form action="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '" class="clear form-inline" name="quickModForm" id="quickModForm">';
		echo '<div class="top-section">';
		if (!empty($context['topics']))
		{
			if (!empty($settings['display_who_viewing']))
			{
				echo '';
				if ($settings['display_who_viewing'] == 1)
					echo count($context['view_members']), ' ', count($context['view_members']) === 1 ? $txt['who_member'] : $txt['members'];
				else
					echo empty($context['view_members_list']) ? '0 ' . $txt['members'] : implode(', ', $context['view_members_list']) . ((empty($context['view_num_hidden']) or $context['can_moderate_forum']) ? '' : ' (+ ' . $context['view_num_hidden'] . ' ' . $txt['hidden'] . ')');
				echo $txt['who_and'], $context['view_num_guests'], ' ', $context['view_num_guests'] == 1 ? $txt['guest'] : $txt['guests'], $txt['who_viewing_board'], '
					';
			}	
			if (!empty($context['can_quick_mod']) && $options['display_quick_mod'] == 1)
				echo '
					<div class="inline_mod_check pull-right"><input type="checkbox" onclick="invertAll(this, this.form, \'topics[]\');" class="input_check" /></div>';	
			echo '<div class="dropdown pull-right">
				  <button class="btn btn-info dropdown-toggle btn-xs" type="button" data-toggle="dropdown">
				  ',$txt['sirala'],'
				  <span class="caret"></span></button>
				  <ul class="dropdown-menu">	
			<li><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=subject', $context['sort_by'] == 'subject' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['subject'], $context['sort_by'] == 'subject' ? ' ' : '', '</a> </li>
			<li><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=starter', $context['sort_by'] == 'starter' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['started_by'], $context['sort_by'] == 'starter' ? ' ' : '', '</a></li>
			<li><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=replies', $context['sort_by'] == 'replies' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['replies'], $context['sort_by'] == 'replies' ? ' ' : '', '</a> 
			<li><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=views', $context['sort_by'] == 'views' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['views'], $context['sort_by'] == 'views' ? ' ' : '', '</a></li>';
			if (empty($context['can_quick_mod']))
				echo '
				<li><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=last_post', $context['sort_by'] == 'last_post' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['last_post'], $context['sort_by'] == 'last_post' ? ' ' : '', '</a></li>';
			else
				echo '
					<li><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=last_post', $context['sort_by'] == 'last_post' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['last_post'], $context['sort_by'] == 'last_post' ? ' ' : '', '</a></li>';
		
			echo '</ul>
				</div>';
			
		}
		else
			echo '<span class="label label-success">', $txt['msg_alert_none'], '</span>';
		echo '</div>';
		if (!empty($context['unapproved_posts_message']))
		{
			echo '<span class="generic_icons error"></span> ', $context['unapproved_posts_message'], '';
		}
		echo'<div class="drop-shadow lifted">
				<div class="boxer mborder">';
		foreach ($context['topics'] as $topic)
		{
			if ($context['can_approve_posts'] && $topic['unapproved_posts'])
				$color_class = !$topic['approved'] ? 'mback' : 'mback';
			elseif ($topic['is_sticky'] && $topic['is_locked'])
				$color_class = 'alert-warning';
			elseif ($topic['is_sticky'])
				$color_class = 'alert-info';
			elseif ($topic['is_locked'])
				$color_class = 'alert-danger';
			else
				$color_class = 'mback';

			echo '<div class="box-row ',$color_class,'">
									
						<div class="box micon">
						<img src="', $topic['first_post']['icon_url'], '" alt="" />
						</div>					
						<div class="box minfo">
						<div ', (!empty($topic['quick_mod']['modify']) ? 'id="topic_' . $topic['first_post']['id'] . '" onmouseout="mouse_on_div = 0;" onmouseover="mouse_on_div = 1;" ondblclick="modify_topic(\'' . $topic['id'] . '\', \'' . $topic['first_post']['id'] . '\');"' : ''), '>
							', $topic['is_sticky'] ? '<strong>' : '', '<span id="msg_' . $topic['first_post']['id'] . '">', $topic['first_post']['link'], (!$context['can_approve_posts'] && !$topic['approved'] ? '&nbsp;<em>(' . $txt['awaiting_approval'] . ')</em>' : ''), '</span>', $topic['is_sticky'] ? '</strong>' : '';
			if ($topic['new'] && $context['user']['is_logged'])
					echo '
							<a href="', $topic['new_href'], '" id="newicon' . $topic['first_post']['id'] . '"><span class="generic_icons im_on"></span></span></a>';
					echo '
							<p>', $txt['started_by'], ' ', $topic['first_post']['member']['link'], '
								<small id="pages' . $topic['first_post']['id'] . '">', $topic['pages'], '</small>
							</p>
						</div>
						</div>			
						<div class="box mreplies">
						', $topic['replies'], '<br/> ', $txt['replies'], '
						</div>
						<div class="box mviews">
						', $topic['views'], '<br/> ', $txt['views'], '
						</div>
						<div class="box mlastpost">
						<a href="', $topic['last_post']['href'], '"><span class="generic_icons last_post"></span></a>
						', $topic['last_post']['time'], '<br />
						', $txt['by'], ' ', $topic['last_post']['member']['link'], '
						</div>';
			if (!empty($context['can_quick_mod']))
			{
				echo '';
				if ($options['display_quick_mod'] == 1)
					echo '<div class="box marac">
						<input type="checkbox" name="topics[]" value="', $topic['id'], '" class="input_check" />
						</div>';
				else
				{
					echo'<div class="box marac">';
					if ($topic['quick_mod']['remove'])
						echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=remove;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><span class="generic_icons delete"></span></a>';
					if ($topic['quick_mod']['lock'])
						echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=lock;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><span class="generic_icons lock"></span></a>';
					if ($topic['quick_mod']['lock'] || $topic['quick_mod']['remove'])
						echo '<br />';
					if ($topic['quick_mod']['sticky'])
						echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=sticky;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><span class="generic_icons sticky"></span></a>';
					if ($topic['quick_mod']['move'])
						echo '<a href="', $scripturl, '?action=movetopic;board=', $context['current_board'], '.', $context['start'], ';topic=', $topic['id'], '.0"><span class="generic_icons move"></span></span></a>';
				echo '</div>';
	
				}
			}
			echo '</div>';
		}
		echo'</div></div>';
		if (!empty($context['can_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
		{
			echo '	<div class="top-section"><div class="form-group form-group-sm pull-right">
						<select class="form-control" name="qaction"', $context['can_move'] ? ' onchange="this.form.moveItTo.disabled = (this.options[this.selectedIndex].value != \'move\');"' : '', '>
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
			if ($context['can_move'])
			{
					echo '
						<select class="form-control" id="moveItTo" name="move_to" disabled="disabled">';

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
				</div></div>';
		}
		echo '	
	<a id="bot"></a>';
		if (!empty($context['can_quick_mod']) && $options['display_quick_mod'] > 0 && !empty($context['topics']))
			echo '
	<input type="hidden" name="' . $context['session_var'] . '" value="' . $context['session_id'] . '" />
	</form>';

		echo '
	<div class="pagesection">
		', template_button_strip($normal_buttons, 'right'), '
		<div class="pagelinks"><ul class="pagination"> ', $veli,'</ul>', !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '&nbsp;&nbsp;<a class="hopla" href="#top"><span class="label label-success">' . $txt['go_up'] . '</span></a>' : '', '</div>
	</div>';
	}
	theme_linktree();
	echo '
	<div class="top-section">
			<div class="form-group pull-right" id="message_index_jump_to">&nbsp;</div>';
	echo '
			<script type="text/javascript"><!-- // --><![CDATA[
				if (typeof(window.XMLHttpRequest) != "undefined")
					aJumpTo[aJumpTo.length] = new JumpTo({
						sContainerId: "message_index_jump_to",
						sJumpToTemplate: "<label class=\"small\" for=\"%select_id%\">', $context['jump_to']['label'], ':<" + "/label> %dropdown_list%",
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
	</div><br class="clear" />';
	echo '
<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/topic.js"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	// Hide certain bits during topic edit.
	hide_prefixes.push("lockicon", "stickyicon", "pages", "newicon");

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
		setInnerHTML(cur_subject_div, \'<input type="text" name="subject" value="\' + subject + \'" size="60" style="width: 95%;" maxlength="80" onkeypress="modify_topic_keypress(event)" class="form-control" /><input type="hidden" name="topic" value="\' + cur_topic_id + \'" /><input type="hidden" name="msg" value="\' + cur_msg_id.substr(4) + \'" />\');
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