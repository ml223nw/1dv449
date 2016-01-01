"use strict";

function Initialize() {
    
  var mapProp = {
    center: new google.maps.LatLng(60.128161, 18.643501),
    zoom:5,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById("map-canvas"),mapProp);
}
google.maps.event.addDomListener(window, 'load', Initialize);