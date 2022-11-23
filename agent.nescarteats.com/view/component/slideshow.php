<?php
$categories = new General('categories');
$cart = new Cart();

$slideshows = $data ? $data['data'] : null;
?>

<?php if ($slideshows) { ?>
<style>
    .carousel-cell{
        width: 100%;
    }
        /* position dots up a bit */
    .slideshows-slider .flickity-page-dots {
      bottom: 25px;
    }
</style>
    <section class="col-lg-5 slideshow-component list">
        <div class="slideshows-slider">
            <?php foreach ($slideshows as $slideshow) { ?>
                <a href="<?= $slideshow->link ?>" class="d-block shadow item mb-4 carousel-cell">
                    <img  src="assets/images/category/burger.jpg" data-flickity-lazyload="assets/images/slideshow/<?= $slideshow->image ?>" alt="<?= $slideshow->title ?>" class="img-fluid rounded carousel-image">
                </a>
            <?php } ?>
        </div>
    </section>
<?php } ?>