<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class WPaLog
	{
		
	
		public function submit_data($args)
				{
					
					$session_id = $args['session_id'];
					$date = $args['date'];				
					$time = $args['time'];								
					$content = $args['content'];							
					$is_read = $args['is_read'];					
		
				
					global $wpdb;
					$table = $wpdb->prefix . "wp_a_log";
					
					
					$wpdb->query( $wpdb->prepare("INSERT INTO $table 
												( id, session_id, date, time, content, is_read )
							VALUES	( %d, %s, %s, %s, %s, %s )",
										array	( '', $session_id, $date, $time, $content, $is_read )
								));
				
				
				}
			
			
			
			
			
			
		
		public function notification_list()
				{
					
					global $wpdb;
					 
					$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
					$limit = 20;

					$offset = ( $pagenum - 1 ) * $limit;
					$entries = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wp_a_log ORDER BY id DESC LIMIT $offset, $limit" );
					 
					if( $entries )
						{ 
			
							$html = '';
							$html.= '<ul>';
			
							$count = 1;
							foreach( $entries as $entry )
								{
									$html.= '<li>'.$entry->content;
									$html.= '<br /><span>'.$entry->date.'</span>';									
									$html.= '<span> '.$entry->time.'</span>';									
									
									$html.= '</li>';									
									
								}
								
							$html.= '</ul>';
							
							$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM {$wpdb->prefix}wp_a_log" );
							$num_of_pages = ceil( $total / $limit );
							$page_links = paginate_links( array(
								'base' => add_query_arg( 'pagenum', '%#%' ),
								'format' => '',
								'prev_text' => __( '&laquo;', 'aag' ),
								'next_text' => __( '&raquo;', 'aag' ),
								'total' => $num_of_pages,
								'current' => $pagenum
							) );
							 
							if ( $page_links ) {
								$html.= '<div class="tablenav"><div class="tablenav-pages" style=" text-align:left;margin: 1em 0;width: 100%;">' . $page_links . '</div></div><br/>';
							}
						}
					else
						{
						 	$html = 'No activity yet.';
						}
					
								
					return $html;
				}		
		
		
		public function attachment_by_id($attach_id)
				{		
					$attachment['title'] = get_the_title($attach_id);
					$attachment['url'] = wp_get_attachment_url($attach_id);
					$attachment_type = wp_check_filetype( $attachment['url'] );
					$attachment['type'] = $attachment_type['ext'];					
					
					return 	$attachment;
				}		
		
		
		public function getuser_by_id($user_id)
				{		
					$user_info = get_userdata($user_id);
					
					$user['user_login'] = $user_info->user_login;
				
					return $user;
				}
		
		
		public function getuser()
				{
					if ( is_user_logged_in() ) 
						{
							
							global $current_user;
							get_currentuserinfo();
							
							$user['display_name'] = $current_user->display_name;
							$user['ID'] = $current_user->ID;	
							$user['user_firstname'] = $current_user->user_firstname;							
							$user['user_lastname'] = $current_user->user_lastname;								
							$user['user_email'] = $current_user->user_email;
							$user['user_login'] = $current_user->user_login;													
							
						}
					else
						{
							$user['display_name'] = "guest";
						}
						
					return $user;
				}
		
		
		
		
		public function wp_a_log_date()
				{	
					$gmt_offset = get_option('gmt_offset');
					$datetime = date('Y-m-d', strtotime('+'.$gmt_offset.' hour'));
					return $datetime;
				}
					
		public function wp_a_log_time()
				{	
					$gmt_offset = get_option('gmt_offset');
					$time = date('H:i:s', strtotime('+'.$gmt_offset.' hour'));
					return $time;
				}
					
		public function wp_a_log_session_id()
				{
					$session_id = session_id();
					return $session_id;
				}
				
		public function wp_a_log_geturl_id()
					{	
						global $post;
						
						if(is_home()) // working fine with http://
							{
								$url['title'] = 'home';						
								$url['src'] = get_bloginfo( 'url' );
							}
						elseif(is_singular()) //for single post or page or custom post
							{
								$url['title'] = get_the_title();
								$url['src'] = get_permalink(get_the_ID());
							}
						elseif( is_tag()) // http added
							{
								$url['title'] = 'tag - '.get_the_title();;
								$url['src'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
							}			
							
						elseif(is_archive()) // http added
							{
								$url['title'] = 'archive - '. get_the_title();;
								$url['src'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
							}
						elseif(is_search())
							{
								$url['title'] = 'search - '.get_the_title();;
								$url['src'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
							}			
							
							
						elseif( is_404())
							{
								$url['title'] = 'erorr_404 - '.get_the_title();;
								$url['src'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
							}			
						elseif( is_admin())
							{
								$url['title'] = 'dashboard - '.get_the_title();;
								$url['src'] = admin_url();
							}	
				
						else
							{
								$url['title'] = 'unknown - '.get_the_title();;
								$url['src'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
							}
									
					
						return $url;
						
					}

		
		public function wp_a_log_html()
				{
	
					$html  = '';
					$html .= '<div class="wp-a-log-container">';
					$html .= '<div class="notification-list">'.$this->notification_list().'</div>';
					$html .= '</div>';
					return $html;
				}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
	
			
	}