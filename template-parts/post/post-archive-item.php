<?php 
$permalink = get_permalink();
$title = get_the_title();
$image = get_the_post_thumbnail_url(get_the_ID());

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

<li class="col-lg-4 col-md-6 archive-item">
    <div class="archive-item__inner">
        <a href="<?php echo $permalink; ?>" class="image-ratio archive-item__image">
            <?php if ($image): ?>
            <img src="<?php echo esc_url($image); ?>"
                 alt="<?php echo $title; ?>" class="archive-item__image__img" />
        
            <?php endif;?>
        </a>
        <div class="archive-item__content">
            <?php if ($categoryString!=''): ?>
            <div class="h4 text--color--primary archive-item__content__terms"><?php echo $categoryString; ?></div>
            <?php endif;?>
            <h2 class="font--weight--700 text--size--24 archive-item__content__title"><?php echo $title; ?></h2>
            <div class="archive-item__content__link">
                <?php echo button_icon(__('Read more'),$permalink,'_self'); ?>
            </div>
        </div>
    </div>
</li>
