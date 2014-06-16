<?php echo $header; ?>
<h1>Step 3 - Configuration</h1>
<div id="column-right">
  <ul>
    <li>License</li>
    <li>Pre-Installation</li>
    <li><b>Configuration</b></li>
    <li>Finished</li>
  </ul>
</div>
<div id="content">
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <p>1. Please enter your database connection details.</p>
    <fieldset>
      <table class="form">
        <tr>
          <td>Database Driver:</td>
          <td><select name="db_driver">
              <option value="mysql">MySQL</option>
            </select></td>
        </tr>
        <tr>
          <td><span class="required">*</span> Database Host:</td>
          <td><input type="text" name="db_host" value="<?php echo $db_host; ?>" />
            <br />
            <?php if ($error_db_host) { ?>
            <span class="required"><?php echo $error_db_host; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> User:</td>
          <td><input type="text" name="db_user" value="<?php echo $db_user; ?>" />
            <br />
            <?php if ($error_db_user) { ?>
            <span class="required"><?php echo $error_db_user; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><input type="text" name="db_password" value="<?php echo $db_password; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> Database Name:</td>
          <td><input type="text" name="db_name" value="<?php echo $db_name; ?>" />
            <br />
            <?php if ($error_db_name) { ?>
            <span class="required"><?php echo $error_db_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td>Database Prefix:</td>
          <td><input type="text" name="db_prefix" value="<?php echo $db_prefix; ?>" />
            <br />
            <?php if ($error_db_prefix) { ?>
            <span class="required"><?php echo $error_db_prefix; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </fieldset>
    <p>2. Please enter your CAS server details.</p>
    <fieldset>
      <table class="form">
	<tr>
	  <td><span class="required">*</span> CAS host:</td>
          <td><input type="text" name="cas_host" value="<?php echo $cas_host; ?>" />
            <br />
            <?php if ($error_cas_host) { ?>
            <span class="required"><?php echo $error_cas_host; ?></span>
            <?php } ?></td>
	</tr>
	<tr>
	  <td>CAS context:</td>
          <td><input type="text" name="cas_context" value="<?php echo $cas_context; ?>" />
            <br />
	</tr>
	<tr>
	  <td><span class="required">*</span> CAS port:</td>
          <td><input type="text" name="cas_port" value="<?php echo $cas_port; ?>" />
            <br />
            <?php if ($error_cas_port) { ?>
            <span class="required"><?php echo $error_cas_port; ?></span>
            <?php } ?></td>
	</tr>
      </table>
    </fieldset>
    <p>3. Please enter your LDAP server details.</p>
    <fieldset>
      <table class="form">
	<tr>
	  <td><span class="required">*</span> LDAP host:</td>
          <td><input type="text" name="ldap_host" value="<?php echo $ldap_host; ?>" />
            <br />
            <?php if ($error_ldap_host) { ?>
            <span class="required"><?php echo $error_ldap_host; ?></span>
            <?php } ?></td>
	</tr>
	<tr>
	  <td><span class="required">*</span> LDAP search base:</td>
          <td><input type="text" name="ldap_search_base" value="<?php echo $ldap_search_base; ?>" />
            <br />
            <?php if ($error_ldap_search_base) { ?>
            <span class="required"><?php echo $error_ldap_search_base; ?></span>
            <?php } ?></td>
	</tr>
      </table>
    </fieldset>
    <p>4. Please enter your Sysaid server details.</p>
    <fieldset>
      <table class="form">
	<tr>
	  <td><span class="required">*</span> Sysaid Server:</td>
          <td><input type="text" name="sysaid_host" value="<?php echo $sysaid_host; ?>" />
            <br />
            <?php if ($error_sysaid_host) { ?>
            <span class="required"><?php echo $error_sysaid_host; ?></span>
            <?php } ?></td>
	</tr>
	<tr>
	  <td><span class="required">*</span> Sysaid Account:</td>
          <td><input type="text" name="sysaid_account" value="<?php echo $sysaid_account; ?>" />
            <br />
            <?php if ($error_sysaid_account) { ?>
            <span class="required"><?php echo $error_sysaid_account; ?></span>
            <?php } ?></td>
	</tr>
      </table>
    </fieldset>
    <p>5. Please enter roles management details.</p>
    <fieldset>
      <table class="form">
	<tr>
	  <td><span class="required">*</span> Roles Management API URL:</td>
          <td><input type="text" name="roles_management_api" value="<?php echo $roles_management_api; ?>" />
            <br />
            <?php if ($error_roles_management_api) { ?>
            <span class="required"><?php echo $error_roles_management_api; ?></span>
            <?php } ?></td>
	</tr>
	<tr>
	  <td><span class="required">*</span> Roles Management Application Name:</td>
          <td><input type="text" name="roles_management_appname" value="<?php echo $roles_management_appname; ?>" />
            <br />
            <?php if ($error_roles_management_appname) { ?>
            <span class="required"><?php echo $error_roles_management_appname; ?></span>
            <?php } ?></td>
	</tr>
	<tr>
	  <td><span class="required">*</span> Roles Management Application Secret:</td>
          <td><input type="text" name="roles_management_secret" value="<?php echo $roles_management_secret; ?>" />
            <br />
            <?php if ($error_roles_management_secret) { ?>
            <span class="required"><?php echo $error_roles_management_secret; ?></span>
            <?php } ?></td>
	</tr>
	<tr>
	  <td><span class="required">*</span> Roles Management Application ID:</td>
          <td><input type="text" name="roles_management_appid" value="<?php echo $roles_management_appid; ?>" />
            <br />
            <?php if ($error_roles_management_appid) { ?>
            <span class="required"><?php echo $error_roles_management_appid; ?></span>
            <?php } ?></td>
	</tr>
      </table>
    </fieldset>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button">Back</a></div>
      <div class="right">
        <input type="submit" value="Continue" class="button" />
      </div>
    </div>
  </form>
</div>
<?php echo $footer; ?>