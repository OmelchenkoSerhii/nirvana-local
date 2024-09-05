import $ from 'jquery'
import 'slick-carousel'
import { popupSelectFlight } from '../../parts/popups'

function orderFlights () {
  $('.flights-list').each(function () {
    const $list = $(this)
    $list.find('.flights-list__card').each(function () {
      const $card = $(this)

      $card.on('click touch', function () {
        popupSelectFlight($card)
      })
    })
  })
}

export { orderFlights }
