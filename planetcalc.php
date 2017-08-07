<?php
/*
Plugin Name: Planetcalc
Plugin URI: http://planetcalc.com/wordpress/
Description: Embed planetcalc calculators in wordpress site
Version: 1.0
Author: Planetcalc
Author URI: http://planetcalc.com/
*/

define('PLANETCALC_DEFAULT_ITEM',72);
define('PLANETCALC_DEFAULT_WIDTH',728);
define('PLANETCALC_DEFAULT_HEIGHT',500);

function planetcalc_embed( $attrs ) {
		$defaults = array( 'sid'=>0,'cid'=>0,'width' => PLANETCALC_DEFAULT_WIDTH, 'height'=>PLANETCALC_DEFAULT_HEIGHT, 'language'=>'en','layout'=>'vertical' );
		$a= shortcode_atts( $defaults, $attrs );
		if (  $a['sid'] ) {
			return '<iframe src="http://planetcalc.com/embed/?id=' . $a['sid'] . "&language_select=" . $a['language']
		. '" scrolling="no" frameborder="0" height="' . $a['height'] . '" width="' . $a['width'] . '">PLANETCALC</iframe>';
		} else  if ( $a['cid'] ) {
			return '<iframe src="http://planetcalc.com/ext/?id=' . $a['cid'] . "&language_select=" . $a['language'] .'&layout=' . $a['layout']
		. '" scrolling="no" frameborder="0" height="' . $a['height'] . '" width="' . $a['width'] . '">PLANETCALC</iframe><p><a class="alignright" style="font-size:85%" href="http://planetcalc.com/' . $a['cid'] . '/">Origin: planetcalc.com</a></p>';
		}
}

add_shortcode('planetcalc','planetcalc_embed');


/// ----------- start: editor buttons ----------------

function planetcalc_shortcode_button_init() {
      if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
           return;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-button');
	wp_enqueue_script('planetcalc-calculators-en',plugin_dir_url( __FILE__ ) . 'calculators.en.js');
	wp_enqueue_script('planetcalc-calculators-ru',plugin_dir_url( __FILE__ ) . 'calculators.ru.js');
	wp_enqueue_script('planetcalc-calculators-es',plugin_dir_url( __FILE__ ) . 'calculators.es.js');
	wp_enqueue_style (  'wp-jquery-ui-dialog');
	add_filter("mce_external_plugins", "planetcalc_register_tinymce_plugin"); 
	add_filter('mce_buttons', 'planetcalc_add_tinymce_button');
}


function planetcalc_register_tinymce_plugin($plugin_array) {
    $plugin_array['planetcalc_button'] = plugin_dir_url( __FILE__ ) . 'planetcalc_shortcode.js';
    return $plugin_array;
}

function planetcalc_add_tinymce_button($buttons) {
    $buttons[] = "planetcalc_button";
    return $buttons;
}

foreach ( array('post.php','post-new.php') as $hook ) {
     add_action( "admin_head-$hook", 'planetcalc_admin_head' );
}

function planetcalc_admin_head() {
    $plugin_url = plugins_url( '/', __FILE__ );
    ?>
<script type='text/javascript'>
if ( !window.Planetcalc ) window.Planetcalc = {  };
window.Planetcalc.plugin_url = '<?php echo $plugin_url; ?>';
window.Planetcalc.default_item = '<?php echo PLANETCALC_DEFAULT_ITEM; ?>';
window.Planetcalc.default_width = '<?php echo PLANETCALC_DEFAULT_WIDTH; ?>';
window.Planetcalc.default_height = '<?php echo PLANETCALC_DEFAULT_HEIGHT; ?>';
</script>
    <?php
}

add_action('admin_init', 'planetcalc_shortcode_button_init');

/// ----------- end: editor buttons ----------------

?>
