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

    var mapOptions = {
      center: { lat: latitude, lng: longitude },
      zoom: zoom,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      styles: MapsWoocommerce.style,
    };

    map = new google.maps.Map(mapElement, mapOptions);
    infowindow = new google.maps.InfoWindow();

    marker = new google.maps.Marker({
      map: map,
      draggable: false,
      position: { lat: latitude, lng: longitude },
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

    loadTootip(latitude, longitude);
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
}

jQuery(document).ready(function ($) {
  initializeMap();
});
