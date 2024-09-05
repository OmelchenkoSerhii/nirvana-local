import $ from 'jquery';

function notifyMeForm() {
    $('.notify-me-form').each(function () {
        let form = $(this);

        form.on('submit', function (e) {
            e.preventDefault();
            let dataArray = form.serializeArray();
            let data = {};
            dataArray.forEach(element => {
                data[element['name']] = element['value'];
            });
            console.log(data);
            $.ajax({
                url: customjs_ajax_object.ajax_url,
                type: 'post',
                data: {
                    action: 'ajax_notify_me',
                    data: JSON.stringify(data),
                },
                success: function (response) {
                    if (response !== '') {
                        console.log(response);
                        form.get(0).reset();
                        form.find('.error').hide();
                        form.find('.order-form__inner').hide();
                        form.find('.success').show(); 
                        setTimeout(function(){
                            //form.find('.success').hide(); 
                        } , 3000);
                    }
                },
                error: function(){
                    form.find('.error').show();
                    form.find('.success').hide(); 
                }
            });

        });
    });

    if($('.notify-me-form').length){
        $(document).on('click', '.js-notify-list-btn' , function(e){
            e.preventDefault();
            $('.notify-me-form').find('.order-form__inner').show();
            $('.notify-me-form').find('.success').hide();
            $('.notify-me-form').find('.error').hide();
            let destination = $(this).data('destination');
            let name = $(this).data('name');
            let year = $(this).data('year');
            let month = $(this).data('month');
            $('.notify-me-form').find('input[name="destination"]').val(destination);
            $('.notify-me-form').find('input[name="title"]').val(name);
            $('.notify-me-form').find('input[name="year"]').val(year);
            $('.notify-me-form').find('input[name="month"]').val(month);
            $('.notify-me-form').find('.success .event').text(name);
            $('.notify-me-form').find('.order-form__title-event').text(name);
            $('#popup-notify').fadeIn()
        });
    }
}

export { notifyMeForm };
