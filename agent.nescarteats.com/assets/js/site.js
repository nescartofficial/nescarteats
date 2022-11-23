(function ($) {
  "use_strict";
  
  // thelipsis
  const text_truncate = document.getElementsByClassName("thellipsis");
  window.onresize = function () {
    Array.prototype.forEach.call(text_truncate, function (element) {
        console.log(element.getAttribute('data-thellipsis-line'))
        line = element.hasAttribute('data-thellipsis-line') ? parseInt(element.getAttribute('data-thellipsis-line')) : 1;
      thellipsis(element, line, false);
    });
  };
  window.dispatchEvent(new Event("resize"));

  // Go Back
  $("#back_button").on("click", function (e) {
    e.preventDefault();
    window.history.back();
  });

  //   Save item to favourite
  $("body").on("click", ".add-favourite", function (e) {
    e.preventDefault();

    icon_path = $(this).find("#Favourite_Outline");
    type = $(this).data("type");
    id = $(this).data("id");
    if (!id && !type) {
    } else {
      wdata = "id=" + id + "&type=" + type + "&req=add-favourite&rtype=html";
      processHttpRequests("controllers/get.php", wdata, "json").then(function (
        result
      ) {
        if (typeof result == "object" && result.success) {
          result.success.toggle
            ? icon_path.attr("fill", "#ef9244")
            : icon_path.attr("fill", "none");
        }
      });
    }
  });

  $(".togglePassword").on("click", function (e) {
    e.preventDefault();
    elem = $($(this).data("field"));
    if ($(this).hasClass("fa-eye-slash")) {
      elem.attr("type", "password");
      $(this).removeClass("fa-eye-slash").addClass("fa-eye");
    } else {
      elem.attr("type", "text");
      $(this).removeClass("fa-eye").addClass("fa-eye-slash");
    }
  });

  // Slide show
//   $(".menu-slider--image").slick({
//     autoplay: true,
//     autoplaySpeed: 6000,
//     infinite: false,
//     arrows: false,
//     slidesToShow: 1,
//     centerMode: true,
//   });

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll(".needs-validation");
  // Loop over them and prevent submission
  Array.from(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add("was-validated");
      },
      false
    );
  });

  // Tooltip
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );

  // Equal Height
  $(".nav-spacer").css({
    "padding-bottom": $(".ot-nav-top").outerHeight() / 15 + "rem",
  });

  function reload2home(time = 5000, to = null) {
    setTimeout(function () {
      // wait for 5 secs(2)
      to ? (location.href = to) : location.reload(); // then reload the page.(3)
    }, time);
  }

  function processHttpRequests(url, data, re) {
    if (url && data) {
      return $.ajax({
        url: url,
        data: data,
        cache: false,
        type: "post",
        dataType: re,
      }).promise();
    }
  }

  $(".toggler").on("click change", function (e) {
    e.preventDefault();
    if ($("#" + $(this).data("toggle")).is(":visible")) {
      $("#" + $(this).data("toggle"))
        .removeClass("d-block")
        .addClass("d-none");
    } else {
      $("#" + $(this).data("toggle"))
        .removeClass("d-none")
        .addClass("d-block");
    }
  });

  $(".world").on("change click", function (e) {
    e.preventDefault();

    type = $(this).data("type");
    append = $(this).data("world-append");
    append_txt = $(this).data("world-append-text")
      ? "<option value=''>" + $(this).data("world-append-text") + "</option>"
      : "<option value=''>All</option>";
    target = $(this).data("world-target");
    value = $(this).val();
    if (!value) {
    } else {
      wdata =
        "value=" +
        parseInt(value) +
        "&type=" +
        type +
        "&append=" +
        append +
        "&req=world&rtype=html";
      processHttpRequests("controllers/get.php", wdata, "json").then(function (
        result
      ) {
        if (typeof result == "object" && result.success) {
          // 	console.log(result);
          if (result.success.type == "country") {
            if (result.success.append) {
              $(target).html(append_txt + result.success.data);
            } else {
              $(target).html(result.success.data);
            }
          }
          if (result.success.type == "state") {
            $(target).html(result.success.data);
          }
        } else {
          $(".lga").html('<option value="">Select LGA</option>');
        }
      });
    }
  });

  const menuModal = document.getElementById("menuModal");
  if (menuModal) {
    menuModal.addEventListener('hidden.bs.modal', function (event) {
        // Loader
        $("#menuModal .loader").removeClass('d-none');
    })
    menuModal.addEventListener("show.bs.modal", (event) => {
      // Button that triggered the modal
      const button = event.relatedTarget;
      // Extract info from data-bs-* attributes
      const menu = button.getAttribute("data-menu");
      // const menu = JSON.parse(button.getAttribute("data-menu"));

      // If necessary, you could initiate an AJAX request here
      // and then do the updating in a callback.
      //
      // Update the modal's content.

      if (menu) {
        const modalTitle = menuModal.querySelector(".modal-title");
        const modalBodyInput = menuModal.querySelector(".modal-body input");

        // menuModal.querySelector("#lecture_id").value = lecture_id;

        wdata = "menu=" + parseInt(menu) + "&rq=menu&rtype=html";
        processHttpRequests("controllers/get-menu.php", wdata, "json").then(
          function (result) {
            if (typeof result == "object" && result.success) {
              // 	console.log(result);
            //   console.log($("[data-menu-id]"));

              // Loader
              $("#menuModal .loader").addClass('d-none');
              // Title
              menuModal.querySelector(".title").innerHTML =
                result.success.title;
              // Category
              menuModal.querySelector(".category").innerHTML =
                result.success.category;
              // Price
              menuModal.querySelector(".price").innerHTML =
                result.success.price;
              // Quantitybtn
              menuModal.querySelector(".quantitybtn").innerHTML =
                result.success.quantitybtn;
              // Favourite
              menuModal.querySelector(".add-favourite").setAttribute('data-id', menu);
              menuModal.querySelector("#Favourite_Outline").setAttribute("fill", result.success.saved_fill);

              // Description
              menuModal.querySelector(".description").innerHTML =
                result.success.description;
              // Load Images
              menuModal.querySelector(".images").innerHTML =
                result.success.images;
                
                if(result.success.addons){
                    // addons
                    menuModal.querySelector(".addons").parentElement.classList.remove('d-none');
                    menuModal.querySelector(".addons").innerHTML =
                    result.success.addons;
                }else{
                    menuModal.querySelector(".addons").parentElement.classList.add('d-none');
                }
                
                if(result.success.variations){
                    // variations
                    menuModal.querySelector(".variations").parentElement.classList.remove('d-none');
                    menuModal.querySelector(".variations").innerHTML =
                    result.success.variations;
                }else{
                    menuModal.querySelector(".variations").parentElement.classList.add('d-none');
                }
                
              // totalamount
              menuModal.querySelector(".cart-menu-amount").innerHTML =
                result.success.totalamount;
              // vendor
              menuModal
                .querySelector(".vendor-slug")
                .setAttribute("href", result.success.vendor);
              // Cart
              menuModal.querySelector(".cart-container").innerHTML =
                result.success.cartbtn;
            } else {
              $(".lga").html('<option value="">Select LGA</option>');
            }
          }
        );

        // if (topic) {
        //   menuModal.querySelector("#topic").value = topic.topic;
        //   menuModal.querySelector("#video").value = topic.video;
        //   menuModal.querySelector("#description").value = topic.description;
        //   menuModal.querySelector("#id").value = topic.id;
        // }

        console.log("edit topic");
      }

      //   modalTitle.textContent = `New message to ${lecture_id}`;
      //   modalBodyInput.value = lecture_id;
    });
  }
})(jQuery);
