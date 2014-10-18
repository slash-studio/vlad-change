function showShortInfo() {
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