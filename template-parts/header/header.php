<?php
$headerTransparent = get_field( 'transparent_header' );
$headerDark        = get_field( 'dark_header' );

$headerLogo      = get_field( 'upload_dark_logo', 'option' );
$headerLogoWhite = get_field( 'upload_light_logo', 'option' );

$hideNav = get_field( 'hide_navigation', 'option' );

$button1 = get_field( 'header_button_1', 'option' );
$button2 = get_field( 'header_button_2', 'option' );

$info_bar = get_field('show_info_bar' , 'option');
$info_bar_content = get_field('header_info_bar_content' , 'option');

?>

<header id="header">
	<div class="header__transparent--bg position-absolute w-100 h-100"></div>
	<?php if($info_bar && $info_bar_content): ?>
		<div class="header__info-bar header__info-bar--closed">
			<div class="header__info-bar__content"><?php echo $info_bar_content; ?></div>
			<span class="header__info-bar__close"></span>
		</div>
	<?php endif; ?>
	<div class="header container">
		<a href="<?php echo get_home_url(); ?>/" title="<?php echo get_bloginfo( 'name' ); ?>" class="header__logo">
			<?php if ( ! empty( $headerLogoWhite ) ) : ?>
				<div class="header__logo__img--white">
					<?php image_acf( $headerLogoWhite ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $headerLogo ) ) : ?>
				<div class="header__logo__img--dark">
					<?php image_acf( $headerLogo ); ?>
				</div>
			<?php endif; ?>
		</a>
				
		<?php if ( ! $hideNav ) : ?>
			<div class="header__nav__wrapper">
				<nav class="header__nav">

					<div class="main-nav text-uppercase font--weight--700 p1">
						<?php
						wp_nav_menu(
							array(
								'menu_id'         => 'main-nav',
								'container_class' => '',
								'theme_location'  => 'main-menu',
								//'walker'          => new EventsSubMenu(),
							)
						);
						?>
					</div>

					<?php if ( $button1 || $button2 ) : ?>
						<div class="header__nav__buttons">
							
							<?php
							if ( $button1 ) :
								$link_url    = $button1['url'];
								$link_title  = $button1['title'];
								$link_target = $button1['target'] ? $button1['target'] : '_self';
								?>
								<a class="button button--transparent" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
							<?php endif; ?>

							<?php
							if ( $button2 ) :
								$link_url    = $button2['url'];
								$link_title  = $button2['title'];
								$link_target = $button2['target'] ? $button2['target'] : '_self';
								?>
								<a class="button button--transparent" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
							<?php endif; ?>

						</div>
					<?php endif; ?>

				</nav>
			</div>
		<?php endif; ?>

		<div class="header__buttons">
			<?php 
			if (is_plugin_active('gtranslate/gtranslate.php')) {
				echo do_shortcode('[gtranslate]');
			}
			?>
			<?php 
			$profile_url = home_url( '/profile/' );
			?>
			<a href="<?php echo $profile_url; ?>" class="active text-uppercase header-login font--weight--700 p1 login">
				<span class="button button--transparent login-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 15 20">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M3.38379 4.7256C3.38379 7.33163 5.45431 9.45177 7.99935 9.45177C10.5446 9.45177 12.6154 7.33163 12.6154 4.7256C12.6154 2.11991 10.5446 0 7.99935 0C5.45436 0 3.38379 2.11986 3.38379 4.7256ZM4.87073 4.7256C4.87073 2.95937 6.27422 1.52241 7.99935 1.52241C9.72472 1.52241 11.1284 2.95937 11.1284 4.7256C11.1284 6.49217 9.72472 7.92937 7.99935 7.92937C6.27422 7.92937 4.87073 6.49217 4.87073 4.7256Z" fill="#fff"/>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M5.11556 20H10.8844C13.4295 20 15.5 17.8801 15.5 15.2744C15.5 12.6686 13.4295 10.5487 10.8844 10.5487H5.11556C2.57057 10.5487 0.5 12.6686 0.5 15.2744C0.5 17.8801 2.57052 20 5.11556 20ZM1.98694 15.2744C1.98694 13.5081 3.39043 12.0711 5.11556 12.0711H10.8844C12.6096 12.0711 14.0131 13.5081 14.0131 15.2744C14.0131 17.0406 12.6096 18.4776 10.8844 18.4776H5.11556C3.39043 18.4776 1.98694 17.0406 1.98694 15.2744Z" fill="#fff"/>
						<path d="M2.53577 15.2744C2.53577 13.7987 3.70582 12.6196 5.11593 12.6196H10.8848C12.2949 12.6196 13.465 13.7987 13.465 15.2744C13.465 16.75 12.2949 17.9291 10.8848 17.9291H5.11593C3.70582 17.9291 2.53577 16.75 2.53577 15.2744Z" fill="#F47920" stroke="#000C26" stroke-width="1.09693"/>
					</svg>
				</span>
				<span class="login-text"><?php _e('My account' , 'nirvana'); ?></span>
			</a>

			<?php if ( ! $hideNav ) : ?>
				<span id="nav-toggle" class="nav-toggle">
					<div class="nav-toggle-icon">
						<span class="nav-toggle-icon__inner"></span>
					</div>
				</span>
			<?php endif; ?>
		</div>
	</div>
</header>
