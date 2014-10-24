function addComment(data) {
   $('div.comment_block ul').append("<li><div class='left'>" +
                        "<a href='#' class='avatar'><img src='avatar.jpg' /></a>" +
                     "</div>" +
                     "<div class='right'>" +
                        "<a href='/profile/" + data.user.id + "' class='author'>" + data.user.name + "</a>" +
                        "<time>" + data.date + "</time>" +
                        "<div class='text'>" + data.message + "</div>" +
                     "</div></li>");
}

function clearCommentBox(isDeleteComments)
{
   isDeleteComments = typeof isDeleteComments == 'undefined' ? false : isDeleteComments;
   $('#comment_text').val('');
   if (isDeleteComments) {
      $('div.comment_block ul li').remove();
   }
}

$(function() {
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
            addComment({message: _message, user: data.user, date: data.date});
            clearCommentBox();
         },
         "json"
      );
      return false;
   });
})