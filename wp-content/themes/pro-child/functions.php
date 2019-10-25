<?php

// =============================================================================
// FUNCTIONS.PHP
// -----------------------------------------------------------------------------
// Overwrite or add your own custom functions to Pro in this file.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Enqueue Parent Stylesheet
//   02. Additional Functions
// =============================================================================

// Enqueue Parent Stylesheet
// =============================================================================

@ini_set( 'upload_max_size' , '75M' );
@ini_set( 'post_max_size', '75M');
@ini_set( 'max_execution_time', '300' );

add_filter( 'x_enqueue_parent_stylesheet', '__return_true' );



// Additional Functions
// =============================================================================

function theme_name_scripts() {
  wp_register_script( 'jquery', 'https://code.jquery.com/jquery-3.2.1.min.js', array(), null, false );
  wp_register_script( 'scrollmagic', '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/ScrollMagic.min.js', array(), null, false );
wp_register_script( 'scrollmagic_debug', '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/debug.addIndicators.min.js', array(), null, false );
  wp_enqueue_script('scrollmagic');
}
add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );


//function custom_header_script() {
//  
//}
//add_action('wp_header','custom_header_script');
//
//


function third_party_tracking_code() { ?>

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-118292307-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-118292307-1');
</script>

<?php }

add_action( 'wp_head', 'third_party_tracking_code' );