jQuery(document).ready(function ($) {
  var latitudeInput = $("#mwplg_maps_woocommerce_map_default_latitude");
  var longitudeInput = $("#mwplg_maps_woocommerce_map_default_longitude");

  if (latitudeInput.val() === "") {
    latitudeInput.val("0");
  }

  if (longitudeInput.val() === "") {
    longitudeInput.val("0");
  }

  latitudeInput.on("input", function () {
    sanitizeAndUpdateInput(latitudeInput);
  });

  longitudeInput.on("input", function () {
    sanitizeAndUpdateInput(longitudeInput);
  });

  $("select#mwplg_maps_woocommerce_map_marker")
    .on("change", function () {
      toggleCustomImageWrapper($(this).val());
    })
    .trigger("change");

  toggleRemoveCustomMarker();

  $("#maps_woocommerce_upload_custom_marker").on("click", function (e) {
    e.preventDefault();
    uploadCustomMarkerMedia();
  });

  var form = $("#mainform");
  form.find("input").on("keydown", function (e) {
    if (e.which === 13) {
      e.preventDefault();
    }
  });

  $("#mwplg_maps_woocommerce_remove_custom_marker").on("click", function (e) {
    e.preventDefault();
    removeImage();
  });

  $("#mwplg_maps_woocommerce_map_marker_custom_image").on(
    "keyup",
    function (e) {
      e.preventDefault();
      toggleRemoveCustomMarker();
    }
  );

  function removeImage() {
    $("#mwplg_maps_woocommerce_map_marker_custom_image").val("");
    $("#mwplg_maps_woocommerce_remove_custom_marker").hide();
  }

  function sanitizeAndUpdateInput(input) {
    var cursorPosition = input[0].selectionStart;
    var sanitizedValue = input.val().replace(/[^0-9.]/g, "");
    input.val(sanitizedValue);
    input[0].setSelectionRange(cursorPosition, cursorPosition);
    if (sanitizedValue === "") {
      input.val("0");
    }
  }

  function toggleRemoveCustomMarker() {
    var customMarkerExists = $(
      "#mwplg_maps_woocommerce_map_marker_custom_image"
    ).val();
    if (customMarkerExists) {
      $("#mwplg_maps_woocommerce_remove_custom_marker").show();
    } else {
      $("#mwplg_maps_woocommerce_remove_custom_marker").hide();
    }
  }

  function uploadCustomMarkerMedia() {
    const upload_custom_marker_media = wp
      .media({
        library: {
          type: "image",
        },
        multiple: false,
      })
      .on("select", function () {
        var attachment = upload_custom_marker_media
          .state()
          .get("selection")
          .first()
          .toJSON();
        if (attachment?.url != "") {
          $("#mwplg_maps_woocommerce_map_marker_custom_image").val(
            attachment.url
          );
          $("#mwplg_maps_woocommerce_map_marker_custom_image").trigger(
            "customImageSelected"
          );
          $("#mwplg_maps_woocommerce_remove_custom_marker").show();
        }
      });

    upload_custom_marker_media.open();
  }

  function toggleCustomImageWrapper(value) {
    if (value == "custom") {
      $(".mwplg_maps_woocommerce_map_marker_custom_image_wrapper").show();
    } else {
      $(".mwplg_maps_woocommerce_map_marker_custom_image_wrapper").hide();
    }
  }
});
