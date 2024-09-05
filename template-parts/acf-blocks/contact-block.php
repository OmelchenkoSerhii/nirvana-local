<?php 
$content = get_sub_field('content');
$form = get_sub_field('form');
$image = get_sub_field('image');


//Block options
$options = get_acf_block_options();
?>
<section 
    <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
    class="section contact-wrapper bg--primary text-color-white <?php echo $options['class']; ?>" 
    <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
>
	<div class="contact contact--right contact--full">
		<div class="container">
			<div class="row row--y--middle">
				<div class="col-12 col-md-6 col-lg-6 contact__content">
					<div class="contact__content__inner">
							<?php if($content): ?>
								<div class="content-block  animate fade-up mb-3 mb-md-5"><?php echo $content; ?></div>
							<?php endif; ?>
							<?php 
							$contactSocial = get_sub_field('social_media_icons');
							if($contactSocial):
							?>
								<div class="social-bar__wrapper animate fade-up mb-4">
									<ul class="contact__social social-bar">
										<?php foreach($contactSocial as $item): 
											$icon = $item['icon'];
											$link = $item['link'];
											if($link):
												$link_url = $link['url'];
												$link_title = $link['title'];
												$link_target = $link['target'] ? $link['target'] : '_self';
												?>
												<li class="social-bar__item">
													<a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" title="<?php echo esc_html( $link_title ); ?>">
														<?php echo $icon; ?>
													</a>
												</li>
											<?php endif; ?>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						<?php if($form): ?>
							<div class="form animate fade-up">
								<?php echo do_shortcode($form); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class=" col-12 col-lg-7 col-md-6 contact__image contact__image--full">
					<div class="contact__image__inner">
						<?php if($image): ?>
							<?php image_acf($image,'animate fade-in'); ?>
						<?php endif;?> 
					</div>
				</div>
			</div>
		</div>
	</div>
</section>