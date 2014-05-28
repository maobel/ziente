(function($) {
    $(window).ready( function(){
         /* automatic keep header always displaying on top */
        if( $("body").hasClass("layout-boxed-md") || $("body").hasClass("layout-boxed-lg") ){

        }else if( $("body").hasClass("keep-header") ){
            var mb = parseInt($("#header-main").css("margin-bottom"));
            var hideheight =  $("#topbar").height()+mb+mb; 
            var hh =  $("#header").height() + mb;  
            var updateTopbar = function(){
                 var pos = $(window).scrollTop();
                 if( pos >= hideheight ){
                    $("#page").css( "padding-top", hh );
                    $("#header").addClass('hide-bar');
                    $("#header").addClass( "navbar navbar-fixed-top" );
                  
                }else {
                    $("#header").removeClass('hide-bar');
                } 
            }
            $(window).scroll(function() {
               updateTopbar();
            });
        }
    });
})(jQuery);