import $  from 'jquery';
import Plyr from 'plyr';


function videoBlock(){

    $('.videoBlock .video').each(function(){
        let player = false;
        let block = $(this);
        if($(this).hasClass('video--oembed')){
            let block = $(this);
            player = new Plyr($(this).get(0), {
                controls: ['play-large'],
                autoplay: false,
                vimeo: {autoplay: false},
                youtube: {autoplay: false},
                fullscreen: {enabled: false}
            });
        
        } else{
            player = new Plyr($(this).get(0), {
                controls: ['play-large'],
                autoplay: false,
                vimeo: {autoplay: false},
                youtube: {autoplay: false},
                fullscreen: {enabled: false}
            });
        }
        
    })

}

export { videoBlock };