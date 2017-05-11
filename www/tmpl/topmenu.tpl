<div class="menu-top">
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" id="button-menu">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">A178</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<?php foreach ($items as $item) { ?>
						<li <?php if($item->link == $uri) { ?>class="active"<?php } ?>>
							<a href="<?=$item->link?>"><?=$item->title?></a>
						</li>
					<?php } ?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#" class="entrance">вход</a></li>
					<li><a href="/register.html">регистрация</a></li>
				</ul>
				<div class="auto">
					<form class="form-inline" name="auth" action="/" method="post">
						<div class="form-group">
							<label class="sr-only" for="exampleInputEmail3">Email address</label>
							<input type="email" class="form-control" id="exampleInputEmail3" placeholder="Email">
						</div>
						<div class="form-group">
							<label class="sr-only" for="exampleInputPassword3">Password</label>
							<input type="password" class="form-control" id="exampleInputPassword3" placeholder="Password">
						</div>
						<button type="submit" class="btn btn-default btn-search">Войти</button>
					</form>
				</div>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
</div> <!-- /.menu-top -->