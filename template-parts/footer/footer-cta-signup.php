<?php 
$content = get_field('sign_up_cta_heading','option');
$form = get_field('sign_up_cta_form','option');
$backgroundImage = get_field('sign_up_cta_background_image','option');
$backgroundColor = get_field('sign_up_cta_background','option');


?>
<section id="sign-up" class="footerCta footerCta--signup" style="background-color: <?php echo $backgroundColor; ?>">
    <?php if($backgroundImage): ?>
        <div class="footerCta__bg">
            <img src="<?php echo $backgroundImage['url']; ?>" alt="">
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="row row--y--middle">
            <div class="col-12 offset-lg-4 col-lg-4 col-md-6 offset-md-3 footerCta__content text--color--white">
                    <div class="footerCta__content__inner">
                        <?php if($content): ?>
                            <div class="content-block"><?php echo $content; ?></div>
                        <?php endif; ?>
                        <?php if($iconBlocks): ?>
                            <ul class="formImageBlock__icon-blocks">
                                <?php foreach($iconBlocks as $item): ?>
                                    <?php 
                                    $icon = $item['icon'];
                                    $label = $item['text'];    
                                    ?>
                                    <li class="formImageBlock__icon-blocks__item">
                                        <?php echo icon_block_acf($icon,$label); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <?php if($form): ?>
                            <div class="form form--signup spacing-pt-40"><?php echo do_shortcode($form);?></div>
                        <?php endif; ?>
                    </div>
            </div>
        </div>
    </div>
</section>