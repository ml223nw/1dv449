"use strict";
/*global map*/

var geoJSON;
var request;
var getDataFromRequest = false;
var openWeatherMapApiKey = "2c267276247019f6f2af31301e80e021";

function GetWeather(northLatitude, eastLongitude, southLatitude, westLongitude) {

    getDataFromRequest = true;
    var requestString = "http://api.openweathermap.org/data/2.5/box/city?bbox=" + northLatitude + "," + eastLongitude + "," + southLatitude + "," + westLongitude + "," + map.getZoom() + "&cluster=yes&format=json" + "&APPID=" + openWeatherMapApiKey;

    request = new XMLHttpRequest();
    request.onload = JsonResult;
    request.open("get", requestString, true);
    request.send();
}

function CheckIfDataHasBeenRequested() {

    while (getDataFromRequest === true) {

        request.abort();
        console.log("DATA REQUEST ABORTED")
        getDataFromRequest = false;
    }
    GetCoordinates();
}

function GetCoordinates() {

    var bounds = map.getBounds();
    var northEast = bounds.getNorthEast();
    var soutWest = bounds.getSouthWest();

    GetWeather(northEast.lat(), northEast.lng(), soutWest.lat(), soutWest.lng());
}

function JsonResult() {

    console.log(this);
    var results = JSON.parse(this.responseText);

    if (results.list.length > 0) {
        ResetData();
        console.log(geoJSON);
    }

}

function ResetData() {

    geoJSON = {
        type: "FeatureCollection",
        features: []
    };

}

