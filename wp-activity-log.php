<?php
/*
Plugin Name: WP Activity Log
Plugin URI: http://paratheme.com/
Description: What is happening on your site frontend or admin get all acitivity.
Version: 1.0
Author: paratheme
Author URI: http://paratheme.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


*/

if ( ! defined('ABSPATH')) exit;  // if direct access 




define('wp_a_log_plugin_url', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
define('wp_a_log_plugin_dir', plugin_dir_path( __FILE__ ) );
define('wp_a_log_wp_url', 'https://wordpress.org/plugins/wp-activity-log/' );
define('wp_a_log_wp_reviews', 'http://wordpress.org/support/view/plugin-reviews/wp-activity-log' );
define('wp_a_log_pro_url', 'http://paratheme.com/items/wp-activity-log-trace-your-wordpress-site-activity/' );
define('wp_a_log_demo_url', 'http://paratheme.com' );
define('wp_a_log_conatct_url', 'http://paratheme.com/contact' );
define('wp_a_log_qa_url', 'http://paratheme.com/qa/' );
define('wp_a_log_plugin_name', 'WP Activity Log' );
define('wp_a_log_share_url', 'http://wordpress.org/plugins/wp-activity-log/' );
define('wp_a_log_tutorial_video_url', '//www.youtube.com/embed/8OiNCDavSQg?rel=0' );


require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/WPaLog.php');



function wp_a_log_init_scripts()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('wp_a_log_js', plugins_url( '/js/wp_a_log-scripts.js' , __FILE__ ) , array( 'jquery' ));	
		wp_localize_script('wp_a_log_js', 'wp_a_log_ajax', array( 'wp_a_log_ajaxurl' => admin_url( 'admin-ajax.php')));
		wp_enqueue_style('wp_a_log_style', wp_a_log_plugin_url.'css/style.css');

		//ParaAdmin
		wp_enqueue_style('ParaAdmin', wp_a_log_plugin_url.'ParaAdmin/css/ParaAdmin.css');
		//wp_enqueue_style('ParaIcons', accordions_plugin_url.'ParaAdmin/css/ParaIcons.css');		
		wp_enqueue_script('ParaAdmin', plugins_url( 'ParaAdmin/js/ParaAdmin.js' , __FILE__ ) , array( 'jquery' ));
		

				
						
				
	}
add_action("init","wp_a_log_init_scripts");







register_activation_hook(__FILE__, 'wp_a_log_activation');
register_deactivation_hook(__FILE__, 'wp_a_log_deactivation');

function wp_a_log_activation()
	{
		load_plugin_textdomain('wp_a_log', false, basename( dirname( __FILE__ ) ) . '/languages' );
		
		
		
		
		
		
		global $wpdb;
		
        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "wp_a_log"
                 ."( UNIQUE KEY id (id),
					id int(100) NOT NULL AUTO_INCREMENT,
					session_id	VARCHAR( 255 )	NOT NULL,
					date	DATE NOT NULL,
					time	TIME NOT NULL,
					content	TEXT NOT NULL,
					is_read	VARCHAR( 255 )	NOT NULL
					)";
		$wpdb->query($sql);
		
		
		
		
		
		$wp_a_log_version= "1.0";
		update_option('wp_a_log_version', $wp_a_log_version); //update plugin version.
		
		$wp_a_log_customer_type= "free"; //customer_type "pro"
		update_option('wp_a_log_customer_type', $wp_a_log_customer_type); //update plugin version.
		
		


		
		
	}

function wp_a_log_deactivation()
	{
		
		// nothing

		
	}


function wp_a_log_register_session(){
    if( !session_id() )
        session_start();


		
}
add_action('init','wp_a_log_register_session');









function wp_a_log_visit()
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();

		$user = $wp_a_log->getuser();		
		$url = $wp_a_log->wp_a_log_geturl_id();
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>visit</u> <a href="'.$url['src'].'">'.$url['title'].'</a>';
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
	
add_action('wp_head', 'wp_a_log_visit');


function wp_a_log_publish_page($ID, $post)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$url = $ID;
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>published</u> page <b>'.get_the_title($ID).'</b>';				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('publish_page', 'wp_a_log_publish_page');





function wp_a_log_publish_post($ID, $post)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$url = $wp_a_log->wp_a_log_geturl_id();
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>published</u> post <b>'.get_the_title($ID).'</b>';				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('publish_post', 'wp_a_log_publish_post');

function wp_a_log_post_updated($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$url = $ID;
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>updated</u> a post <b>'.get_the_title($ID).'</b>';				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
//add_action('post_updated', 'wp_a_log_post_updated');

function wp_a_log_deleted_post($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>deleted</u> a post <b>'.get_the_title($ID).'</b>';				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('deleted_post', 'wp_a_log_deleted_post');


function wp_a_log_trashed_post($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$url = $ID;
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>trashed</u> a post <b>'.get_the_title($ID).'</b>';				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('trashed_post', 'wp_a_log_trashed_post');


function wp_a_log_untrashed_post($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$url = $ID;
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>untrashed</u> a post <b>'.get_the_title($ID).'</b>';				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('untrashed_post', 'wp_a_log_untrashed_post');



function wp_a_log_wp_insert_comment($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$url = $wp_a_log->wp_a_log_geturl_id();
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>posted comment</u> on a post' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('wp_insert_comment', 'wp_a_log_wp_insert_comment');



function wp_a_log_edit_comment($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>edited comment</u> on a post' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('edit_comment', 'wp_a_log_edit_comment');



function wp_a_log_trash_comment($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>trashed comment</u> on a post' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('trash_comment', 'wp_a_log_trash_comment');


function wp_a_log_untrash_comment($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>untrashed comment</u> on a post' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('untrash_comment', 'wp_a_log_untrash_comment');

function wp_a_log_spam_comment($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>spamed comment</u> on a post' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('spam_comment', 'wp_a_log_spam_comment');


function wp_a_log_unspam_comment($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>unspamed comment</u> on a post' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('unspam_comment', 'wp_a_log_unspam_comment');

function wp_a_log_delete_comment($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>deleted comment</u> on a post' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('delete_comment', 'wp_a_log_delete_comment');



function wp_a_log_switch_theme($name)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>switched theme</u> <b>'.$name.'</b>' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('switch_theme', 'wp_a_log_switch_theme');


function wp_a_log_delete_site_transient_update_themes($name)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>theme deleted</u>' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('delete_site_transient_update_themes', 'wp_a_log_delete_site_transient_update_themes');


function wp_a_log_upgrader_process_complete($upgrader, $extra)
	{
		$slug = $upgrader->theme_info();
		
		$theme   = wp_get_theme( $slug );
		$name    = $theme->name;
		$version = $theme->version;
		
		
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>updated theme</u> '.$name .' - '.$version;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
//add_action('upgrader_process_complete', 'wp_a_log_upgrader_process_complete');









function wp_a_log_activated_plugin($plugin_name)
	{
		if(!empty($plugin_name))
			{
				$plugin_dir  = explode( '/', $plugin_name );
				$plugin_data = array_values( get_plugins( '/' . $plugin_dir[0] ) );
				$plugin_data = array_shift( $plugin_data );
				$plugin_name = $plugin_data['Name'];
				
			}
		
		
		
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>activated plugin</u> <b>'.$plugin_name.'</b>'	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('activated_plugin', 'wp_a_log_activated_plugin');


function wp_a_log_deactivated_plugin($plugin_name)
	{
		
		if(!empty($plugin_name))
			{
				$plugin_dir  = explode( '/', $plugin_name );
				$plugin_data = array_values( get_plugins( '/' . $plugin_dir[0] ) );
				$plugin_data = array_shift( $plugin_data );
				$plugin_name = $plugin_data['Name'];
				
			}
		
		
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>deactivated plugin</u> <b>'.$plugin_name.'</b>'	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('deactivated_plugin', 'wp_a_log_deactivated_plugin');


function wp_a_log_wp_login($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$ID.'</b>  <u>logged-in</u>.'	;				

		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('wp_login', 'wp_a_log_wp_login');



function wp_a_log_wp_logout()
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();
		$user = $wp_a_log->getuser();
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>logged-out</u>.'	;				

		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('wp_logout', 'wp_a_log_wp_logout');





function wp_a_log_wp_login_failed($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$ID.'</b>  <u>login failed</u>.'	;				

		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('wp_login_failed', 'wp_a_log_wp_login_failed');




function wp_a_log_user_register($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser_by_id($ID);
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['user_login'].'</b>  <u>register</u> on your site.'	;				

		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('user_register', 'wp_a_log_user_register');


function wp_a_log_delete_user($ID)
	{
		$wp_a_log = new WPaLog();

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();			
		$args['time'] = $wp_a_log->wp_a_log_time();	
		$user = $wp_a_log->getuser();
		$deleted_user = $wp_a_log->getuser_by_id($ID);		
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>deleted user</u> <b>'.$deleted_user['user_login'].'</b>' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('delete_user', 'wp_a_log_delete_user');



function wp_a_log_profile_update($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();
		$update_user = $wp_a_log->getuser_by_id($ID);		
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>update profile</u> <b>'.$update_user['user_login'].'</b>' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('profile_update', 'wp_a_log_profile_update');



function wp_a_log_add_attachment($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();
		$attachment = $wp_a_log->attachment_by_id($ID);	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>added attachment</u> <b>'.$attachment['title'].'.'.$attachment['type'].'</b>' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}
add_action('add_attachment', 'wp_a_log_add_attachment');



function wp_a_log_edit_attachment($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();
		$attachment = $wp_a_log->attachment_by_id($ID);	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>edited attachment</u> <b>'.$attachment['title'].'.'.$attachment['type'].'</b>' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}

add_action('edit_attachment', 'wp_a_log_edit_attachment');


function wp_a_log_delete_attachment($ID)
	{
		$wp_a_log = new WPaLog();	

		$args['session_id'] = $wp_a_log->wp_a_log_session_id();
		$args['date'] = $wp_a_log->wp_a_log_date();				
		$args['time'] = $wp_a_log->wp_a_log_time();		
		$user = $wp_a_log->getuser();
		$attachment = $wp_a_log->attachment_by_id($ID);	
		$args['is_read'] = '0';	
		$args['content'] = '<b>'.$user['display_name'].'</b>  <u>deleteed attachment</u> <b>'.$attachment['title'].'.'.$attachment['type'].'</b>' 	;				
		
		
		//submit data when visit.	
		$wp_a_log->submit_data($args);
	}

add_action('delete_attachment', 'wp_a_log_delete_attachment');












function wp_a_log_display($atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'id' => "",

				), $atts);


			$post_id = $atts['id'];

			$wp_a_log_themes = get_post_meta( $post_id, 'wp_a_log_themes', true );

			$html = '';

			$wp_a_log = new WPaLog();
			$html.= $wp_a_log->wp_a_log_html();						


			return $html;
	
							





}

add_shortcode('wp_a_log', 'wp_a_log_display');



add_action('admin_menu', 'wp_a_log_menu_init');

function wp_a_log_menu_settings(){
	include('wp-a-log-settings.php');	
}


function wp_a_log_menu_init()
	{
		
		
		add_menu_page(__('WP Activity Log','access_denied'), __('WP Activity Log','access_denied'), 'manage_options', 'wp_a_log_menu_settings', 'wp_a_log_menu_settings');

	
		
	}



