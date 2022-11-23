(function ($) {
    'use_strict';
    function reloadto(to = null, time = 5000) {
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

    function messagingModal(id = null, link = null, text = 'Display text', title = 'success', icn = 'error', cBtn = true) {
        swal.fire({
            title: title,
            text: text,
            type: 'success',
            showCancelButton: cBtn,
            confirmButtonColor: '#fe6101',
            cancelButtonColor: '#d33',
            confirmButtonText: 'View',
        }).then((result) => {
            if (result.value) {
                // wdata = 'id=' + id + '&req=set-messaging&rtype=html';
                reloadto(link, 0);
            }
        })
    }

    function showMessaging() {
        wdata = 'req=get-order&rtype=html';
        //console.log(wdata);
        processHttpRequests('controllers/get.php', wdata, 'json').then(function (result) {
            if (typeof result == 'object') {
                if (result.success) {
                    // setInterval(function () { showMessaging() }, 5000);
                    messagingModal(result.success.id, result.success.link, result.success.message, result.success.title, 'success');
                } else {
                    //alertToast(result.error);
                }
            }
        });
    }


    function sendOrderDelivery() {
        wdata = 'req=send-delivr-order&rtype=html';
        processHttpRequests('controllers/get.php', wdata, 'json').then(function (result) {
            if (typeof result == 'object') {
                if (result.success) {
                    wdata = 'order_id=' + result.success.order_id +
                        '&key=' + result.success.key +
                        '&invoice=' + result.success.invoice +
                        '&user=' + result.success.user +
                        '&details=' + result.success.details +
                        '&req=add-delivery-foma&rtype=html';
                    processHttpRequests('http://localhost/project/personal/delivr/api/get.php', wdata, 'json').then(function (result) {
                        if (typeof result == 'object') {
                            if (result.success) {
                                wdata = 'order_id=' + result.success.order_id + '&req=update-delivr&status=2&rtype=html';
                                processHttpRequests('controllers/get.php', wdata, 'json').then(function (result) {
                                    if (typeof result == 'object') {
                                        if (result.success) {
                                            console.log('save');
                                        }
                                    } else {
                                        console.log('not sent');
                                    }
                                });
                            } else {
                                console.log(result.error);

                            }
                        }
                    });
                    // setInterval(function () { showMessaging() }, 5000);
                    // messagingModal(result.success.id, result.success.link, result.success.message, result.success.title, 'success');
                } else {
                    //alertToast(result.error);
                }
            }
        });
    }

    setInterval(function () { sendOrderDelivery() }, 20000);
    setInterval(function () { showMessaging() }, 20000);

})(jQuery);