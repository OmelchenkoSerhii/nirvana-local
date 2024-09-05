import $ from 'jquery'
import 'slick-carousel'

function initFaresFlight () {
  $('.popup-select-flight__slider').each(function () {
    const $block = $(this)
    const $slider = $block.find('.popup-select-flight__slider-list')

    $slider.slick({
      dots: false,
      arrows: true,
      infinite: false,
      slidesToShow: 2,
      speed: 500,
      cssEase: 'linear',
      centerPadding: '0',
      adaptiveHeight: false,
      autoplay: false,
      prevArrow: $block.find('.slider-arrows .button--arrow-left'),
      nextArrow: $block.find('.slider-arrows .button--arrow').last(),
      responsive: [
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    })

    $slider.on('init', function () {
      $(window).trigger('heightChanges')
    })
  })
}

function destroyFaresFlight () {
  $('.popup-select-flight__slider').each(function () {
    $(this).find('.popup-select-flight__slider-list').slick('unslick').slick('reinit')
  })
}

export { initFaresFlight, destroyFaresFlight }
