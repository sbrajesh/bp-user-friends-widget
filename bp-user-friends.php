<?php

/*
 * Plugin Name: BP User Friends Widget
 * Plugin URI: http://buddydev.com
 * Author: Brajesh Singh
 * Author URI: http://buddydev.com
 * Version: 1.0
 * Description: Show the friends of currently logged in user or displayed user based on the current screen
 * License: GPL
 *
 */
class BPUserFriendsWidget extends WP_Widget{
    
function __construct() {
        parent::__construct(false, __('User Friends Widget','bp-user-friends'));
    }
    
    //display
    
    function widget($args, $instance) {
        global $bp;
        //do not display if the user is not logged in or you are not viewing a profile
        $user_id=false;
        if(is_user_logged_in())
            $user_id=bp_loggedin_user_id ();
        
        if(bp_is_user())
            $user_id=bp_displayed_user_id ();
        if(!$user_id)
            return;
        
       
        extract( $args );
        echo $before_widget.
                $before_title.
                    $instance['title'].
                $after_title;
               self::show_list($user_id,$instance['max']);
        echo $after_widget; 
        
    }
  //update
  function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['max'] = absint( $new_instance['max'] );
        $instance['per_page'] = absint( $new_instance['per_page'] );

        return $instance; 
      }
  //widget options    
 function form($instance) {
     
        $instance = wp_parse_args( (array) $instance, array( 'title'=>__('Friends','bp-user-friends'),'max' => 5,'per_page'=>5 ) );
        $title = strip_tags( $instance['title'] );
        $max =absint( $instance['max'] );
        $per_page=  absint($instance['per_page']);
        ?>
        <p>
                <label for="bp-user-friends-widget-title"><?php _e( 'Title' , 'bp-user-friends'); ?>
                    <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $title ); ?>" />
                </label>
        </p>
        <p>
            <label for="bp-user-friends-widget-per-page"><?php _e( 'Max Number of suggestions:', 'bp-user-friends'); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'max' ); ?>" name="<?php echo $this->get_field_name( 'max' ); ?>" type="text" value="<?php echo esc_attr( $max ); ?>" style="width: 30%" />
            </label>
        </p>
<?php
}   



function show_list($user_id,$max){
  

 if ( bp_has_members( 'user_id='.$user_id ) ) : ?>

	<div  class="widget-user-paginaion">


		<div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	

	<ul id="widget-members-friends-list" class="item-list">

	<?php while ( bp_members() ) : bp_the_member(); ?>

		<li>
			<div class="item-avatar">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
			</div>

			<div class="item">
				<div class="item-title">
					<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>

					

				</div>

				<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span></div>

				
			</div>

			

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>

	</ul>

	

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="widget-user-paginaion">

		<div class="widget-user-pag-count">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="widget-user-pagination-links">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	
		<p><?php _e( "Sorry, no members were found.", 'bp-user-friends' ); ?></p>
	

<?php endif; 


 
}

}//end of class

//register widget
function bp_user_friends_register_widget(){
  add_action('widgets_init', create_function('', 'return register_widget("BPUserFriendsWidget");') );
  
}
add_action('bp_loaded','bp_user_friends_register_widget');

 

?>