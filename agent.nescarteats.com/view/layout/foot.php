<script src="assets/vendors/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>


<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
<!--<script src="https://unpkg.com/flickity-fullscreen@2/fullscreen.js"></script>-->

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script type="text/javascript" src="assets/vendors/slick/slick.min.js"></script>
<script type="text/javascript" src="assets/vendors/thellipsis.js"></script>
<script src="assets/js/oniontabs-cart.js"></script>

<?php if ($user->isLoggedIn()) { ?>
    <?php if ($user->data()->vendor) { ?>
        <?php if (Input::get('action') && Input::get('action') == 'manage-menus') { ?>
            <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

            <script type="text/javascript" src="assets/vendors/select2/select2.min.js"></script>
            <script type="text/javascript" src="assets/vendors/select2/select2.init.js"></script>
            <script type="text/javascript" type="text/javascript" src="assets/vendors/jquery.repeater/jquery.repeater.min.js"></script>

            <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
            <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
            <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
            <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
            <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
            <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>


            <script src="https://unpkg.com/@yaireo/tagify"></script>
            <script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>
            <script src="assets/js/menus.js"></script>
        <?php } ?>
        
        <script src="assets/js/vendor.js"></script>
    <?php } else { ?>

        <?php if ($user->isLoggedIn() && (Input::get('page') && Input::get('page') == 'checkout') || (Input::get('action') && Input::get('action') == 'wallet')) { ?>
            <script src="https://js.paystack.co/v1/inline.js" SameSite="None"></script>
            <script src="https://checkout.flutterwave.com/v3.js"></script>
        <?php } ?>
        
        <script src="assets/js/user.js"></script>
    <?php } ?>

    <script src="assets/js/dashboard.js"></script>
<?php } ?>

<script src="assets/js/site.js"></script>

</body>

</html>