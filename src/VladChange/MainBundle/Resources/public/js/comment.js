$(function() {
   function addComment(message, owner, create_date) {
      $('div.comment_block').append(message);
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
            addComment(_message, '', '');
            $('#comment_text').val('');
         },
         "json"
      );
      return false;
   });
})