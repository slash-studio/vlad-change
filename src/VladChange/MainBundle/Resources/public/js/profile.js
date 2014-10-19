function manipulateProject(t, _id) {
   $.post(
      '/project/' + t,
      {
         id: _id
      },
      function(data) {
         //remove project from dom if
      },
      "json"
   );
}

ymaps.ready(init);

function createPlacemark(info) {

    var coords = [info.lat, info.lon];
    placemark = new ymaps.Placemark(
        coords,
        {
            name: info.name,
            short_desc: info.shortDesc,
        },
        {
            preset: 'islands#icon',
            iconColor: '#0095b6',
        }
    );
    return placemark;
}

function init() {

    map = new ymaps.Map("map", {
        center: [43.15, 131.85],
        zoom: 11,
        controls: ['zoomControl']
    });

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
}

$(function(){
  $(document).on('click', '#close_left.disable', function(){
    $('#user_left').animate({
      left: '-280'
    }, 500);
    $('#close_left').animate({
      left: '-40'
    }, 500, function(){
      $('#close_left div').addClass('active');
      $('#close_left').animate({
        left: '00'
      }, 500);
    });
    $(this).removeClass('disable').addClass('active');
  });

  $(document).on('click', '#close_left.active', function(){
    $('#close_left').animate({
      left: '-40'
    }, 500, function(){
      $('#close_left div').removeClass('active');
      $('#close_left').animate({
        left: '280'
      }, 500);
      $('#user_left').animate({
        left: '0'
      }, 500);
    });
    $(this).removeClass('active').addClass('disable');
  });
});