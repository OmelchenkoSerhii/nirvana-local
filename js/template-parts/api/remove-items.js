import $ from 'jquery';

function basketRemoveItem() {
    $('.js-basket-remove').on('click' , function () {
        let type = $(this).data('type');
        let element = $(this);
        let loader = $('.booking-summary__loading');
        if(type == 'extra'){
            loader.fadeIn();
            let data = {};
            data.order = $(this).data('order');
            data.id = $(this).data('id');
            if(data.order && data.id){
                $.ajax({
                    url: customjs_ajax_object.ajax_url,
                    type: 'post',
                    data: {
                        action: 'ajax_remove_extra',
                        data: JSON.stringify(data),
                    },
                    success: function (response) {
                        if (response !== 'response') {
                            console.log();
                            loader.fadeOut();
                            element.parent().remove();
                        }
                    },
                    error: function (error) {
                        loader.fadeOut();
                        console.log(error);
                    }
                });
            }
        } else if(type == 'accom'){
            loader.fadeIn();
            let data = {};
            data.order = $(this).data('order');
            data.id = $(this).data('id');
            data.index = $(this).data('index');
            data.hotel = $(this).data('hotel');
            data.name = $(this).data('name');
            console.log(data);
            if(data.order && data.id  && data.hotel && data.name){
                
                $.ajax({
                    url: customjs_ajax_object.ajax_url,
                    type: 'post',
                    data: {
                        action: 'ajax_remove_accom_from_quote',
                        data: JSON.stringify(data),
                    },
                    success: function (response) {
                        if (response !== '') {
                            loader.fadeOut();
                            element.parent().remove();
                        
                        }
                    },
                    error: function (error) {
                        loader.fadeOut();
                        console.log(error);
                    }
                });
            } else{
                loader.fadeOut();
            }
        }
    });
}

export { basketRemoveItem };
