<?php defined("SYSPATH") or die("No direct script access.") ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>
<div id="wrapper">
  <div id="map" style="width: 100%; height: 214px;"></div>
</div>
<style>
.ui-widget-overlay.ui-front:visible {
  z-index: 998;
}
.ui-widget-overlay.ui-front:visible img {
  z-index: 999;
}
</style>
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
   integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
   crossorigin=""></script><script>

   var zoomImg = document.getElementsByClassName('g-fullsize-link');
   zoomImg[0].addEventListener('click', toggleMiniMap, false);

function onEachFeature(feature, layer) {
   // do nothing
}

function toggleMiniMap() {
    var miniMap = document.getElementById('map');
    if (document.getElementById('g-fullsize-overlay')) {
        miniMap.style.display = '';
        document.getElementsByTagName('body')[0].removeEventListener('click', toggleMiniMap);
    }
    else {
        miniMap.style.display = 'none';
        window.exifGpsOsmSidebarMiniMap = document.getElementsByTagName('body')[0].addEventListener('click', toggleMiniMap, false);
    }
}

var map = L.map('map').setView([<?=$longitude; ?>, <?=$latitude; ?>], 13);

var geojsonFeatures = [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [
          <?=$longitude; ?>,
          <?=$latitude; ?>
        ]
      }
    },
];

var ggFeatureGroup = L.geoJSON(geojsonFeatures, {
  onEachFeature: onEachFeature
}).addTo(map);
map.fitBounds(ggFeatureGroup.getBounds());

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> collaborators'
}).addTo(map);

</script>
