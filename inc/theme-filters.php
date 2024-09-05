<?php
/*
=====================
	Theme Filters
=====================
*/

function posts_by_title_search($where, $wp_query)
{
    global $wpdb;
    if ($search_term = $wp_query->get('search_prod_title')) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql(like_escape($search_term)) . '%\'';
    }
    return $where;
}
add_filter('posts_where', 'posts_by_title_search', 10, 2);


function posts_by_title_letter($where, $query)
{
    global $wpdb;

    $starts_with = $query->get('starts_with');

    if ($starts_with) {
        $where .= " AND $wpdb->posts.post_title LIKE '$starts_with%'";
    }

    return $where;
}
add_filter('posts_where', 'posts_by_title_letter', 10, 2);

function filter_get_params($query) //Function to retrieve arguments from the link
{
    foreach ($query['filter_params'] as $key => $value) :

        switch ($key): //checking GET params and compiling tax query

                //Add taxonomies into query
            case 'tax':
                $query['tax_query'] = array(
                    'relation' => 'AND',
                );
                foreach ($value as $tax) :
                    if (isset($_GET[$tax]) && $_GET[$tax] != '') :
                        $terms = $_GET[$tax];
                        foreach ($terms as $key => $value) :
                            $arr = array(
                                'taxonomy' => $key,
                                'field' => 'term_id',
                                'terms' => explode(',', $value),
                            );
                            array_push($query['tax_query'], $arr);
                        endforeach;
                    endif;
                endforeach;
                break;

            case 'pre_tax_query':
                if ($value) :
                    $query['tax_query'] = array(
                        'relation' => 'AND',
                    );
                    foreach ($query['pre_tax_query'] as $tax => $terms_array) :

                        $arr = array(
                            'taxonomy' => $tax,
                            'field' => 'term_id',
                            'terms' => $terms_array,
                        );
                        array_push($query['tax_query'], $arr);
                    endforeach;
                endif;

                break;

                //Add search string into query
            case 'search':
                if (isset($_GET['search'])) :
                    //$query['s'] = $_GET['search'];
                    $query['search_prod_title'] = $_GET['search'];
                endif;
                break;

                //Add alphabet letter for title string into query
            case 'letter':
                if (isset($_GET['letter'])) :
                    $query['starts_with'] = $_GET['letter'];
                endif;

                break;

                //Add year string into query
            case 'year':
                if (isset($_GET['year'])) :
                    $query['year'] = $_GET['year'];
                endif;
                break;

                //Add month into query
            case 'month':
                if (isset($_GET['month'])) :
                    $query['monthnum'] = $_GET['month'];
                endif;
                break;
                //Add month into query
            case 'sort':
                if (isset($_GET['sort'])) {
                    switch ($_GET['sort']):
                        case ('date-asc'):
                            $query['orderby'] = 'meta_value';
                            $query['order'] = 'ASC';
                            $query['meta_key'] = 'date';
                            break;
                        case ('date-desc'):
                            $queryArgs['orderby'] = 'meta_value';
                            $queryArgs['order'] = 'DESC';
                            $queryArgs['meta_key'] = 'date';
                            break;
                        case ('a-z'):
                            $query['orderby'] = 'title';
                            $query['order'] = 'ASC';
                            break;
                        case ('z-a'):
                            $query['orderby'] = 'title';
                            $query['order'] = 'DESC';
                            break;

                        default:
                            $query['orderby'] = 'title';
                            $query['order'] = 'ASC';
                    endswitch;
                }
                break;
        endswitch;
    endforeach;

    return $query;
}


/**
 * Ajax function that returns loop
 */

function filter_loop()
{
    if (isset($_POST['query'])) {
        $data = json_decode(stripslashes($_POST['query']), true);

        ob_start();
        get_template_part($data['template'], '', $data);
        $loop_html = ob_get_clean();

        
        $data['posts_per_page'] = -1;
        $data['fields'] = 'ids';
        $posts = new WP_Query($data);
        $total_posts = $posts->post_count;

        $result = array(
            'loop' => $loop_html,
            'pagination' => '',
            'total_posts' => $total_posts
        );
        return wp_send_json_success($result);
    }
    die();
}
add_action('wp_ajax_nopriv_filter_loop', 'filter_loop');
add_action('wp_ajax_filter_loop', 'filter_loop');


function filter_taxonomy($tax_name, $tax_title, $query)
{
    if (!$query) return false;

    ob_start();

    $terms = get_terms([
        'taxonomy'   => $tax_name,
        'hide_empty' => true,
        'parent'     => 0
    ]);

    if ($terms && !is_wp_error($terms)) : ?>
        <div class="filter filter-dropdown" data-type="taxonomy" data-taxonomy="<?php echo $tax_name; ?>">
            <div class="filter-dropdown__inner">

                <div class="filter-dropdown__list">
                    <span class="filter-dropdown__list__title"><?php echo $tax_title; ?></span>
                    <span class="filter-dropdown__list__clear"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-cross.svg"></span>
                    <ul class="options">
                        <li class="option">
                            <label class="option__label js-filter-option" for="-1">
                                <input class="option__checkbox js-filter-val" type="radio" name="<?php echo $tax_name; ?>" id="-1" value="-1" data-label="<?php _e('Countries'); ?>">
                                <span class="option__text"><?php _e('All Countries'); ?></span>
                            </label>
                        </li>
                        <?php foreach ($terms as $term) :
                            $name = $term->name;
                            $id =   $term->term_id;
                        ?>
                            <li class="option">
                                <label class="option__label js-filter-option" for="<?php echo $term->slug ?>">
                                    <input class="option__checkbox js-filter-val" type="radio" name="<?php echo $tax_name; ?>" id="<?php echo $term->slug ?>" value="<?php echo $id; ?>" data-label="<?php echo $term->name ?>">
                                    <span class="option__text"><?php echo $name; ?></span>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

        </div>
    <?php endif;
    $html = ob_get_clean();
    return $html;
}


function filter_month($query)
{
    if (!$query) return false;
    ob_start();
    $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    ?>

    <div class="filter filter-month filter-dropdown" data-type="month">
        <div class="filter-dropdown__inner">

            <div class="filter-dropdown__list">
                <span class="filter-dropdown__list__title"><?php _e('Month'); ?></span>
                <span class="filter-dropdown__list__clear"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-cross.svg"></span>
                <ul class="options">
                    <?php
                    $active = true;
                    ?>
                    <li class="option">
                        <span class="option__label js-filter-option">
                            <input class="option__checkbox js-filter-val" type="radio" name="month" id="all" value="all" data-label="Month" <?php if ($active) {
                                                                                                                                echo 'checked="true"';
                                                                                                                            } ?>>
                            <label class="option__text" for="all">All</label>
                        </span>
                    </li>
                    <?php
                    $loopCounter = 1;
                    foreach ($months as $term) :
                        $active = 999;
                    ?>
                        <li class="option">
                            <label class="option__label js-filter-option" for="<?php echo $term; ?>">
                                <input class="option__checkbox js-filter-val" type="radio" name="month" id="<?php echo $term; ?>" value="<?php echo $loopCounter; ?>" data-label="<?php echo $term; ?>" <?php if ($active === $loopCounter) {
                                                                                                                                                                                                            echo 'checked="true"';
                                                                                                                                                                                                        } ?>>
                                <span class="option__text <?php echo $term; ?>"><?php echo $term; ?></span>
                            </label>
                        </li>
                        <?php $loopCounter++; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}


function filter_search($query)
{
    if (!$query) return false;

    ob_start();
    $ajaxParams = array();
    ?>
    <div class="filter filter-search" data-type="search">
        <form class="filter-search__form">
            <input type="text" name="search" class="filter-search__input js-filter-val" placeholder="<?php echo _e('Search by name...') ?>" value="<?php if (array_key_exists('search', $ajaxParams) && $ajaxParams['search'] != '') {
                                                                                                                                                        echo $ajaxParams['search'];
                                                                                                                                                    } ?>" />
            <button type="submit" class="filter-search__submit"><?php _e('Search', 'nirvana'); ?></button>
        </form>
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}