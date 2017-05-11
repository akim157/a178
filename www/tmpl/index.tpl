<div class="section-table">
	<div class="section-row">
		<div class="section-cell">
			<div class="section-center form-search">
				<h2>Найти нужную деталь</h2>
				<div class="alert alert-warning warning-search">
					<span class="close">X</span>
					<strong>Предупреждение!</strong> Вы не ввели данные в строку поиска.
				</div>
				<div class="alert alert-warning warning-extended-search">
					<span class="close">X</span>
					<strong>Предупреждение!</strong> Вы не выбрали данные в фильтре.
				</div>
				<form class="form-inline" name="search" action="/search.html" method="post" onSubmit="return validatorSearch()">
					<div class="form-group">
						<label class="sr-only" for="search">Email address</label>
						<input type="search" class="form-control" id="search" placeholder="поиск" name="query">
					</div>
					<button type="submit" class="btn btn-default btn-search">поиск</button>
				</form>
				<a href="#" class="click-search">Расширенный поиск</a>
				<div class="main-search">
					<form class="form-inline" name="extended_search" action="/extesearch.html" method="post" onSubmit="return validatorExtendedSearch()">
						<div class="form-group">
							<select name="marka" id="marka" class="form-control">
								<option value="0">Марка авто</option>
								<?php foreach($blog->marki as $marka) { ?>
									<option value="<?=$marka->id?>"><?=$marka->name?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<select name="model" id="model-avto" class="form-control" disabled>
								<option value="0">Модель авто</option>
							</select>
						</div>
						<!--<div class="form-group">
							<select name="group" id="group-avto" class="form-control" disabled>
								<option value="0">Группа деталей</option>
								<option value="2">Группа деталей 2</option>
								<option value="3">Группа деталей 3</option>
								<option value="4">Группа деталей 4</option>
								<option value="5">Группа деталей 5</option>
							</select>
						</div>
						<div class="form-group">
							<select name="detail" id="detail-avto" class="form-control" disabled>
								<option value="0">Деталь</option>
								<option value="2">Деталь 2</option>
								<option value="3">Деталь 3</option>
								<option value="4">Деталь 4</option>
								<option value="5">Деталь 5</option>
							</select>
						</div>-->
						<a href="#" class="reset-filter">Сбросить фильтры</a>
						<button type="submit" class="btn btn-default btn-search">найти</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div> <!-- /.section-table -->