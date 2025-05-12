</div><!-- #content -->

<footer id="footer" class="footer">
	<div class="container">
		<div class="vantage__wrap flex-center-sb">
			<?php $vantage = new WP_Query(array(
				'post_type' => 'vantage'
			));

			if ($vantage->have_posts()) :
				while ($vantage->have_posts()) : $vantage->the_post(); ?>
					<div class="vantage__item flex-center-sb">
						<img class="vantage__icon" src="<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>" alt="<?php the_title(); ?>">
						<span class="vantage__title"><?php the_title(); ?></span>
					</div>
			<?php endwhile;
				wp_reset_postdata();
			endif; ?>
		</div>
	</div>
</footer><!-- #footer -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>

</html>