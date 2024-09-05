<footer id="footer" class="footer">

	<div class="footer__main py-md-10 pt-8 pb-2 pb-sm-5">

		<div class="container">
			<div class="row align-items-center justify-content-center">

				<div class="col-12 col-md-3 mb-5 mb-md-0 footer__main__col">

					<?php
					$footerLogo = get_field( 'footer_logo', 'option' );
					?>
					<?php if ( $footerLogo ) : ?>
						<a href="<?php echo get_home_url(); ?>" class="footer__main__logo">
							<img src="<?php echo esc_url( $footerLogo['url'] ); ?>" alt="<?php echo esc_attr( $footerLogo['alt'] ); ?>" />
						</a>
					<?php endif; ?>

					<?php
					$footerText = get_field( 'footer_text', 'option' );
					if ( $footerText ) :
						?>
						<div class="footer__main__text">
							<div class="content-block mt-3"><?php echo $footerText; ?></div>
						</div>
					<?php endif; ?>

				</div>

				<div class="col-12 col-sm-8 col-md-6 col-lg-5 mb-5 mb-sm-0 offset-lg-1 footer__main__col">
					<nav class="footer__main__nav text-uppercase p1">
						<?php
						wp_nav_menu(
							array(
								'menu_id'         => 'footer-nav',
								'container_class' => 'footer-nav',
								'theme_location'  => 'footer-menu-1',
							)
						);
						?>
					</nav>
				</div>

				<div class="col-8 col-sm-4 col-md-3 col-lg-2 offset-lg-1 footer__main__col">
					<?php
					$footerSocial = get_field( 'social_media_icons', 'option' );
					if ( $footerSocial ) :
						?>
						<div class="footer__main__social d-flex flex-column">
							<p class="text-center text-sm-left"><?php echo esc_html_e( 'Share your experience with us', 'nirvana' ); ?></p>
							<ul class="footer__main__social__list d-flex justify-content-between align-items-center">
								<?php
								foreach ( $footerSocial as $item ) :
									$icon = $item['icon'];
									$link = $item['link'];
									if ( $link ) :
										$link_url    = $link['url'];
										$link_title  = $link['title'];
										$link_target = $link['target'] ? $link['target'] : '_self';
										?>
										<li>
											<a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" title="<?php echo esc_html( $link_title ); ?>">
												<?php echo $icon; ?>
											</a>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="footer__bottom">
		<div class="container footer__bottom__inner">

			<?php
			$footerCopyright = get_field( 'footer_copyright', 'option' );
			if ( $footerCopyright ) :
				?>
				<div class="footer__bottom__copyright"><?php echo $footerCopyright; ?></div>
			<?php endif; ?>

			<?php
			$menu_footer = wp_nav_menu(
				array(
					'menu_id'         => 'footer-nav',
					'container_class' => 'footer-nav',
					'theme_location'  => 'footer-bottom-menu',
					'echo'            => false,
					'fallback_cb'     => '__return_false',
				)
			);

			if ( ! empty( $menu_footer ) ) {
				?>
				<div class="footer__bottom__nav">
					<?php echo $menu_footer; ?>
				</div>
			<?php } ?>
		</div>
	</div>
	
	<?php acf_map_display(); ?>
</footer>
