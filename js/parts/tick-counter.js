import { gsap } from 'gsap'

function tickCounter () {
  const tickers = Array.from(document.querySelectorAll('.ticker')).filter(ticker => !isNaN(parseInt(ticker.textContent)))

  gsap.from(tickers, {
    textContent: 0,
    duration: 4,
    ease: 'power1.in',
    snap: { textContent: 1 },
    stagger: {
      each: 1.0,
      onUpdate: function () {
        this.targets()[0].innerHTML = numberWithCommas(Math.ceil(this.targets()[0].textContent))
      },
      onComplete: function () {
        const element = this.targets()[0]

        if (element.getAttribute('data-ticker-after')) {
          element.innerHTML += element.getAttribute('data-ticker-after')
        }
      }
    },
    scrollTrigger: {
      trigger: '.ticker',
      toggleActions: 'play none none none'
    }
  })
}

function numberWithCommas (x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}

export { tickCounter }
