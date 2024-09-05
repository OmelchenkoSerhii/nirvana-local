import $  from 'jquery';
import 'slick-carousel';

function mapLocations(){
    $('.locations-block').each(function(){
        let block = $(this);
        let sliderInfo = $(this).find('.locations-carousel');
        let sliderMaps = $(this).find('.locations-map__slider');
        let activePin = false;
        sliderInfo.slick({
            dots: false,
            arrows: true,
            infinite: true,
            speed: 500,
            cssEase: 'linear',
            adaptiveHeight: false,
            autoplay: false,
            autoplaySpeed: 3000,
            prevArrow: block.find('.locations-carousel__nav__prev'),
            nextArrow: block.find('.locations-carousel__nav__next'),
            asNavFor: sliderMaps
        });

        sliderMaps.slick({
            dots: false,
            arrows: false,
            infinite: true,
            speed: 500,
            cssEase: 'linear',
            adaptiveHeight: true,
            autoplay: false,
            fade: true,
        });
        
        sliderInfo.on('init', function(){
            $(window).trigger('heightChanges');
        });

        block.find('.locations-accordion').each(function(){
            let accordion_block = $(this);
            let items = $(this).find('.locations-accordion__item');

            items.each(function(){
                let item = $(this);
                let btn = $(this).find('.locations-accordion__item__header');
                let content = $(this).find('.locations-accordion__item__content');
                let country = $(this).data('area');
                btn.on('click', function(){
                    let btn = accordion_block.find('.locations-accordion__item.active .locations-accordion__item__header');
                    if(item.hasClass('active')){
                        content.slideToggle();
                        item.removeClass('active');
                        block.find('[data-country="'+country+'"]').removeClass('active');
                    } else{
                        if(activePin){
                            activePin.removeClass('active');
                        }
                        let countryOld = accordion_block.find('.locations-accordion__item.active').data('area');
                        accordion_block.find('.locations-accordion__item.active .locations-accordion__item__content').slideUp();
                        accordion_block.find('.locations-accordion__item.active').removeClass('active');
                        block.find('[data-country="'+countryOld+'"]').removeClass('active');
                        content.slideToggle();
                        item.toggleClass('active');
                        block.find('[data-country="'+country+'"]').toggleClass('active');
                    }
                });
            })
        });

        block.find('*[data-country]').on('click',function(){
            block.find('[data-country]').removeClass('active');
            block.find('[data-countrypoint]').removeClass('active');
            $(this).addClass('active');
            let country = $(this).data('country');
            
            let countryItem = block.find('.locations-accordion__item[data-area="'+country+'"]');
            let content = countryItem.find('.locations-accordion__item__content');
            let accordion = countryItem.parent();
            accordion.find('.locations-accordion__item.active .locations-accordion__item__content').slideUp();
            accordion.find('.locations-accordion__item.active').removeClass('active');
            content.slideDown(function(){
                if($(window).width() < 992){
                    $('html, body').animate({ scrollTop: countryItem.offset().top - 70}, 500);
                }
            });
            countryItem.addClass('active');

            if(activePin){
                activePin.removeClass('active');
            }

           
        });

        block.find('*[data-countrypoint]').on('click',function(){
            block.find('[data-countrypoint]').removeClass('active');
            block.find('[data-country]').removeClass('active');
            let country = $(this).data('countrypoint');
            let city = $(this).data('city');

            $(this).addClass('active');
            block.find('[data-country="'+country+'"]').addClass('active');

            let countryItem = block.find('.locations-accordion__item[data-area="'+country+'"]');
            let content = countryItem.find('.locations-accordion__item__content');
            let accordion = countryItem.parent();
            if(!countryItem.hasClass('active')){
                accordion.find('.locations-accordion__item.active .locations-accordion__item__content').slideUp();
                accordion.find('.locations-accordion__item.active').removeClass('active');
                content.slideDown(function(){
                    if($(window).width() < 992){
                        $('html, body').animate({ scrollTop: countryItem.offset().top - 70}, 500);
                    }
                });
                countryItem.addClass('active');
            }

            block.find('.locations-accordion__item__link').removeClass('active');
            block.find('.locations-accordion__item__link[data-citylink="'+city+'"]').addClass('active');

            
        });

        block.find('*[data-citylink]').hover(
            function(){
                activePin = block.find('.location-points__item.active');

                activePin.removeClass('active');
                let city = $(this).data('citylink');

                block.find('.location-points__item[data-city="'+city+'"]').addClass('active');

            },
            function(){
                block.find('.location-points__item.active').removeClass('active');
                activePin.addClass('active');
            }
        );

    });
        
}

export { mapLocations };