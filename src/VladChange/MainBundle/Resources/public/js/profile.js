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