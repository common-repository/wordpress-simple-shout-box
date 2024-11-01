<?php
/*
Plugin Name: Wordpress Shout Box Widget
Plugin URI: http://wordpress.org/extend/plugins/wordpress-simple-shout-box/
Description: This is a simple wordpress plugin to display shoutbox in your widget, this shoutbox saves the history of shouts in database..
Version: 2.0.2
Author: Ashok Kumar
Author URI: http://google.com/
*/
register_activation_hook( __FILE__, 'shoutbox_install' );
register_deactivation_hook( __FILE__, 'shoutbox_uninstall' );

function shoutbox_install(){

	global $wpdb;
	$wpdb->query( "CREATE TABLE IF NOT EXISTS `wp_tbl_qshout` (
		  `chat_id` int(11) NOT NULL auto_increment,
		  `user` varchar(30) collate latin1_general_ci NOT NULL,
		  `message` text collate latin1_general_ci NOT NULL,
		  `url` varchar(255) collate latin1_general_ci default NULL,
		  `date_post` datetime NOT NULL,
		  `ip` varchar(100) collate latin1_general_ci NOT NULL,
		  PRIMARY KEY  (`chat_id`));" );
				  
	$wpdb->query( "CREATE TABLE IF NOT EXISTS `wp_tbl_smiley` (
		  `smiley_id` int(11) NOT NULL auto_increment,
		  `smiley` char(10) collate latin1_general_ci NOT NULL,
		  `desc` varchar(100) collate latin1_general_ci NOT NULL,
		  `icon_name` varchar(50) collate latin1_general_ci NOT NULL,
		  PRIMARY KEY  (`smiley_id`));" );
				  
	$wpdb->query( "INSERT INTO `wp_tbl_smiley` VALUES (1, ':)', 'Smile', 'smile.png');" );
	$wpdb->query( "INSERT INTO `wp_tbl_smiley` VALUES (2, ':D', 'Laugh', 'laugh.png');" );
	$wpdb->query( "INSERT INTO `wp_tbl_smiley` VALUES (3, ':(', 'Sad', 'sad.png');" );
	$wpdb->query( "INSERT INTO `wp_tbl_smiley` VALUES (4, ';)', 'Wink', 'wink.png');" );
	$wpdb->query( "INSERT INTO `wp_tbl_smiley` VALUES (5, ':@', 'Angry', 'angry.png');" );
//	return true;

} 

function shoutbox_uninstall() {
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS wp_tbl_smiley" );
	$wpdb->query( "DROP TABLE IF EXISTS wp_tbl_qshout" );
}

//add_action('widgets_init', 'shoutbox_addcss');

function gilumothry(){?>
		<script type="text/javascript">
			var root = '<?php echo WP_PLUGIN_URL;?>/wordpress-simple-shout-box/class_qshout.php';
		</script>
<?php 
}
add_action('wp_head','gilumothry');

function my_init_method() {
	if ( !is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', WP_PLUGIN_URL .'/wordpress-simple-shout-box/jquery-1.4.2.js');
		wp_enqueue_script( 'jquery' );
	}
    wp_deregister_script( 'shoutbox_js' );
    wp_register_script( 'shoutbox_js', WP_PLUGIN_URL .'/wordpress-simple-shout-box/jquery.qshout.js');
    wp_enqueue_script( 'shoutbox_js' );
}    
add_action('init', 'my_init_method');


function shoutbox(){
	?>
	<script>
		//var root = <?php echo WP_PLUGIN_URL;?>;
		//var jq = $.noConflict();
	   jQuery(function(){
		 jQuery.QShout();
	   });
	</script>
	<?php 	
	$output = '<div id="qsId"> </div>';
	return $output;
}

class Qshout_widget extends WP_Widget {
		// Constructor
		function Qshout_widget() {
			$widget_ops = array('description' => __('Displays ShoutBox in your sidebar', 'QShout'));
			$this->WP_Widget('qshoutbox', __('QshoutBox'), $widget_ops);
		}

		// Display Widget
		function widget($args, $instance) {
			extract($args);
			$title = esc_attr($instance['title']);
			echo $before_widget.$before_title.$title.$after_title;
				echo shoutbox();
			echo $after_widget;
		}

		// When Widget Control Form Is Posted
		function update($new_instance, $old_instance) {
			if (!isset($new_instance['submit'])) {
				return false;
			}
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			return $instance;
		}


		function form($instance) {
			global $wpdb;
			$instance = wp_parse_args((array) $instance, array('title' => __('QshoutBox', 'QShout')));
			$title = esc_attr($instance['title']);
	?>

				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp-testimonials'); ?>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
	<?php
		}
	}
	
add_action('widgets_init', 'widget_Qshout_init');
function widget_Qshout_init() {
	register_widget('Qshout_widget');
}
?>