(function ($) {
	'use_strict';

	function reload2home(time = 5000) {
		setTimeout(function () { // wait for 5 secs(2)
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

	$messagePoint = $('#mp_modal');
	// Messages module
	var Messaging = {
		showMessage: function ($title, $msg, $function) {
			$messagePoint.find('#mp_modal').text($title);
			$messagePoint.find('.modal-body').text($msg);
			return $messagePoint.modal('show').on('hidden.bs.modal', $function).promise();
		}
	}
	
    
	function payWithFlutterwave(datapay) {
		FlutterwaveCheckout({
// 			public_key: "FLWPUBK-aa2246b69237bb88a461ca1dac928cfa-X",
			public_key: "FLWPUBK_TEST-536f0aa81ba277d40743061e14ddfea8-X",
			tx_ref: datapay.ref,
			amount: datapay.price,
			currency: "NGN",
			payment_options: "card, mobilemoneyghana, ussd",
			callback: function (response) {
				var data = "req=" + datapay.req + "&transaction_id=" + response.transaction_id + "&ref=" + response.flw_ref + "&data=" + JSON.stringify(datapay), url = 'controllers/verifyflutterwave.php';
				processHttpRequests(url, data, 'html').then(function (results) {
					console.log(results);
					if (typeof results === 'object') {
						var result = results;
					} else {
						var result = JSON.parse(results);
					}
					if (typeof result.success == 'boolean' && result.success) {
						result.to ? window.location.replace(result.to) : window.location.replace('order-complete');
						// Messaging.showMessage('Payment successful', result.message, function (e) {
						// 	if (e.type == 'hidden') {
						// 		window.location.reload(true);
						// 	}
						// });

					} else {
						console.log(results);
						if (typeof result == 'object') {
							var $obj_str = '';
							for (var i in result) {
								$obj_str += result[i] + ', ';
							}
							window.location.reload(true);
							// Messaging.showMessage('Errors', $obj_str, function (e) {
							// 	if (e.type == 'hidden') {
							// 		$(this).text($previousText);
							// 	}
							// });
						}
					}
				});
			},
			onclose: function (incomplete) {
				if (incomplete || window.verified === false) {
					document.querySelector("#payment-failed").style.display = 'block';
				} else {
					document.querySelector("form").style.display = 'none';
					if (window.verified == true) {
						document.querySelector("#payment-success").style.display = 'block';
					} else {
						document.querySelector("#payment-pending").style.display = 'block';
					}
				}
			},
			meta: {
				price: datapay.price,
			},
			customer: {
				email: datapay.email,
				phone_number: datapay.phone,
				name: datapay.name,
			},
		});
	}

	// pay with paystack
	function payWithPayStack(datapay) {
		switch (datapay.type) {
			case 'pay':
				var handler = PaystackPop.setup({
					key: 'pk_test_79a9bcf62f6cc3dea92bb38212241b85f9b86f0a', // test
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
							console.log(results);
							if (typeof results === 'object') {
								var result = results;
							} else {
								var result = JSON.parse(results);
							}
							if (typeof result.success == 'boolean' && result.success) {
        						result.to ? window.location.replace(result.to) : window.location.replace('dashboard/order-complete');

							} else {
								console.log(results);
								if (typeof result == 'object') {
									var $obj_str = '';
									for (var i in result) {
										$obj_str += result[i] + ', ';
									}
								}
							}
						});
					},
					onClose: function () {
						//alert('');
				// 		Messaging.showMessage('Window Closed', 'Don\'t worry. We trust you can do all things later. Thank you for attempting to subscribe to a package', function (e) {
				// 			if (e.type == 'hidden') {
				// 				//$('#pnow').text('Order Now');
				// 			}
				// 		});
					}
				});
				break;

		}
		handler.openIframe();
	}

	function payWith(type = 'pay-transfer') {
		// req = type ? 'pay-transfer' : 'pay-ondelivery';
		wdata = 'req=' + type + '&rtype=html';
		//console.log(wdata);
		processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
			if (typeof result == 'object') {
				if (result.success) {
					result.to ? window.location.replace(result.to) : window.location.replace('dashboard/order-complete');
				} else {
				    $("#checkout_order").html('Proceed to payment').removeAttr('disabled');
					alertToast(result.error);
				}
			}
		});
	}

	function displayCount() {
		wdata = 'req=get-count&rtype=html';
		//console.log(wdata);
		processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
			if (typeof result == 'object') {
				if (result.success) {
					$('.cart-count').text(result.success.count);
					//alertToast(result.success.title, 'success');
				} else {
					//alertToast(result.error);
				}
			}
		});
	}

	$('body').on('click', '.add-cart', function (e) {
		e.preventDefault();
		
		const add_btn = $(this);
		
		const is_force = $(this).data('force');
		const pid = $(this).data('pid');
		const quantity = $(".item-quantity-" + pid)
		const amount = $('.cart-menu-amount')
		const total = $('.cart-total-amount')
		const amount_dp = $('.cart-total-amount-dp')
		const data = $('#addcart_form').serialize();
		if (pid) {
		    force_add = is_force ? '&force=true' : '';
			wdata = data + '&pid=' + pid + '&req=add-cart&rtype=html'+force_add;
			processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object') {
					if (result.success) {
						// console.log(e.target.parentElement);
						// e.target.parentElement.classList.remove('btn');
						$('.nav-cart').hasClass('d-none') ? $('.nav-cart').removeClass('d-none') : null;
						$('.product-quantity-controller').removeClass('d-none');
						e.target.parentElement.classList.remove('btn-primary');
						e.target.parentElement.classList.add('btn-link');
						e.target.parentElement.classList.add('active');
				        
				        // 		
						add_btn.removeClass('add-cart').addClass('btn-outline-accent').attr("data-bs-dismiss", "modal").text('Added to Cart');
						
				        // Set Counter
						quantity.val(result.success.quantity).text(result.success.quantity);
						amount.text(result.success.amount);
						total.text(result.success.total_amount);
						amount_dp.text(result.success.total_amount);

						$('.cart-count').text(result.success.count);
					} else {
					    add_btn.parent().parent().before("<p class='error-info small fs-14p text-danger mt-2 text-center'>You have meal from another restaurant in cart. if you continue, all your previous meal from cart will be removed. <a href='#' data-force='true' class='add-cart' data-pid='"+pid+"'>Do you want to continue?</a></p>");
					    setTimeout(function () { // wait for 5 secs(2)
							add_btn.parent().parent().find('p.error-info').remove();
						}, 5000);
					}
				}
			});
		}
	});

	$('#clear-cart').on('click', function (e) {
		e.preventDefault();
		wdata = '&req=clear-cart&rtype=html';
		//console.log(wdata);
		processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
			if (typeof result == 'object') {
				if (result.success) {
					$('.cart-count').text(result.success.count);
					alertToast(result.success.title, 'success');
				} else {
					alertToast(result.error);
				}
			}
		});

		reload2home(1000);
	});

	$('body').on('click', '.menu-variation', function (e) {
		e.preventDefault();
		
		variation_body = $(this).parent().parent().parent();
		
		$('.menu-variation-btn').removeClass('active');
		$(this).next().addClass('active');
		
		variation = $(this).data('id');
		menu = $(this).data('menu');
		quantity = $(".item-quantity-" + variation)

		if (variation) {
			wdata = 'menu=' + menu + '&variation=' + variation + '&req=add-variation-item&rtype=html';
			processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object') {
					if (result.success) {
						$(this).attr('checked', 'true').siblings(".btn").addClass('active');

						$('.cart-count').text(result.success.count);
						quantity.val(result.success.quantity).text(result.success.quantity);
						$('.cart-menu-amount').text(result.success.amount);
						
					} else {
					    variation_body.append("<p class='error-info small fs-14p text-danger mt-2 text-center'>Menu need to be added to cart first</p>");
					    setTimeout(function () { // wait for 5 secs(2)
							variation_body.find('p.error-info').remove();
						}, 3000);
					}
				}
			});
		}
	});

	$('body').on('click', '.menu-addon--quantity', function (e) {
		e.preventDefault();
		console.log('here')

		rq = $(this).data('rq');
		addon = $(this).data('id');
		menu = $(this).data('menu');
		quantity = $(".item-quantity-" + addon);
		addon_quantity = $(".addon-quantity-" + addon);
		type = $(this).data('type');
		
		if (addon) {
			wdata = 'menu=' + menu + '&addon=' + addon + '&type=' + type + '&req=add-addon-quantity&rtype=html';
			processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
				console.log('here');
				if (typeof result == 'object') {
					if (result.success) {
						$('.cart-count').text(result.success.count);
						quantity.val(result.success.quantity).text(result.success.quantity);
						addon_quantity.val(result.success.addon_quantity).text(result.success.addon_quantity);
						$('.cart-menu-amount').text(result.success.amount);
					} else {

					}
				}
			});
		}
	});

	$('body').on('click', '.menu-addon', function (e) {
		e.preventDefault();
		
		addon_body = $(this).parent().parent().parent();
		
		addon_btn = $(this).next();
		addon_quantity = $(this).next().find('.menu-addon-quantity');

		addon = $(this).data('id');
		menu = $(this).data('menu');
		quantity = $(".item-quantity-" + addon)
		
		if (addon) {
			wdata = 'menu=' + menu + '&addon=' + addon + '&req=add-addon-item&rtype=html';
			processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object') {
					if (result.success) {
						if(result.success.added){
							addon_btn.addClass('active');
							addon_quantity.removeClass('d-none');
						}else{
							addon_btn.removeClass('active');
							addon_quantity.addClass('d-none');
						}
						// $(this).attr('checked', 'true');

						$('.cart-count').text(result.success.count);
						quantity.val(result.success.quantity).text(result.success.quantity);
						$('.cart-menu-amount').text(result.success.amount);
					} else {
					    addon_body.append("<p class='error-info small fs-14p text-danger mt-2 text-center'>Menu need to be added to cart first</p>");
					    setTimeout(function () { // wait for 5 secs(2)
							addon_body.find('p.error-info').remove();
						}, 3000);
					}
				}
			});
		}
	});
	
	$('body').on('click', '.inc-item', function (e) {
		e.preventDefault();
		$(this).attr('disabled')

		pid = $(this).data('pid');
		quantity = $(".item-quantity-" + pid)
		amount = $('.cart-menu-amount')
		total = $('.cart-total-amount')
		amount_dp = $('.cart-total-amount-dp')

		if (pid) {
			wdata = 'pid=' + pid + '&req=inc-item&rtype=html';
			processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
				console.log('here');
				if (typeof result == 'object') {
					if (result.success) {
						//alertToast(result.success.title, 'success');
						$('.cart-count').text(result.success.count);
						$('#oniontabs-nav').find('.shopping-cart').html(result.success.dropdown);
						quantity.val(result.success.quantity).text(result.success.quantity);
						amount.text(result.success.amount);
						total.text(result.success.total_amount);
						amount_dp.text(result.success.total_amount);
					} else {

					}
				}
			});
		}
	});

	$('body').on('click', '.dec-item', function (e) {
		e.preventDefault();
		$(this).attr('disabled')

		pid = $(this).data('pid');
		quantity = $(".item-quantity-" + pid)
		amount = $('.cart-menu-amount')
		total = $('.cart-total-amount')
		amount_dp = $('.cart-total-amount-dp')

		console.log(pid)
		if (pid) {
			wdata = 'pid=' + pid + '&req=dec-item&rtype=html';
			//console.log(wdata);
			processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object') {
					if (result.success) {
						//alertToast(result.success.title, 'success');
						$('.cart-count').text(result.success.count);
						$('#oniontabs-nav').find('.shopping-cart').html(result.success.dropdown);
						quantity.val(result.success.quantity).text(result.success.quantity);
						amount.text(result.success.amount);
						total.text(result.success.total_amount);
						amount_dp.text(result.success.total_amount);
					} else {

					}
				}
			});
		}
	});

	$('.item-remove').on('click', '.cart-remove-item', function (e) {
		e.preventDefault();
		remove_btn = $(this);

		$(this).addClass('disabled')

		pid = $(this).data('pid');
		quantity = $("#item-quantity-" + pid)
		amount = $('.cart-total-amount')
		amount_dp = $('.cart-total-amount-dp')
		if (pid) {
			wdata = 'pid=' + pid + '&req=cart-remove-item&rtype=html';
			//console.log(wdata);
			processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object') {
					console.log(result)
					if (result.success) {
						$('.cart-count').text(result.success.count);

						quantity.val(result.success.quantity).text(result.success.quantity);
						amount.text(result.success.amount);
						amount_dp.text(result.success.amount_dp);
						remove_btn.parent().parent().remove(); 
						
						if(result.success.count < 1){
						    reload2home(100);
						}
						return;
					} else {
						// console.log('here');
						// alertToast(result.error);
					}
				}
			});
		}
	});

	$('#oniontabs-cart-pay').on('click', function (e) {
		e.preventDefault();
		wdata = 'req=pay-card&rtype=html';
		processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
			if (typeof result == 'object') {
				if (result.success) {
					//console.log(result);
					datapay = { 'req': result.success.req, 'email': result.success.email, 'phone': result.success.phone, 'ref': result.success.ref, 'price': result.success.amount, 'type': result.success.type, };
					payWithPayStack(datapay);
				} else {
					console.log(result);
				}
			}
		});

	});

	$("#fund-wallet").on('click', function (e) {
		e.preventDefault();

		dinfo = $('.info');
		// $(this).html("<i class='fa fa-spinner  fa-pulse'></i>");

		amount = $('#fund-amount').val();
		if (!amount) {
			dinfo.html('<p class="text-danger text-center"><i class="fa fa-times fa-2x"></i><br/>Something went wrong! Enter an amount to fund.</p>');
			setTimeout(function () { // wait for 5 secs(2)
				dinfo.html('');
			}, 5000);
		} else {

			wdata = 'amount=' + amount + '&req=fund-wallet&rtype=html';
			processHttpRequests('controllers/get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object') {
					if (result.success) {
						datapay = { 'req': result.success.req, 'email': result.success.email, 'phone': result.success.phone, 'ref': result.success.token, 'price': result.success.amount, 'type': result.success.type, 'to': result.success.to, };
				        // payWithPayStack(datapay);
				        payWithFlutterwave(datapay);
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

	$(".cart-nav").on("click", function (e) {
		e.preventDefault();
		if ($(".shopping-cart").is(':visible')) {
			console.log('here')
			$(".shopping-cart").fadeOut("fast");
		} else {
			console.log('outhere')
			$(".shopping-cart").fadeIn("fast");
		}
		// $(".shopping-cart").fadeToggle("fast");
	});

	$('.checkout_delivery').on('change', function (e) {
		if ($('#' + $(this).data('toggle')).is(':visible')) {
			$('#' + $(this).data('toggle')).removeClass('d-block').addClass('d-none');
		} else {
			$('#' + $(this).data('toggle')).removeClass('d-none').addClass('d-block');
		}
	});

	$('#checkout_order').on('click', function (e) {
		e.preventDefault();
		
		btn = $(this);
		btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...').attr('disabled', true)

		values = {};
		pay_form = $('#payment_form');

		$.each(pay_form.serializeArray(), function (i, field) {
			values[field.name] = field.value;
		});

		if (values) {
			action = 'add-payment';
			data = JSON.stringify({ 'payment': values.payment_method });

			wdata = 'data=' + data + '&req=checkout&action=' + action + '&rtype=html';
			processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object' && result.success) {
					if (result.success.method == 'card') {
						datapay = { 'req': result.success.req, 'email': result.success.email, 'phone': result.success.phone, 'ref': result.success.ref, 'price': result.success.amount, 'type': result.success.type, };
						payWithPayStack(datapay);
					} else if (result.success.method == 'card-flutterwave') {
					    datapay = { 'req': result.success.req, 'email': result.success.email, 'phone': result.success.phone, 'ref': result.success.ref, 'price': result.success.amount, 'type': result.success.type, };
						payWithFlutterwave(datapay);
					} else if (result.success.method == 'transfer') {
						payWith('pay-transfer');
					} else if (result.success.method == 'wallet') {
						payWith('pay-wallet');
					}else if (result.success.method == 'ondelivery') {
						payWith('pay-ondelivery');
					}

				} else {
        		    btn.html('Proceed to payment').removeAttr('disabled');
				// 	console.log("failed");
				}
			});
		}else{
		    btn.html('Proceed to payment').removeAttr('disabled');
		}
	});

	$('#checkout_continue').on('click', function (e) {
		e.preventDefault();
		position = $(this).data('position');
		// console.log(position);

		values = {};
		bill_form = $('#billing_form');
		delivery_form = $('#delivery_form');
		pay_form = $('#payment_form');

		if (position == 'address') {
			$.each(bill_form.serializeArray(), function (i, field) {
				values[field.name] = field.value;
			});
		} else if (position == 'delivery') {
			$.each(delivery_form.serializeArray(), function (i, field) {
				values[field.name] = field.value;
			});
		} else if (position == 'payment') {
			$.each(pay_form.serializeArray(), function (i, field) {
				values[field.name] = field.value;
			});
		}

		if (values) {
			// console.log(values)
			// if (position == 'address') {
			// 	action = 'add-bill';
			// 	data = JSON.stringify({ 'country': values.country, "city": values.city, "state": values.state, "address": values.address });
			// 	$(this).data('position', "delivery");
			// } else if (position == 'delivery') {
			// 	action = 'add-delivery';
			// 	data = JSON.stringify({ "delivery": values.options, });
			// 	$(this).data('position', "payment");
			// } else 
			if (position == 'payment') {
				action = 'add-payment';
				data = JSON.stringify({ 'payment': values.payment_method });
			}


			wdata = 'data=' + data + '&req=checkout&action=' + action + '&rtype=html';
			// console.log(wdata)
			processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
				if (typeof result == 'object' && result.success) {

					if (position == 'payment') {
						console.log('here payment')
						// console.log(result.success);
						if (result.success.method == 'card') {
							console.log('card payment')
							datapay = { 'req': result.success.req, 'email': result.success.email, 'phone': result.success.phone, 'ref': result.success.ref, 'price': result.success.amount, 'type': result.success.type, };
							payWithPayStack(datapay);
						} else if (result.success.method == 'ondelivery') {
							console.log('ondelivery')
							payWith('pay-ondelivery');
						} else if (result.success.method == 'card') {
							console.log('transfer')
							payWith();
						}
					} else {
						// if (position == 'address') {
						// 	console.log('here address')
						// 	$('#billing_form').fadeOut();
						// 	$('#delivery_form').removeClass('d-none').fadeIn(3000);
						// }
						// if (position == 'delivery') {
						// 	console.log('here address')
						// 	$('#delivery_form').fadeOut();
						// 	$('#payment_form').removeClass('d-none').fadeIn(3000);
						// }
					}

				} else {
					// $('#state-lga').html('<option value="">Select LGA</option>');
				}
			});

		}
		// console.log(position);
		// $(this).data('position', 'payment')
	});

	$('.checkout-pay-btn').on('click', function (e) {
		e.preventDefault();

		checkout_form = document.forms.checkout_form;

		checkout_form.submit();
		console.log(checkout_form);
	});

	$('.checkout-pay-btn-final').on('click', function (e) {
		e.preventDefault();
		type = $(this).data('pay-type');
		wdata = 'pay_type' + type + '&&req=checkout-pay&rtype=html';
		processHttpRequests('controllers/oniontabs-cart-get.php', wdata, 'json').then(function (result) {
			if (typeof result == 'object' && result.success) {
				if (result.success.method == 'card') {
					datapay = { 'req': result.success.req, 'email': result.success.email, 'phone': result.success.phone, 'ref': result.success.ref, 'price': result.success.amount, 'type': result.success.type, };
					payWithPayStack(datapay);
				}
			} else {

			}
		});
	});

})(jQuery);