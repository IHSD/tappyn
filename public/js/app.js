$(document).foundation();

 $('#nav-icon4').click(function(){
    $(this).toggleClass('open');
    $('header nav').slideToggle();
    $('header nav').toggleClass('open');
  });