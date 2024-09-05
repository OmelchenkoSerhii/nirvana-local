import $ from 'jquery';
import 'slick-carousel';
import { initPopups } from '../../parts/popups';
import { maps } from '../../parts/maps';

function hotelsListGallery() {
    $('.accommodations__item-image-gallery').each(function () {
        let block = $(this);
        let slider = $(this);

        slider.slick({
            dots: true,
            arrows: false,
            infinite: false,
            slidesToShow: 1,
            speed: 500,
            cssEase: 'linear',
            centerPadding: '0',
            adaptiveHeight: false,
            autoplay: false,
            autoplaySpeed: 5000,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                    }
                },
            ]
        });

    });
}

function hotelsListSearch() {
    $('.js-accoms-search').each(function () {
        let orderNumber = $(this).data('order');
        let form = $(this).find('.order-form');
        let accomsList = $(this).find('.js-accoms-list');
        let accomsResult = $(this).find('.js-accoms-result');
        let loader = $(this).find('.book-accommodation__form-loading');

        let isBedbanks = $(this).hasClass('js-bedbanks-search');

        form.on('submit', function (e) {
            e.preventDefault();
            let data = {};
            data['date-checkin'] = form.find('input[name="date-checkin"]').val();
            data['date-checkout'] = form.find('input[name="date-checkout"]').val();
            data['rooms'] = [];
            form.find('.people-field__rooms .people-field__room').each(function(){
                let room = {};
                room['adults_qtt'] = $(this).find('input[name="adults_qtt"]').val();
                room['childs_qtt'] = $(this).find('input[name="childs_qtt"]').val();
                room['infants_qtt'] = $(this).find('input[name="infants_qtt"]').val();
                room['ages'] = [];
                $(this).find('select[name="ages[]"]').each(function () {
                    room['ages'].push($(this).val());
                });
                room['infant_ages'] = [];
                $(this).find('select[name="infant_ages[]"]').each(function () {
                    room['infant_ages'].push($(this).val());
                });
                data['rooms'].push(room);
            });
            data['currency'] = form.find('select[name="currency"]').val();

            data.order = orderNumber;
            console.log(data);
            loader.fadeIn();
            $.ajax({
                url: customjs_ajax_object.ajax_url,
                type: 'post',
                data: {
                    action: isBedbanks?'ajax_search_bedbanks':'ajax_search_accoms',
                    data: JSON.stringify(data),
                },
                success: function (response) {
                    if (response !== '') {
                        loader.fadeOut();
                        //console.log(response);
                        accomsResult.html(response);
                        hotelsListGallery();
                        initPopups();
                        maps();
                        let sort_type = $('.sort-dropdown__content li.active').data('sort');
                        let sort_order = $('.sort-dropdown__content li.active').data('type');
                        sort_items(sort_type, sort_order);
                        $('.accommodations__item[data-priority="1"]').css('order' , -1);
                    }
                },
                error: function (error) {
                    console.log(error);
                    loader.fadeOut();
                }
            });
        });

        //sort
        let dropdown = $(this).find('.sort-dropdown');
        let dropdown_btn = $(this).find('.sort-dropdown__button');
        let content = $(this).find('.sort-dropdown__content');
        let selected = $(this).find('.sort-dropdown__selected');

        function sort_items(sortType, sortOrder) {
            var $listItems = $(".accommodations__item");

            $listItems.each(function () {
                let value = $(this).data(sortType);
                if (!value) value = 0;

                if (sortOrder == 'desc') {
                    $(this).css("order", 10000000 - parseInt($(this).data(sortType)));
                } else {
                    $(this).css("order", parseInt($(this).data(sortType)));
                }
            });
        }

        sort_items('distance', 'asc');
        $('.accommodations__item[data-priority="1"]').css('order' , -1);

        dropdown_btn.on('click', function () {
            content.slideToggle();
            dropdown_btn.toggleClass('opened');
        });
        content.find('li').on('click', function () {
            let sort = $(this).data('sort');
            let type = $(this).data('type');
            content.find('li').removeClass('active');
            $(this).addClass('active');
            selected.text(content.find('li.active').text());
            content.slideUp();
            dropdown_btn.removeClass('opened');
            sort_items(sort, type);
        });
        $(document).on("click", function (event) {
            var $trigger = dropdown;
            if ($trigger !== event.target && !$trigger.has(event.target).length) {
                content.slideUp();
            }
        });
    });
}

export { hotelsListGallery, hotelsListSearch };
