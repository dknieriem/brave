
var resetForm = function()
{
    jQuery('#toolset-maps-distance-center')
        .attr('placeholder','Your Location or Zip Code');

    jQuery('.events-map .wpv-filter-form .form-group')
    .contents().filter(function() { 
        return this.nodeType == Node.TEXT_NODE; })
    .remove();

    jQuery('.events-map .wpv-filter-form .js-toolset-maps-distance-current-location')
    .remove();

}


jQuery(document).ready(resetForm);

jQuery(function()
{
    jQuery('.events-map .js-wpv-view-layout').on("swipeleft", swipeleftHandler);
    jQuery('.events-map .js-wpv-view-layout').on("swiperight", swiperightHandler);

    jQuery( document ).on( 'js_event_wpv_pagination_completed', function( event, data ) {    
        jQuery('.events-map .js-wpv-view-layout').on("swipeleft", swipeleftHandler);
        jQuery('.events-map .js-wpv-view-layout').on("swiperight", swiperightHandler);
    });
    function swiperightHandler( event )
    {
        //event.preventDefault();
        //console.log(jQuery(this));
        //console.log(jQuery(this).find('.js-wpv-pagination-previous-link'));
        jQuery('.view-and-pagination-container .js-wpv-pagination-previous-link').click();
    }

    function swipeleftHandler( event )
    {
        //event.preventDefault();
        //console.log(jQuery(this));
        //console.log(jQuery(this).find('.js-wpv-pagination-next-link'));
        jQuery('.view-and-pagination-container .js-wpv-pagination-next-link').click();
        // var thiz = jQuery( this ),
        // view_number = thiz.data( 'viewnumber' ),
        // page = thiz.data( 'pagination' )['page'],
        // form = jQuery( '.js-wpv-filter-form-' + view_number );

        // WPViews.view_sorting.set_sort_data_for_pagination( form );

        // wpv_stop_rollover[ view_number ] = true;
        // self.trigger_pagination( view_number, page );
    }

});

