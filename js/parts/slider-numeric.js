import $ from 'jquery'
import 'slick-carousel'

function numericSlider () {
  $('.slider--numeric').each(function () {
    const $block = $(this)
    const $sliderNumbers = $(this).find('.slider--numeric__numbers')
    const $sliderContent = $(this).find('.slider--numeric__content')

    $sliderNumbers.slick({
      slidesToShow: 7,
      slidesToScroll: 1,
      centerMode: true,
      centerPadding: '0',
      arrows: true,
      infinite: false,
      speed: 500,
      cssEase: 'linear',
      adaptiveHeight: false,
      variableWidth: false,
      focusOnSelect: true,
      prevArrow: $block.find('.slider--numberic__numbers-nav .button--arrow-left'),
      nextArrow: $block.find('.slider--numberic__numbers-nav .button--arrow').last(),
      asNavFor: $sliderContent,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 5
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 3
          }
        }
      ]
    })

    $sliderContent.slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      centerMode: true,
      centerPadding: '0',
      arrows: false,
      infinite: false,
      speed: 500,
      cssEase: 'linear',
      adaptiveHeight: false,
      variableWidth: false,
      asNavFor: $sliderNumbers
    });

    [$sliderNumbers, $sliderContent].forEach(function () {
      const $element = $(this)

      $element.on('init', function () {
        $(window).trigger('heightChanges')
      })
    })
  })
}

export { numericSlider }
