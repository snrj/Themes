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
	echo '
	<div id="statistics" class="main_section small">
		<div class="cat_bar">
				<h3 class="catbg">
			<span class="generic_icons general"></span>', $context['page_title'], '
				</h3>
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
			<div class="cat_bar">
			<h3 class="catbg">
					<span class="generic_icons views"></span>
					 ', $txt['general_stats'], '
			</h3>
			</div>
				<div class="drop-shadow lifted">
						<dl class="stats">
							<dt>', $txt['total_members'], ':</dt>
							<dd>', $context['show_member_list'] ? '<a href="' . $scripturl . '?action=mlist">' . $context['num_members'] . '</a>' : $context['num_members'], '</dd>
							<dt>', $txt['total_posts'], ':</dt>
							<dd>', $context['num_posts'], '</dd>
							<dt>', $txt['total_topics'], ':</dt>
							<dd>', $context['num_topics'], '</dd>
							<dt>', $txt['total_cats'], ':</dt>
							<dd>', $context['num_categories'], '</dd>
							<dt>', $txt['users_online'], ':</dt>
							<dd>', $context['users_online'], '</dd>
							<dt>', $txt['most_online'], ':</dt>
							<dd>', $context['most_members_online']['number'], ' - ', $context['most_members_online']['date'], '</dd>
							<dt>', $txt['users_online_today'], ':</dt>
							<dd>', $context['online_today'], '</dd>';
	if (!empty($modSettings['hitStats']))
		echo '
							<dt>', $txt['num_hits'], ':</dt>
							<dd>', $context['num_hits'], '</dd>';
	echo '
						</dl>
				</div>
			</div>
			<div class="col-sm-6">
			<div class="cat_bar">
			<h3 class="catbg">
					<span class="generic_icons views"></span> ', $txt['general_stats'], '
			</h3>
			</div>
				<div class="drop-shadow lifted">
						<dl class="stats">
							<dt>', $txt['average_members'], ':</dt>
							<dd>', $context['average_members'], '</dd>
							<dt>', $txt['average_posts'], ':</dt>
							<dd>', $context['average_posts'], '</dd>
							<dt>', $txt['average_topics'], ':</dt>
							<dd>', $context['average_topics'], '</dd>
							<dt>', $txt['total_boards'], ':</dt>
							<dd>', $context['num_boards'], '</dd>
							<dt>', $txt['latest_member'], ':</dt>
							<dd>', $context['common_stats']['latest_member']['link'], '</dd>
							<dt>', $txt['average_online'], ':</dt>
							<dd>', $context['average_online'], '</dd>
							<dt>', $txt['gender_ratio'], ':</dt>
							<dd>', $context['gender']['ratio'], '</dd>';

	if (!empty($modSettings['hitStats']))
		echo '
							<dt>', $txt['average_hits'], ':</dt>
							<dd>', $context['average_hits'], '</dd>';
	echo '
						</dl>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="cat_bar">
					<h3 class="catbg">
						<span class="generic_icons posters"></span> ', $txt['top_posters'], '
					</h3>
				</div>
					<div class="drop-shadow lifted">
						<div class="poll-section">';
	foreach ($context['top_posters'] as $poster)
	{
		echo '
								<strong>
									', $poster['link'], '
								</strong><span class="pull-right">', $poster['num_posts'], '</span>

								<div class="progress progress-bar-striped  active">';
		if (!empty($poster['post_percent']))
			echo '
								<div class="progress-bar progress-bar-info" style="width:', $poster['post_percent'], '%;"></div>';
		echo '
								</div>';
	}
	echo '
							</div>		
					</div>
			</div>
			<div class="col-sm-6">
				<div class="cat_bar">
					<h3 class="catbg">
							<span class="generic_icons inbox"></span> ', $txt['top_boards'], '
					</h3>
				</div>
					<div class="drop-shadow lifted">
							<div class="poll-section">';
	foreach ($context['top_boards'] as $board)
	{
		echo '
								<strong>
									', $board['link'], '
								</strong><span class="pull-right">', $board['num_posts'], '</span>

								<div class="progress progress-bar-striped  active">';
		if (!empty($board['post_percent']))
			echo '
									<div class="progress-bar progress-bar-info" style="width: ', $board['post_percent'], '%;">
									</div>';
		echo '
								</div>';
	}
	echo '
							</div>						
					</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="cat_bar">
					<h3 class="catbg">
						<span class="generic_icons replies"></span> ', $txt['top_topics_replies'], '
					</h3>
				</div>
					<div class="drop-shadow lifted">				
							<div class="poll-section">';
	foreach ($context['top_topics_replies'] as $topic)
	{
		echo '
								<strong>
									', $topic['link'], '
								</strong>
								<span class="pull-right">' . $topic['num_replies'] . '</span>

								<div class="progress progress-bar-striped  active">';
		if (!empty($topic['post_percent']))
			echo '
									<div class="progress-bar progress-bar-success" style="width: ', $topic['post_percent'], '%;">
									</div>';
		echo '
								</div>';
	}
	echo '
							</div>
					</div>
			</div>
			<div class="col-sm-6">
				<div class="cat_bar">
					<h3 class="catbg">
							<span class="generic_icons views"></span> ', $txt['top_topics_views'], '
					</h3>
				</div>
				<div class="drop-shadow lifted">
						<div class="poll-section">';

	foreach ($context['top_topics_views'] as $topic)
	{
		echo '
							<strong>', $topic['link'], '</strong>
							<span class="pull-right">' . $topic['num_views'] . '</span>
							<div class="progress progress-bar-striped  active">';

		if (!empty($topic['post_percent']))
			echo '
								<div class="progress-bar progress-bar-success" style="width: ', $topic['post_percent'] , '%;">
								</div>';
		echo '
								
							</div>';
	}
	echo '
						</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="cat_bar">
					<h3 class="catbg">
							<span class="generic_icons replies"></span>', $txt['top_starters'], '
					</h3>
				</div>
				<div class="drop-shadow lifted">
						<div class="poll-section">';
	foreach ($context['top_starters'] as $poster)
	{
		echo '
							<strong>
								', $poster['link'], '
							</strong>
							<span class="pull-right">', $poster['num_topics'], '</span>
							<div class="progress progress-bar-striped  active">';
		if (!empty($poster['post_percent']))
			echo '
								<div class="progress-bar progress-bar-danger" style="width: ', $poster['post_percent'], '%;">
								</div>';
		echo '
								
							</div>';
	}
	echo '
						</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="cat_bar">
					<h3 class="catbg">
							<span class="generic_icons history"></span> ', $txt['most_time_online'], '
					</h3>
				</div>
				<div class="drop-shadow lifted">
						<div class="poll-section">';
	foreach ($context['top_time_online'] as $poster)
	{
		echo '
							<strong>
								', $poster['link'], '
							</strong>	<span class="pull-right">', $poster['time_online'], '</span>
							<div class="progress progress-bar-striped  active">';
		if (!empty($poster['time_percent']))
			echo '
								<div class="progress-bar progress-bar-warning" style="width: ', $poster['time_percent'], '%;">
								</div>';
		echo '
							
							</div>';
	}
	echo '
						</div>
				</div>
			</div>
		</div>
		<br class="clear" />
		<div class="col-sm-12">
			<div class="cat_bar">
					<h3 class="catbg">
						<span class="generic_icons history"></span> ', $txt['forum_history'], '
					</h3>
			</div>';
	if (!empty($context['yearly']))
	{
		echo '	<div class="table-responsive">
			<table class="table small" id="stats">
			<thead>
				<tr>
					<th class="lefttext">', $txt['yearly_summary'], '</th>
					<th>', $txt['stats_new_topics'], '</th>
					<th>', $txt['stats_new_posts'], '</th>
					<th>', $txt['stats_new_members'], '</th>
					<th', empty($modSettings['hitStats']) ? ' class=""' : '', '>', $txt['smf_stats_14'], '</th>';

		if (!empty($modSettings['hitStats']))
			echo '
					<th class="last_th">', $txt['page_views'], '</th>';

		echo '
				</tr>
			</thead>
			<tbody>';
		foreach ($context['yearly'] as $id => $year)
		{
			echo '
				<tr class="" id="year_', $id, '">
					<th class="lefttext" >
						<img id="year_img_', $id, '" src="', $settings['images_url'], '/collapse.gif" alt="*" /> <a href="#year_', $id, '" id="year_link_', $id, '">', $year['year'], '</a>
					</th>
					<th>', $year['new_topics'], '</th>
					<th>', $year['new_posts'], '</th>
					<th>', $year['new_members'], '</th>
					<th>', $year['most_members_online'], '</th>';

			if (!empty($modSettings['hitStats']))
				echo '
					<th>', $year['hits'], '</th>';

			echo '
				</tr>';

			foreach ($year['months'] as $month)
			{
				echo '
				<tr class="" id="tr_month_', $month['id'], '">
					<th class="stats_month">
						<img src="', $settings['images_url'], '/', $month['expanded'] ? 'collapse.gif' : 'expand.gif', '" alt="" id="img_', $month['id'], '" /> <a id="m', $month['id'], '" href="', $month['href'], '" onclick="return doingExpandCollapse;">', $month['month'], ' ', $month['year'], '</a>
					</th>
					<th>', $month['new_topics'], '</th>
					<th>', $month['new_posts'], '</th>
					<th>', $month['new_members'], '</th>
					<th>', $month['most_members_online'], '</th>';

				if (!empty($modSettings['hitStats']))
					echo '
					<th>', $month['hits'], '</th>';

				echo '
				</tr>';

				if ($month['expanded'])
				{
					foreach ($month['days'] as $day)
					{
						echo '
				<tr class="" id="tr_day_', $day['year'], '-', $day['month'], '-', $day['day'], '">
					<td class="stats_day">', $day['year'], '-', $day['month'], '-', $day['day'], '</td>
					<td>', $day['new_topics'], '</td>
					<td>', $day['new_posts'], '</td>
					<td>', $day['new_members'], '</td>
					<td>', $day['most_members_online'], '</td>';

						if (!empty($modSettings['hitStats']))
							echo '
					<td>', $day['hits'], '</td>';

						echo '
				</tr>';
					}
				}
			}
		}

		echo '
			</tbody>
		</table>
		</div></div>
	</div>
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/stats.js"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var oStatsCenter = new smf_StatsCenter({
			sTableId: \'stats\',

			reYearPattern: /year_(\d+)/,
			sYearImageCollapsed: \'expand.gif\',
			sYearImageExpanded: \'collapse.gif\',
			sYearImageIdPrefix: \'year_img_\',
			sYearLinkIdPrefix: \'year_link_\',

			reMonthPattern: /tr_month_(\d+)/,
			sMonthImageCollapsed: \'expand.gif\',
			sMonthImageExpanded: \'collapse.gif\',
			sMonthImageIdPrefix: \'img_\',
			sMonthLinkIdPrefix: \'m\',

			reDayPattern: /tr_day_(\d+-\d+-\d+)/,
			sDayRowClassname: \'\',
			sDayRowIdPrefix: \'tr_day_\',

			aCollapsedYears: [';

		foreach ($context['collapsed_years'] as $id => $year)
		{
			echo '
				\'', $year, '\'', $id != count($context['collapsed_years']) - 1 ? ',' : '';
		}

		echo '
			],

			aDataCells: [
				\'date\',
				\'new_topics\',
				\'new_posts\',
				\'new_members\',
				\'most_members_online\'', empty($modSettings['hitStats']) ? '' : ',
				\'hits\'', '
			]
		});
	// ]]></script>';
	}
}

?>