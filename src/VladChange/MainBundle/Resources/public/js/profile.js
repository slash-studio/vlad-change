$(function() {
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

   $(document).on('click', '.to_archive', function() {
      manipulateProject('archive', $(this).attr('data-id'));
      return false;
   });

   $(document).on('click', '.delete', function() {
      manipulateProject('delete', $(this).attr('data-id'));
      return false;
   });

   $(document).on('click', '.edit', function() {
      $(location).attr('href', '/edit_project/' + $(this).attr('data-id'));
   });
});

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