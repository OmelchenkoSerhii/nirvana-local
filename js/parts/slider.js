import $  from 'jquery';
import 'slick-carousel';

function basicSliders(){
    $('.slider').each(function(){
        let block = $(this);
        let slider = $(this).find('.slider--single');

        slider.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            infinite: false,
            speed: 500,
            autoplay: true,
            cssEase: 'linear',
            adaptiveHeight: false,
            variableWidth: false,
            prevArrow: block.find('.slider__arrow-rs--prev'),
            nextArrow: block.find('.slider__arrow-rs--next')
        });
        
        slider.on('init', function(){
            $(window).trigger('heightChanges');
        });
    });
}

export { basicSliders };
