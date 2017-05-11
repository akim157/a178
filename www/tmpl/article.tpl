<div class="col-md-12">
	<!--<h1>АМОРТИЗАТОР ДВЕРИ БАГАЖНИКА 74820STXA212M1</h1>-->
	<h1><?=$article->title?></h1>
	<div class="row">
		<div class="col-md-4 pop">
			<img src="<?=$article->img?>" alt="img" style="cursor: pointer;">
		</div>
		<div class="col-md-6">
			<p><b>Наименование:</b> <span><?=$article->title?></span></p>
			<p><b>Артикул:</b> <span><?=$article->number?></span></p>
			<!--<p>Производитель: <span>Honda</span></p>-->
			<p><b>Состояние:</b> <span><?=$article->state?></span></p>
			<p><b>Описание:</b> <span><?=$article->full?></span></p>
		</div>
	</div>
	<!--<div class="row">
		<div class="col-md-12">
			<p>Описание: <span><?=$article->full?></span></p>
		</div>
	</div>-->

	<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<img src="" class="imagepreview" style="width: 100%;" >
				</div>
			</div>
		</div>
	</div>
</div>