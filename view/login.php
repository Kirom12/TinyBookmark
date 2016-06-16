<div class="login">
	<div class="login-title">Connexion</div>
	<form method="POST" action="<?php echo BASE_URL . "/?a=connect" ?>">
		<label for="pseudo">Pseudo :</label>
		<input type="text" id="pseudo" name="pseudo">

		<label for="password">Password :</label>
		<input type="password" id="password" name="password">

		<div class="login-submit">
			<input type="submit" value="Login">
		</div>
	</form>
</div>