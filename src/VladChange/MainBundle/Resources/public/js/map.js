ymaps.ready(init);

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
                    new ymaps.Placemark(
                        [placemarks[i].x, placemarks[i].y], 
                        {
                            balloonContent: 'цвет <strong>воды пляжа бонди</strong>'
                        }, 
                        {
                            preset: 'islands#icon',
                            iconColor: '#0095b6'
                        }
                    )
                );    
            }
        }
    })    

    map.events.add('dblclick', function (e) {
        e.preventDefault();
        var coords = e.get('coords');
        map.geoObjects.add(
            new ymaps.Placemark(
                e.get('coords'), 
                {
                    balloonContent: 'цвет <strong>воды пляжа бонди</strong>'
                }, 
                {
                    preset: 'islands#icon',
                    iconColor: '#0095b6'
                }
            )
        );

        $.ajax({
            url : "api.addPlacemark/" + coords.join('&'),
        });
    });
}