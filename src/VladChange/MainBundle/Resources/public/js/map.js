ymaps.ready(init);

function createPlacemark(x, y, balloonText, event) {

    HintLayout = ymaps.templateLayoutFactory.createClass( "<div class='my-hint'>" +
            "<b>{{ properties.object }}</b><br />" +
            "{{ properties.address }}" +
            "</div>", {
                getShape: function () {
                    var el = this.getElement(),
                        result = null;
                    if (el) {
                        var firstChild = el.firstChild;
                        result = new ymaps.shape.Rectangle(
                            new ymaps.geometry.pixel.Rectangle([
                                [0, 0],
                                [firstChild.offsetWidth, firstChild.offsetHeight]
                            ])
                        );
                    }
                    return result;
                }
            }
        );

    placemark = new ymaps.Placemark(
        [x, y],
        {
            object: balloonText
        },
        {
            preset: 'islands#icon',
            iconColor: '#0095b6',
            hintLayout: HintLayout
        }
    );

    ymaps.geocode([x, y]).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);

            placemark.properties
                .set({
                    address: firstGeoObject.properties.get('name'),
                });
        });

    if (event) {
        placemark.events.add('click', event);

    }
    return placemark;
}

function addPlacemark(coords) {
    map.hint.close();
    window.location = '/add_project/?lat=' + map.lat + '&lon=' + map.lon;
}

function init() {

    map = new ymaps.Map("map", {
        center: [43.15, 131.85],
        zoom: 11,
        controls: ['zoomControl']
    });

    $.ajax({
        url : "api.getAllPlacemark",
        success: function(placemarks) {
            for (i = 0; i < placemarks.length; i++) {
                map.geoObjects.add(
                    createPlacemark(placemarks[i].x, placemarks[i].y, placemarks[i].short_desc, function(e) {
                        showInfo();
                        var coords = e.get('coords');
                        var center = map.getCenter();
                        var gotoPoint = map.options.get('projection').fromGlobalPixels(
                            map.converter.pageToGlobal([160, 300]), map.getZoom()
                        );
                        map.lastSelectMark = coords;
                        var deltaLat = coords[0] - gotoPoint[0];
                        var deltaLon = coords[1] - gotoPoint[1];
                        map.panTo([center[0] + deltaLat, center[1] + deltaLon]);
                    })
                );
            }
        }
    })

    map.events.add('contextmenu', function (e) {
        map.lat = e.get('coords')[0];
        map.lon = e.get('coords')[1];
        map.hint.open([map.lat, map.lon], "<a href='javascript:addPlacemark();'> Добавить проект </a>");
    });

}