// some scripts

// jquery ready start
$(document).ready(function () {
  // jQuery code

  // var html_download = '<a href="http://bootstrap-ecommerce.com/templates.html" class="btn btn-dark rounded-pill" style="font-size:13px; z-index:100; position: fixed; bottom:10px; right:10px;">Download theme</a>';
  //  $('body').prepend(html_download);

  //////////////////////// Prevent closing from click inside dropdown
  $(document).on("click", ".dropdown-menu", function (e) {
    e.stopPropagation();
  });

  ///////////////// fixed menu on scroll for desctop
  if ($(window).width() < 768) {
    $(".nav-home-aside .title-category").click(function (e) {
      e.preventDefault();
      $(".menu-category").slideToggle("fast", function () {
        $(".menu-category .submenu").hide();
      });
    });

    $(".has-submenu a").click(function (e) {
      e.preventDefault();
      $(this).next().slideToggle("fast");
    });
  } // end if

  function processHttpRequests(url, data, re, type = "post") {
    if (url && data) {
      return $.ajax({
        url: url,
        data: data,
        cache: false,
        type: type,
        dataType: re,
      }).promise();
    }
  }

  // Go Back
  $(".hide-eyedropper").on("click", function (e) {
    e.preventDefault();
    value = $(this).data("value");
    elem = $($(this).data("elem"));
    if ($(this).hasClass("fa-eye-slash")) {
      elem.text("***");
      $(this).removeClass("fa-eye-slash").addClass("fa-eye");
    } else {
      elem.text(value);
      $(this).removeClass("fa-eye").addClass("fa-eye-slash");
    }
  });

  // Slideshows
  $(".single-slider").slick({
    infinite: false,
    arrows: false,
    dots: true,
    slidesToShow: 1,
  });

  $(".default-slider").slick({
    infinite: false,
    arrows: false,
    slidesToShow: 2,
  });

  $(".slideshows-slider").slick({
    autoplay: true,
    infinite: false,
    arrows: false,
    slidesToShow: 1,
  });

  $(".category-slider").slick({
    infinite: false,
    arrows: false,
    slidesToShow: 2,
  });

  $(".menu-slider").slick({
    infinite: false,
    arrows: false,
    slidesToShow: 1,
  });

  $(".menu-slider--image").slick({
    autoplay: true,
    autoplaySpeed: 6000,
    infinite: false,
    arrows: false,
    slidesToShow: 1,
    centerMode: true,
  });

  $(".menu-slider--item").slick({
    autoplay: true,
    autoplaySpeed: 6000,
    infinite: false,
    arrows: false,
    slidesToShow: 1,
    centerMode: true,
  });

  $(".subscription-slider").slick({
    infinite: false,
    arrows: false,
    dots: false,
    slidesToShow: 1,
    centerMode: true,
  });
});
// jquery end
