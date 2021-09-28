<?php
/**
 * Template file for footer area
 */
$footer_copyright = '<p>IDEATO DA <a target="_blank" href="http://www.studiozecchinisrl.it/">ZECCHINI & ASSOCIATI</a>, SVILUPPATO DA <a target="_blank" href="https://www.seositimarketing.it/">SEOSITIMARKETING</a></p>'
?>
<!-- Footer Section -->
<footer class="site-footer">		
	<div class="container">
		
		   <?php get_template_part('sidebar','footer');?>
		
		<?php if($footer_copyright != null): ?>
			<div class="row">
			<div class="col-md-12">
					<div class="site-info wow fadeIn animated" data-wow-delay="0.4s">
						<?php echo wp_kses_post($footer_copyright); ?>
					</div>
				</div>			
			</div>	
		<?php endif; ?>
		
	</div>
</footer>
<!-- /Footer Section -->
<div class="clearfix"></div>
</div><!--Close of wrapper-->
<!--Scroll To Top--> 
<a href="#" class="hc_scrollup"><i class="fa fa-chevron-up"></i></a>
<!--/Scroll To Top--> 
<?php wp_footer(); ?>
</body>
</html>