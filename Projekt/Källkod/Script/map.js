"use strict";
/*global google*/
/*global map*/
/*global CheckIfDataHasBeenRequested*/

var map;

function Initialize() {

    var mapProperties = {
        center: new google.maps.LatLng(60.128161, 18.643501),
        zoom: 5,
        disableDefaultUI: true,
        zoomControl: true,
        mapTypeId: google.maps.MapTypeId.HYBRID
    };

    map = new google.maps.Map(document.getElementById("map-canvas"), mapProperties);
    google.maps.event.addListener(map, 'idle', CheckIfDataHasBeenRequested);

}

google.maps.event.addDomListener(window, 'load', Initialize);