new WOW().init();

/*-----sticky-header-start-------*/

$(document).ready(function() {
    if ($(window).width() <= 1920) {
      $('.navesticky').removeClass('sticky');
  } else {
      $('.navesticky').addClass('sticky');
      $('.sticky').stickMe(); 
  }
 });

/*-----sticky-header-end-------*/

/*-----menu-start-------*/

jQuery(document).ready(function(){
    jQuery("#menu-button").bind('touchstart mousedown', function(event){
       event.preventDefault();
       if (jQuery("#menu-button").hasClass("menu-opened"))
       {
       jQuery("#menu-button").removeClass("menu-opened");
       }
       else{
        jQuery("#menu-button").addClass("menu-opened");
       }
    });
});

/*-----menu-end-------*/

