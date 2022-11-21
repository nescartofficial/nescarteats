(function ($) {
    'use_strict';

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
    
    function uploadImage(image) {
        var data = new FormData();
        data.append("image", image);
        console.log(data);
        $.ajax({
            url: 'controllers/oniontabs-editor.php',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: "post",
            success: function (url) {
                var image = $('<img>').attr('src', url);

                console.log(image);
                $('.summernote').summernote("insertNode", image[0]);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    $('.summernote').summernote({
        height: 300,
        callbacks: {
            onImageUpload: function (image) {
                //send = sendFile(files[0], editor, welEditable);
                uploadImage(image[0]);
                // console.log(send);
                //$summernote.summernote('insertNode', imgNode);
            }
        }
    });
    /************************************/
    //inline-editor
    /************************************/
    $('.inline-editor').summernote({
        airMode: true
    });

    /************************************/
    //edit and save mode
    /************************************/
    window.edit = function () {
        $(".click2edit").summernote()
    },
        window.save = function () {
            $(".click2edit").summernote('destroy');
        }

    var edit = function () {
        $('.click2edit').summernote({
            focus: true
        });
    };

    var save = function () {
        var markup = $('.click2edit').summernote('code');
        $('.click2edit').summernote('destroy');
    };

    /************************************/
    //airmode editor
    /************************************/
    $('.airmode-summer').summernote({
        airMode: true
    });
})(jQuery);