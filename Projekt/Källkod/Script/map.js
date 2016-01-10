"use strict";
/*global google*/
/*global map*/
/*global CheckIfDataHasBeenRequested*/

var map;

function Initialize() {

    var mapProperties = {
        center: new google.maps.LatLng(60.128161, 18.643501),
        zoom: 5,
        minZoom: 3,
        disableDefaultUI: true,
        zoomControl: true,
        mapTypeId: google.maps.MapTypeId.HYBRID
    };

    map = new google.maps.Map(document.getElementById("map-canvas"), mapProperties);
    google.maps.event.addListener(map, 'idle', CheckIfDataHasBeenRequested);
    
    var infowindow = new google.maps.InfoWindow();

    map.data.addListener('click', function(event) {
        
    infowindow.setContent("" + "<strong>" + event.feature.getProperty("city") + "</strong>" + "<br><br>" + "<strong>" + "Temperature: " + "</strong>" + event.feature.getProperty("temperature") + "&deg;C" + "<br>" + "<strong>" + "Weather: " + "</strong>" + event.feature.getProperty("weather"));
    
        infowindow.setOptions({
            position: {
                lat: event.latLng.lat(),
                lng: event.latLng.lng()
            },
            pixelOffset: {
                width: -2,
                height: -15
            }
            
        });
        infowindow.open(map);
    });
    
}

google.maps.event.addDomListener(window, 'load', Initialize);