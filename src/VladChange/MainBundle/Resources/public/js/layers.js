function showInfo(data, address) {
   $('#add_comment').attr('data-id', data.id);
   var $si = $('#short_info');
   $si.find('h1').text(data.name);
   var author = data.author.name + " " + data.author.surname;
   $si.find('.place').text(address); //to do
   $si.find('.author a').attr('href', '/profile/' + data.author.id).text(author);
   var $info = $('#full_info .info');

   $info.children('.info h1').text(data.name)
   $info.children('.text').html('<p>' + data.desc + '</p>')
   $info.children('.bottom_info').find('.author').text(author);
   $info.children('.bottom_info').find('time').text(data.createDate);
   $si.slideDown(500, function(){
      // Animation complete.
   });

   $("#full_info").animate({
      transform: 'translateX(0)'
   }, 500, function(){
      $("#close_info").animate({
         top: 80
      }, 500);
   });
}

function hideInfo() {
   $("#close_info").animate({
      top: 0
   }, 50, function(){
      $("#short_info").slideUp(500, function(){
         // Animation complete.
      });

      $("#full_info").animate({
         transform: 'translateX(4000)'
      }, 800, function(){
         $(this).css('transform', 'translateX(100%)');
      });
   });
   var center = map.getCenter();
   map.panTo(map.lastSelectMark);
}

$(function(){
   $('#close_info').click(function(){
      hideInfo();
   });
});