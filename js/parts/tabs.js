import $  from 'jquery';

function tabs() {

    if ($('.tabs').length) {
        var sectionId = window.location.hash;

        if (sectionId) {
            $('.tabs__nav').removeClass('active');
            $('.tabs__nav[href="' + sectionId + '"]').addClass('active');
            $('.tabs__item').removeClass('active');
            $('.tabs__item' + sectionId).addClass('active');
        }

        $('.js-tab-nav').on('click', function ( e ) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var id = $(this).attr('href');
            $('.tabs__nav').removeClass('active');
            $(this).addClass('active');
            $('.tabs__item.active').removeClass('active').fadeOut(function(){
                $('.tabs__item' + id).fadeIn().addClass('active');
            });
        });

        $('.tabs__item').each(function () {
            var block = $(this);
            var header = block.find('.tabs__item__header');
            var content = block.find('.tabs__item__content');

            header.on('click', function () {
                //block.toggleClass('tabs__item--active');
                //content.slideToggle();
            });
        });
    }
}

export { tabs };