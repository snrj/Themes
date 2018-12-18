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

// This is just the basic "login" form.</strong></div>
function template_login()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>

		<form class="form-horizontal login" action="', $scripturl, '?action=login2" name="frmLogin" id="frmLogin" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
		<div class="panel panel-info">
		<div class="panel-heading"><img src="', $settings['images_url'], '/icons/login_sm.gif" alt="" class="icon" /> ', $txt['login'], '</div>
		<div class="panel-body form-group">';

	// Did they make a mistake last time?
	if (!empty($context['login_errors']))
		foreach ($context['login_errors'] as $error)
			echo '
				<div class="alert alert-danger"><strong>', $error, '</strong></div>';

	// Or perhaps there's some special description for this time?
	if (isset($context['description']))
		echo '
				<div class="alert alert-success"><strong>', $context['description'], '</strong></div>';

	// Now just get the basic information - username, password, etc.
	echo '
					<label class="control-label col-sm-3" for="sel1">', $txt['username'], ':</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" name="user" id="email" value="', $context['default_username'], '" placeholder="', $txt['username'], '"/>
    </div>
					<label class="control-label col-sm-3" for="sel1">', $txt['password'], ':</label>
    <div class="col-sm-9"><input type="password" name="passwrd" value="', $context['default_password'], '" placeholder="', $txt['password'], '" class="input_password form-control" /> </div>';

	if (!empty($modSettings['enableOpenID']))
		echo '<label class="control-label col-sm-3" for="sel1">', $txt['openid'], ':</label>
    <div class="col-sm-9"><input type="text" name="openid_identifier" class="input_text openid_login form-control" placeholder="', $txt['openid'], '" />&nbsp;<em><a href="', $scripturl, '?action=helpadmin;help=register_openid" onclick="return reqWin(this.href);" class="help">(?)</a></em></div>';

	echo '
				
					<label for="sel1" class="control-label col-sm-6">', $txt['mins_logged_in'], ':</label>
					<div class="col-sm-6"><select class="form-control" id="sel1" name="cookielength">
						<option value="60" selected="selected">', $txt['one_hour'], '</option>
						<option value="1440">', $txt['one_day'], '</option>
						<option value="10080">', $txt['one_week'], '</option>
						<option value="43200">', $txt['one_month'], '</option>
						<option value="-1">', $txt['forever'], '</option>
					</select></div><div class="text-center">
					<label for="sel1" class="checkbox-inline">
					<input type="checkbox" name="cookieneverexp"', $context['never_expire'] ? ' checked="checked"' : '', '  onclick="this.form.cookielength.disabled = this.checked;" />', $txt['always_logged_in'], '</label>';
	// If they have deleted their account, give them a chance to change their mind.
	if (isset($context['login_show_undelete']))
		echo '
					<div class="alert alert-danger">
					<strong>', $txt['undelete_account'], ':</strong>
					<input type="checkbox" name="undelete" class="input_check" /></div>';
	echo '<br/>
				<button type="submit" class="btn btn-info" value="', $txt['login'], '" >', $txt['login'], '</button>
				<p class="smalltext"><a href="', $scripturl, '?action=reminder">', $txt['forgot_your_password'], '</a></p><input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				<input type="hidden" name="hash_passwrd" value="" />
				</div></div>
		</div></form>';

	// Focus on the correct input - username or password.
	echo '
		<script type="text/javascript"><!-- // --><![CDATA[
			document.forms.frmLogin.', isset($context['default_username']) && $context['default_username'] != '' ? 'passwrd' : 'user', '.focus();
		// ]]></script>';
}

// Tell a guest to get lost or login!
function template_kick_guest()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// This isn't that much... just like normal login but with a message at the top.
	echo '
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
	<form class="form-horizontal login" action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" name="frmLogin" id="frmLogin"', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
		<div class="panel panel-danger">
		<div class="panel-heading">', $txt['warning'], '</div>';

	// Show the message or default message.
	echo '
			<div class="panel-body form-group">
				', empty($context['kick_message']) ? $txt['only_members_can_access'] : $context['kick_message'], '<br />
				', $txt['login_below'], ' <a href="', $scripturl, '?action=register">', $txt['register_an_account'], '</a> ', sprintf($txt['login_with_forum'], $context['forum_name_html_safe']), '
			</div></div>';

	// And now the login information.
	echo '
			<div class="panel panel-info">
				<div class="panel-heading">
					<img src="', $settings['images_url'], '/icons/login_sm.gif" alt="" class="icon" /> ', $txt['login'], '
			</div>
					<div class="panel-body form-group">';

	// Now just get the basic information - username, password, etc.
	echo '
					<label class="control-label col-sm-3" for="text">', $txt['username'], ':</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" name="user" id="email" value="', $context['default_username'], '"placeholder="', $txt['username'], '"/>
    </div>
					<label class="control-label col-sm-3" for="text">', $txt['password'], ':</label>
    <div class="col-sm-9"><input type="password" name="passwrd" value="', $context['default_password'], '" placeholder="', $txt['password'], '" class="input_password form-control" /> </div>';

	if (!empty($modSettings['enableOpenID']))
		echo '<label class="control-label col-sm-3" for="text">', $txt['openid'], ':</label>
    <div class="col-sm-9"><input type="text" name="openid_identifier" class="input_text openid_login form-control" placeholder="', $txt['openid'], '" />&nbsp;<em><a href="', $scripturl, '?action=helpadmin;help=register_openid" onclick="return reqWin(this.href);" class="help">(?)</a></em></div>';

	echo '
				
					<label for="sel1" class="control-label col-sm-6">', $txt['mins_logged_in'], ':</label>
					<div class="col-sm-6"><select class="form-control" id="sel1" name="cookielength">
						<option value="60" selected="selected">', $txt['one_hour'], '</option>
						<option value="1440">', $txt['one_day'], '</option>
						<option value="10080">', $txt['one_week'], '</option>
						<option value="43200">', $txt['one_month'], '</option>
						<option value="-1">', $txt['forever'], '</option>
					</select></div><div class="text-center">
					<label for="sel1" class="checkbox-inline">
					<input type="checkbox" name="cookieneverexp"', $context['never_expire'] ? ' checked="checked"' : '', '  onclick="this.form.cookielength.disabled = this.checked;" />', $txt['always_logged_in'], '</label>';
	// If they have deleted their account, give them a chance to change their mind.
	if (isset($context['login_show_undelete']))
		echo '
					<div class="alert alert-danger">
					<strong>', $txt['undelete_account'], ':</strong>
					<input type="checkbox" name="undelete" class="input_check" /></div>';
	echo '<br/>
				<button type="submit" class="btn btn-info" value="', $txt['login'], '" >', $txt['login'], '</button>
				<p class="centertext smalltext"><a href="', $scripturl, '?action=reminder">', $txt['forgot_your_password'], '</a></p><input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				<input type="hidden" name="hash_passwrd" value="" />
				</div></div>
		</div></form>';

	// Do the focus thing...
	echo '
		<script type="text/javascript"><!-- // --><![CDATA[
			document.forms.frmLogin.user.focus();
		// ]]></script>';
}

// This is for maintenance mode.
function template_maintenance()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Display the administrator's message at the top.
	echo '
<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
<form action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '"', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
	<div class="tborder login" id="maintenance_mode">
		<div class="cat_bar">
			<h3 class="catbg">', $context['title'], '</h3>
		</div>
		<p class="description">
			<img class="floatleft" src="', $settings['images_url'], '/construction.png" width="40" height="40" alt="', $txt['in_maintain_mode'], '" />
			', $context['description'], '<br class="clear" />
		</p>
		<div class="title_bar">
			<h4 class="titlebg">', $txt['admin_login'], '</h4>
		</div>
		<span class="upperframe"><span></span></span>
		<div class="roundframe">
			<dl>
				<dt>', $txt['username'], ':</dt>
				<dd><input type="text" name="user" size="20" class="input_text" /></dd>
				<dt>', $txt['password'], ':</dt>
				<dd><input type="password" name="passwrd" size="20" class="input_password" /></dd>
				<dt>', $txt['mins_logged_in'], ':</dt>
				<dd><input type="text" name="cookielength" size="4" maxlength="4" value="', $modSettings['cookieTime'], '" class="input_text" /></dd>
				<dt>', $txt['always_logged_in'], ':</dt>
				<dd><input type="checkbox" name="cookieneverexp" class="input_check" /></dd>
			</dl>
			<p class="centertext"><input type="submit" value="', $txt['login'], '" class="button_submit" /></p>
		</div>
		<span class="lowerframe"><span></span></span>
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" /><input type="hidden" name="hash_passwrd" value="" />
	</div>
</form>';
}

// This is for the security stuff - makes administrators login every so often.
function template_admin_login()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Since this should redirect to whatever they were doing, send all the get data.
	echo '
<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>

<form action="', $scripturl, $context['get_data'], '" method="post" accept-charset="', $context['character_set'], '" name="frmLogin" id="frmLogin" onsubmit="hashAdminPassword(this, \'', $context['user']['username'], '\', \'', $context['session_id'], '\');">
	<div class="tborder login" id="admin_login">
		<div class="cat_bar">
			<h3 class="catbg">
				<span class="ie6_header floatleft"><img src="', $settings['images_url'], '/icons/login_sm.gif" alt="" class="icon" /> ', $txt['login'], '</span>
			</h3>
		</div>
		<span class="upperframe"><span></span></span>
		<div class="roundframe centertext">';

	if (!empty($context['incorrect_password']))
		echo '
			<div class="error">', $txt['admin_incorrect_password'], '</div>';

	echo '
			<strong>', $txt['password'], ':</strong>
			<input type="password" name="admin_pass" size="24" class="input_password" />
			<a href="', $scripturl, '?action=helpadmin;help=securityDisable_why" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" /></a><br />
			<input type="submit" style="margin-top: 1em;" value="', $txt['login'], '" class="button_submit" />';

	// Make sure to output all the old post data.
	echo $context['post_data'], '
		</div>
		<span class="lowerframe"><span></span></span>
	</div>
	<input type="hidden" name="admin_hash_pass" value="" />
</form>';

	// Focus on the password box.
	echo '
<script type="text/javascript"><!-- // --><![CDATA[
	document.forms.frmLogin.admin_pass.focus();
// ]]></script>';
}

// Activate your account manually?
function template_retry_activate()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Just ask them for their code so they can try it again...
	echo '
		<form action="', $scripturl, '?action=activate;u=', $context['member_id'], '" method="post" accept-charset="', $context['character_set'], '">
			<div class="title_bar">
				<h3 class="titlebg">', $context['page_title'], '</h3>
			</div>
			<span class="upperframe"><span></span></span>
			<div class="roundframe">';

	// You didn't even have an ID?
	if (empty($context['member_id']))
		echo '
				<dl>
					<dt>', $txt['invalid_activation_username'], ':</dt>
					<dd><input type="text" name="user" size="30" class="input_text" /></dd>';

	echo '
					<dt>', $txt['invalid_activation_retry'], ':</dt>
					<dd><input type="text" name="code" size="30" class="input_text" /></dd>
				</dl>
				<p><input type="submit" value="', $txt['invalid_activation_submit'], '" class="button_submit" /></p>
			</div>
			<span class="lowerframe"><span></span></span>
		</form>';
}

// Activate your account manually?
function template_resend()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Just ask them for their code so they can try it again...
	echo '
		<form action="', $scripturl, '?action=activate;sa=resend" method="post" accept-charset="', $context['character_set'], '">
			<div class="title_bar">
				<h3 class="titlebg">', $context['page_title'], '</h3>
			</div>
			<span class="upperframe"><span></span></span>
			<div class="roundframe">
				<dl>
					<dt>', $txt['invalid_activation_username'], ':</dt>
					<dd><input type="text" name="user" size="40" value="', $context['default_username'], '" class="input_text" /></dd>
				</dl>
				<p>', $txt['invalid_activation_new'], '</p>
				<dl>
					<dt>', $txt['invalid_activation_new_email'], ':</dt>
					<dd><input type="text" name="new_email" size="40" class="input_text" /></dd>
					<dt>', $txt['invalid_activation_password'], ':</dt>
					<dd><input type="password" name="passwd" size="30" class="input_password" /></dd>
				</dl>';

	if ($context['can_activate'])
		echo '
				<p>', $txt['invalid_activation_known'], '</p>
				<dl>
					<dt>', $txt['invalid_activation_retry'], ':</dt>
					<dd><input type="text" name="code" size="30" class="input_text" /></dd>
				</dl>';

	echo '
				<p><input type="submit" value="', $txt['invalid_activation_resend'], '" class="button_submit" /></p>
			</div>
			<span class="lowerframe"><span></span></span>
		</form>';
}

?>