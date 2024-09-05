<?php 
$permalink = get_permalink();
$title = get_the_title();
$image = get_the_post_thumbnail_url(get_the_ID());
$excerpt = excerpt(20,get_the_ID());

if(get_field('external_link')){
    $permalink = get_field('post_link');
}

$category = get_the_category(get_the_ID());
$category[0]->name;
$categoryString = '';
if($category):
    $loopCounter = 0;
    foreach($category as $cat):
        if($loopCounter!=0) $categoryString.=', ';
        $categoryString.= $cat->name;
        $loopCounter++;
    endforeach;
endif; 

$text = get_field('article_info',get_the_ID());

?>

<li class="col-12 archive-item archive-item--featured">
    <div class="archive-item__inner">
        <div class="row row--y--middle">
            <div class="col-md-6 archive-item--featured__col archive-item--featured__col__img">
                <a href="<?php echo $permalink; ?>" class="image-ratio archive-item__image">
                    <?php if ($image): ?>
                    <img src="<?php echo esc_url($image); ?>"
                        alt="<?php echo $title; ?>" class="post-archive-item__image__img" />
                   
                    <?php endif;?>
                </a>
            </div>
            <div class="col-md-6 archive-item--featured__col archive-item--featured__col__content">
                <div class="archive-item__content">
                    <?php if ($categoryString!=''): ?>
                    <div class="h4 text--color--primary archive-item__content__terms"><?php echo $categoryString; ?></div>
                    <?php endif;?>
                    <h2 class="h3 archive-item__content__title"><?php echo $title; ?></h2>
                    <?php if($excerpt): ?>
                        <p class="text--color--grey archive-item__content__excerpt"><?php echo $excerpt; ?></p>
                    <?php endif; ?>
                    <?php echo button_icon(__('Read more'),$permalink,'_self','archive-item__content__link'); ?>
                </div>
            </div>
        </div>
    </div>
</li>
