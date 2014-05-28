// JavaScript Document
/*! Copyright (c) 2009 Brandon Aaron (http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 *
 * Version: 3.0.2
 * 
 * Requires: 1.2.2+
 */

(function($) {
    $(document).ready(function() {
        $("#toolspanelcontent").animate( {
            "left": -206
        } ).addClass("inactive");
        $("#toolspanel .pn-button").click(function(){
            if(  $("#toolspanelcontent").hasClass("inactive")  ){												 
                $("#toolspanelcontent").animate( {
                    "left": 0
                } ).addClass("active").removeClass("inactive");
                $(this).removeClass("open").addClass("close");
            }else {
                $("#toolspanelcontent").animate( {
                    "left": -206
                } ).addClass("inactive").removeClass("active");
                $(this).removeClass("close").addClass("open");
            }
        });
	
        $("#pnpartterns a").click( function(){  
            $("#pnpartterns a").removeClass("active");
            $(this).addClass("active");
            //document.body.className = document.body.className.replace(/pattern\w*/,"");
            
            $('body').attr('id', ($(this).attr("id").replace(/\.\w+$/,"")));
            $('#pnpartterns input').val($(this).attr("id").replace(/\.\w+$/,""));
            
        //$("body").addClass($(this).attr("id").replace(/\.\w+$/,""));				
        } );
    });
})(jQuery);
