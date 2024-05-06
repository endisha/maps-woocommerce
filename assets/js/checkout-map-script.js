var map;
var marker;
var infowindow;

function initializeMap() {
  var mapElement = document.getElementById("map-woocommerce-checkout-order");

  if (mapElement) {
    var latitude = parseFloat(MapsWoocommerce.latitude);
    var longitude = parseFloat(MapsWoocommerce.longitude);
    var zoom = parseInt(MapsWoocommerce.default_zoom);
    var markerIcon = MapsWoocommerce.marker;
    var customMarkerIcon = MapsWoocommerce.custom_marker_image;
    var autoDetect = MapsWoocommerce.auto_detect;

    setCoordinatesValues(latitude, longitude);
    loadTootip(latitude, longitude);

    var mapOptions = {
      center: {
        lat: latitude,
        lng: longitude,
      },
      zoom: zoom,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      styles: MapsWoocommerce.style,
    };

    map = new google.maps.Map(mapElement, mapOptions);
    infowindow = new google.maps.InfoWindow();

    marker = new google.maps.Marker({
      map: map,
      draggable: true,
      position: {
        lat: latitude,
        lng: longitude,
      },
    });

    if (markerIcon && markerIcon !== "default" && markerIcon !== "custom") {
      var markerIconUrl =
        MapsWoocommerce.markers_url + "/" + markerIcon + ".png";
      marker.setIcon(markerIconUrl);
    } else if (
      markerIcon &&
      markerIcon === "custom" &&
      customMarkerIcon !== ""
    ) {
      var customMarkerSize = new google.maps.Size(40, 40);
      marker.setIcon({
        url: customMarkerIcon,
        scaledSize: customMarkerSize,
      });
    } else {
      marker.setIcon();
    }

    var geolocation = navigator.geolocation;

    if (geolocation) {
      if (autoDetect) {
        getGeoLocation(geolocation);
      }

      var getLocationButton = document.createElement("button");
      getLocationButton.textContent = MapsWoocommerce.get_my_location_button;
      getLocationButton.className = "get-location-button";

      getLocationButton.addEventListener("click", function (event) {
        event.preventDefault();
        getGeoLocation(geolocation);
      });

      map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
        getLocationButton
      );
    }

    google.maps.event.addListener(marker, "dragend", function () {
      var newPosition = marker.getPosition();
      var latitude = newPosition.lat();
      var longitude = newPosition.lng();
      setCoordinatesValues(latitude, longitude);
      loadTootip(latitude, longitude);
    });
  }
}

function getGeoLocation(geolocation) {
  geolocation.getCurrentPosition(
    function (position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;

      setCoordinatesValues(latitude, longitude);
      loadTootip(latitude, longitude);

      var newPosition = new google.maps.LatLng(latitude, longitude);
      marker.setPosition(newPosition);
      map.setCenter(newPosition);
    },
    function (error) {
      if (error.code == error.PERMISSION_DENIED) {
        showLocationPermissionDeniedMessage();
      }
    }
  );
}

function setCoordinatesValues(latitude, longitude) {
  jQuery("#" + MapsWoocommerce.latitude_key).val(latitude);
  jQuery("#" + MapsWoocommerce.longitude_key).val(longitude);
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

function showLocationPermissionDeniedMessage() {
  infowindow.setContent(MapsWoocommerce.location_access_denied_message);
  infowindow.open(map, marker);
}

jQuery(document).ready(function ($) {
  initializeMap();

  if ($("#billing_latitude_longitude").length > 0) {
    $(".maps-woocommerce-map-wrapper").insertAfter(
      $("#billing_latitude_longitude")
    );
    $("#map-woocommerce-checkout-order").css("display", "block");

    $("#billing_latitude_longitude").remove();
  }
});
