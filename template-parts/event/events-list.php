<?php 
$args = ( ! empty( $args ) ) ? $args : false; 

if($args):
    $args['order'] = 'ASC';
    $args['orderby'] = 'meta_value';
    $args['meta_key'] = 'event_start_date';

    $query = new WP_Query( $args );
    //print_r($args);
    if ( $query->have_posts() ) : 
    ?>
        <ul class="category-event__list-inner">
            <?php $postsCounter = 0; ?>
            <?php while ( $query->have_posts() ) :
                $query->the_post(); 
              
                $show = true;
                $date = get_field('event_start_date');
                $date_tbd = get_field('tbd_date');
                if($date):
                    $date = DateTime::createFromFormat('Ymd', $date);
                    if(isset($args['monthnumber'])):
                        if(intval($args['monthnumber']) != intval($date->format('n'))):
                            $show = false; 
                        endif; 
                    endif;
                endif;
                if(isset($args['monthnumber']) && !$date):
                    $show = false; 
                endif; 
                if($show):
                    $postsCounter++;
                    ?>
                    <li class="category-event__list-item d-flex mb-md-2 flex-column flex-md-row" <?php if($date_tbd) echo 'style="order: 25;"'; ?>>
                        <?php get_template_part('/template-parts/event/event', 'card'); ?>
                    </li>
                <?php endif; ?>
            <?php endwhile; ?>
            <?php if($postsCounter == 0): ?>
                <li><h2><?php _e('No events found'); ?></h2></li>
            <?php endif; ?>
        </ul>
    <?php else: ?>
        <ul class="category-event__list-inner">
            <li><h2><?php _e('No events found'); ?></h2></li>
        </ul>
    <?php endif; ?>

<?php endif; ?>

