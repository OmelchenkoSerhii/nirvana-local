import $  from 'jquery';
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import Plyr from 'plyr';

function videoBanner(){

    $('.heroBanner .video').each(function(){
        let player = false;
        let block = $(this);
        if($(this).hasClass('video--oembed')){
            let block = $(this);
            player = new Plyr($(this).get(0), {
                controls: false,
                autoplay: true,
                vimeo: {autoplay: true},
                youtube: {autoplay: true},
                muted: true,
                volume: 0
            });
            player.on('ready', event => {
                let video = block.find('.plyr__video-embed__container');
                let bannerInner = block;
                function videoSize() {
                    var $windowHeight = bannerInner.height();
                    var $videoHeight = video.outerHeight();
                    var $scale = $windowHeight / $videoHeight;
                    if ($videoHeight <= $windowHeight) {
                        video.attr('style',
                            "-webkit-transform:scale("+$scale+") translateY(0%)!important; transform:scale("+$scale+") translateY(0%)!important"
                        );
                    };
                }
                videoSize();
                $(window).on('load resize',function(){
                    videoSize();
                });
            });
        } else{
            player = new Plyr($(this).get(0), {
                controls: false,
                autoplay: true,
                vimeo: {autoplay: true},
                youtube: {autoplay: true},
                muted: true,
                volume: 0
            });
        }
        $(window).on('loadCompleted',function(){
            if(player){
                player.muted = true;
                player.volume = 0;
                player.play();

                gsap.from(block ,{
                    scrollTrigger: {
                        trigger: block,
                        pin:false,
                        start: "top bottom",
                        end: "bottom top",
                        scrub: 1,
                        markers: false,
                        onToggle: function(self){
                            if(self.isActive){
                                player.play();
                            } else{
                                player.pause();
                            }
                        },
                    },        
                });
               
            }
        })
    })

}

export { videoBanner };