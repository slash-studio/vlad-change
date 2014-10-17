ymaps.ready(init);

function init(){    

    map = new ymaps.Map("map", {
        center: [43.15, 131.85],
        zoom: 11,
        controls: ['zoomControl']
    });

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
        
    });
}