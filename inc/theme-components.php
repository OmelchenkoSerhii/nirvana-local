<?php
/**
 * Components
 *
 * @package nirvana
 */

function button( $label = 'Button', $href = '#', $target = '_self', $class = '' ) {
	return '<a class="button ' . $class . '" href="' . $href . '" target="' . $target . '"><span class="button__text">' . $label . '</span></a>';
}

function button_acf( $link, $class = '', $customLabel = '' ) {
	if ( $link ) :
		$link_url    = $link['url'];
		$link_title  = $link['title'];
		$link_target = $link['target'] ? $link['target'] : '_self';
		if ( $customLabel ) {
			$link_title = $customLabel;
		}
		return '<a class="button ' . $class . '" href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '"><span class="button__text">' . esc_html( $link_title ) . '</span></a>';
	endif;
}


function button_icon( $label = 'Button', $href = '#', $target = '_self', $class = '' ) {
	return '<a class="button buttonIcon ' . $class . '" href="' . $href . '" target="' . $target . '"><span class="buttonIcon__inner"><span class="buttonIcon__text">' . esc_html( $label ) . '</span><span class="buttonIcon__icon">' . get_inline_svg( 'icon-arrow.svg' ) . '</span></span></a>';
}

function button_icon_acf( $link, $class = '', $customLabel = '' ) {
	if ( $link ) :
		$link_url    = $link['url'];
		$link_title  = $link['title'];
		$link_target = $link['target'] ? $link['target'] : '_self';
		if ( $customLabel ) {
			$link_title = $customLabel;
		}
		return '<a class="button buttonIcon ' . $class . '" href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '"><span class="buttonIcon__inner"><span class="buttonIcon__text">' . esc_html( $link_title ) . '</span><span class="buttonIcon__icon">' . get_inline_svg( 'icon-arrow.svg' ) . '</span></span></a>';
	endif;
}

function button_back_acf( $link, $class = '', $customLabel = '' ) {
	if ( $link ) :
		$link_url    = $link['url'];
		$link_title  = $link['title'];
		$link_target = $link['target'] ? $link['target'] : '_self';
		if ( $customLabel ) {
			$link_title = $customLabel;
		}
		return '<a class="buttonIconBack ' . $class . '" href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '"><span class="buttonIconBack__inner"><span class="buttonIconBack__icon">' . get_inline_svg( 'icon-arrow.svg' ) . '</span><span class="buttonIconBack__text">' . esc_html( $link_title ) . '</span></span></a>';
	endif;
}

function button_icon_bg_acf( $link, $class = '', $customLabel = '' ) {
	if ( $link ) :
		$link_url    = $link['url'];
		$link_title  = $link['title'];
		$link_target = $link['target'] ? $link['target'] : '_self';
		if ( $customLabel ) {
			$link_title = $customLabel;
		}
		return '<a class="buttonIconBg ' . $class . '" href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '"><span class="buttonIconBg__inner"><span class="buttonIconBg__text">' . esc_html( $link_title ) . '</span><span class="buttonIconBg__icon">' . get_inline_svg( 'icon-play.svg' ) . '</span></span></a>';
	endif;
}

function button_download_acf( $file, $class = '', $customLabel = '' ) {
	if ( $file ) :
		$link_url    = $file['url'];
		$link_title  = __( 'Download PDF', 'rocket-saas' );
		$link_target = '_blank';
		if ( $customLabel ) {
			$link_title = $customLabel;
		}
		return '<a class="button button--download ' . $class . '" href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '"><span class="button__inner"><span class="button__icon">' . get_inline_svg( 'icon-download.svg' ) . '</span><span class="button__text">' . esc_html( $link_title ) . '</span></span></a>';
	endif;
}

function icon_block_acf( $icon, $label = '' ) {
	if ( $icon ) {
		return '<div class="icon-block"><span class="icon-block__icon"><img src="' . $icon['url'] . '"></span><span class="icon-block__label">' . $label . '</span></div>';
	}
}

// ACF image
function image_acf( $image, $img_class = '', $noscript = true, $block_class = '' , $ratio = false ) {
	if ( $image && ! empty( $image ) ) :
		$imgWidth  = $image['width'];
		$imgHeight = $image['height'];

		if ( $image['mime_type'] === 'image/svg+xml' ) {
			$img       = wp_get_attachment_image_src( $image['id'], 'full' );
			$imgWidth  = $img[1];
			$imgHeight = $img[2];
		}

		$imgRatio     = 100 * $imgHeight / $imgWidth;
		$imgRatio     = $ratio?$ratio:$imgRatio;
		$blockPadding = 'style="padding-bottom:' . $imgRatio . '%;"';
		?>

		<div class="image-ratio <?php echo $block_class; ?>" <?php echo $blockPadding; ?>>
			<img src="<?php echo esc_url( $image['url'] ); ?>" class="<?php echo $img_class; ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
		</div>

		<?php if ( $noscript ) : ?>
			<noscript>
				<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
			</noscript>
		<?php endif; ?>
		<?php
	endif;
}

/**
 * Get image width, height, alt, link from ACF field
 *
 * @param string|array $image  Image url or ACF image array.
 *
 * @return array ($width, $height, $alt, $link)
 *
 * @package nirvana
 */
function get_image_data_acf( $image ) {
	$image_width  = $image['width'];
	$image_height = $image['height'];
	$image_alt    = $image['alt'];
	$image_link   = $image['link'];

	if ( 'image/svg+xml' === $image['mime_type'] ) {
		$image        = wp_get_attachment_image_src( $image['id'], 'full' );
		$image_width  = $image[1];
		$image_height = $image[2];
	}

	return array( $image_width, $image_height, $image_alt, $image_link );
}

/**
 * Echo image block with image ratio
 *
 * @param string|array $image         Image url or ACF image array.
 * @param boolean      $is_ratio      Should it be displayed with ratio?
 *                                    Default true.
 * @param string       $image_class   Optional. Class on the <img> tag.
 * @param string       $block_class   Optional. Class on the block wrapper <img> tag.
 * @param string       $alt           Optional. Alt text on the image.
 *                                    Default ''.
 * @param boolean      $original_size Optional. If you use 2x images,
 *                                    set it to true to make image less on 50%
 *                                    Default false.
 *
 * @package nirvana
 * @since 1.0
 */
function the_image( $image, $is_ratio = true, $image_class = '', $block_class = '', $alt = '', $original_size = false ) {
	if ( is_array( $image ) ) {
		list($img_width, $img_height, $alt, $image_link) = get_image_data_acf( $image );
	} else {
		$image_link = $image;
		$img_width  = null;
		$img_height = null;
	}

	$padding = '';
	$ratio   = '';
	if ( $is_ratio && is_array( $image ) ) {
		$ratio = 100 * $img_height / $img_width;

		$padding = 'style="padding-bottom:' . $ratio . '%;"';
	}

	$output = '';

	$output     .= sprintf(
		'<div class="%s %s" %s>',
		( $is_ratio ) ? 'image-ratio' : 'image-block',
		esc_attr( $block_class ),
		( $is_ratio ) ? esc_attr( $padding ) : ''
	);
		$output .= sprintf(
			'<img src="%s" class="%s" alt="%s" %s %s>',
			esc_url( $image_link ),
			esc_attr( $image_class ),
			esc_attr( $alt ),
			( $original_size && $ratio ) ? 'style="padding: ' . $ratio / 4 . '% 25%;"' : '',
			( $original_size && ! $ratio ) ? 'style="zoom: 50%;"' : ''
		);
	$output     .= '</div>';

	$allowed_html = array(
		'div' => array(
			'class' => array(),
			'style' => array(),
		),
		'img' => array(
			'class' => array(),
			'src'   => array(),
			'alt'   => array(),
			'style' => array(),
		),
	);

	echo wp_kses( $output, $allowed_html );
}

/**
 * Echo SVG from sprite.svg
 *
 * @param string  $svg_id     SVG ID in the sprite.svg file.
 *                            For example: list-icon-grey-cancel,
 *                            list-icon-grey-accept, list-icon-grey-plus
 * @param int     $width      Optional. SVG width.
 *                            Default null.
 * @param int     $height     Optional. SVG height.
 *                            Default null.
 * @param string  $svg_class  Optional. Class on the SVG.
 *                            Default ''.
 * @param string  $fill       Optional. SVG color as HEX.
 *
 * @package nirvana
 */
function the_svg_by_sprite( $svg_id, $width = null, $height = null, $svg_class = '', $fill = '' ) {
	$output      = sprintf(
		'<svg %s %s %s %s>',
		( $svg_class ) ? 'class="' . esc_attr( $svg_class ) . '"' : '',
		( $width ) ? 'width="' . esc_attr( $width ) . '"' : '',
		( $height ) ? 'height="' . esc_attr( $height ) . '"' : '',
		( $fill ) ? 'fill="' . esc_attr( $fill ) . '"' : '',
	);
		$output .= sprintf(
			'<use href="%s"></use>',
			esc_url( get_template_directory_uri() . '/assets/images/sprite.svg#' . sanitize_key( $svg_id ) )
		);
	$output     .= '</svg>';

	$allowed_html = array(
		'svg' => array(
			'class'  => array(),
			'width'  => array(),
			'height' => array(),
			'fill'   => array(),
		),
		'use' => array(
			'href' => array(),
		),
	);

	echo wp_kses( $output, $allowed_html );
}

/**
 * Echo rating block
 *
 * @param int    $rating       Count filled stars. From 0 to 5.
 *                             For example: The number 3 will output 3 filled stars and 2 empty.
 * @param string $block_class  Optional. Class on the block rating.
 *                             Default ''.
 *
 * @package nirvana
 */
function the_rating( $rating, $block_class = '' ) {
	if ( ! is_int( $rating ) ) {
		return;
	}

	if ( $rating > 5 ) {
		$rating = 5;
	} elseif ( $rating < 0 ) {
		$rating = 0;
	}

	$output = sprintf(
		'<div class="rating %s">',
		( $block_class ) ? esc_attr( $block_class ) : ''
	);

	// Output filled stars.
	for ( $i = 0; $i < $rating; $i++ ) {
		$output     .= '<svg width="23" height="22" fill="#f47920">';
			$output .= sprintf(
				'<use href="%s#rating-star-filled"></use>',
				get_template_directory_uri() . '/assets/images/sprite.svg'
			);
		$output     .= '</svg>';
	}

	// Output empty stars.
	/*for ( $i = $rating; $i < 5; $i++ ) {
		$output     .= '<svg width="23" height="22" fill="#f47920">';
			$output .= sprintf(
				'<use href="%s#rating-star-empty"></use>',
				get_template_directory_uri() . '/assets/images/sprite.svg'
			);
		$output     .= '</svg>';
	}*/

	$output .= '</div>';

	$allowed_html = array(
		'div' => array(
			'class' => array(),
		),
		'svg' => array(
			'class'  => array(),
			'width'  => array(),
			'height' => array(),
			'fill'   => array(),
		),
		'use' => array(
			'href' => array(),
		),
	);

	echo wp_kses( $output, $allowed_html );
}

function the_icon_bookmark_min_nights( $nights = '5', $block_class = '' ) {
	ob_start();
	?>
	<div class="icon-bookmark icon-bookmark--dark-blue <?php echo sanitize_html_class( $block_class ); ?>">
		<div class="icon-bookmark__content">
			<span class="text--color--light-orange h3 font--weight--700"><?php echo esc_html( $nights ); ?></span>
			<span style="padding-bottom: 15px;" class=" z-1 text-uppercase text--color--default px-1 text--size--12 font--weight--700"><?php esc_html_e( 'Nights Minimum Stay', 'nirvana' ); ?></span>
		</div>
	</div>
	<?php
	$output = ob_get_clean();

	echo wp_kses_post( $output );
}

function the_icon_bookmark_primary( $block_label = 'Priority Hotel', $block_class = '' ) {
	ob_start();
	?>
	<div class="icon-bookmark icon-bookmark--orange <?php echo sanitize_html_class( $block_class ); ?>">
		<div class="icon-bookmark__content">
			<span class="z-1 text-uppercase text--color--default px-1 text--size--12 font--weight--700"><?php esc_html_e( $block_label, 'nirvana' ); ?></span>
		</div>
	</div>
	<?php
	$output = ob_get_clean();

	echo wp_kses_post( $output );
}

function the_icon_bookmark( $block_label , $block_class = '' ) {
	ob_start();
	if(!$block_label) return null;
	?>
	<div class="icon-bookmark <?php echo sanitize_html_class( $block_class ); ?>">
		<div class="icon-bookmark__content">
			<span class="z-1 text-uppercase text--color--default px-1 text--size--12 font--weight--700"><?php esc_html_e( $block_label, 'nirvana' ); ?></span>
		</div>
	</div>
	<?php
	$output = ob_get_clean();

	echo wp_kses_post( $output );
}

function the_acf_link( $link, $is_button = true, $custom_class = '' ) {
	$style = null;
	$arrow = null;

	// Check if it is ACF Custom Link or default ACF Link.
	if ( isset($link['link']) ) {
		$style = $link['style'];
		$arrow = $link['arrow'];
		$link  = $link['link'];
	}

	if($link):

		$target = isset($link['target']) ? $link['target'] : '_self';
		$class  = sprintf(
			'%s %s %s %s',
			( $is_button ) ? 'button' : '',
			( $is_button && $style ) ? 'button--' . $style : '',
			( $is_button && $arrow ) ? 'button--arrow' : '',
			( $custom_class ) ? esc_attr( $custom_class ) : '',
		);

		$output = sprintf(
			'<a href="%s" class="%s" target="%s">%s</a>',
			esc_url( $link['url'] ),
			esc_attr( $class ),
			esc_attr( $target ),
			esc_html( $link['title'] )
		);

		$allowed_html = array(
			'a' => array(
				'href'   => array(),
				'class'  => array(),
				'target' => array(),
			),
		);

		echo wp_kses( $output, $allowed_html );
	endif;
}

add_filter(
	'safe_style_css',
	function( $styles ) {
		$styles[] = 'zoom';
		return $styles;
	}
);

/**
 * Echo tag
 *
 * @param string $text  Text inside the tag.
 *
 * @param string $style Style for the tag.
 *                      Allowed values: orange
 *
 * @package nirvana
 */
function the_tag( $text, $style, $additional_classes = '' ) {
	$output = sprintf( '<span class="tag tag--%s %s">%s</span>', esc_attr( $style ), esc_attr( $additional_classes ), esc_html( $text ) );

	$allowed_html = array(
		'span' => array(
			'class' => array(),
		),
	);

	echo wp_kses( $output, $allowed_html );
}

/**
 * Echo direct time in format H:M
 *
 * @param string $direct_time Time of the direct
 *
 * @package nirvana
 */
function the_direct_time( $direct_time ) {
	if ( $direct_time ) {
		$direct_time = explode( ':', $direct_time );

		$hours   = ( $direct_time[0] ) ? $direct_time[0] . 'H' : '';
		$minutes = ( $direct_time[1] ) ? $direct_time[1] . 'M' : '';

		$output = sprintf(
			'%s %s',
			$hours,
			$minutes,
		);

		echo esc_html( $output );
	}

	return null;
}
