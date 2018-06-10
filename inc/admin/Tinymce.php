<?php
/**
 * 
 * @author Precious Omonzejele <omonze@peepsipi.com>
 */
if(!defined('ABSPATH')){
	exit();
}
class Mauv_TinyMCE {
 
    /**
    * Constructor. Called when the plugin is initialised.
    */
    function __construct() {
 
 		if ( is_admin() ) {
		    add_action( 'init', array( &$this, 'setup_tinymce_plugin' ) );
		    add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts_css' ) );
		    add_action( 'admin_print_footer_scripts', array( &$this, 'admin_footer_scripts' ) );
		}
    }
    /**
	* Check if the current user can edit Posts or Pages, and is using the Visual Editor
	* If so, add some filters so we can register our plugin
	*/
	function setup_tinymce_plugin() {
	 
	    // Check if the logged in WordPress User can edit Posts or Pages
	    // If not, don't register our TinyMCE plugin
	    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
	        return;
	    }
	 
	    // Check if the logged in WordPress User has the Visual Editor enabled
	    // If not, don't register our TinyMCE plugin
	    /*if ( get_user_option( 'rich_editing' ) !== 'true' ) {
	        return;
	    }*/
	 
	    // Setup some filters
	    add_filter( 'mce_external_plugins', array( &$this, 'add_tinymce_plugin' ) );
	    add_filter( 'mce_buttons', array( &$this, 'add_tinymce_toolbar_button' ) );
	 
	}
	/**
	 * Adds a TinyMCE plugin compatible JS file to the TinyMCE / Visual Editor instance
	 *
	 * @param array $plugin_array Array of registered TinyMCE Plugins
	 * @return array Modified array of registered TinyMCE Plugins
	 */
	function add_tinymce_plugin( $plugin_array ) {
	    $plugin_array['custom_class'] = MAUV_PK_ASSETS_PATH. 'js/tinymce.js';
	    return $plugin_array;
	}
	/**
	 * Adds a button to the TinyMCE / Visual Editor which the user can click
	 * to insert a custom CSS class.
	 *
	 * @param array $buttons Array of registered TinyMCE Buttons
	 * @return array Modified array of registered TinyMCE Buttons
	 */
	function add_tinymce_toolbar_button( $buttons ) {
	    array_push( $buttons, 'mauvera_shortcode_btn' );
	    array_push( $buttons, 'mauvera_shortcode_form' );
	    return $buttons;
	}
	/**
	* Enqueues CSS for TinyMCE Dashicons
	*/
	function admin_scripts_css() {
		//wp_enqueue_style( 'tinymce-custom-class', plugins_url( 'tinymce-custom-class.css', __FILE__ ) );
	}
/**
* Adds the Custom Class button to the Quicktags (Text) toolbar of the content editor
*/
function admin_footer_scripts() {
	// Check the Quicktags script is in use
	if ( ! wp_script_is( 'quicktags' ) ) {
		return;
	}
	?>
	<script type="text/javascript">
		QTags.addButton( 'mauvera_shortcode_btn', 'Insert mauvera ticket button/link shortcode', insert_mauv_btn );
		QTags.addButton( 'mauvera_shortcode_form', 'Insert mauvera form ticket shortcode', insert_mauv_btn('form') );
		function insert_mauv(type = 'btn') {
		    // Ask the user to enter a CSS class
		    var result = prompt('Enter the ticket id');
		    if ( !result ) {
		        // User cancelled - exit
		        return;
		    }
		    if (result.length === 0) {
		        // User didn't enter anything - exit
		        return;
		    }
		    // Insert
			if(type == 'btn'){
		    QTags.insertContent('[mauvera_ticket ticket_id= '+ticket_id+']click to buy ticket[/mauvera_ticket]');
			}else{
		    QTags.insertContent('[mauvera_ticket ticket_id= '+ticket_id+']click to buy ticket[/mauvera_ticket]');
			}
		}
	</script>
	<?php
}
 
}
 
$mauv_tinymce = new Mauv_TinyMCE;