<div class="login">
	<div class="login-title">Connexion</div>
	<form method="POST" action="<?php echo BASE_URL . "/?a=connect" ?>">
		<label for="pseudo">Pseudo :</label>
		<input type="text" id="pseudo" name="pseudo" required>

		<label for="password">Password :</label>
		<input type="password" id="password" name="password" required>

		<input type="checkbox" id="remember-box" name="remember" value="true"><label for="remember-box"> Remember</label>

		<div class="login-submit">
			<input type="submit" value="Login">
		</div>
	</form>
</div>