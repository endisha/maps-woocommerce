var map;
var marker;
var infowindow;
var updatedStyle;
var latitude;
var longitude;

function showLoader() {
  var loader = document.getElementById("map-loader");
  if (loader) {
    jQuery("#map-woocommerce-checkout-order").append(jQuery("#map-loader"));
    loader.style.display = "flex";
  }
}

function hideLoader() {
  var loader = document.getElementById("map-loader");
  if (loader) {
    loader.style.display = "none";
  }
}

function updatingMapEffect(on) {
  var mapElement = document.getElementById("map-woocommerce-checkout-order");
  if (mapElement) {
    mapElement.style.opacity = on ? "0.2" : "initial";
  }
}

function initializeMap() {
  showLoader();

  var mapElement = document.getElementById("map-woocommerce-checkout-order");

  if (mapElement) {
    function updateMap(style) {
      var zoomValue = jQuery("#mwplg_maps_woocommerce_map_zoom").val();
      var zoom = !isNaN(zoomValue) ? parseInt(zoomValue, 10) : 0;

      if (isNaN(zoom)) {
        zoom = 0;
      }
      const markerIcon = jQuery("#mwplg_maps_woocommerce_map_marker").val();

      let latitude =
        parseFloat(
          jQuery("#mwplg_maps_woocommerce_map_default_latitude").val()
        ) || 0;
      let longitude =
        parseFloat(
          jQuery("#mwplg_maps_woocommerce_map_default_longitude").val()
        ) || 0;

      if (!style) {
        style = MapsWoocommerce.style;
      }
      const mapOptions = {
        center: { lat: latitude, lng: longitude },
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: style,
        streetViewControl: false,
      };

      map = map || new google.maps.Map(mapElement, mapOptions);
      infowindow = new google.maps.InfoWindow();
      map.setOptions(mapOptions);

      google.maps.event.addListener(
        map,
        "zoom_changed",
        function () {
          const newZoom = map.getZoom();
          jQuery("#mwplg_maps_woocommerce_map_zoom").val(newZoom);
        },
        { passive: true }
      );

      if (marker) {
        marker.setMap(null);
      }

      marker = new google.maps.Marker({
        map,
        draggable: true,
        position: { lat: latitude, lng: longitude },
      });

      if (markerIcon && markerIcon !== "default" && markerIcon !== "custom") {
        var markerIconUrl =
          MapsWoocommerce.markers_url + "/" + markerIcon + ".png";

        marker.setIcon(markerIconUrl);
      } else if (markerIcon && markerIcon === "custom") {
        const customMarkerIcon = jQuery(
          "#mwplg_maps_woocommerce_map_marker_custom_image"
        ).val();
        const customMarkerSize = new google.maps.Size(40, 40);
        marker.setIcon({
          url: customMarkerIcon,
          scaledSize: customMarkerSize,
        });
      } else {
        marker.setIcon();
      }

      loadTootip(latitude, longitude);

      google.maps.event.addListener(marker, "dragend", function () {
        const newPosition = marker.getPosition();
        const newLatitude = newPosition.lat();
        const newLongitude = newPosition.lng();
        setCoordinatesValues(newLatitude, newLongitude);
        loadTootip(newLatitude, newLongitude);
      });

      hideLoader();
    }

    function updateMapStyle() {
      updatingMapEffect(true);
      var style_name = jQuery("#mwplg_maps_woocommerce_map_style").val();
      var data = {
        action: "mwplg_maps_woocommerce_get_style_preview",
        style_name: style_name,
        nonce: MapsWoocommerce.nonce,
      };

      jQuery.ajax({
        type: "POST",
        url: MapsWoocommerce.ajax_url,
        data: data,
        success: function (response) {
          updatedStyle = response.data;
          updateMap(updatedStyle);
          updatingMapEffect(false);
        },
        error: function (error) {
          jQuery("#mwplg_maps_woocommerce_map_style").val(
            MapsWoocommerce.style_name
          );
          updatingMapEffect(false);
        },
      });
    }

    jQuery("#mwplg_maps_woocommerce_map_zoom").on("change", function () {
      updateMap(updatedStyle);
    });

    jQuery("#mwplg_maps_woocommerce_map_zoom").on("input", function () {
      updateMap(updatedStyle);
    });

    jQuery("#mwplg_maps_woocommerce_map_marker").on("change", function () {
      updateMap(updatedStyle);
    });

    jQuery("#mwplg_maps_woocommerce_map_marker_custom_image").on(
      "change",
      function () {
        updateMap(updatedStyle);
      }
    );

    jQuery("#mwplg_maps_woocommerce_map_marker_custom_image").on(
      "input",
      function () {
        updateMap(updatedStyle);
      }
    );

    jQuery("#mwplg_maps_woocommerce_map_marker_custom_image").on(
      "customImageSelected",
      function () {
        updateMap(updatedStyle);
      }
    );

    jQuery("#mwplg_maps_woocommerce_map_default_latitude").on(
      "input",
      function () {
        updateMap(updatedStyle);
      }
    );

    jQuery("#mwplg_maps_woocommerce_map_default_longitude").on(
      "input",
      function () {
        updateMap(updatedStyle);
      }
    );

    jQuery("#upload-custom-marker").on("click", function () {
      updateMap(updatedStyle);
    });

    jQuery("#remove-custom-marker").on("click", function () {
      updateMap(updatedStyle);
    });

    jQuery("#mwplg_maps_woocommerce_map_style").on("change", updateMapStyle);
  }
}

function setCoordinatesValues(latitude, longitude) {
  jQuery("#mwplg_maps_woocommerce_map_default_latitude").val(latitude);
  jQuery("#mwplg_maps_woocommerce_map_default_longitude").val(longitude);
}

function loadTootip(latitude, longitude) {
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode(
    { location: { lat: latitude, lng: longitude } },
    function (results, status) {
      if (status === "OK") {
        if (results[0]) {
          var address = results[0]?.formatted_address;
          if (address != "") {
            var contentString =
              '<div class="map-address"><strong>' +
              MapsWoocommerce.address_label +
              ":</strong><br>" +
              address +
              "</div>";

            infowindow.setContent(contentString);
            infowindow.open(map, marker);
          }
        }
      }
    }
  );
}

jQuery(document).ready(function ($) {
  initializeMap();
});
