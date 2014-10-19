function showInfo(data, address, relation) {
   $('button.likes').removeAttr('disabled');
   var $si = $('#short_info');
   $si.find('h1').text(data.name);
   var author = data.author.name + " " + data.author.surname;
   $si.find('.place').text(address); //to do
   $si.find('.author a').attr('href', '/profile/' + data.author.id).text(author);
   var $info = $('#full_info .info');
   $info.children('.info h1').text(data.name)
   $info.children('.text').html('<p>' + data.desc + '</p>')
   $info.children('.bottom_info').find('.author').text(author);
   $('button.likes').text(data.voices);
   $('button.likes').attr('relation', relation);
   $('button.likes').attr('projectId', data.id);

   if (relation == 0) {
      $('button.likes').removeClass('active');
   } else if(relation == 1) {
      alert(relation);
      $('button.likes').attr('disabled', 'disabled');
   } else if (relation == 2){
      $('button.likes').addClass('active');
   }

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

$(function(){
   $('button.likes').click(function(){
      var projectID = $(this).attr('projectId');
      if (map.relations[projectID] == 0) {
         $.ajax({
              url : "api/updateLike/"+ projectID + "&t=add&a=like",
              success: function() {
                  $(this).addClass('active');
                  map.relations[projectID] = 2;
                  $(this).text(parseInt($(this).text()) + 1);
                  map.projects[projectID].options.set('iconColor', baloonColors[map.relations[projectID]]);   
              }
          })
      } else if (map.relations[projectID] == 2){
         $.ajax({
              url : "api/updateLike/"+ projectID + "&t=remove&a=like",
              success: function() {
                  $(this).removeClass('active');
                  map.relations[projectID] = 0;
                  $(this).text(parseInt($(this).text()) - 1);
                  map.projects[projectID].options.set('iconColor', baloonColors[map.relations[projectID]]);   
              }
          })
         
      } 
      
   });
   
});