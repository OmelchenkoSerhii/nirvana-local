import $  from 'jquery';
import jQuery  from 'jquery';
import bodymovin from "lottie-web";


function lottie(){
    $('.lottie').each(function(){
        let path = $(this).data('path');
        let animation = bodymovin.loadAnimation({
            container: this, // Required
            path: path, // Required
            renderer: 'svg', // Required
            loop: true, // Optional
            autoplay: true, // Optional
            name: "Hello World", // Name for future reference. Optional.
        })
    });
}


export { lottie };