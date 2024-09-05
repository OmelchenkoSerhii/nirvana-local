<footer id="footer" class="footer footer-booking">

	<div class="footer__bottom">
		<div class="footer__bottom__inner">

			<?php
			$footerCopyright = get_field( 'footer_copyright', 'option' );
			if ( $footerCopyright ) :
				?>
				<div class="footer__bottom__copyright"><?php echo $footerCopyright; ?></div>
			<?php endif; ?>

		</div>
	</div>
	
	<?php acf_map_display(); ?>
</footer>
