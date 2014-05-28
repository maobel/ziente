// JavaScript Document
(function($) {
    $(document).ready(function() {
        var id = "#venustheme_tabshome_info_venustheme_tabshome_";
        if($(id + 'enable_cat option:selected').val() == 0)
            $("#row_venustheme_tabshome_info_venustheme_tabshome_list_cat").hide();
        else
            $("#row_venustheme_tabshome_info_venustheme_tabshome_list_cat").show();
        $(id + 'enable_cat').change(function() {
            currentValue = $(this).val();
            name = $("#row_venustheme_tabshome_info_venustheme_tabshome_list_cat");
            $(this).find("option").each(function(index, Element) {		
                if($(Element).val() == currentValue){		          
                    $(name).hide();
                }else{
                    $(name).show();
                }
            });		
        });
    } );
})(jQuery);
