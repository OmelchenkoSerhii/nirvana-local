import $ from 'jquery'

// eslint-disable-next-line no-extend-native
Number.prototype.pad = function (n) {
  n = (undefined === n) ? 2 : n
  return (new Array(n).join('0') + this).slice(-n)
}

function eventTimeToStart () {
  function updateTimeText (days, hrs, mins, secs) {
    $('[data-time-type=days]').text(days)
    $('[data-time-type=hrs]').text(hrs.pad())
    $('[data-time-type=mins]').text(mins.pad())
    $('[data-time-type=secs]').text(secs.pad())
  }

  $('.single-event__time-to-start').each(function () {
    const timeToStart = Date.parse($('[data-time-to-start]').data('time-to-start'))

    const date = new Date(timeToStart);
    const _secs = 1000
    const _mins = _secs * 60
    const _hrs = _mins * 60
    const _days = _hrs * 24

    function showTimeDifference () {
      const timeDifference = date - new Date();

      if (timeDifference < 0) {
        clearInterval(timer)

        updateTimeText(0, 0, 0, 0)

        return
      }
      const days = Math.floor(timeDifference / _days)
      const hrs = Math.floor((timeDifference % _days) / _hrs)
      const mins = Math.floor((timeDifference % _hrs) / _mins)
      const secs = Math.floor((timeDifference % _mins) / _secs)

      updateTimeText(days, hrs, mins, secs)
    }

    const timer = setInterval(showTimeDifference, 1000)
  })
}

export { eventTimeToStart }
