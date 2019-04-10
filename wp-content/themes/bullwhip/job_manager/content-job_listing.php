<?php global $post; ?>
<li <?php job_listing_class(); ?> data-longitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_long ); ?>">
	<a href="<?php the_job_permalink(); ?>">
		<div class="position">
			<h3><?php the_title(); ?></h3>
		</div>
		<div class="location">
			<?php the_job_location( false ); ?>
		</div>
		<ul class="meta">
			<?php do_action( 'job_listing_meta_start' ); ?>

			<li class="job-type <?php echo get_the_job_type() ? sanitize_title( get_the_job_type()->slug ) : ''; ?>"><?php the_job_type(); ?></li>

			<?php do_action( 'job_listing_meta_end' ); ?>
		</ul>
	</a>
    <div class="job_description" itemprop="description">
        <?php echo apply_filters( 'the_job_description', get_the_content() ); ?>
    </div>
    <?php // print do_shortcode('[button url="'.get_the_job_permalink().'"]Apply Now[/button]'); ?>
</li>