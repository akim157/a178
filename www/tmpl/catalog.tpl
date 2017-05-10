<section id="content">
    <div class="container content_catalog">
        <div class="row">
            <div class="col-md-12">
                <h1>Каталог</h1>
                <div class="row margin-call">
                    <?php foreach($catalog as $cat) { ?>
                        <div class="col-md-2 cell-col">
                            <a href="<?=$cat->link?>"><span class="cell-catalog"><?=$cat->name?></span></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>