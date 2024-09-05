<?php
$headerTransparent = get_field( 'transparent_header' );
$headerDark        = get_field( 'dark_header' );

$headerLogo      = get_field( 'upload_dark_logo', 'option' );
$headerLogoWhite = get_field( 'upload_light_logo', 'option' );

$hideNav = get_field( 'hide_navigation', 'option' );

$button1 = get_field( 'header_button_1', 'option' );
$button2 = get_field( 'header_button_2', 'option' );

$title    = isset( $args['title'] ) ? $args['title'] : false;
$api      = new NiravanaAPI();
$event_id = '';
if ( isset( $_GET['eventid'] ) ) :
	$event_id = $_GET['eventid'];
elseif ( isset( $_POST['eventid'] ) ) :
	$event_id = $_POST['eventid'];
endif;
?>

<header id="header" class="header-booking">
	<div class="header">
		<div class="header-booking__logo">
			<a href="<?php echo get_home_url(); ?>/" title="<?php echo get_bloginfo( 'name' ); ?>" class="header__logo">
				<?php if ( ! empty( $headerLogo ) ) : ?>
					<div class="header__logo__img">
						<?php image_acf( $headerLogo ); ?>
					</div>
				<?php endif; ?>
			</a>
		</div>

		<div class="header-booking__main">
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
		</div>

		<span id="nav-toggle" class="nav-toggle">
			<div class="nav-toggle-icon">
				<span class="nav-toggle-icon__inner"></span>
			</div>
		</span>
	</div>
</header>
