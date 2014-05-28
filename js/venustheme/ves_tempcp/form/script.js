// JavaScript Document
(function($) {
    $(document).ready(function() {
        jQuery("div.bgpattern").each( function(){
            var wrap = this;
            if( $("input",wrap).val() ){	
                $("#" + $("input",wrap).val()).addClass("active"); 
            }
            $("div",this).click( function(){
                $("input",wrap).val( $(this).attr("id").replace(/\.\w+$/,"") );
                $("div",wrap).removeClass( "active" );
                $(this).addClass("active");
            } );
            
            
            var countOp = $('#ves_tempcp_info_ves_tempcp_skin option').length;
            if(countOp == 0){
                $('#ves_tempcp_info_ves_tempcp-head').parent().hide();
                $('#ves_tempcp_info_ves_tempcp').hide();
                $('#ves_tempcp_info_theme_info-head').parent().show();
            }
            if(countOp > 0){
                $('#ves_tempcp_info_ves_tempcp-head').parent().show();
                $('#ves_tempcp_info_theme_info-head').parent().hide();
                $('#ves_tempcp_info_theme_info').hide();
            }
        
        } );
    } );
})(jQuery);
