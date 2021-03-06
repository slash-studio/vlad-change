ymaps.ready(init);

baloonColors = {
    "0" : '#FF0000',
    "1" : '#FF00FF',
    "2" : '#0000FF',
    "3" : '#00FF00',
};

function getAddress(placemark, coords, info){
    ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);
            placemark.properties.set('address', firstGeoObject.properties.get('name'));
            info.address = firstGeoObject.properties.get('name');
        });
}
function createPlacemark(info, event) {

    HintLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="my-hint">' +
            '<h1>{{ properties.name }}</h1>' +
            '<div class="street">{{ properties.address }}</div>' +
            '</div>',
            {
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

    var coords = [info.lat, info.lon];

    placemark = new ymaps.Placemark(
        coords,
        {
            name: info.name,
            short_desc: info.shortDesc,
        },
        {
            preset: 'islands#icon',
            iconColor: baloonColors[info.relation],
            hintLayout: HintLayout
        }
    );
    getAddress(placemark, coords, info);
    map.relations[info.id] = info.relation;
    map.projects[info.id] = placemark;
    placemark.events.add('dblclick', function(e) {

        e.preventDefault();
        $.ajax({
            url : "api/getPlacemarkInfo/" + info.id,
            success: function(data) {
                if ($.isEmptyObject(data)) return;
                clearCommentBox(true);
                showInfo(data, info.address, info.relation);
                var coords = e.get('coords');
                var center = map.getCenter();
                var gotoPoint = map.options.get('projection').fromGlobalPixels(
                    map.converter.pageToGlobal([160, 300]), map.getZoom()
                );
                map.lastSelectMark = coords;
                var deltaLat = coords[0] - gotoPoint[0];
                var deltaLon = coords[1] - gotoPoint[1];
                map.panTo([center[0] + deltaLat, center[1] + deltaLon]);
            }
        });
    })
    placemark.events.add('click', function(e){e.preventDefault();});

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

    map.relations = {};
    map.projects = {};

    $.ajax({
        url : "api/getAllPlacemark",
        success: function(placemarks) {
            for (i = 0; i < placemarks.length; i++) {
                map.geoObjects.add(
                    createPlacemark(placemarks[i])
                );
            }
        }
    })

    map.events.add('contextmenu', function (e) {
        if (!map.hint.isOpen()){
            map.lat = e.get('coords')[0];
            map.lon = e.get('coords')[1];
            map.hint.open([map.lat, map.lon], "<a href='javascript:addPlacemark();'> Добавить проект </a>");
        } else {
            map.hint.close();
        }
    });

    map.events.add('boundschange', function (e) {
        map.hint.close();
    });
}