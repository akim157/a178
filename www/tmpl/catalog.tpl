<div class="col-md-12">
    <h1>Каталог</h1>
    <div class="row margin-call">
        <?php foreach($catalog as $cat) { ?>
            <div class="col-md-2 cell-col">
                <a href="<?=$cat->link?>" data-toggle="tooltip" data-placement="bottom" title="<?=$cat->name?>"><span class="cell-catalog"><?=$cat->name?></span></a>
            </div>
        <?php } ?>
        <?php if(!$catalog) { ?>
            <h4>Нет данных</h4>
        <?php } ?>
    </div>
</div>