$(document).ready(function() {
  $(window).bind('scroll', function() {
    // The value of where the "scoll" is.
    if($(window).scrollTop() > 30){
      $('.menuyeri').addClass('fixle');
      $('.gizlilogo').addClass('logogel');
      $('.ben').addClass('logogit');
    }else{
      $('.menuyeri').removeClass('fixle');
      $('.gizlilogo').removeClass('logogel');
      $('.ben').removeClass('logogit');
    }
  })
});
