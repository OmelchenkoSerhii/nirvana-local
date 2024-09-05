import $ from 'jquery'

function buttonScrollDown () {
  const $buttons = $('.button--scroll-down')

  $buttons.each(function () {
    const $button = $(this)

    $button.on('click', function (e) {
      e.preventDefault()

      if ($button.parents('section').length) {
        const $nextSection = $button.parents('section').next()

        $('html, body').animate({
          scrollTop: $nextSection.offset().top - 200
        }, 1000)
      } else {
        $('html, body').animate({
          scrollTop: $(window).height() + window.pageYOffset + 'px'
        }, 1000)

        $button.fadeOut()
      }
    })

    if (!$button.parents('section').length) {
      document.addEventListener('scroll', hideButtonAtBottom)

      function hideButtonAtBottom () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 2000) {
          $button.fadeOut()
          document.removeEventListener('scroll', hideButtonAtBottom)
        }
      }
    }
  })
}

export { buttonScrollDown }
