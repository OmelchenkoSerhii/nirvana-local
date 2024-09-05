<?php 
$back_button = get_sub_field('back_button');

$title = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$content = get_sub_field('content');
$icon_blocks = get_sub_field('icon_blocks');

$enable_buttons = get_sub_field('enable_buttons');
$button1 = get_sub_field('button_1');
$button2 = get_sub_field('button_2');


$map_type = get_sub_field('map_type');
$map_location = get_sub_field('map_location');
$image = get_sub_field('images');


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
			<div class="row ">
				<div class="col-12 col-md-6 col-lg-4 contact__content">
					<div class="contact__content__inner">
                            <?php if($back_button): ?>
                                <div class="mb-md-5 mb-3">
                                    <?php echo button_back_acf($back_button); ?>
                                </div>
                            <?php endif; ?>

                            <?php if($title): ?>
								<h2><?php echo $title; ?></h2>
							<?php endif; ?>

                            <?php if($subtitle): ?>
								<h4 class="text-color-primary text-uppercase mt-1"><?php echo $subtitle; ?></h4>
							<?php endif; ?>

							<?php if($content): ?>
								<div class="content-block mt-4"><?php echo $content; ?></div>
							<?php endif; ?>

							<?php 
							if($icon_blocks):
							?>
								<div class="contacts-list__wrapper animate fade-up mt-4">
									<ul class="contacts-list">
										<?php foreach($icon_blocks as $item): 
											$icon = $item['icon'];
											$text = $item['text'];
                                            $enable_link = $item['enable_link'];
                                            $link = $item['link'];
											if($enable_link && $link):
												$link_url = $link['url'];
												$link_title = $link['title'];
												$link_target = $link['target'] ? $link['target'] : '_self';
												?>
												<li class="contacts-list__item">
													<a class="contacts-list__item__inner" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" title="<?php echo esc_html( $link_title ); ?>">
														<?php echo $icon; ?>
													</a>
												</li>
                                            <?php else: ?>
                                                <li class="contacts-list__item">
													<div class="contacts-list__item__inner">
														<span class="contacts-list__item__icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-<?php echo $icon;?>.svg" alt=""></span>
                                                        <span class="contacts-list__item__text"><?php echo $text; ?></span>
                                                    </div>
												</li>
											<?php endif; ?>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>

                            <?php if($enable_buttons && ($button1 || $button2)): ?>
                                <div class="buttonsBlock mt-md-5 mt-3 animate fade-up">
                                    <div class="buttonsBlock__inner">
                                        <?php echo button_acf($button1); ?>
                                        <?php echo button_acf($button2); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
					
					</div>
				</div>
				<div class="col-12 col-lg-7 offset-lg-1 col-md-6 contact__image contact__image--full">
					<div class="contact__image__inner">
                        <?php 
                        if($map_type == 'map'): ?>
                            <div id="map" class="map-wrapper"></div>
                            <script>
                                var mapStyle = [
                                    {
                                        "featureType": "all",
                                        "elementType": "geometry",
                                        "stylers": [
                                            {
                                                "color": "#202c3e"
                                            }
                                        ]
                                    },
                                    {
                                        "featureType": "all",
                                        "elementType": "labels.text.fill",
                                        "stylers": [
                                            {
                                                "gamma": 0.01
                                            },
                                            {
                                                "lightness": 20
                                            },
                                            {
                                                "weight": "1.39"
                                            },
                                            {
                                                "color": "#ffffff"
                                            }
                                        ]
                                    },
                                    {
                                        "featureType": "all",
                                        "elementType": "labels.text.stroke",
                                        "stylers": [
                                            {
                                                "weight": "0.96"
                                            },
                                            {
                                                "saturation": "9"
                                            },
                                            {
                                                "visibility": "on"
                                            },
                                            {
                                                "color": "#000000"
                                            }
                                        ]
                                    },
                                    {
                                        "featureType": "all",
                                        "elementType": "labels.icon",
                                        "stylers": [
                                            {
                                                "visibility": "off"
                                            }
                                        ]
                                    },
                                    {
                                        "featureType": "landscape",
                                        "elementType": "geometry",
                                        "stylers": [
                                            {
                                                "lightness": 30
                                            },
                                            {
                                                "saturation": "9"
                                            },
                                            {
                                                "color": "#29446b"
                                            }
                                        ]
                                    },
                                    {
                                        "featureType": "poi",
                                        "elementType": "geometry",
                                        "stylers": [
                                            {
                                                "saturation": 20
                                            }
                                        ]
                                    },
                                    {
                                        "featureType": "poi.park",
                                        "elementType": "geometry",
                                        "stylers": [
                                            {
                                                "lightness": 20
                                            },
                                            {
                                                "saturation": -20
                                            }
                                        ]
                                    },
                                    {
                                        "featureType": "road",
                                        "elementType": "geometry",
                                        "stylers": [
                                            {
                                                "lightness": 10
                                            },
                                            {
                                                "saturation": -30
                                            }
                                        ]
                                    },
                                    {
                                        "featureType": "road",
                                        "elementType": "geometry.fill",
                                        "stylers": [
                                            {
                                                "color": "#193a55"
                                            }
                                        ]
                                    },
                                    {
                                        "featureType": "road",
                                        "elementType": "geometry.stroke",
                                        "stylers": [
                                            {
                                                "saturation": 25
                                            },
                                            {
                                                "lightness": 25
                                            },
                                            {
                                                "weight": "0.01"
                                            }
                                        ]
                                    },
                                    {
                                        "featureType": "water",
                                        "elementType": "all",
                                        "stylers": [
                                            {
                                                "lightness": -20
                                            }
                                        ]
                                    }
                                ];
                                function initMap() {
                                // The location of Uluru
                                const uluru = { lat: <?php echo $map_location['latitude']; ?>, lng: <?php echo $map_location['longitude']; ?> };
                                // The map, centered at Uluru
                                const map = new google.maps.Map(document.getElementById("map"), {
                                    zoom: 15,
                                    center: uluru,
                                    styles: mapStyle
                                });
                                // The marker, positioned at Uluru
                                const marker = new google.maps.Marker({
                                    position: uluru,
                                    map: map,
                                });
                                }
                            </script>
                        <?php else: ?>
                            <?php if($image): ?>
                                <?php image_acf($image,'animate fade-in'); ?>
                            <?php endif;?>
                        <?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>