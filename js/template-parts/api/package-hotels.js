import $ from 'jquery'
import 'slick-carousel'
import { CelticAjaxCheckCode } from '../../parts/celtic';


function packageListSearch() {
    $('.js-packages-search').each(function () {
        let orderNumber = $(this).data('order');
        let form = $(this).find('.order-form');
        let accomsList = $(this).find('.js-package-result');
        let loader = $(this).find('.book-accommodation__form-loading');
        form.on('submit', function (e) {
            e.preventDefault();
            let data = {};
            data['date-checkin'] = form.find('input[name="date-checkin"]').val();
            data['date-checkout'] = form.find('input[name="date-checkout"]').val();
            data['rooms'] = [];
            form.find('.people-field__rooms .people-field__room').each(function () {
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
                    action: 'ajax_search_packages',
                    data: JSON.stringify(data),
                },
                success: function (response) {
                    if (response !== '') {
                        loader.fadeOut();
                        //console.log(response);
                        accomsList.html(response);
                        //hotelsListGallery();
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        form.find('.people-field .button.js-close').on('click',function(){
            form.trigger('submit');
        });


        //sort
        let sort_dropdown = $(this).find('.js-sort-dropdown');
        sort_dropdown.each(function () {
            let dropdown = $(this);
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

            sort_items('priority', 'desc');

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

        let nights_dropdown = $(this).find('.js-nights-dropdown');
        let nights_options = [];
        accomsList.find('.accommodations__item').each(function () {
            nights_options.push($(this).data('night'));
        });
        function uniq(a) {
            return a.sort().filter(function (item, pos, ary) {
                return !pos || item != ary[pos - 1];
            });
        }
        nights_options = uniq(nights_options);

        nights_dropdown.each(function () {
            let dropdown = $(this);
            let dropdown_btn = $(this).find('.sort-dropdown__button');
            let content = $(this).find('.sort-dropdown__content');
            let selected = $(this).find('.sort-dropdown__selected');

            nights_options.forEach((val) => {
                content.append('<li data-sort="night" data-value="' + val + '">' + val + '</li>');
            });
            dropdown_btn.on('click', function () {
                content.slideToggle();
                dropdown_btn.toggleClass('opened');
            });
            content.find('li').on('click', function () {
                let val = $(this).data('value');
                content.find('li').removeClass('active');
                $(this).addClass('active');
                selected.text(content.find('li.active').text());
                content.slideUp();
                dropdown_btn.removeClass('opened');
                console.log(val);
                accomsList.find('.accommodations__item').hide();
                accomsList.find('.accommodations__item[data-night="' + val + '"]').show();
            });
            $(document).on("click", function (event) {
                var $trigger = dropdown;
                if ($trigger !== event.target && !$trigger.has(event.target).length) {
                    content.slideUp();
                }
            });
        });

    });
}

function packageRoomsList() {

    $('.package-hotel-item').each(function () {
        const block = $(this)

        if(block.data('index') != 0){
            block.hide();
        }

        /*Hotels navigation*/
        block.find('.js-next-hotel').on('click' , function(){
            block.hide();
            let nextHotel = $('.package-hotel-item[data-index="'+(block.data('index')+1)+'"]');
            nextHotel.show();
            // Scroll to the previous block
            $('html, body').animate({
                scrollTop: nextHotel.offset().top
            }, 1000); // Adjust the duration as needed
        });
        block.find('.js-prev-hotel').on('click' , function(){
            block.hide();
            let prevHotel = $('.package-hotel-item[data-index="'+(block.data('index')-1)+'"]');
            prevHotel.show();
            // Scroll to the previous block
            $('html, body').animate({
                scrollTop: prevHotel.offset().top
            }, 1000); // Adjust the duration as needed
        });
        /*Hotel navigation end*/

        block.find('.js-packages-tour-rooms').each(function(){
            let hotel = $(this);
            const slider = hotel.find('.package-popup-rooms-list')
            slider.each(function(){
                let slider_block = $(this);
                slider_block.slick({
                    dots: false,
                    arrows: true,
                    infinite: false,
                    slidesToShow: 2,
                    speed: 500,
                    cssEase: 'linear',
                    centerPadding: '0',
                    adaptiveHeight: false,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    prevArrow: hotel.find('.slider-arrows .button--arrow-left'),
                    nextArrow: hotel.find('.slider-arrows .button--arrow').last(),
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1
                            }
                        },
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 2
                            }
                        }
                    ]
                })
        
                slider_block.on('init', function () {
                    $(window).trigger('heightChanges')
                })
            });

            //predefine default accom
            let activeRoom = hotel.find('.package-rooms-list .package-room.active').data('roomid');
            let activeRoomID = hotel.find('.package-rooms-list .package-room.active').data('room');
            let activeRoomTitle = hotel.find('.package-rooms-list .package-room.active').data('title');
            let activeRoomBB = hotel.find('.package-rooms-list .package-room.active').data('bb');
            let activeSelectionState = hotel.find('.package-rooms-list .package-room.active').data('state');
            let activePrice = hotel.find('.package-rooms-list .package-room.active').data('price');
            let activeDuration = hotel.find('.package-rooms-list .package-room.active').data('duration');
            let activeOccupancy = hotel.find('.package-rooms-list .package-room.active').data('occupancy');
            hotel.find('#accom-id').val(activeRoom);
            hotel.find('#room-id').val(activeRoomID);
            hotel.find('#room-title').val(activeRoomTitle);
            hotel.find('#room-bb').val(activeRoomBB);
            hotel.find('#room-price').val(activePrice);
            hotel.find('#room-duration').val(activeDuration);
            hotel.find('#room-occupancy').val(activeOccupancy);
            hotel.find('#accom-selection-state').val(activeSelectionState);

            hotel.find('.js-open-packages-popup').on('click', function () {
                let popup = $(this).data('popup');
                hotel.find('#'+popup).fadeIn();
                hotel.find('#'+popup).find('.package-popup-rooms-list').slick("resize");
            });

            hotel.find('.js-upgrade-room').on('click', function () {
                let room = $(this).data('room');
                hotel.find('.package-rooms-list .package-room').hide().removeClass('active');
                hotel.find('.package-rooms-list .package-room[data-room="' + room + '"]').show().addClass('active');

                let activeRoom = hotel.find('.package-rooms-list .package-room.active').data('roomid');
                let activeRoomID = hotel.find('.package-rooms-list .package-room.active').data('room');
                let activeRoomTitle = hotel.find('.package-rooms-list .package-room.active').data('title');
                let activeRoomBB = hotel.find('.package-rooms-list .package-room.active').data('bb');
                let activeSelectionState = hotel.find('.package-rooms-list .package-room.active').data('state');
                let activePrice = hotel.find('.package-rooms-list .package-room.active').data('price');
                let activeDuration = hotel.find('.package-rooms-list .package-room.active').data('duration');
                let activeOccupancy = hotel.find('.package-rooms-list .package-room.active').data('occupancy');
                hotel.find('#accom-id').val(activeRoom);
                hotel.find('#room-id').val(activeRoomID);
                hotel.find('#room-title').val(activeRoomTitle);
                hotel.find('#room-bb').val(activeRoomBB);
                hotel.find('#room-price').val(activePrice);
                hotel.find('#accom-selection-state').val(activeSelectionState);
                hotel.find('#room-duration').val(activeDuration);
                hotel.find('#room-occupancy').val(activeOccupancy);
                hotel.find('.package-rooms-popup').fadeOut();
            });
        });
    });
    $('#package-book-form').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let loader = $('.book-accommodation__form-loading');
        let redirectURL = form.attr('action');

        let data = {};
        data.order = form.data('order');
        data.hotels = [];
        $('.js-package-hotel-data').each(function(){
            let dataForm = $(this);
            let hotel = {};
            hotel.hotel_id = dataForm.find('#hotel-id').val();
            hotel.hotel_post_id = dataForm.find('#hotel-post-id').val();
            hotel.AccomId = dataForm.find('#accom-id').val();
            hotel.room_id = dataForm.find('#room-id').val();
            hotel.room_title = dataForm.find('#room-title').val();
            hotel.room_bb = dataForm.find('#room-bb').val();
            hotel.room_price = dataForm.find('#room-price').val();
            hotel.room_duration = dataForm.find('#room-duration').val();
            hotel.room_occupancy = dataForm.find('#room-occupancy').val();
            hotel.tour_id = dataForm.find('#tour-id').val();
            hotel.SelectionState = dataForm.find('#accom-selection-state').val();
            data.hotels.push(hotel);
        });

        $.ajax({
            url: customjs_ajax_object.ajax_url,
            type: 'post',
            data: {
                action: 'ajax_reserve_package_accom',
                data: JSON.stringify(data),
            },
            success: function (response) {
                console.log(response);
                if (response !== '') {
                    //console.log(response);
                    //console.log('success');
                    //console.log(redirectURL);
                    window.location.href = redirectURL;
                    //console.log(redirectURL);

                } else {
                    loader.fadeOut();
                    $errorsOutput.html('Error.');
                    $errorsOutput.show();
                }

            },
            error: function (error) {
                //console.log(error);
                loader.fadeOut();
                $errorsOutput.html('Error.');
                $errorsOutput.show();
            }
        });
    });
}


function packageReserveForms() {
    $('#reserve-package-accom-form').each(function () {
        let form = $(this);
        let orderNumber = form.find('#order-number').val();
        let errors = form.find('.order-form__errors');
        form.find('.dropdown').each(function () {
            let btn = $(this).find('.dropdown__header');
            let content = $(this).find('.dropdown__content');
            let item = $(this).find('.dropdown__content li');
            let input = $(this).find('.traveller-id');
            let inputName = $(this).find('.traveller-name');
            btn.click(function () {
                content.slideToggle();
            });
            item.click(function () {
                content.slideUp();
                let value = $(this).data('person');
                let text = $(this).text();
                input.val(value);
                inputName.val(text);
                btn.text(text);
            });
        });

        form.find('.js-traveller').each(function () {
            let checkbox = $(this).find('.book-accommodation__content-adults-input--ageshow input');
            let age = $(this).find('.book-accommodation__content-adults-input--age');
            checkbox.on('change', function () {
                if ($(this).prop("checked")) {
                    age.show();
                } else {
                    age.hide();
                }
            });
        });


        form.on('submit', function (e) {
            e.preventDefault();
            let data = {};
            data.order = form.data('order');
            data.rooms = [];

            let redirectURL = form.attr('action');
            let rooms = form.find('.book-accommodation__form-item');

            let loader = form.find('.book-accommodation__form-loading');

            let $errorsOutput = form.find('.order-form__errors');
            let $errors = [];
            let $errorsText = '';
            let passengersError = false;

            let $celtic_codes = $(this).find('.celtic-code');

            let celtic_codes = []
            $celtic_codes.each(function () {
                celtic_codes.push($(this).val())
            })

            const toFindDuplicates = arry => arry.filter((item, index) => arry.indexOf(item) !== index);
            const duplicateElements = toFindDuplicates(celtic_codes);

            if (duplicateElements.length) {
                $errors.push('Client reference number should be unique.  The same client reference number can not be used twice.');
            }

            rooms.each(function () {
                let roomBlock = $(this);
                roomBlock.find('.hotel-data-item').each(function(){
                    let room = {};
                    room.id = $(this).find('.room_id').val();
                    room.unique_index = $(this).find('.room_index').val();
                    room.hotel = $(this).find('.hotel_id').val();
                    room.name = $(this).find('.room_name').val();
                    room.component_id = $(this).find('.room_component_id').val();
                    room.price = $(this).find('.room_price').val();
                    room.selection_state = $(this).find('.room_selection_state').val();
                    room.room_order_index = $(this).find('.room_order_index').val();
                    room.room_tour_index = $(this).find('.room_tour_index').val();
                    room.locator = $(this).find('.room_locator').html();
                    room.passengers = [];

                    roomBlock.find('.js-traveller').each(function () {
                        let obj = {};
                        obj.name = $(this).find('.name').val();
                        obj.surname = $(this).find('.surname').val();
                        obj.title = $(this).find('.title').val();
                        obj.type = $(this).find('.type').val();
                        obj.age = $(this).find('.age').val() ? $(this).find('.age').val() : 25;
                        obj.celtic_code = '';

                        if ($(this).find('.celtic-code').length) {
                            obj.celtic_code = $(this).find('.celtic-code').val() ?? '';
                        }

                        if (obj.name && obj.surname && obj.age) {
                            if ($(this).find('.celtic-code').length) {
                                if (obj.celtic_code === undefined || !obj.celtic_code) {
                                    $errors.push('You must enter your client reference number in order to continue.');
                                } else {
                                    const celtic_response = CelticAjaxCheckCode(obj.celtic_code, $(this).find('input[name="event_id"]').val())

                                    if (celtic_response.success === false) {
                                        $errors.push(celtic_response.data.message);
                                        return;
                                    }
                                }
                            }

                            room.passengers.push(obj);
                        } else {
                            $errors.push('Please enter all travellers.');
                            return;
                        }
                    });
                    data.rooms.push(room);
                });
            });

            if ($errors.length) {
                e.preventDefault();

                $errors.forEach(element => {
                    $errorsText += element + '<br/>';
                })
                $errorsOutput.html($errorsText);
                $errorsOutput.show();
            } else {
                $errorsOutput.html('');
                $errorsOutput.hide();

                loader.fadeIn();

                //console.log(data);

                $.ajax({
                    url: customjs_ajax_object.ajax_url,
                    type: 'post',
                    data: {
                        action: 'ajax_reserve_package',
                        data: JSON.stringify(data),
                    },
                    success: function (response) {
                        console.log(response);
                        if (response !== '') {
                            //console.log(response);
                            //console.log('success');
                            //console.log(redirectURL);
                            window.location.href = redirectURL;
                            //console.log(redirectURL);

                        } else {
                            loader.fadeOut();
                            $errorsOutput.html('Error.');
                            $errorsOutput.show();
                        }

                    },
                    error: function (error) {
                        //console.log(error);
                        loader.fadeOut();
                        $errorsOutput.html('Error.');
                        $errorsOutput.show();
                    }
                });

            }
        });
    });

    $('#reserve-package-passengers-form').each(function () {
        let form = $(this);
        let orderNumber = form.find('#order-number').val();
        let errors = form.find('.order-form__errors');

       
        form.find('.js-traveller').each(function () {
            let checkbox = $(this).find('.book-accommodation__content-adults-input--ageshow input');
            let age = $(this).find('.book-accommodation__content-adults-input--age');
            checkbox.on('change', function () {
                if ($(this).prop("checked")) {
                    age.show();
                } else {
                    age.hide();
                }
            });
        });


        form.on('submit', function (e) {
            e.preventDefault();
            let data = {};
            data.order = form.data('order');
            data.passengers = [];

            let redirectURL = form.attr('action');
            let rooms = form.find('.book-accommodation__form-item');

            let loader = form.find('.book-accommodation__form-loading');

            let $errorsOutput = form.find('.order-form__errors');
            let $errors = [];
            let $errorsText = '';
            let passengersError = false;

            let $celtic_codes = $(this).find('.celtic-code');

            let celtic_codes = []
            $celtic_codes.each(function () {
                celtic_codes.push($(this).val())
            })

            const toFindDuplicates = arry => arry.filter((item, index) => arry.indexOf(item) !== index);
            const duplicateElements = toFindDuplicates(celtic_codes);

            if (duplicateElements.length) {
                $errors.push('Client reference number should be unique.  The same client reference number can not be used twice.');
            }

            rooms.each(function () {
                $(this).find('.js-travellers-list .js-traveller').each(function () {
                    let obj = {};
                    obj.name = $(this).find('.name').val();
                    obj.surname = $(this).find('.surname').val();
                    obj.title = $(this).find('.title').val();
                    obj.type = $(this).find('.type').val();
                    obj.age = $(this).find('.age').val() ? $(this).find('.age').val() : 25;
                    obj.celtic_code = undefined;

                    if ($(this).find('.celtic-code').length) {
                        obj.celtic_code = $(this).find('.celtic-code').val() ?? '';
                    }

                    if (obj.name && obj.surname && obj.age) {
                        if ($(this).find('.celtic-code').length) {
                            if (obj.celtic_code === undefined || !obj.celtic_code) {
                                $errors.push('You must enter your client reference number in order to continue.');
                            } else {
                                const celtic_response = CelticAjaxCheckCode(obj.celtic_code, $(this).find('input[name="event_id"]').val())

                                if (celtic_response.success === false) {
                                    $errors.push(celtic_response.data.message);
                                    return;
                                }
                            }
                        }
                        data.passengers.push(obj);
                    } else {
                        passengersError = true;
                        return;
                    }
               
                });
            });

            if (passengersError) {
                $errors.push('Please enter all travellers.');
            }

            if ($errors.length) {
                e.preventDefault();

                $errors.forEach(element => {
                    $errorsText += element + '<br/>';
                })
                $errorsOutput.html($errorsText);
                $errorsOutput.show();
            } else {
                $errorsOutput.html('');
                $errorsOutput.hide();

                loader.fadeIn();


                $.ajax({
                    url: customjs_ajax_object.ajax_url,
                    type: 'post',
                    data: {
                        action: 'ajax_add_package_passengers',
                        data: JSON.stringify(data),
                    },
                    success: function (response) {
                        console.log(response);
                        if (response !== '') {
                            //console.log(response);
                            //console.log('success');
                            //console.log(redirectURL);
                            window.location.href = redirectURL;

                        } else {
                            loader.fadeOut();
                            $errorsOutput.html('Error.');
                            $errorsOutput.show();
                        }

                    },
                    error: function (error) {
                        //console.log(error);
                        loader.fadeOut();
                        $errorsOutput.html('Error.');
                        $errorsOutput.show();
                    }
                });

            }
        });
    });



    $('#api-package-extra-form').each(function () {
        let form = $(this);
        let $orderNum = form.find('#order-number').val();
        let loader = form.find('.book-accommodation__form-loading');
        let popupAdded = form.find('#popup-extra-added');
        let popupRemoved = form.find('#popup-extra-removed');
        let popupInsurance = form.find('#popup-extra-insurance');
        let popupInsuranceEmpty = form.find('#popup-extra-insurance-empty');

        let bypassInsurance = false;

        //add/remove on save button clicked
        form.find('.extras-card').each(function () {
            let $extra = $(this);
            let $title = $(this).find('.js-extra-title').val();
            let $extraID = $(this).find('.extra-id').val();
            let $extraNBR = $(this).find('.extra-nbr-code').val();
            let $qtt = $(this).find('.js-extra-qtt');
            let $personsSelected = $(this).find('.js-person-selected');
            let $personsSelectedVal = $(this).find('.js-person-selected-val');
            let $insurance = $(this).find('.insurance-checkbox');
            let $travellersList = $(this).find('.extras-card__travellers');
            let $selectAll = $(this).find('.js-select-all');
            let $clearAll = $(this).find('.js-clear-all');
            let $saveButton = $(this).find('.js-save-extras');

            //$saveButton.on('click', saveExtras);

            $selectAll.on('click', function () {
                $extra.find('.extras-card__travellers-item .extras-card__travellers-item__label:not(.disabled)').each(function () {
                    $(this).find('.person').prop("checked", true);
                });
            });

            $clearAll.on('click', function () {
                $extra.find('.extras-card__travellers-item .extras-card__travellers-item__label:not(.mandatory)').each(function () {
                    $(this).find('.person').prop("checked", false);
                });
            });
        });

        form.on('submit', function (e) {
            e.preventDefault();

            let extrasDataArray = {};
            extrasDataArray.order = $orderNum;
            extrasDataArray.data = [];

            let insuranceExists = false;
            let insuranceEmpty = true;
            let insuranceAccepted = false;

            popupInsuranceEmpty.fadeOut();

            form.find('.extras-card').each(function () {
                let $extra = $(this);
                let $title = $(this).find('.js-extra-title').val();
                let $extraID = $(this).find('.extra-id').val();
                let $extraNBR = $(this).find('.extra-nbr-code').val();
                let $qtt = $(this).find('.js-extra-qtt');
                let $personsSelected = $(this).find('.js-person-selected');
                let $personsSelectedVal = $(this).find('.js-person-selected-val');
                let $insurance = $(this).find('.insurance-checkbox');
                let $travellersList = $(this).find('.extras-card__travellers');
                let $selectAll = $(this).find('.js-select-all');
                let $clearAll = $(this).find('.js-clear-all');
                let $saveButton = $(this).find('.js-save-extras');
                let data = {};
                data.id = $extraID;
                data.NBRCode = $extraNBR;
                data.Name = $title;
                data.travellers = [];
                $extra.find('.extras-card__travellers-item').each(function () {
                    let traveller = {};
                    traveller.ref_number = $(this).find('.person').val();
                    traveller.type = $(this).find('.type').val();
                    traveller.age_code = $(this).find('.agecode').val();
                    traveller.tour_id = $(this).find('.tour_id').val();
                    traveller.price = $(this).find('.price').val();
                    traveller.selected = $(this).find('.person').prop("checked") ? 1 : 0;
                    if (traveller.selected == 1) {
                        data.travellers.push(traveller);
                    }
                });

                extrasDataArray.data.push(data);

                if ($insurance.length) insuranceExists = true;
                if ($insurance.length && data.travellers.length) insuranceEmpty = false;
                if ($insurance.length && data.travellers.length) {
                    if (!$insurance.prop("checked")) {
                        insuranceAccepted = false;
                        popupInsurance.fadeIn();
                        popupInsurance.find('.js-approve-insurance').on('click', function (e) {
                            e.preventDefault();
                            $insurance.prop("checked", true);
                            popupInsurance.fadeOut();
                            popupInsurance.find('.js-approve-insurance').off('click');
                        });
                    } else {
                        insuranceAccepted = true;
                    }
                }
            });


            if (!insuranceExists || (insuranceExists && insuranceAccepted) || (insuranceExists && insuranceEmpty && bypassInsurance)) {
                loader.fadeIn();
                $.ajax({
                    url: customjs_ajax_object.ajax_url,
                    type: 'post',
                    data: {
                        action: 'ajax_reserve_packages_extras',
                        data: JSON.stringify(extrasDataArray),
                    },
                    success: function (response) {
                        console.log(response);
                        if (response !== '') {
                            //console.log(response);
                            //loader.fadeOut();

                            let redirectURL = form.attr('action');
                            window.location.href = redirectURL;
                        }
                    },
                    error: function (response) {
                        //console.log(response);

                    }
                });
            }
            if (insuranceExists && insuranceEmpty && !bypassInsurance) {
                bypassInsurance = true;
                popupInsuranceEmpty.fadeIn();
            }
        });
    });

    
    $('#api-package-tranfers-form').each(function () {
        let form = $(this);
        let $orderNum = form.find('#order-number').val();

        let loader = form.find('.book-accommodation__form-loading');

        form.on('submit', function (e) {
            e.preventDefault();
            let redirectURL = form.attr('action');
            let data = {};
            let transfer = form.find('input[name=transfer]:checked');
            let trasfersArray = [];
            form.find('.transfer-card').each(function () {
                let card = $(this);
                let isChecked = card.find('[name="transfer_id"]:checked').length;
                //console.log(isChecked);
                let obj = {};
                obj.transfer_id = card.find('[name="transfer_id"]').val();
                obj.passengers = [];
                card.find('.extras-card__travellers-item').each(function () {
                    let person_obj = {};
                    let person_selected = $(this).find('.person:checked');
                    person_obj.id = $(this).find('.person').val();
                    person_obj.tour_id = $(this).find('.tour_id').val();
                    //if(person_selected.length){
                        obj.passengers.push(person_obj);
                    //}
                });
                if (isChecked) {
                    trasfersArray.push(obj);
                }
            });
            data.transfers = trasfersArray;
            data.order = form.find('#order-number').val();
            loader.fadeIn();
            //console.log(data);
            $.ajax({
                url: customjs_ajax_object.ajax_url,
                type: 'post',
                data: {
                    action: 'ajax_reserve_tour',
                    data: JSON.stringify(data),
                },
                success: function (response) {
                    if (response !== '') {
                        //console.log(response);
                        //loader.fadeOut();

                        window.location.href = redirectURL;

                    }
                }
            });
        });
    });

}

export { packageRoomsList, packageReserveForms, packageListSearch }
