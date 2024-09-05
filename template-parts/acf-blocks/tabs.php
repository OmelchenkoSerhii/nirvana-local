<?php 
$heading = get_sub_field('heading');
    

//Block options
$options = get_acf_block_options();
?>
<section 
    <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
    class="section tabs-section <?php echo $options['class']; ?>" 
    <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
>
    <div class="container">
        <div class="heading tabs-section__heading pb-5">
            <?php echo $heading;?>
        </div>
        <div class="col-8 tabs__wrapper">
            <div class="tabs row">
                    <?php if( have_rows('title_row') ):?>
                        <?php $i = 0;?>
                        <?php $s = 0;?>
                        <?php while( have_rows('title_row') ) : the_row();  
                            $title = get_sub_field('title');
                        ?>
                            <input type="radio" name="tab-btn" id="tab-btn-<?php echo ++$i ;?>" value="" checked>
                            <label for="tab-btn-<?php echo ++$s ;?>"><?php echo $title ?></label>
                        <?php endwhile; ?>
                    <?php endif; ?>
                <?php if( have_rows('items_row') ):?>
                    <?php $a = 0;?>
                    <?php while( have_rows('items_row') ) : the_row();  
                        $content = get_sub_field('content');
                    ?>
                        <div class="tabs__itemContent" id="content-<?php echo ++$a ;?>">
                            <div class="content-block">
                                <?php if($content) : ?>
                                    <?php echo $content;?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
