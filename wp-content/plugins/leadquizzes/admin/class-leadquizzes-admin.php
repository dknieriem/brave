<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Leadquizzes
 * @subpackage Leadquizzes/admin
 * @author     LeadQuizzes <support@leadquizzes.com>
 */

class Leadquizzes_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/leadquizzes-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        // wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/leadquizzes-admin.js', array( 'jquery' ), $this->version, false );

    }

    public function create_leadquiz() {
        register_post_type( 'leadquiz',
            array(
                'labels' => array(
                    'name' => 'LeadQuizzes',
                    'singular_name' => 'Quiz',
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New Quiz',
                    'edit' => 'Edit',
                    'edit_item' => 'Edit Quiz',
                    'new_item' => 'New Quiz',
                    'view' => 'View',
                    'view_item' => 'View Quiz',
                    'search_items' => 'Search LeadQuizzes',
                    'not_found' => 'No LeadQuizzes found',
                    'not_found_in_trash' => 'No LeadQuizzes found in Trash',
                    'parent' => 'Parent Quiz'
                ),
     
                'public' => true,
                'menu_position' => 15,
                'menu_icon' => plugins_url( 'img/icon.png', __FILE__ ),
                'rewrite'            => array('slug'=>'q', 'with_front' => true),
                'capability_type'    => 'page',
                'has_archive'        => false,
                'hierarchical'       => true,
                'supports' => array( 'title' )
            )
        );
        flush_rewrite_rules();
    }

    public function my_admin() {
        add_meta_box( 'leadquiz_meta_box',
            'Quiz Details',
            'display_leadquiz_meta_box',
            'leadquiz', 'normal', 'high'
        );

        function display_leadquiz_meta_box( $leadquiz ) {
            // Retrieve current quiz url and isHomePage based on post ID
            $quiz_url = esc_html( get_post_meta( $leadquiz->ID, 'quiz_url', true ) );

            ?>
            <div class="form-row-wrapper">
                <div class="form-row">
                    <label for="quiz_url">Quiz URL</label> <em>https://quiz.leadquizzes.com/q/adC2wu</em>
                    <div class="form-input">
                        <input type="text" class="input" size="80" id="quiz_url" name="quiz_url" value="<?php echo $quiz_url; ?>" />
                    </div>
                </div>
            </div>

            <?php if($quiz_url) : ?>
                <div class="preview-panel">
                    <h1>Preview</h1>
                    <?php the_quiz(); ?>
                </div>
            <?php endif; ?>

            <?php
        }

    }

    public function add_leadquiz_fields( $leadquiz_url, $leadquiz ) {
        // Check post type for leadquiz
        if ( $leadquiz->post_type == 'leadquiz' ) {
            // Store data in post meta table if present in post data
            if ( isset( $_POST['quiz_url'] ) && $_POST['quiz_url'] != '' ) {
                update_post_meta( $leadquiz_url, 'quiz_url', $_POST['quiz_url'] );
            }
        }
    }

}
