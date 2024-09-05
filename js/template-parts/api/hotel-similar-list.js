import $ from 'jquery';
import 'slick-carousel';

function hotelSimilarList(){
    $('.hotel-similar-list').each(function(){
        let block = $(this);
        let slider = block.find('.hotel-similar-list__list');

        slider.slick({
            dots: false,
            arrows: true,
            infinite: true,
            slidesToShow: 3,
            speed: 500,
            cssEase: 'linear',
            centerPadding: '0', 
            adaptiveHeight: false,
            autoplay: true,
            autoplaySpeed: 5000,
            prevArrow: block.find('.slider-arrows .button--arrow-left'),
            nextArrow: block.find('.slider-arrows .button--arrow').last(),
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                    }
                },
            ]
        });
        
        slider.on('init', function(){
            $(window).trigger('heightChanges');
        });
    });
}

export { hotelSimilarList };
