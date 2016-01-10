"use strict";
/*global google*/
/*global map*/

var geoJSON;
var request;
var getDataFromRequest = false;
var openWeatherMapApiKey = "123";

function GetWeather(northLat, eastLng, southLat, westLng) {

    getDataFromRequest = false;
    var requestString = "http://api.openweathermap.org/data/2.5/box/city?bbox=" + westLng + "," + northLat + "," + eastLng + "," + southLat + "," + map.getZoom() + "&cluster=yes&format=json" + "&APPID=" + openWeatherMapApiKey;

    request = new XMLHttpRequest();
    request.onload = JsonResult;
    request.open("get", requestString, true);
    request.send();
}

function JsonResult() {

    var resultOfJson = JSON.parse(this.responseText);

    if (resultOfJson.list.length > 0) {
        ResetData();

        for (var i = 0; i < resultOfJson.list.length; i++) {
            geoJSON.features.push(JsonToGeoJson(resultOfJson.list[i]));
        }
        RenderIcons(geoJSON);
        }
    
    }

function JsonToGeoJson(weatherItem) {

    var feature = {

        type: "Feature",
        properties: {
            city: weatherItem.name,
            weather: weatherItem.weather[0].main,
            temperature: weatherItem.main.temp,
            icon: "http://openweathermap.org/img/w/" + weatherItem.weather[0].icon + ".png",
            coordinates: [weatherItem.coord.lon, weatherItem.coord.lat]
        },
        geometry: {
            type: "Point",
            coordinates: [weatherItem.coord.lon, weatherItem.coord.lat]
        }
    
    };
    
    map.data.setStyle(function(feature) {

        return {
            icon: { url: feature.getProperty('icon'), anchor: new google.maps.Point(25, 25) }
            
            };
    
        });
        return feature;
    }


function CheckIfDataHasBeenRequested() {

    if (getDataFromRequest == true) {
        
        request.abort();
        console.log("DATA REQUEST ABORTED");
        getDataFromRequest = false;
        }
    GetCoordinates();
    }

function GetCoordinates() {

    var bounds = map.getBounds();
    var northEast = bounds.getNorthEast();
    var southWest = bounds.getSouthWest();
    GetWeather(northEast.lat(), northEast.lng(), southWest.lat(), southWest.lng());
}

function RenderIcons(weather) {

    map.data.addGeoJson(geoJSON);
    getDataFromRequest = false;
}

function ResetData() {

    geoJSON = {
        type: "FeatureCollection",
        features: []
    };
        map.data.forEach(function(feature) {
        map.data.remove(feature);
    });

}

