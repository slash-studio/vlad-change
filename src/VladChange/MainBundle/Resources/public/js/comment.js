$(function() {
   function addComment(message, owner, create_date) {
      $('div.comment_block ul').append("<li><div class='left'>" +
                           "<a href='#' class='avatar'><img src='avatar.jpg' /></a>" +
                        "</div>" +
                        "<div class='right'>" +
                           "<a href='/profile/" + owner.id + "' class='author'>" + owner.name + "</a>" +
                           "<time>" + create_date + "</time>" +
                           "<div class='text'>" + message + "</div>" +
                        "</div></li>");
   }

   $(document).on('click', '#add_comment', function() {
      var _message = $('#add_comment_block textarea').val();
      if (_message === '') return false;
      $.post(
         '/api/addComment',
         {
            project_id: $(this).attr('data-id'),
            message: _message
         },
         function(data) {
            // data.owner - имя фамилия отправителя
            // data.create_date - дата создания
            addComment(_message, data.owner, data.create_date);
            $('#comment_text').val('');
         },
         "json"
      );
      return false;
   });
})