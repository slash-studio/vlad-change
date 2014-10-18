function showInfo() {
   $("#short_info").slideDown(500, function(){
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
   }, 500, function(){
      $("#short_info").slideUp(500, function(){
         // Animation complete.
      });

      $("#full_info").animate({
         transform: 'translateX(4000)'
      }, 800, function(){
         $(this).css('transform', 'translateX(100%)');
      });
   });
}

$(function(){
   $('#close_info').click(function(){
      hideInfo();
   });
});