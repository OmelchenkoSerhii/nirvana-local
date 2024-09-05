import $  from 'jquery';
import { gsap } from "gsap";


function scrollToAnchor(){

    $('a[href^="#"]:not(.slider-arrow, .quote--button)').click(function(e){
        e.preventDefault();
        let href = $(this).attr('href');    
        $('html, body').animate({ scrollTop: $(href).offset().top - 100}, 1000);
    })
        
}

function scrollToHash(){
    let hash = window.location.hash.substr(1);
    if(hash && $('#'+hash).length){
        $('html, body').animate({ scrollTop: $('#'+hash).offset().top - 100}, 100);
        $(window).on('carouselInited', function(){
            $('html, body').animate({ scrollTop: $('#'+hash).offset().top - 100}, 100);
        })
    }
}

function requestQuoteLink() {
    $(".quote--button").on("click", function(e) {
        e.preventDefault();
        
        let quoteTarget = $(this).attr("href");
        let pathArray = window.location.origin + "/#request_a_quote";

        if($(quoteTarget).length) {
            $('html, body').animate({ scrollTop: $(quoteTarget).offset().top - 100}, 1000);
        } else {
            window.location.href = pathArray;
        }

    });
}

export { scrollToAnchor,scrollToHash, requestQuoteLink };