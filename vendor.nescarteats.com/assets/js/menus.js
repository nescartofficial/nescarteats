// some scripts

// jquery ready start
$(document).ready(function () {
  // Choices -
  const element = document.querySelector(".js-choice");
  const choices = element ? new Choices(element) : null;

  $(".repeater").repeater({
    defaultValues: {
      "textarea-input": "foo",
    },
    show: function () {
      $(this).slideDown();
    },
    hide: function (deleteElement) {
      if (confirm("Are you sure you want to delete this element?")) {
        $(this).slideUp(deleteElement);
      }
    },
    ready: function (setIndexes) {},
  });

  window.outerRepeater = $(".outer-repeater").repeater({
    isFirstItemUndeletable: true,
    defaultValues: { "text-input": "outer-default" },
    show: function () {
      console.log("outer show");
      $(this).slideDown();
    },
    hide: function (deleteElement) {
      console.log("outer delete");
      $(this).slideUp(deleteElement);
    },
    repeaters: [
      {
        isFirstItemUndeletable: true,
        selector: ".inner-repeater",
        defaultValues: { "inner-text-input": "inner-default" },
        show: function () {
          console.log("inner show");
          $(this).slideDown();
        },
        hide: function (deleteElement) {
          console.log("inner delete");
          $(this).slideUp(deleteElement);
        },
      },
    ],
  });

  // Tagify
  // The DOM element you wish to replace with Tagify
  var input = document.querySelector(".ot-tagify");
  // initialize Tagify on the above input node reference
  new Tagify(input);

  // Register the plugin
  const inputElement = document.querySelector('input[type="file"].filepond');
  if (inputElement) {
    FilePond.registerPlugin(
      FilePondPluginImagePreview,
      FilePondPluginImageExifOrientation,
      FilePondPluginFileValidateSize,
      FilePondPluginFileValidateType,
      FilePondPluginImageResize
    );
    // Get a reference to the file input element

    // Create a FilePond instance
    const pond = FilePond.create(inputElement, {
      credits: false,
      onaddfilestart: (file) => {
        isLoadingCheck();
      },
      onprocessfile: (files) => {
        isLoadingCheck();
      },
      server: "controllers/filepond.php",

      onreorderfiles(files, origin, target) {
        url = "controllers/filepond.php";
        wdata = "filename=" + files[0].filename + "&rq=cover&rtype=html";
        processHttpRequests(url, wdata, "json").then(function (result) {
          if (typeof result == "object" && result.success) {
            // console.log('cover set')
          }
        });
      },
      //         onupdatefiles(files){
      //             // console.log(pond.getFiles());
      //             url = inputElement.hasAttribute('data-classified') ? 'controllers/filepond-classified.php' : 'controllers/filepond.php';
      //             wdata = 'filename=' + files[0].filename + '&rq=cover&rtype=html';
      //             processHttpRequests(url, wdata, 'json').then(function (result) {
      // 				if (typeof result == 'object' && result.success) {
      // 				    // console.log('cover set')
      // 				}
      // 			});
      //         },
    });

    function isLoadingCheck() {
      var isLoading =
        pond.getFiles().filter((x) => x.status !== 5).length !== 0;
      if (isLoading) {
        $('button[type="submit"]').attr("disabled", "disabled");
      } else {
        $('button[type="submit"]').removeAttr("disabled");
      }
    }
    pond.storeAsFile = true;
  }
});
// jquery end
