(function ($) {
	'use_strict';

	$('.slugit').slugit();
	$('.zero_config').DataTable();

	// var quillElem = document.querySelector('form .sa-quill-control');
	// if (quillElem) {
	// 	form = document.querySelector('form');
	// 	form.onsubmit = function () {
	// 		// Populate hidden form on submit
	// 		var container = $(".ql-container");
	// 		var content = container.find(".ql-editor").html();
	// 		var field = container.data('ot-field');
	// 		if (content && field) {
	// 			// form['' + field].value = content;
	// 			quillElem.value = content;
	// 			// console.log($(form.serializeArray()));
	// 			// console.log("Submitted", $(form).serialize(), $(form).serializeArray());

	// 			// $.post(form.action,
	// 			//   $(form).serialize() + "content=" + content,
	// 			//   function (data, status) {
	// 			//     alert("Data: " + data + "\nStatus: " + status);
	// 			//   }
	// 			// );

	// 			// No back end to actually submit to!
	// 			// alert('Open the console to see the submit data!')
	// 		}
	// 		form.submit();
	// 	};
	// }

	$messagePoint = $('#mp_modal');
	// Messages module
	var Messaging = {
		showMessage: function ($title, $msg, $function) {
			$messagePoint.find('#mp_modal').text($title);
			$messagePoint.find('.modal-body').text($msg);
			return $messagePoint.modal('show').on('hidden.bs.modal', $function).promise();
		}
	}

	function reload2home(time = 5000, to = null) {
		setTimeout(function () { // wait for 5 secs(2)
			to ? location.href = to :
				location.reload(); // then reload the page.(3)
		}, time);
	}

	function processHttpRequests(url, data, re) {
		if (url && data) {
			return $.ajax({
				url: url,
				data: data,
				cache: false,
				type: 'post',
				dataType: re
			}).promise();
		}
	}

	// pay with paystack
	function payWithPayStack1(datapay) {
		switch (datapay.type) {
			case 'pay':
				var handler = PaystackPop.setup({
					key: 'pk_test_769cf3bbc6f51fadd8edbe9f295a852c3e66c109', // test
					email: datapay.email, // the customer email
					amount: parseFloat(datapay.price) * 100,
					ref: datapay.ref, // unique reference use hash uniquw
					metadata: {
						custom_fields: [
							{
								display_name: "Mobile Number", // customer name
								variable_name: "mobile_number", // 
								value: datapay.phone // phone
							}
						]
					},
					callback: function (response) {
						// response object is returned!
						//console.log(response);
						var data = "req=" + datapay.req + "&ref=" + response.reference + "&data=" + JSON.stringify(datapay), url = 'controllers/verifytransactions.php';
						//console.log(data);
						processHttpRequests(url, data, 'html').then(function (results) {
							//console.log(results);
							if (typeof results === 'object') {
								var result = results;
							} else {
								var result = JSON.parse(results);
							}
							if (typeof result.success == 'boolean' && result.success) {
								console.log(result.message);
								Messaging.showMessage('Payment successful', result.message, function (e) {
									if (e.type == 'hidden') {
										window.location.reload(true);
									}
								});

							} else {
								console.log(results);
								if (typeof result == 'object') {
									var $obj_str = '';
									for (var i in result) {
										$obj_str += result[i] + ', ';
									}

									Messaging.showMessage('Errors', $obj_str, function (e) {
										if (e.type == 'hidden') {
											$(this).text($previousText);
										}
									});
								}
							}
						});
					},
					onClose: function () {
						//alert('');
						Messaging.showMessage('Window Closed', 'Don\'t worry. We trust you can do all things later. Thank you for attempting to subscribe to a package', function (e) {
							if (e.type == 'hidden') {
								//$('#pnow').text('Order Now');
							}
						});
					}
				});
				break;

		}
		handler.openIframe();
	}

	$('.toggler').on('click', function (e) {
		e.preventDefault();
		if ($('#' + $(this).data('toggle')).is(':visible')) {
			$('#' + $(this).data('toggle')).removeClass('d-block').addClass('d-none');
		} else {
			$('#' + $(this).data('toggle')).removeClass('d-none').addClass('d-block');
		}
	});

	$('#project').on('change', function (e) {
		e.preventDefault();
		$(".project_type").addClass('d-none');
		value = $(this).val()
		console.log(value)
		if (value == "others") {
			$(".project_type").removeClass('d-none');
		} else {
			$(".project_type").addClass('d-none');
		}
	});

	$('#house-state').on('change', function (e) {
		e.preventDefault();

		state = $(this).val()
		console.log(state)

		wdata = 'stateid=' + parseInt(state) + '&req=house-lga&rtype=html';
		console.log(wdata);
		processHttpRequests('controllers/get.php', wdata, 'json').then(function (result) {

			if (typeof result == 'object' && result.success) {
				console.log(result);
				$('#state-lga').html(result.success);
			} else {
				$('#state-lga').html('<option value="">Select LGA</option>');
			}
		});
	});

	$('.menus').on('change click', function (e) {
		e.preventDefault();

		type = $(this).data('menu-type');
		append = $(this).data('menu-append');
		append_txt = $(this).data('menu-append-text') ? "<option value=''>" + $(this).data('menu-append-text') + "</option>" : null;
		target = $(this).data('menu-target');
		value = $(this).val()
		if (!value) {

		} else {
			wdata = 'value=' + parseInt(value) + '&type=' + type + '&append=' + append + '&req=menus&rtype=html';
			processHttpRequests('controllers/get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object' && result.success) {
						console.log(result);
					if (result.success.type == 'country') {
						if (result.success.append) {
							$(target).html(append_txt + result.success.data);
						} else {
							$(target).html(result.success.data);
						}
					}
					if (result.success.type == 'state') {
						$(target).html(result.success.data);
					}
				} else {
					$('.lga').html('<option value="">Select LGA</option>');
				}
			});
		}
	});

	$('.world').on('change click', function (e) {
		e.preventDefault();

		type = $(this).data('type');
		append = $(this).data('world-append');
		append_txt = $(this).data('world-append-text') ? "<option value=''>" + $(this).data('world-append-text') + "</option>" : null;
		target = $(this).data('world-target');
		value = $(this).val()
		if (!value) {

		} else {
			wdata = 'value=' + parseInt(value) + '&type=' + type + '&append=' + append + '&req=world&rtype=html';
			processHttpRequests('controllers/get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object' && result.success) {
					// 	console.log(result);
					if (result.success.type == 'country') {
						if (result.success.append) {
							$(target).html(append_txt + result.success.data);
						} else {
							$(target).html(result.success.data);
						}
					}
					if (result.success.type == 'state') {
						$(target).html(result.success.data);
					}
				} else {
					$('.lga').html('<option value="">Select LGA</option>');
				}
			});
		}
	});

	function resetState() {
		//$('.state').html('<option value="">Select State</option>');
		$('.lga').html('<option value="">Select LGA</option>');
	}
	$("#pay").on('click', function (e) {
		e.preventDefault();
		dinfo = $('.check-info');
		$(this).html("<i class='fa fa-spinner  fa-pulse'></i>");


		cid = $(this).data('cid');
		if (!cid) {
			dinfo.html('<p class="lead text-danger">Something went wrong! reload page and try again.</p>');
			setTimeout(function () { // wait for 5 secs(2)
				dinfo.html('');
				$(this).html("Pay: 2000");
			}, 5000);
		} else {

			wdata = '&req=pay&rtype=html';
			processHttpRequests('controllers/get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object') {
					if (result.success) {
						//console.dir(result.success);
						datapay = { 'req': 'pay', 'email': result.success.email, 'ref': result.success.token, 'price': result.success.amount, 'type': 'pay', };
						payWithPayStack1(datapay);
					} else {
						dinfo.addClass('text-center text-danger').text(result.error);

						setTimeout(function () { // wait for 5 secs(2)
							dinfo.html('');
							$(this).html("Pay: 2000");
						}, 5000);


					}
				}
			});
		}

	});
})(jQuery);