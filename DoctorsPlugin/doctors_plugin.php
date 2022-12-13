<?php
/**
 * Plugin Name: Doctors Plugin
 * Description: Doctors post type plugin, contains archive and single pages templates for doctors. 
 * Plugin URI: 
 * Author: Dimitris Kantarakis
 * Version: 1   
 * License: GPL3.0
 * 
**/
//* Don't access this file directly
defined( 'ABSPATH' ) or die();
include 'Widgets/doctor-widget.php';



// Register Doctor Custom Type
function custom_post_type() {
    
    $menu_icon = file_get_contents( plugin_dir_path( __FILE__ ) . 'assets/red-cross.svg' );
	$labels = array(
		'name'                  => _x( 'Doctors', 'Post Type General Name', 'doctors' ),
		'singular_name'         => _x( 'Doctor', 'Post Type Singular Name', 'doctors' ),
		'menu_name'             => __( 'Doctors', 'doctors' ),
		'name_admin_bar'        => __( 'doctors', 'doctors' ),
		'archives'              => __( 'Doctors Archives', 'doctors' ),
		'attributes'            => __( 'Doctors Attributes', 'doctors' ),
		'parent_item_colon'     => __( 'Parent Doctor:', 'doctors' ),
		'all_items'             => __( 'All Doctors', 'doctors' ),
		'add_new_item'          => __( 'Add New Doctor', 'doctors' ),
		'add_new'               => __( 'Add New', 'doctors' ),
		'new_item'              => __( 'New Doctor', 'doctors' ),
		'edit_item'             => __( 'Edit Doctor', 'doctors' ),
		'update_item'           => __( 'Update Doctor', 'doctors' ),
		'view_item'             => __( 'View Doctor', 'doctors' ),
		'view_items'            => __( 'View Doctors', 'doctors' ),
		'search_items'          => __( 'Search Doctor', 'doctors' ),
		'not_found'             => __( 'Doctor not found', 'doctors' ),
		'not_found_in_trash'    => __( 'Doctor not found in Trash', 'doctors' ),
		'featured_image'        => __( 'Doctor Featured Image', 'doctors' ),
		'set_featured_image'    => __( 'Set Doctor featured image', 'doctors' ),
		'remove_featured_image' => __( 'Remove Doctor featured image', 'doctors' ),
		'use_featured_image'    => __( 'Use as featured image', 'doctors' ),
		'insert_into_item'      => __( 'Insert into Doctor', 'doctors' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Doctor', 'doctors' ),
		'items_list'            => __( 'Doctors list', 'doctors' ),
		'items_list_navigation' => __( 'Doctors list navigation', 'doctors' ),
		'filter_items_list'     => __( 'Filter Doctors list', 'doctors' ),
	);
	$args = array(
		'label'                 => __( 'doctor', 'doctors' ),
		'description'           => __( 'Post Type Description', 'doctors' ),
		'labels'                => $labels,
		'supports'              => array('title','thumbnail','date'),
		'taxonomies'            => array(''),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
        'menu_icon'             => 'data:image/svg+xml;base64,' . base64_encode( $menu_icon ),
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'doctors', $args );

}
add_action( 'init', 'custom_post_type', 0 );


// enqueue all our scripts
function enqueue() {
    $my_js_ver  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/assets/myscript.js' ));
    $my_css_ver = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/assets/mystyle.css' ));
    wp_enqueue_style( 'mypluginstyle', plugins_url( '/assets/mystyle.css', __FILE__ ), false, $my_css_ver);
    wp_enqueue_script( 'mypluginscript', plugins_url( '/assets/myscript.js', __FILE__ ), array(), $my_js_ver );
}

add_action( 'admin_enqueue_scripts', 'enqueue' );
add_action( 'wp_enqueue_scripts', 'enqueue' );


//change title text to Name
function change_title_text( $title ){
    $screen = get_current_screen();
  
    if  ( 'doctors' == $screen->post_type ) {
         $title = 'Ονοματεπώνυμο';
    }
  
    return $title;
}
add_filter( 'enter_title_here', 'change_title_text' );


//register doctor info meta box
function register_meta_boxes() {
	add_meta_box( 'doctor-info', __( 'Στοιχεία Γιατρού', 'doctors' ), 'my_display_callback', 'doctors' );
    add_meta_box('by-doctor', __('Σύνδεση άρθρου με γιατρό', 'by-doctor'), 'by_doctor_callback','post');
}
add_action( 'add_meta_boxes', 'register_meta_boxes' );

//display of meta box

function by_doctor_callback($post){
    $i = 0;
    wp_nonce_field('by-doctor', 'by_doctor_nonce');

    $doctors = get_post_meta($post -> ID, '_post_connected_doctors_key',true);
    
    $doctorsToDisplay = preg_split("#,#", $doctors);


    $r = new WP_Query( array( 'post_type' => 'doctors' ) ); ?>
    <div class="select-doctor-container">   
        <div class="select-by-doctor-box">     
            <select name="doctors" id="doctors-selection"><?php
            if ($r -> have_posts()):	{
                while ($r -> have_posts()) : $r -> the_post();
                ?> 
                <option value="doctor_name"><?php echo get_the_title(); ?></option><?php
                endwhile;
            }
            endif;   ?>
            </select>
        </div>
        <div class="button-div"><button class="add-button">Προσθήκη</button></div>
        <div class="by-doctors-box-spans"><?php
        if(!empty($doctorsToDisplay) ){             //&& in_array(" ", $doctorsToDisplay, FALSE)
            foreach ($doctorsToDisplay as $doctor){                
                if($doctor == ''){
                    echo " ";
                }
                else{?>
                <div class="doctor-name-box">
                    <span class="doctor-name"><?php echo esc_attr($doctor) ?></span>
                    <span class="remove" id="remove_<?php echo esc_attr( $i++ )?>">x</span>
                </div><?php                
                }
            }
        ?>
        </div>
        <?php
        }?>   
        <div id="error-message" style="display:none">Ο γιατρός υπάρχει ήδη στο άρθρο.</div>
        <input type="hidden" id="doctors-input" name="doctors-input" value="<?php echo esc_attr($doctors) ?>"></input>
    </div>
    <?php
}

function save_by_doctor_meta_box( $post_id ) {
	if(!isset($_POST['by_doctor_nonce'])){
        return $post_id;
    }

    $nonce = $_POST['by_doctor_nonce'];
    if(!wp_verify_nonce($nonce,'by-doctor')){
        return $post_id;
    }

    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
        return $post_id;
    }

    if(!current_user_can('edit_post',$post_id)){
        return $post_id;
    }

    update_post_meta($post_id,'_post_connected_doctors_key', $_POST['doctors-input']);
}
add_action( 'save_post', 'save_by_doctor_meta_box' );



function my_display_callback( $post ) {
	wp_nonce_field('doctor_info', 'doctor_info_nonce');

    // $name = get_post_meta($post -> ID, '_doctor_name_key',true);
    // $last_name = get_post_meta($post -> ID, '_doctor_last_name_key',true);
    $specialty = get_post_meta($post -> ID, '_doctor_specialty_key',true);
    $phone = get_post_meta($post -> ID, '_doctor_phone_key',true);
    $email = get_post_meta($post -> ID, '_doctor_email_key',true);
    $fb = get_post_meta($post -> ID, '_doctor_fb_key',true);


    ?>
    <table class="add_doctors_table">
    <!-- <tr>
        <th scope="row"><label for="doctor_name">Όνομα:</label></th>            
        <td><input type="text" id="doctor_name" name="doctor_name" value=""></input></td>        //insert value
    </tr>
    <tr>
        <th scope="row"><label for="doctor_last_name">Επίθετο:</label></th>
        <td><input type="text" id="doctor_last_name" name="doctor_last_name" value=""></input></td> //insert value
    </tr> --> 
    <tr>
        <th scope="row"><label for="doctor_specialty">Ειδικότητα:</label></th>
        <td><input type="text" id="doctor_specialty" name="doctor_specialty" value="<?php echo esc_attr($specialty) ?>"></input></td>
    </tr>
    <tr>
        <th scope="row"><label for="doctor_phone">Τηλέφωνο:</label></th>
        <td><input type="tel" id="doctor_phone" name="doctor_phone" value="<?php echo esc_attr($phone) ?>"></input></td>
    </tr>
    <tr>
        <th scope="row"><label for="doctor_email">Email:</label></th>
        <td><input type="text" id="doctor_email" name="doctor_email" value="<?php echo esc_attr($email) ?>"></input></td>
    </tr>
    <tr>
        <th scope="row"><label for="doctor_fb">Facebook:</label></th>
        <td><input type="text" id="doctor_fb" name="doctor_fb" placeholder="https://www.facebook.com/" value="<?php echo esc_attr($fb) ?>" ></input></td>
    </tr>
    </table>

    <?php
}


//save metabox fields
function save_meta_box( $post_id ) {
	if(!isset($_POST['doctor_info_nonce'])){
        return $post_id;
    }

    $nonce = $_POST['doctor_info_nonce'];
    if(!wp_verify_nonce($nonce,'doctor_info')){
        return $post_id;
    }

    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
        return $post_id;
    }

    if(!current_user_can('edit_post',$post_id)){
        return $post_id;
    }

    // $name = sanitize_text_field($_POST['doctor_name']);
    // $last_name = sanitize_text_field($_POST['doctor_last_name']);
    // $specialty = sanitize_text_field($_POST['doctor_specialty']);
    // $phone = sanitize_text_field($_POST['doctor_phone']);
    // $email = sanitize_text_field($_POST['doctor_email']);
    // $fb = sanitize_text_field($_POST['doctor_fb']);
    // if(array_key_exists('doctor_name_field', $_POST)){
    //     update_post_meta(
    //         $post_id,
    //         '_doctor_name_key',
    //         $_POST['doctor_name_field']
    //     );
    // }    
    // update_post_meta($post_id,'_doctor_name_key', $_POST['doctor_name']);
    // update_post_meta($post_id,'_doctor_last_name_key',$_POST['doctor_last_name']);
    update_post_meta($post_id,'_doctor_specialty_key', $_POST['doctor_specialty']);
    update_post_meta($post_id,'_doctor_phone_key',$_POST['doctor_phone']);
    update_post_meta($post_id,'_doctor_email_key', $_POST['doctor_email']);
    update_post_meta($post_id,'_doctor_fb_key', $_POST['doctor_fb']);

}
add_action( 'save_post', 'save_meta_box' );


//create custom columns for 'all doctors' display
add_filter( 'manage_doctors_posts_columns', 'set_custom_edit_doctors_columns' );
function set_custom_edit_doctors_columns($columns) {
    //unset( $columns['title'] );
    unset( $columns['date'] );
    //$columns['doctor_name'] = __( 'Όνομα', 'doctor_name' );
    $columns['doctor_img'] = __( 'Featured Image', 'doctor_img' );
    $columns['doctor_specialty'] = __( 'Ειδικότητα', 'doctor_specialty' );
    $columns['doctor_email'] = __( 'Email', 'doctor_email' );
    $columns['doctor_phone']=__('Αριθμός τηλεφώνου','doctor_phone');

    return $columns;
}

add_filter( 'manage_edit-doctors_columns', 'rename_title' );
function rename_title($columns){
    $columns['title']='Ονοματεπώνυμο';
    return $columns;
}
// do_action( 'manage_link_custom_column', 'doctor_name', $item->link_id );

//add content in the custom columns
add_action( 'manage_doctors_posts_custom_column' , 'custom_doctors_column', 10, 2 );
function custom_doctors_column( $column, $post_id ) {
    switch ( $column ) {
        // case 'doctor_name' :
        //     $doctor_name = get_post_meta( $post_id, '_doctor_name_key', true );
        //     if ( is_string( $doctor_name ) )
        //         echo $doctor_name;
        //     else
        //         _e( 'Unable to get author(s)', 'your_text_domain' );
        //     break;

        case 'doctor_img' :
            echo get_the_post_thumbnail(  $post_id, array( 80, 80)); 
            break;

        case 'doctor_specialty' :
            echo get_post_meta( $post_id , '_doctor_specialty_key' , true ); 
            break;

        case 'doctor_email' :
            echo get_post_meta( $post_id , '_doctor_email_key' , true ); 
            break;
        
        case 'doctor_phone' :
            echo get_post_meta( $post_id , '_doctor_phone_key' , true ); 
            break;
    }
}

// //add custom template for archive page
// add_filter('archive_template', 'get_archive_doctors_template');
// function get_archive_doctors_template($template) {
//     global $post;
//     //$plugin_root_dir = plugin_dir_url(__FILE__).'/DoctorsPlugin/';
   
//     if (is_post_type_archive('doctors')) {
//         $template = WP_PLUGIN_DIR .'/'. plugin_basename( dirname(__FILE__) ) .'/page-templates/archive-doctors.php';
//     }
//     return $template;
// }

function load_cpt_archive_template($template){
    global $post;
    if($post->post_type == "doctors"){
        $plugin_path = plugin_dir_path( __FILE__ );
        $template_name = 'page-templates/archive-doctors.php';

        if($template === get_stylesheet_directory( ). '/' .$template_name){
            return $template;
        }

        return $plugin_path . $template_name;
    }
    return $template;
}
add_filter('archive_template', 'load_cpt_archive_template');


function load_cpt_single_template($template){
    global $post;
    if($post->post_type == "doctors"){
        $plugin_path = plugin_dir_path( __FILE__ );
        $template_name = 'page-templates/single-doctors.php';

        if($template === get_stylesheet_directory( ). '/' .$template_name){
            return $template;
        }

        return $plugin_path . $template_name;
    }
    return $template;
}
add_filter('single_template', 'load_cpt_single_template');

// //add custom template for single page
// add_filter('single_template', 'get_single_doctors_template');
// function get_single_doctors_template($template) {
//     if(is_singular('doctors')){
//         $template = WP_PLUGIN_DIR .'/'. plugin_basename( dirname(__FILE__) ) .'/page-templates/single-doctors.php';
//     }
// return $template;
// }

//pagination 
function doctors_pagesize( $query ) {
	if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'doctors' ) ) {
		$query->set( 'posts_per_page', 6 );
		return;
	}
}
add_action( 'pre_get_posts', 'doctors_pagesize', 1 );


// add_action( 'get_header', 'child_remove_page_titles' );
// function child_remove_page_titles() {
// if ( is_page_template('single-doctors.php') )
// remove_action( 'genesis_post_title', 'genesis_do_post_title' );
// } 

//register widget

// function register(){
//     if(!$this->activated('doctors_widget')) return;

//     $doctors_widget = new DoctorWidget();

//     $doctors_widget -> register();
// }
// register_widget('DoctorWidget');

function activate() {
    // generated a CPT
    custom_post_type();
    // flush rewrite rules
    flush_rewrite_rules();
}

function deactivate() {
    // flush rewrite rules
    flush_rewrite_rules();
}

// activation
register_activation_hook( __FILE__,  'activate'  );

// deactivation
register_deactivation_hook( __FILE__, 'deactivate'  );


