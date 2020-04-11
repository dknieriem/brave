var images = document.querySelectorAll('.rellax');
new simpleParallax(images, {
	scale: 1.5
}); 
/*{
	scale: 2.0
}*/

/* Blog Sidebar Sticky */

jQuery('.blog__sidebar').hcSticky({
  stickTo: '.post-sidebar',
  bottom: 20,
  responsive: {
  	768: {
  		disable: true
  	}
  }
});

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