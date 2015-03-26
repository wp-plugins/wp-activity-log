<?php	


if ( ! defined('ABSPATH')) exit; // if direct access 



if(empty($_POST['wp_a_log_hidden']))
	{
		$wp_a_log_member_social_field = get_option( 'wp_a_log_member_social_field' );
		$wp_a_log_member_social_icon = get_option( 'wp_a_log_member_social_icon' );

	}
else
	{	
		if($_POST['wp_a_log_hidden'] == 'Y') {
			//Form data sent
			$wp_a_log_member_social_field = stripslashes_deep($_POST['wp_a_log_member_social_field']);
			update_option('wp_a_log_member_social_field', $wp_a_log_member_social_field);
			
			$wp_a_log_member_social_icon = stripslashes_deep($_POST['wp_a_log_member_social_icon']);
			update_option('wp_a_log_member_social_icon', $wp_a_log_member_social_icon);			

			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.', 'wp_a_log' ); ?></strong></p></div>
	
			<?php
			} 
	}
	
	
	
    $wp_a_log_customer_type = get_option('wp_a_log_customer_type');
    $wp_a_log_version = get_option('wp_a_log_version');
	
	
?>





<div class="wrap">

	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".__(wp_a_log_plugin_name.' Settings', 'wp_a_log')."</h2>";?>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="wp_a_log_hidden" value="Y">
        <?php settings_fields( 'wp_a_log_plugin_options' );
				do_settings_sections( 'wp_a_log_plugin_options' );
			
		?>

    <div class="para-settings wp_a_log-settings">
    
        <ul class="tab-nav"> 
            <li nav="1" class="nav1 active">Activity</li>       
            <li nav="2" class="nav2">Help & Upgrade</li>    
        </ul> <!-- tab-nav end --> 
		<ul class="box">
       		<li style="display: block;" class="box1 tab-box active">
            
				<div class="option-box">
                    <p class="option-title">Latest Activity</p>
                    <p class="option-info"></p>
					<?php echo do_shortcode("[wp_a_log]"); ?>
                </div>
            </li>
                        
            <li style="display: none;" class="box2 tab-box">
				
				<div class="option-box">
                    <p class="option-title">Need Help ?</p>
                    <p class="option-info">Feel free to contact with any issue for this plugin, Ask any question via forum <a href="<?php echo wp_a_log_qa_url; ?>"><?php echo wp_a_log_qa_url; ?></a> <strong style="color:#139b50;">(free)</strong><br />

    <?php

    if($wp_a_log_customer_type=="free") // free
        {
    
            echo 'You are using <strong> '.$wp_a_log_customer_type.' version  '.$wp_a_log_version.'</strong> of <strong>'.wp_a_log_plugin_name.'</strong>, To get more feature you could try our premium version. ';
            
            echo '<br /><a href="'.wp_a_log_pro_url.'">'.wp_a_log_pro_url.'</a>';
            
        }
    else
        {
    
            echo 'Thanks for using <strong> premium version  '.$wp_a_log_version.'</strong> of <strong>'.wp_a_log_plugin_name.'</strong> ';	
            
            
        }
    
     ?>       

                    
                    </p>

                </div>
                
				<div class="option-box">
                    <p class="option-title">Submit Reviews...</p>
                    <p class="option-info">We are working hard to build some awesome plugins for you and spend thousand hour for plugins. we wish your three(3) minute by submitting five star reviews at wordpress.org. if you have any issue please submit at forum.</p>
                	<img src="<?php echo wp_a_log_plugin_url."css/five-star.png";?>" /><br />
                    <a target="_blank" href="<?php echo wp_a_log_wp_reviews; ?>">
                		<?php echo wp_a_log_wp_reviews; ?>
               		</a>
                    
                    
                    
                </div>
                
            </li>            
        </ul>
    
    
		

        
    </div>




<!-- 

<p class="submit">
                    <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes','wp_a_log' ); ?>" />
                </p>
-->
                
		</form>


</div>
