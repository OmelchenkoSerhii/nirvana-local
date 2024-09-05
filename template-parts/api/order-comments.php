<?php 
$order = $args['order'];
$api = $args['api'];
$link = get_field('api_summary_page','option');
?>
<div class="order-dates">
    <form class="order-form" method="POST" action="<?php echo $link; ?>">
        <div class="order-form__field">
            <textarea name="comment" id="" cols="30" rows="10" placeholder="If you have any additional information to tell us, please enter it here..."></textarea>
        </div>
       
        <div class="order-form__field">
            <button type="submit" class="submit">Next</button>
        </div>
    </form>
</div>
