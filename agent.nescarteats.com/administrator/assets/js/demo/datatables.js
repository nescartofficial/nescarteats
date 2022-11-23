// Call the dataTables jQuery plugin
$(document).ready(function () {
  $.fn.dataTable.moment('HH:mm MMM D, YY');
  $.fn.dataTable.moment('HH:mma MMM D, YY');
  $.fn.dataTable.moment('HH:mma MMM D, YYYY');
  // $.fn.dataTable.moment('D d, M Y H:i A');
  // $.fn.dataTable.moment('dddd, MMMM Do, YYYY');
  $('#dataTable').DataTable();

  $('.dataTable-order-5').DataTable({
    "order": [[$('.dataTable-order-5').data('pos'), $('.dataTable-order-5').data('type')]]
    // "order": [[$(this).data('pos'),, 'asc']]
  });
  $('.dataTable-order-2').DataTable({
    "order": [[$('.dataTable-order-2').data('pos'), $('.dataTable-order-2').data('type')]]
    // "order": [[$(this).data('pos'),, 'asc']]
  });
  $('.dataTable-order-3').DataTable({
    "order": [[$('.dataTable-order-3').data('pos'), $('.dataTable-order-3').data('type')]]
    // "order": [[$(this).data('pos'),, 'asc']]
  });
  $('.dataTable-order-4').DataTable({
    "order": [[$('.dataTable-order-4').data('pos'), $('.dataTable-order-4').data('type')]]
    // "order": [[$(this).data('pos'),, 'asc']]
  });
});
