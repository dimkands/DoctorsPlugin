<?php get_header();?>
<div class="wrap-doctors-archive">
	<div class="title-col" id="doctors-title"> <h1>Doctors Archive</h1></div>
	<div class="doctors-archive-content">
 		<div class="doctors-archive-row">
		<?php
		if (have_posts()):		
			while (have_posts()) :the_post();?>
				<div class="doctors-archive-column">	
					<div class="doctor-name"><?php the_title(); ?></div><?php
					if ( has_post_thumbnail() ) {?>
						<img class="doctor-image"><?php the_post_thumbnail(array(300, 300)); ?></img>
					<?php }
					else  { ?>
						<img width="300" height="300" class="attachment-300x300 size-300x300 wp-post-image" sizes="(max-width: 300px) 100vw, 300px" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/doctor_placeholder.jpg'; ?>"></img>
					<?php } ?>
					<table class="doctor-archive-table">
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
					<?php $post_link = get_post_permalink($post -> ID); ?>
					<div class="link-box"><a class="doctor-single-page" href="<?php echo esc_attr($post_link)?>">Σελίδα Γιατρού</a></div>
				</div>		
			<?php endwhile;				
		endif;
			//  wp_reset_query();
		?>
		</div>
		<div class="container-pagination">
			<div class="newer-entries"><?php previous_posts_link('«Newer Entries',0);?></div> 
			<div class="older-entries"><?php next_posts_link( 'Older Entries »', 0 );?></div> 
		</div>
	<?php wp_reset_postdata();?>
</div>
<?php 
get_sidebar();
get_footer(); 
