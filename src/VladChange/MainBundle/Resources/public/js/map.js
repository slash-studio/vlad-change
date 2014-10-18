ymaps.ready(init);

function createPlacemark(x, y, balloonText, event) {

    placemark = new ymaps.Placemark(
        [x, y],
        {
            balloonContent: balloonText
        },
        {
            preset: 'islands#icon',
            iconColor: '#0095b6'
        }
    );
    if (event) {
        placemark.events.add('click', event);
    }
    return placemark;
}

function addPlacemark(x, y) {
    window.location = '/add_project/?lat=' + x + '&lon=' + y;
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
                    createPlacemark(placemarks[i].x, placemarks[i].y, 'Охуенный проект', function(e) {
                        showInfo();
                        var coords = e.get('coords');
                        var center = map.getCenter();
                        var gotoPoint = map.options.get('projection').fromGlobalPixels(
                            map.converter.pageToGlobal([160, 300]), map.getZoom()
                        );
                        map.setCenter([center[0] + coords[0] - gotoPoint[0], center[1] + coords[1] - gotoPoint[1]]);
                    })
                );
            }

        }
    })


    // map.events.add('click', function (e) {
    //     e.preventDefault();
    //     var coords = e.get('coords');
    //     // map.geoObjects.add(
    //     //     createPlacemark(coords[0], coords[1], 'Охуенный новый проект', function() {alert("хуик")})
    //     // );
    //     // var projection = map.options.get('projection');
    //     // $('#map').bind('click', function (e) {
    //     //     console.log(projection.fromGlobalPixels(
    //     //         map.converter.pageToGlobal([e.pageX, e.pageY]), map.getZoom()
    //     //     ).join(', ');
    //     // });
    //     alert(coords);

    //     var projection = map.options.get('projection');
    //     alert(projection.fromGlobalPixels(
    //         map.converter.pageToGlobal([160, 300]), map.getZoom()
    //     ));
    //     map.setCenter(projection.fromGlobalPixels(
    //         map.converter.pageToGlobal([160, 300]), map.getZoom()
    //     ));
    // });

    map.events.add('dblclick', function (e) {
        e.preventDefault();
        var coords = e.get('coords');
        map.geoObjects.add(
            createPlacemark(coords[0], coords[1], 'Охуенный новый проект', function() {alert("хуик")})
        );
        // addPlacemark

        var center = map.getCenter();

        var projection = map.options.get('projection');

        var gotoPoint = projection.fromGlobalPixels(
            map.converter.pageToGlobal([160, 300]), map.getZoom()
        );

        map.setCenter([center[0] + coords[0] - gotoPoint[0], center[1] + coords[1] - gotoPoint[1]]);
    });
}
