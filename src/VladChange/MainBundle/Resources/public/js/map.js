ymaps.ready(init);

function createPlacemark(x, y, balloonText, event){

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

function init(){

    map = new ymaps.Map("map", {
        center: [43.15, 131.85],
        zoom: 11,
        controls: ['zoomControl']
    });

    $.ajax({
        url : "api.getAllPlacemark",
        success: function(placemarks){
            for (i = 0; i < placemarks.length; i++) {
                map.geoObjects.add(
                    createPlacemark(placemarks[i].x, placemarks[i].y, 'Охуенный проект', function() {
                        showShortInfo();
                    })
                );
            }
        }
    })

    map.events.add('dblclick', function (e) {
        e.preventDefault();
        var coords = e.get('coords');
        map.geoObjects.add(
            createPlacemark(coords[0], coords[1], 'Охуенный новый проект', function() {alert("хуик")})
        );

        $.ajax({
            url : "api.addPlacemark/" + coords.join('&'),
        });
    });
}