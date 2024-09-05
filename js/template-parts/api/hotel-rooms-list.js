import $ from 'jquery'
import 'slick-carousel'

function hotelRoomsList () {
  $('.hotel-rooms-list').each(function () {
    const block = $(this)
    const slider = block.find('.hotel-rooms-list__list')

    slider.slick({
      dots: false,
      arrows: true,
      infinite: false,
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
            slidesToShow: 1
          }
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 2
          }
        }
      ]
    })

    slider.on('init', function () {
      $(window).trigger('heightChanges')
    })
  })
}

function hotelRoomsListSearch () {
  $('.js-rooms-search').each(function () {
    const hotelID = $(this).data('hotel')
    const hotelCode = $(this).data('hotelcode')
    const orderNumber = $(this).data('order')
    const form = $(this).find('.order-form')
    const roomsList = $(this).find('.js-rooms-list')
    const minNights = roomsList.data('nights')
    const loader = $(this).find('.book-accommodation__form-loading')
    form.on('submit', function (e) {
      e.preventDefault()
      const data = {}
      data['date-checkin'] = form.find('input[name="date-checkin"]').val()
      data['date-checkout'] = form.find('input[name="date-checkout"]').val()
      data.rooms = []
      form.find('.people-field__rooms .people-field__room').each(function () {
        const room = {}
        room.adults_qtt = $(this).find('input[name="adults_qtt"]').val()
        room.childs_qtt = $(this).find('input[name="childs_qtt"]').val()
        room.infants_qtt = $(this).find('input[name="infants_qtt"]').val()
        room.ages = []
        $(this).find('select[name="ages[]"]').each(function () {
          room.ages.push($(this).val())
        })
        room.infant_ages = [];
        $(this).find('select[name="infant_ages[]"]').each(function () {
            room.infant_ages.push($(this).val());
        });
        data.rooms.push(room)
      })

      data.order = orderNumber
      data.hotel = hotelID
      data.hotelcode = hotelCode
      data.minNights = minNights
      loader.fadeIn()
      $.ajax({
        url: customjs_ajax_object.ajax_url,
        type: 'post',
        data: {
          action: 'ajax_search_rooms',
          data: JSON.stringify(data)
        },
        success: function (response) {
          if (response !== '') {
            loader.fadeOut()
            // console.log(response);
            roomsList.html(response)
            hotelRoomsList()
            hotelRoomQttSelect()
          }
        },
        error: function (error) {
          console.log(error)
        }
      })
    })
  })
}

function hotelBedbanksRoomsListSearch () {
  $('.js-bedbanks-rooms-search').each(function () {
    const hotelID = $(this).data('hotel')
    const hotelCode = $(this).data('hotelcode')
    const orderNumber = $(this).data('order')
    const form = $(this).find('.order-form')
    const roomsList = $(this).find('.js-bedbanks-rooms-list')
    const minNights = roomsList.data('nights')
    const loader = $(this).find('.book-accommodation__form-loading')
    form.on('submit', function (e) {
      e.preventDefault()
      const data = {}
      data['date-checkin'] = form.find('input[name="date-checkin"]').val()
      data['date-checkout'] = form.find('input[name="date-checkout"]').val()
      data.rooms = []
      form.find('.people-field__rooms .people-field__room').each(function () {
        const room = {}
        room.adults_qtt = $(this).find('input[name="adults_qtt"]').val()
        room.childs_qtt = $(this).find('input[name="childs_qtt"]').val()
        room.infants_qtt = $(this).find('input[name="infants_qtt"]').val()
        room.ages = []
        $(this).find('select[name="ages[]"]').each(function () {
          room.ages.push($(this).val())
        })
        room.infant_ages = [];
        $(this).find('select[name="infant_ages[]"]').each(function () {
            room.infant_ages.push($(this).val());
        });
        data.rooms.push(room)
      })

      data.order = orderNumber
      data.hotel = hotelID
      data.hotelcode = hotelCode
      data.minNights = minNights
      loader.fadeIn()
      $.ajax({
        url: customjs_ajax_object.ajax_url,
        type: 'post',
        data: {
          action: 'ajax_search_bedbanks_rooms',
          data: JSON.stringify(data)
        },
        success: function (response) {
          if (response !== '') {
            loader.fadeOut()
            roomsList.html(response)
            hotelRoomsList()
            hotelRoomQttSelect()
          }
        },
        error: function (error) {
          console.log(error)
        }
      })
    })
  })
}

function hotelRoomQttSelect () {
  $('.hotel-rooms-qtt-select').each(function () {
    const item = $(this)
    const btn = $(this).find('.hotel-rooms-qtt-select__header')
    const content = $(this).find('.hotel-rooms-qtt-select__dropdown')
    const input = $(this).find('.hotel_rooms')
    btn.on('click', function () {
      item.toggleClass('active')
    })
    item.hover(function () {
      item.addClass('active')
    } , function () {
      item.removeClass('active')
    })
    item.find('[data-key]').on('click', function () {
      item.removeClass('active')
      const value = parseInt($(this).data('key'))
      input.val(value)
      if (value == 0) {
        btn.text('Select')
      } else {
        btn.text('Selected(' + value + ')')
      }
    })
  })
}

function roomsSelectFormValidation () {
  $('#rooms-reserve-form').each(function () {
    const $form = $(this)
    const $errorsOutput = $form.find('.order-form__errors')
    const roomsFieldName = $form.find('input.hotel_rooms').attr('name')
    $form.on('submit', function (e) {
      const fields = $(this).serializeArray()

      const $errors = []
      let $errorsText = ''
      let $roomsQttFlag = false
      $form.find('.hotel_rooms').each(function () {
        const val = $(this).val()
        if (val != '0' && val != 0) {
          $roomsQttFlag = true
        }
      })

      if (!$roomsQttFlag) {
        $errors.push('Please select rooms.')
      }
      if ($errors.length) {
        e.preventDefault()

        $errors.forEach(element => {
          $errorsText += element + '<br/>'
        })
        $errorsOutput.html($errorsText)
        $errorsOutput.show()
      } else {
        $errorsOutput.html('')
        $errorsOutput.hide()
      }
    })
  })
}

export { hotelRoomsList, hotelRoomsListSearch, hotelBedbanksRoomsListSearch,  hotelRoomQttSelect, roomsSelectFormValidation }
