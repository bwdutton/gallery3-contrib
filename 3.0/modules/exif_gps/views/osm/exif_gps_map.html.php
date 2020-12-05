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
var map = L.map('map').setView([-104.99404, 39.75621], 13);
var ggFeatureGroup = L.geoJSON(geojsonFeature);
ggFeatureGroup.addTo(map);
map.fitBounds(ggFeatureGroup.getBounds());

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> collaborators'
}).addTo(map);

// L.marker(.addTo(map);

</script>
<?= $theme->dynamic_bottom() ?>
