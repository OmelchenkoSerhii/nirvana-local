import $  from 'jquery';
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

function appearence(){
    $('.animate').each(function(){
        let el = $(this);
        let disabled = false;
        gsap.from(el ,{
            scrollTrigger: {
                trigger: el,
                pin:false,
                start: "top 80%",
                end: "bottom 20%",
                scrub: 1,
                markers: false,
                onToggle: function(self){
                    if(self.isActive && !disabled){
                        
                        el.addClass('in');

                        self.disable();
                        disabled = true;
                    } else{
                        self.disable();
                    }
                },
            },        
        });
    });
    $('.animate-in-out').each(function(){
        let el = $(this);
        let disabled = false;
        gsap.from(el ,{
            scrollTrigger: {
                trigger: el,
                pin:false,
                start: "top 80%",
                end: "bottom bottom",
                scrub: 1,
                markers: false,
                toggleClass: "active",
                // onToggle: function(self){
                //     if(self.isActive && !disabled){
                        
                //         el.addClass('in');

                //         // self.disable();
                //         // disabled = true;
                //     } else{
                //         // self.disable();
                //         el.addClass('in');
                //         el.addClass('out');
                //     }
                // },
            },
            bottom: 0,        
        });
    });
    $('.animate-now').each(function(){
        let el = $(this);
        let disabled = false;
        gsap.from(el ,{
            scrollTrigger: {
                trigger: el,
                pin:false,
                start: "top bottom",
                end: "bottom top",
                scrub: 1,
                markers: false,
                onToggle: function(self){
                    if(self.isActive && !disabled){
                        
                        el.addClass('in');

                        self.disable();
                        disabled = true;
                    } else{
                        self.disable();
                    }
                },
            },        
        });
    });
}

export { appearence };