<?php defined("SYSPATH") or die("No direct script access.") ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>
<div id="g-exif-map-header">
  <div id="g-exif-map-header-buttons">
    <?= $theme->dynamic_top() ?>
  </div>
  <h1><?= html::clean($title) ?></h1>
</div>
<br />
<div id="wrapper">
  <div id="map" style="width: 100%; height: 500px;"></div>
</div>
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
   integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
   crossorigin=""></script><script>

function onEachFeature(feature, layer) {
   // does this feature have a property named popupContent?
   if (feature.properties && feature.properties.image_html_base64) {
       layer.bindPopup(
         "<h3 style=\"max-width: 200px; text-align: center; overflow-wrap: break-word; margin-bottom: 1ex;\">" + feature.properties.name + "</h3>" +
         "<div style=\"text-align: center;\"><a href=\"" + feature.properties.url + "\">" +
         // decode HTML element with thumb
         window.atob(feature.properties.image_html_base64)
         + "</a></div>"
       );
   }
}
var geojsonFeature = {
   "type": "Feature",
   "properties": {
       "name": "Coors Field",
       "amenity": "Baseball Stadium",
       "popupContent": "This is where the Rockies play!"
   },
   "geometry": {
       "type": "Point",
       "coordinates": [-104.99404, 39.75621]
   }
};



var request = new XMLHttpRequest();
var int_offset = 0;
request.open('GET', '<?= url::abs_site("exif_gps/geojson/{$query_type}/{$query_id}"); ?>/' +  int_offset, true);

request.onload = function() {
  if (this.status >= 200 && this.status < 400) {
      // Success!
      var geojsonFeatures = JSON.parse(this.response);

      var map = L.map('map').setView([0, 0], 13);
      var ggFeatureGroup = L.geoJSON(geojsonFeatures, {
        onEachFeature: onEachFeature
      }).addTo(map);
      map.fitBounds(ggFeatureGroup.getBounds());

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> collaborators'
      }).addTo(map);
      // L.marker(.addTo(map);

  } else {
    // We reached our target server, but it returned an error

  }
};

request.onerror = function() {
  // There was a connection error of some sort
};

request.send();

</script>
<?= $theme->dynamic_bottom() ?>
