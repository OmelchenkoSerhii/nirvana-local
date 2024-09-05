<?php 
$content = get_field('book_a_demo_cta_heading','option');
$form = get_field('book_a_demo_cta_form','option');
$iconBlocks = get_field('book_a_demo_cta_icon_blocks','option');
$backgroundImage = get_field('book_a_demo_cta_background_image','option');
$backgroundColor = get_field('book_a_demo_cta_background','option');


?>
<section id="free-trial" class="footerCta" style="background-color: <?php echo $backgroundColor; ?>">
    <?php if($backgroundImage): ?>
        <div class="footerCta__bg">
            <img src="<?php echo $backgroundImage['url']; ?>" alt="">
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="row row--y--middle">
            <div class="col-12 col-xl-4 offset-lg-2 col-lg-5 col-md-7 footerCta__content text--color--white">
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