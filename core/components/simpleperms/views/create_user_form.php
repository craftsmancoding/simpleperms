<form action="<?php print CMP_MGR_URL; ?>&action=<?php print $action; ?>" method="post">

	<label for="username" class="modxcmp_label">User Name</label>
	<input type="text" name="username" id="username" class="modxcmp_input" value="<?php print $username; ?>" />
	
	<label for="email" class="modxcmp_label">Email</label>
	<input type="text" name="email" id="email" class="modxcmp_input" value="<?php print $email; ?>" />
	
	<label for="password1" class="modxcmp_label">Password (twice)</label>
	<input type="password" name="password1" id="password1" class="modxcmp_input" value="<?php print $password1; ?>" />
	<input type="password" name="password2" id="password2" class="modxcmp_input" value="<?php print $password2; ?>" />

	<?php 
	//------------------------------------------------------------------------------
	if (!$user_type):
	//------------------------------------------------------------------------------
	?>
		<label for="user_type" class="modxcmp_label"><?php print $this->modx->lexicon('user_type'); ?> <a href="https://github.com/craftsmancoding/simpleperms/wiki/User-Types" target="_new" class="modxcmp_info">?</a></label>
		<select name="user_type" id="user_type">
			<option value="admin"<?php print $selected['admin']; ?>>Admin</option>
			<option value="editor"<?php print $selected['editor']; ?>>Editor</option>
			<option value="author"<?php print $selected['author']; ?>>Author</option>
			<option value="subscriber"<?php print $selected['subscriber']; ?>>Subscriber</option>
		</select>
	<?php
	//------------------------------------------------------------------------------
	endif;
	//------------------------------------------------------------------------------
	?>
	<br />
	<br />
	<input type="submit" value="<?php print $this->modx->lexicon('create_user'); ?>" />		

</form>