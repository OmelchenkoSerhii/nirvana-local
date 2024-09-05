import $  from 'jquery';
import 'slick-carousel';

function partnersCarousel(){
    $('.partners-carousel').each(function(){
        let block = $(this);
        let slider = block.find('.partners-carousel__list');

        slider.slick({
            dots: false,
            arrows: true,
            infinite: true,
            slidesToShow: 3,
            speed: 500,
            cssEase: 'linear',
            centerMode: true,
            centerPadding: '100px', 
            adaptiveHeight: false,
            autoplay: true,
            focusOnSelect: true,
            autoplaySpeed: 5000,
            prevArrow: block.find('.partners-carousel__arrows .button--arrow-left'),
            nextArrow: block.find('.partners-carousel__arrows .button--arrow').last(),
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

export { partnersCarousel };
