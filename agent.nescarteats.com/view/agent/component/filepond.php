<?php
$item = $data && isset($data['item']) ? $data['item'] : null;
$classified = $data && isset($data['classified']) ? $data['classified'] : null;

$images = $item ? explode(',', $item->image) : null;
$cover = $item ? $item->cover : null;
?>

<style>
    .filepond--item {
        width: calc(50% - 0.5em);
    }

    @media (min-width: 30em) {
        .filepond--item {
            width: calc(50% - 0.5em);
        }
    }

    @media (min-width: 50em) {
        .filepond--item {
            width: calc(33.33% - 0.5em);
        }
    }
</style>
<div class="col-md-12 mb-3">
    <p class="form-text mb-3">The first picture - is the title picture. You can change the order of photos: just grab your photos and drag</p>
    <p class="form-text m-0">Each picture must not exceed 6 Mb</p>
    <p class="form-text m-0">Supported formats are *.jpg, *.gif and *.png</p>
</div>

<?php if ($images) {  ?>
    <ul class="row">
        <?php foreach ($images as $k => $v) { ?>
            <div class="col-6 col-lg-2 mb-3">
                <label>
                    <input name="cover_image" value="<?= trim($v) ?>" data-type="local" <?= $cover == $v ? 'checked' : null; ?> type="radio" /> <?= $cover == $v ? "Cover" : 'Choose'; ?>
                    <img src="assets/images/menus/<?= trim($v) ?>" class="img-fluid" style="height: 104px; width: 104px; object-fit: cover;">
                </label>
            </div>
        <?php } ?>
    </ul>
<?php } ?>

<input type="file" data-label="Upload" name="file" class="filepond" accept="image/jpeg, image/png," id="file" multiple data-allow-reorder="true" data-max-file-size="6MB" data-min-files="5" data-max-files="20" <?= $classified ? 'data-classified="yes"' : null; ?> <?= $item ? null : 'required'; ?> />

<label class="d-none" for="file">Choose menus Cover</label>