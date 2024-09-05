import $ from 'jquery'
import { initFaresFlight, destroyFaresFlight } from '../template-parts/api/slider-fares-flight'

function initPopups () {
  $('.popup-block').each(function () {
    const $popup = $(this)
    const $closeBtn = $popup.find('.popup-block__close , .js-close-popup')
    const $background = $popup.find('.popup-block__background')

    const id = $popup.attr('id')

    // Show popup when user clicked on element with id which the same to popup id
    $(`[href="#${id}"]`).on('click', function (e) {
      e.preventDefault()
      $popup.fadeIn()
    });

    // Hide popup when user click on background or close button
    [$background, $closeBtn].forEach(($element) => {
      $element.on('click', function (e) {
        e.preventDefault()
        $popup.fadeOut()
      })
    })

    $popup.find('.popup-block__wrapper').on('click', function (event) {
      event.stopPropagation()
    })
  })

  $('document').on('click', '.popup-block__close' , function(e){
    $(this).closest('.popup-block').fadeOut();
  })
}

function popupSelectFlight ($card) {
  const $popup = $('.popup-select-flight')
  const $closeBtn = $popup.find('.popup-block__clos , .js-close-popupe')
  const $background = $popup.find('.popup-block__background')

  initFaresFlight();

  [$background, $closeBtn].forEach(($element) => {
    $element.on('click', function (e) {
      destroyFaresFlight()
    })
  })
}

export { initPopups, popupSelectFlight }
