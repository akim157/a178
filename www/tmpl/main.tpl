<!DOCTYPE html>
<html lang="en">
<?=$header?>
<body>
<header id="header">
	<?=$top?>
	<?php if($_SERVER["REQUEST_URI"] == '/') { ?>
		<?=$center?>
	<?php } else { ?>
		<section id="content">
			<div class="container form-search">
				<div class="row"><?=$center?></div>
			</div>
		</section>
	<?php } ?>
</header>
<!-- scripts -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
<script src="http://js.nicdn.de/bootstrap/formhelpers/docs/assets/js/bootstrap-formhelpers-phone.format.js"></script>
<script src="http://js.nicdn.de/bootstrap/formhelpers/docs/assets/js/bootstrap-formhelpers-phone.js"></script>
</body>
</html>