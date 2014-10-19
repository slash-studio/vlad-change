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