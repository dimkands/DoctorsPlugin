<?php ?>
<style>.single-featured-image-header{display:none}</style>

<?php 
get_header();

    ?>
<div class="wrap">
	<div id="primary" class="content-area">
        <div class="doctor-title">
            <h1>Doctor Info</h1>
            <hr class="title-seperator">
        </div>
        <div><a href="<?php echo get_post_type_archive_link('doctors') ?>"><-Επιστροφή στους Γιατρούς</a></div><?php
        while ( have_posts() ) :the_post();
            ?>
            <div class="doctor-card"><?php
            if ( has_post_thumbnail() ) {?>
					<img class="doctor-image"><?php the_post_thumbnail(array(200, 200)); ?></img><?php 
                }
				else { ?>
					<div class="image-container"><img width="200" height="200" class="doctor-image" sizes="(max-width: 200px) 100vw, 300px" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/doctor_placeholder.jpg'; ?>"></img></div><?php
                    } ?>
                <div class="doctor-info">
                <div class="doctor-name"><?php the_title(); ?></div><hr class="name-seperator">
                <table class="single-doctor-table">
                    <tr>
                        <th>Ειδικότητα:</th>
                        <td><div class="doctor-specialty"><?php echo get_post_meta($post -> ID, '_doctor_specialty_key', true ); ?></div></td>
                    </tr>
                    <tr>
                        <th>Τηλέφωνο:</th>
                        <td><div class="doctor-phone"><?php echo get_post_meta($post -> ID, '_doctor_phone_key', true ); ?></div></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><div class="doctor-email"><?php echo get_post_meta($post -> ID, '_doctor_email_key', true ); ?></div></td>
                    </tr>
                    <tr>
                        <th>Facebook:</th>
                        <td><div class="doctor-fb"><?php echo get_post_meta($post -> ID, '_doctor_fb_key', true ); ?></div></td>
                    </tr>
                </table>
                </div>
            </div>
        <?php
        endwhile;
?>  </div>
</div>
<?php get_sidebar(); 

get_footer();
