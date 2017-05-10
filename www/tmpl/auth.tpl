<div id="authorization">
	<header>
		<h2 class="fon_head">Авторизация:</h2>
	</header>
	<div id="authorization_form">
		<?php if($message) {?><span class="message"><?=$message?></span><?php } ?>
		<form name="auth" action="<?=$action?>" method="post">
			<p>
				<label class="cursor" for="login">Логин:</label>
			</p>
			<p>
				<input type="text" name="login" id="login" />
			</p>
			<p>
				<label class="cursor" for="password">Пароль:</label>
			</p>
			<p>
				<input type="password" name="password" id="password" />
			</p>
			<p>
				<input type="submit" class="sub" name="auth" value="Войти" />
			</p>
		</form>
		<p>
			<a href="<?=$link_register?>" id="registration">Регистрация</a>
		</p>
		<p>
			<a href="<?=$link_reset?>" id="forgotYourPassword">Забыли пароль?</a>
		</p>
		<p>
			<a href="<?=$link_remind?>">Забыли логин?</a>
		</p>
	</div>
</div>