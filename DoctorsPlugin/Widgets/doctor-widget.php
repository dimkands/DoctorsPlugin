<?php
/**
 * Plugin Name: Doctors Plugin
 * Description: This plugin will add a Custom Post Type for Doctors
 * Plugin URI: 
 * Author: Dim Kant
 * Version: 1   
 * License: GPL3.0
 * 
**/
// require_once ABSPATH.'wp-includes/class-wp-widget.php';


class DoctorWidget extends WP_Widget
{
    public $widget_id;
    public $widget_name;
    public $widget_options= array();
    public $control_options = array();


    //USE THESE NAMES FOR THE FUNCTIONS FOR TO BE LOOKED BY API
    //widget constructor()
    public function __construct(){
        $widget_ops = array( 
			'classname' => 'doctor-widget-new',
			'description' => 'Latest Doctors New',
		);
		parent::__construct( 'DoctorWidget', 'Latest Docs Widget', $widget_ops );
        $this->alt_option_name = 'doctor_recent_entries';
    }

    //widget function
    function widget($args, $instance) {
        $cache = wp_cache_get('doctor-widget-new', 'widget');

        if ( !is_array($cache) )
            $cache = array();

        if ( isset($cache[$args['doctor-widget-new']]) ) {
            echo $cache[$args['doctor-widget-new']];
            return;
        }

        ob_start();
        extract($args);

        //adding title
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Recently added Doctors') : $instance['title'], $instance, $this->id_base);
        //checking view posts count
        if ( !$number = (int) $instance['number'] )
            $number = 10;
        else if ( $number < 1 )
            $number = 1;
        else if ( $number > 15 )
            $number = 15;
        //declaring post
        $r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => true, 'post_type' => array( 'doctors')));
        if ($r->have_posts()) :
        ?>
        <?php echo $before_widget; ?>
        <?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <ul class="recent-doctors">
            <?php  while ($r->have_posts()) : $r->the_post(); ?> <!-- while doctors exist create li elements with info -->
            <li>
                <div class="single-doctor-widget"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
                    <div class="doctor-widget-thumbnail"><?php the_post_thumbnail( array(100, 100));?> </div>
                    <div class="doctor-widget-info">
                        <div class="doctor-widget-name"><?php if ( get_the_title() ) the_title(); else the_ID(); ?> </div>
                        <div class="doctor-widget-specialty"><?php echo get_post_meta(get_the_ID(), '_doctor_specialty_key', true ); ?> </div>
                        <div class="doctor-widget-email"> </i><?php echo get_post_meta(get_the_ID(), '_doctor_email_key', true ); ?> </div>
                        <div class="doctor-widget-phone"> </i><?php echo get_post_meta(get_the_ID(), '_doctor_phone_key', true ); ?> </div></a>
                    </div>
                </div>
            </li>
            <?php endwhile; ?>
        </ul>
        <?php echo $after_widget; ?>
        <?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;

        $cache[$args['doctor-widget-new']] = ob_get_flush();
        wp_cache_set('doctor-widget-new', $cache, 'widget');
    }

    function flush_widget_cache() {
        wp_cache_delete('doctor-widget-new', 'widget');
    }
    // form function()
    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
            $number = 5;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        <?php
    }

    //update function()
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }

}
add_action( 'widgets_init', function(){
	register_widget( 'DoctorWidget' );
}); 

