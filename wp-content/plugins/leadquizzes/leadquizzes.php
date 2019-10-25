<?php

/**
 * 
 * @link              https://leadquizzes.com
 * @since             1.0.0
 * @package           Leadquizzes
 *
 * Plugin Name:       LeadQuizzes
 * Plugin URI:        https://leadquizzes.com
 * Description:       LeadQuizzes Wordpress Plugin.
 * Version:           1.1.0
 * Author:            LeadQuizzes
 * Author URI:        https://leadquizzes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       leadquizzes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Activate LeadQuizzes Plugin
 */
function activate_leadquizzes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-leadquizzes-activator.php';
	Leadquizzes_Activator::activate();
}

/**
 * Deactivate LeadQuizzes Plugin
 */
function deactivate_leadquizzes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-leadquizzes-deactivator.php';
	Leadquizzes_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_leadquizzes' );
register_deactivation_hook( __FILE__, 'deactivate_leadquizzes' );

/**
 * The core plugin class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-leadquizzes.php';

function get_quiz_url() {
    global $post;
    $qurl = get_post_meta($post->ID, 'quiz_url', true);
    return $qurl;
}

function get_the_quiz( $u = null ) 
{
    $url = '';

    if($u) {
        $url = $u;
    } else {
        global $post;
        $url = get_quiz_url();
    }

    $domain = "https://quiz.leadquizzes.com";

    wp_enqueue_script( 'leadquiz_iframe', 'https://quiz.leadquizzes.com/js/iframeResizer/iframeResizer.min.js', array(), false, false );
    wp_enqueue_script( 'leadquiz_init', plugin_dir_url( __FILE__ ) . '/public/js/leadquizzes-public.js', array(), false, false );

    $quizBox = '<div id="LQQuiz" class="solo"><iframe src="'. $url .'"  scrolling="no" frameborder="0"></iframe></div>';

    return $quizBox;

}

function the_quiz( $u = null ) {
    echo get_the_quiz( $u );
}

function get_lquiz_template($single_template)
{
     global $post;

     if ($post->post_type == 'leadquiz') {
          $single_template = dirname( __FILE__ ) . '/view/single-leadquiz.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'get_lquiz_template' );

add_shortcode('leadquiz', 'leadquiz_shorcode');
function leadquiz_shorcode($atts, $content = null) {
    extract(shortcode_atts(array(
        'url'        => '',
        'title'     => 'Quiz Popup',
        'height'    => 'auto',
        'width'     => '100%',
        'class'     => ''
    ), $atts));

    $domain = "https://quiz.leadquizzes.com";

    $fancybox_css = "https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css";

    $fancybox_js = "https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js";

    $iframe = '<iframe src="'. $url .'"  scrolling="no" frameborder="0" style="width:100% !important; margin: 0 !important; padding: 0 !important;"></iframe>';

    $button_css = "<style>.popup-button{padding:15px 31px;text-align:center;border-radius:2px;display:inline-block;vertical-align:top;border:1px solid #23a6f2;box-shadow:none;font-weight:700;font-size:14px;line-height:16px;background:#23a6f2;color:#fff!important;transition:all .2s linear;outline:0}.popup-button:hover{border-color:#137bb8;background:#137bb8}</style>";

    wp_enqueue_script( 'leadquiz_iframe', 'https://quiz.leadquizzes.com/js/iframeResizer/iframeResizer.min.js', array(), false, false );
    wp_enqueue_script( 'leadquiz_init', plugin_dir_url( __FILE__ ) . '/public/js/leadquizzes-public.js', array(), false, false );

    $error = "
        <div style='border: 2px solid red; border-radius: 5px; padding: 20px; margin: 50px 0 70px;'>
            <h3>Something went wrong!!!</h3>
            <p style='margin: 0;'>Something is wrong with your quiz shortcode. Make sure you have used valid URL.</p>
        </div>";


    if (!$url) {
        return $error;
    } else {
        $quizBox = "<div id='LQQuiz' style='width: $width; height: $height; position: relative !important;clear:both; margin: 20px 0;overflow:auto;'";

        if($class) {
            $quizBox .= " class='$class'";
        }

        $quizBox .= "> $iframe </div>";

        return $quizBox;
    }
}

/**
 * Begins execution of the plugin.
 */
function run_leadquizzes() {

	$plugin = new Leadquizzes('1.1.0');
	$plugin->run();

}
run_leadquizzes();