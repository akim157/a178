<div class="uzer_panel">
	<header>
		<h2 class="fon_head">Панель пользователя</h2>
	</header>
	<div class="user_content">
		<p class="center">Здравствуйте,<br /><b><?=$user->name?></b>!</p>
		<p class="center">
			<img src="<?=$user->avatar?>" alt="<?=$user->login?>" class="avatar" />
		</p>
		<nav>
			<?php foreach ($items as $item) { ?>
				<div>
					<i class="arrow">&nbsp;&nbsp;</i>
					<a <?php if ($item->link == $uri) { ?>class="active"<?php } ?> href="<?=$item->link?>"><?=$item->title?></a>
				</div>
			<?php } ?>
		</nav>
	</div>
</div>