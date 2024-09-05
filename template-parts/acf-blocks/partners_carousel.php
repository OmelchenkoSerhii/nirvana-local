<?php
$heading = get_sub_field('heading');
$partners = get_sub_field('partners');

//Block options
$options = get_acf_block_options();


?>
<section 
    <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
    class="section partners-carousel <?php echo $options['class']; ?>" 
    <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
>
    <div class="container animate fade-up">
        <div class="content-block">
            <?php echo $heading; ?>
        </div>
    </div>

    <div class="container-fluid px-0 animate fade-up">
        <div class="partners-carousel__wrapper">
            <ul class="partners-carousel__list">
                <?php foreach($partners as $partner) : ?>
                    <li class="partners-carousel__item">
                        <a href="<?php echo $partner['link']; ?>">
                            <div class="partners-carousel__item-inner">
                                <div class="logo-wrapper">
                                    <?php image_acf($partner['logo']); ?>
                                </div>
                                <?php image_acf($partner['background_image']); ?>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="partners-carousel__arrows">
                <button class="button button--arrow button--arrow-left button--orange"></button>
                <button class="button button--arrow button--orange"></button>
            </div>
        </div>        
    </div>
</section>
<?php
