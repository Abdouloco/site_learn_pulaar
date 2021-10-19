jQuery(document).ready(function($) {
    /** Header Search form show/hide */
    $('html').on( 'click', function() {
        $('.example').slideUp();
    });

    $('.site-header .form-section').on( 'click', function(event) {
        event.stopPropagation();
    });

    $("#search-btn").on( 'click', function() {
        $(".example").slideToggle();
        return false;
    });

    $('.btn-form-close').on( 'click', function(){
        $(".example").slideToggle();
        return false;
    });

    //accessibility menu
    $("#top-navigation ul li a").on( 'focus', function(){
       $(this).parents("li").addClass("focus");
   }).on( 'blur', function(){
       $(this).parents("li").removeClass("focus");
   });
});
