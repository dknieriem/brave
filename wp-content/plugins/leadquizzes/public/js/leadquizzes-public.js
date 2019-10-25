(function( $ ) {
	'use strict';
    
    var iframes = document.getElementsByTagName("iframe");
    for(var i = 0; i < iframes.length; i++) {
        $(iframes[i]).iFrameResize({checkOrigin:false});
    }

})( jQuery );
