<?php
$categories = new General('categories');
$cart = new Cart();

$slideshows = $data ? $data['data'] : null;
?>

<?php if ($slideshows) { ?>
    <section class="col-lg-5 slideshow-component list">
        <div class="slideshows-slider">
            <?php foreach ($slideshows as $slideshow) { ?>
                <a href="<?= $slideshow->link ?>" class="d-block shadow item mb-4">
                    <img src="assets/images/slideshow/<?= $slideshow->image ?>" alt="<?= $slideshow->title ?>" class="img-fluid rounded">
                </a>
            <?php } ?>
        </div>
    </section>
<?php } ?>