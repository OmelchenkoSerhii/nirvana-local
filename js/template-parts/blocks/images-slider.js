import $  from 'jquery';
import 'slick-carousel';

function imagesSlider(){
    $('.imagesSlider').each(function(){
        let block = $(this);
        let slider = $(this).find('.images-slider');
        let col = slider.data('col');
        slider.slick({
            dots: false,
            arrows: true,
            infinite: true,
            slidesToShow: col,
            speed: 500,
            cssEase: 'linear',
            adaptiveHeight: false,
            centerMode: false,
            autoplay: true,
            autoplaySpeed: 5000,
            focusOnSelect: true,
            prevArrow: block.find('.images-slider__nav__prev'),
            nextArrow: block.find('.images-slider__nav__next')
        });
        
        slider.on('init', function(){
            $(window).trigger('heightChanges');
        });
    });
}

export { imagesSlider };
