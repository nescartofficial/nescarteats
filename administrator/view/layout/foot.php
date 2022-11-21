<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
</div>
<!-- /main-wrapper -->

<!-- Add Message communication point by modal-->
<div class="modal fade" id="mp_modal" tabindex="-1" role="dialog" aria-labelledby="messagePoint" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content gbg rounded-0">
      <div class="modal-header bg-success py-2 rounded-0">
        <h5 class="modal-title text-light" id="message_modal_title">Thank You</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer py-2 mt-4">
        <span id='ms_action_point' class="mr-auto"></span>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Optional JavaScript -->

<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="includes/template/assets/libs/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="includes/template/assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="includes/template/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="includes/js/jquery-ui.min.js"></script>
<script src="includes/js/jquery.validate.min.js"></script>
<script src="includes/js/jquery.scrollbar.js"></script>
<script src="includes/js/jquery.scroll-reveal.js"></script>

<?php if ($user->isLoggedIn()) { ?>
  <!-- <script src="includes/vendors/datatables/jquery.dataTables.min.js"></script>
  <script src="includes/vendors/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="includes/js/demo/datatables.js"></script> -->

  <!-- <script src="https://js.paystack.co/v1/inline.js"></script> -->
  <!-- apps -->
  <script src="includes/template/dist/js/app.min.js"></script>
  <script src="includes/template/dist/js/app.init.light-sidebar.js"></script>
  <script src="includes/template/dist/js/app-style-switcher.js"></script>
  <!-- slimscrollbar scrollbar JavaScript -->
  <script src="includes/template/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
  <script src="includes/template/assets/extra-libs/sparkline/sparkline.js"></script>
  <!--Wave Effects -->
  <script src="includes/template/dist/js/waves.js"></script>
  <!--Menu sidebar -->
  <script src="includes/template/dist/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="includes/template/dist/js/custom.min.js"></script>
  <!--This page JavaScript -->
  <!--Morris JavaScript -->
  <!--c3 charts -->
  <script src="includes/template/assets/extra-libs/c3/d3.min.js"></script>
  <script src="includes/template/assets/extra-libs/c3/c3.min.js"></script>
  <script src="includes/template/dist/js/pages/dashboards/dashboard5.js"></script>

  <!--This page plugins -->
  <script src="includes/template/assets/extra-libs/DataTables/datatables.min.js"></script>
  <script src="includes/template/dist/js/pages/datatable/datatable-basic.init.js"></script>
  <script src="includes/js/demo/moment.js"></script>
  <script src="includes/js/demo/datetime-moment.js"></script>
  <script src="includes/js/demo/datatables.js"></script>

  <script src="includes/vendors/summernote2/dist/summernote-bs4.min.js"></script>
  <!-- Requires oniontabs-editor.php -->
  <script src="includes/js/oniontabs-editor.js"></script>


  <!-- This Page JS -->
  <script src="includes/template/assets/libs/select2/dist/js/select2.full.min.js"></script>
  <script src="includes/template/assets/libs/select2/dist/js/select2.min.js"></script>
  <script src="includes/template/dist/js/pages/forms/select2/select2.init.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
  <script src="includes/js/bs-custom-file-input.min.js"></script>

  <script src="includes/vendors/slugit/jquery.slugit.min.js"></script>
  <script src="includes/js/alert-order.js?vs=2.1"></script>
<?php } ?>

<script src="includes/js/site.js?vs=2.1"></script>

</body>

</html>