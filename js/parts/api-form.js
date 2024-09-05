import $ from 'jquery';
import 'daterangepicker';
import { CelticAjaxCheckCode } from './celtic';


function apiForm() {

    $('.order-form').each(function () {

        function numberOfNights(startDate, endDate) {
            let start = startDate ? moment(startDate, 'D MMMM YYYY') : moment().subtract(29, 'days');
            let end = endDate ? moment(endDate, 'D MMMM YYYY') : moment();
            return end.diff(start, 'days');
        }



        var customSelect = $(".custom-select");
        $(this).find('.custom-select').each(function () {
            var thisCustomSelect = $(this),
                $select = $(this).find('select'),
                options = thisCustomSelect.find("option"),
                firstOptionText = thisCustomSelect.find("option:selected").text(),
                firstOptionImage = thisCustomSelect.find("option:selected").data('img');

            var selectedItem = $("<div></div>", {
                class: "selected-item"
            })
                .appendTo(thisCustomSelect)
                .html(firstOptionText + '<img class="flag" src="' + firstOptionImage + '">');

            var allItems = $("<div></div>", {
                class: "all-items all-items-hide"
            }).appendTo(thisCustomSelect);

            options.each(function () {
                var that = $(this),
                    optionText = that.text(),
                    optionImage = that.data('img');

                var item = $("<div></div>", {
                    class: "item",
                    rel: that.val(),
                    on: {
                        click: function () {
                            var selectedOptionText = that.html();
                            selectedItem.html(selectedOptionText + '<img class="flag" src="' + optionImage + '">').removeClass("arrowanim");
                            allItems.addClass("all-items-hide");
                            $select.val(that.val());
                        }
                    }
                })
                    .appendTo(allItems)
                    .html(optionText + '<img class="flag" src="' + optionImage + '">');
            });
        });

        var selectedItem = $(".selected-item"),
            allItems = $(".all-items");

        selectedItem.on("click", function (e) {
            var currentSelectedItem = $(this),
                currentAllItems = currentSelectedItem.next(".all-items");

            allItems.not(currentAllItems).addClass("all-items-hide");
            selectedItem.not(currentSelectedItem).removeClass("arrowanim");

            currentAllItems.toggleClass("all-items-hide");
            currentSelectedItem.toggleClass("arrowanim");

            e.stopPropagation();
        });

        $(document).on("click", function () {
            var opened = $(".all-items:not(.all-items-hide)"),
                index = opened.parent().index();

            customSelect
                .eq(index)
                .find(".all-items")
                .addClass("all-items-hide");
            customSelect
                .eq(index)
                .find(".selected-item")
                .removeClass("arrowanim");
        });


        $(this).find('.datepicker').each(function () {
            let picker = $(this);
            let start = picker.data('start') ? moment(picker.data('start'), 'MMMM D, YYYY') : moment().subtract(29, 'days');
            let end = picker.data('end') ? moment(picker.data('end'), 'MMMM D, YYYY') : moment();
            let nights = picker.find('.js-night');
            let nonSelected = picker.hasClass('datepicker--not-selected');

            let checkinField = picker.find('.order-form__field--checkin');
            let checkoutField = picker.find('.order-form__field--checkout');

            if (start.isSame(end)) {
                //end.add(1, 'days')
            }

            let startRange = moment(start);
            let endRange = moment(end);

            let checkin = picker.find('input[name="date-checkin"]');
            let checkout = picker.find('input[name="date-checkout"]');

            if (!nonSelected) {
                checkin.val(start.format('D MMMM YYYY'));
                checkout.val(end.format('D MMMM YYYY'));
                nights.text(end.diff(start, 'days'));
            }

            checkinField.daterangepicker({
                singleDatePicker: true,
                startDate: start,
                minDate: startRange.subtract(21, 'days'),
                maxDate: endRange.add(21, 'days'),
                autoUpdateInput: true,
                autoApply: true,
                defaultDate: null,
                locale: {
                    firstDay: 1,
                    format: 'MM/DD/YYYY',
                    daysOfWeek: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                }
            }, function (start) {

            });
            checkoutField.daterangepicker({
                singleDatePicker: true,
                startDate: end,
                minDate: start,
                maxDate: endRange.add(21, 'days'),
                autoUpdateInput: true,
                autoApply: true,
                defaultDate: null,
                locale: {
                    firstDay: 1,
                    format: 'MM/DD/YYYY',
                    daysOfWeek: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                }
            }, function (start) {
                checkout.val(start.format('D MMMM YYYY'));
                let startVal = checkin.val();
                let endVal = checkout.val();
                nights.show();
                nights.text(numberOfNights(startVal, endVal));
                picker.removeClass('active');
            });


            checkinField.on('apply.daterangepicker', function (ev, picker) {
                let start = picker.startDate;
                checkin.val(start.format('D MMMM YYYY'));
                checkoutField.data('daterangepicker').minDate = start;
                checkoutField.data('daterangepicker').startDate = start;
                checkoutField.data('daterangepicker').endDate = start;

                let startVal = checkin.val();
                let endVal = checkout.val();
                nights.text(numberOfNights(startVal, endVal));

                setTimeout(function () {
                    checkoutField.trigger('click');
                }, 200);
            });

            checkinField.on('show.daterangepicker', function (ev, el) {
                picker.addClass('active');
            });

            checkinField.on('hide.daterangepicker', function (ev, el) {
                picker.removeClass('active');
            });
            checkoutField.on('show.daterangepicker', function (ev, el) {
                picker.addClass('active');
            });
            checkoutField.on('hide.daterangepicker', function (ev, el) {
                picker.removeClass('active');
            });

        });

        $(this).find('.dropdown-field').each(function () {
            let item = $(this);
            let header = $(this).find('.dropdown-field__header');
            let content = $(this).find('.dropdown-field__content');
            let closeBtn = $(this).find('.js-close');
            header.on('click', function () {
                content.toggle();
                header.toggleClass('opened');
            });
            closeBtn.on('click', function () {
                content.hide();
                header.removeClass('opened');
            })

            $(document).on("click", function (event) {
                var $trigger = item;
                if ($trigger !== event.target && !$trigger.has(event.target).length) {
                    content.hide();
                    header.removeClass('opened');
                }
            });
        });

        $(this).find('.people-field').each(function () {
            let block = $(this);
            let qttSelect = $(this).find('.people-field__qtt');
            let roomContent = $(this).find('.room-content').html();
            let roomsList = $(this).find('.people-field__rooms');
            let addRoomBtn = $(this).find('.js-add-room');
            let closeBtn = $(this).find('.js-close');

            addRoomBtn.on('click', function () {
                let content = $(roomContent);
                content.find('.js-room-number').text(roomsList.find('.js-extra-room').length + 2);
                block.find('*[data-name="rooms_qtt"]').text(roomsList.find('.js-extra-room').length + 2);
                content.find('.people-field__qtt').each(function () {
                    let field = $(this);
                    initPeopleQtt(field);
                });
                roomsList.append(content);
            });

            $('body').on('click', '.js-remove-room', function () {
                block.find('*[data-name="rooms_qtt"]').text(roomsList.find('.js-extra-room').length);
                $(this).parent().remove();
            })

            function initPeopleQtt($el) {
                let qttBlock = $el;
                let input = $el.find('input');
                let plus = $el.find('.plus');
                let minus = $el.find('.minus');
                let ages = $el.find('.people-field__qtt__ages');

                input.on('change', function () {
                    let label = $(this).data('label');
                    let value = parseInt($(this).val());
                    let name = $(this).attr('name');

                    let qtt = 0;

                    if(name == 'childs_qtt' || name == 'infants_qtt'){
                        roomsList.find('input[name="childs_qtt"]').each(function () {
                            qtt += parseInt($(this).val());
                        });
                        /*roomsList.find('input[name="infants_qtt"]').each(function () {
                            qtt += parseInt($(this).val());
                        });*/
                    } else{
                        roomsList.find('input[name="' + name + '"]').each(function () {
                            qtt += parseInt($(this).val());
                        });
                    }                    

                    if(name == 'infants_qtt'){
                        //let tempName = 'childs_qtt';
                        //block.find('[data-name="' + tempName + '"]').html(qtt);
                        //block.find('[data-name="' + tempName + '_label"]').text(qtt == 1 ? block.find('[data-name="' + tempName + '_label"]').data('single') : block.find('[data-name="' + tempName + '_label"]').data('plural'));

                    } else{
                        block.find('[data-name="' + name + '"]').html(qtt);
                        block.find('[data-name="' + name + '_label"]').text(qtt == 1 ? block.find('[data-name="' + name + '_label"]').data('single') : block.find('[data-name="' + name + '_label"]').data('plural'));
                    }

                    if (ages.length) {
                        let element = $('<li><span class="d-flex flex-column w-100"><span class="top"><span>' + label + ' ' + value + ' Age</span><input type="number" required class="number" name="ages[]" placeholder="Age"/></span><span class="text-fields"><input type="text" name="first-name[]" required placeholder="First Name"/><input type="text" name="surname[]" required placeholder="Last Name"/></span></li>');
                        if (ages.hasClass('people-field__qtt__ages--child')) {
                            element = $('<li><span class="d-flex flex-column w-100"><span class="top"><span>' + label + ' ' + value + ' Age</span><select required class="select" name="ages[]"></span></span></li>');
                        }
                        if (ages.hasClass('people-field__qtt__ages--infant')) {
                            element = $('<li><span class="d-flex flex-column w-100"><span class="top"><span>' + label + ' ' + value + ' Age</span><select required class="select" name="infant_ages[]"></span></span></li>');
                        }
                        let current = ages.find('li').length;
                        if (current > value) {
                            ages.find("li:last").remove();
                        } else {
                            if(ages.hasClass('people-field__qtt__ages--infant')){
                                for (let i = 0; i <= 1; i++) {
                                    element.find('select').append('<option value="' + i + '">' + i + '</option>');
                                }
                            } else{
                                for (let i = 2; i <= 11; i++) {
                                    element.find('select').append('<option value="' + i + '">' + i + '</option>');
                                }
                            }
                            ages.append(element);
                        }
                        if (ages.find('li').length) {
                            ages.show();
                        } else {
                            ages.hide();
                        }
                    }
                });

                qttBlock.find('.people-field__qtt__ages--child>li').each(function (index) {
                    let age = $(this).data('age');
                    console.log(age);
                    let element = $('<span class="d-flex flex-column w-100"><span class="top"><span>Child ' + (index + 1) + ' Age</span><select required class="select" name="ages[]"></span></span>');
                    for (let i = 1; i <= 11; i++) {
                        element.find('select').append('<option value="' + i + '" ' + (age == i ? 'selected="selected"' : '') + '>' + i + '</option>');
                    }
                    $(this).html(element);
                });
                if (qttBlock.find('.people-field__qtt__ages--child>li').length) {
                    qttBlock.find('.people-field__qtt__ages--child').show();
                }

                qttBlock.find('.people-field__qtt__ages--infant>li').each(function (index) {
                    let age = $(this).data('age');
                    console.log(age);
                    let element = $('<span class="d-flex flex-column w-100"><span class="top"><span>Infant ' + (index + 1) + ' Age</span><select required class="select" name="infant_ages[]"></span></span>');
                    for (let i = 0; i <= 1; i++) {
                        element.find('select').append('<option value="' + i + '" ' + (age == i ? 'selected="selected"' : '') + '>' + i + '</option>');
                    }
                    $(this).html(element);
                });
                if (qttBlock.find('.people-field__qtt__ages--infant>li').length) {
                    qttBlock.find('.people-field__qtt__ages--infant').show();
                }

                plus.click(function () {
                    let val = parseInt(input.val());
                    if (val == 0) {
                        minus.removeClass('disabled');
                    }
                    input.val(val + 1).trigger('change');
                    //block.find('[data-name="' + input.attr('name') + '"]').text(val + 1);
                });

                minus.click(function () {
                    let val = parseInt(input.val());
                    if (val == 1) {
                        minus.addClass('disabled');
                    }
                    if (val == 0) {

                    } else {
                        input.val(val - 1).trigger('change');
                        //block.find('[data-name="' + input.attr('name') + '"]').text(val - 1);
                    }
                });
            }

            qttSelect.each(function () {
                initPeopleQtt($(this));
            })
        });

    });

}

function bookDatesFormValidation() {
    $('#order-dates').each(function () {
        let $form = $(this);
        let $errorsOutput = $form.find('.order-form__errors');
        let loader = $(this).find('.book-accommodation__form-loading');
        let redirect_url = $(this).find('input[name="redirect_url"]').val();
        $form.on('submit', function (e) {
            e.preventDefault();
            let $errors = [];
            let data = {};

            data.order = $(this).find('input[name="order_token"]').val();
            data['currency'] = $(this).find('select[name="currency"]').val();
            data['eventid'] = $(this).find('input[name="eventid"]').val();
            data['clientid'] = $(this).find('input[name="clientid"]').val();

            data['date-checkin'] = $(this).find('input[name="date-checkin"]').val();
            data['date-checkout'] = $(this).find('input[name="date-checkout"]').val();
            data['rooms'] = [];
            $form.find('.people-field__rooms .people-field__room').each(function () {
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

            //Errors handling
            if (!data['date-checkin'] || data['date-checkin'] == '') {
                $errors.push('Please select check-in date.');
            }
            if (!data['date-checkout'] || data['date-checkout'] == '') {
                $errors.push('Please select check-out date.');
            }
            if (!data['eventid'] || data['eventid'] == '') {
                $errors.push('Event is not selected.');
            }
            if (!data['currency'] || data['currency'] == '') {
                $errors.push('Please select currency.');
            }
            data['rooms'].forEach(element => {
                if (element['adults_qtt'] == '0' && element['childs_qtt'] == '0') {
                    $errors.push('Please select number of Travellers.');
                }
            });
            let $errorsText = '';

            if ($errors.length) {
                $errors.forEach(element => {
                    $errorsText += element + '<br/>';
                })
                $errorsOutput.html($errorsText);
                $errorsOutput.show();
            } else {
                $errorsOutput.html('');
                $errorsOutput.hide();

                console.log(data);
                loader.fadeIn();

                //Ajax request for creating booking post
                $.ajax({
                    url: customjs_ajax_object.ajax_url,
                    type: 'post',
                    data: {
                        action: 'ajax_create_booking',
                        data: JSON.stringify(data),
                    },
                    success: function (response) {
                        if (response !== '') {
                            //loader.fadeOut();
                            window.location.href = redirect_url;
                        }
                    },
                    error: function (error) {
                        $errorsOutput.html('An error has occured proccessing your request. Plese try again. ');
                        $errorsOutput.show();
                        loader.fadeOut();
                    }
                });
            }
        });
    });

    $('#order-hotel-search').each(function () {
        let $form = $(this);
        let $errorsOutput = $form.find('.order-form__errors');
        let loader = $(this).find('.book-accommodation__form-loading');
        let redirect_url = $(this).find('input[name="redirect_url"]').val();
        $form.on('submit', function (e) {
            e.preventDefault();
            let $errors = [];
            let data = {};

            data.order = $(this).find('input[name="order_token"]').val();
            data['currency'] = $(this).find('select[name="currency"]').val();
            data['eventid'] = $(this).find('input[name="eventid"]').val();
            data['clientid'] = $(this).find('input[name="clientid"]').val();

            data['date-checkin'] = $(this).find('input[name="date-checkin"]').val();
            data['date-checkout'] = $(this).find('input[name="date-checkout"]').val();
            data['rooms'] = [];
            $form.find('.people-field__rooms .people-field__room').each(function () {
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

            //Errors handling
            if (!data['date-checkin'] || data['date-checkin'] == '') {
                $errors.push('Please select check-in date.');
            }
            if (!data['date-checkout'] || data['date-checkout'] == '') {
                $errors.push('Please select check-out date.');
            }
            if (!data['eventid'] || data['eventid'] == '') {
                $errors.push('Event is not selected.');
            }
            if (!data['currency'] || data['currency'] == '') {
                $errors.push('Please select currency.');
            }
            data['rooms'].forEach(element => {
                if (element['adults_qtt'] == '0' && element['childs_qtt'] == '0') {
                    $errors.push('Please select number of Travellers.');
                }
            });
            let $errorsText = '';

            if ($errors.length) {
                $errors.forEach(element => {
                    $errorsText += element + '<br/>';
                })
                $errorsOutput.html($errorsText);
                $errorsOutput.show();
            } else {
                $errorsOutput.html('');
                $errorsOutput.hide();

                //console.log(data);
                loader.fadeIn();

                //Ajax request for creating booking post
                $.ajax({
                    url: customjs_ajax_object.ajax_url,
                    type: 'post',
                    data: {
                        action: 'ajax_create_booking',
                        data: JSON.stringify(data),
                    },
                    success: function (response) {
                        if (response !== '') {
                            //loader.fadeOut();
                            window.location.href = redirect_url;
                        }
                    },
                    error: function (error) {
                        //$errorsOutput.html('An error has occured proccessing your request. Plese try again. ');
                        $errorsOutput.show();
                    }
                });
            }
        });
    });

    $('#change-accom-dates').each(function () {
        let $form = $(this);
        let form = $(this);
        let $errorsOutput = $form.find('.order-form__errors');
        let orderNumber = $form.data('order');
        let loader = $(this).find('.book-accommodation__form-loading');
        let redirect_url = $(this).attr('action');
        form.on('submit', function (e) {
            e.preventDefault()
            const data = {}
            data['date-checkin'] = form.find('input[name="date-checkin"]').val()
            data['date-checkout'] = form.find('input[name="date-checkout"]').val()
            data.rooms = []
            form.find('.people-field__rooms .people-field__room').each(function () {
                const room = {}
                room.adults_qtt = $(this).find('input[name="adults_qtt"]').val()
                room.childs_qtt = $(this).find('input[name="childs_qtt"]').val()
                room.infants_qtt = $(this).find('input[name="infants_qtt"]').val()
                room.ages = []
                $(this).find('select[name="ages[]"]').each(function () {
                    room.ages.push($(this).val())
                })
                room.infant_ages = [];
                $(this).find('select[name="infant_ages[]"]').each(function () {
                    room.infant_ages.push($(this).val());
                });
                data.rooms.push(room)
            })

            data.order = orderNumber
            loader.fadeIn()
            $.ajax({
                url: customjs_ajax_object.ajax_url,
                type: 'post',
                data: {
                    action: 'ajax_update_accom',
                    data: JSON.stringify(data)
                },
                success: function (response) {
                    window.location.href = redirect_url;
                },
                error: function (error) {
                    window.location.href = redirect_url;
                }
            })
        })

    });

    $('#single-hotel-search').each(function () {
        let $form = $(this);
        let form = $(this);
        let $errorsOutput = $form.find('.order-form__errors');
        let orderNumber = $form.data('order');
        let loader = $(this).find('.book-accommodation__form-loading');
        let redirect_url = $(this).attr('action');
        form.on('submit', function (e) {
            e.preventDefault()
            const data = {}
            data['date-checkin'] = form.find('input[name="date-checkin"]').val()
            data['date-checkout'] = form.find('input[name="date-checkout"]').val()
            data.rooms = []
            form.find('.people-field__rooms .people-field__room').each(function () {
                const room = {}
                room.adults_qtt = $(this).find('input[name="adults_qtt"]').val()
                room.childs_qtt = $(this).find('input[name="childs_qtt"]').val()
                room.infants_qtt = $(this).find('input[name="infants_qtt"]').val()
                room.ages = []
                $(this).find('select[name="ages[]"]').each(function () {
                    room.ages.push($(this).val())
                })
                room.infant_ages = [];
                $(this).find('select[name="infant_ages[]"]').each(function () {
                    room.infant_ages.push($(this).val());
                });
                data.rooms.push(room)
            })

            data.order = orderNumber
            loader.fadeIn()
            
        })

    });
}


function extrasCard() {

    $('.extras-card').each(function () {

        let btn = $(this).find('.extras-card__dropdown-btn');
        let content = $(this).find('.extras-card__dropdown');

        btn.click(function () {
            btn.toggleClass('active');
            content.slideToggle();
        });

    });

}

function reserveAccomForm() {
    $('#reserve-accom-form').each(function () {
        let form = $(this);
        let popup = $(this).find('#popup-accom-reserve');
        let roomsList = popup.find('.popup-accom-reserve__list');
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

        form.find('.js-remove-room').click(function () {
            let roomIndex = $(this).data('index');
            let loader = form.find('.book-accommodation__form-loading');
            let data = {
                index: roomIndex,
                order: orderNumber,
            };

            loader.fadeIn();
            $.ajax({
                url: customjs_ajax_object.ajax_url,
                type: 'post',
                data: {
                    action: 'ajax_remove_accom',
                    data: JSON.stringify(data),
                },
                success: function (response) {
                    form.find('.book-accommodation__form-item[data-index="' + roomIndex + '"]').slideUp(function () {
                        form.find('.book-accommodation__form-item[data-index="' + roomIndex + '"]').remove();
                        let qtt = form.find('.book-accommodation__form-item').length;
                        form.find('.book-accommodation__form-item').each(function (index) {
                            $(this).data('index', index);
                            $(this).find('.room-counter').text('Room ' + (index + 1) + ' of ' + qtt);
                        });
                        loader.fadeOut();
                        if(form.find('.book-accommodation__form-item').length == 0){
                            form.find('.book-accommodation__form-norooms').show();
                            form.find('button[type="submit"]').hide();
                        }
                    });
                }
            });
        });

        form.on('submit', function (e) {
            e.preventDefault();
            let data = {};
            data.order = form.data('order');
            data.rooms = [];
            let rooms = form.find('.book-accommodation__form-item');

            let loader = form.find('.book-accommodation__form-loading');

            let $errorsOutput = form.find('.order-form__errors');
            let $errors = [];
            let $errorsText = '';
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

            if (!$errors.length) {
                rooms.each(function () {
                    let room = {};
                    room.id = $(this).find('.room_id').val();
                    room.unique_index = $(this).find('.room_index').val();
                    room.hotel = $(this).find('.hotel_id').val();
                    room.hotel_name = $(this).find('.hotel_name').val();
                    room.name = $(this).find('.room_name').val();
                    room.price = $(this).find('.room_price').val();
                    room.locator = $(this).find('.room_locator').html();
                    room.passengers = [];
                    $(this).find('.js-traveller').each(function () {
                        let obj = {};
                        obj.name = $(this).find('.name').val();
                        obj.surname = $(this).find('.surname').val();
                        obj.title = $(this).find('.title').val();
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


                            room.passengers.push(obj);
                        } else {
                            $errors.push('Please enter all travellers.');
                            return;
                        }
                    });
                    data.rooms.push(room);
                });
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
                        action: 'ajax_reserve_accom',
                        data: JSON.stringify(data),
                    },
                    success: function (response) {
                        console.log(response);
                        if (response !== '') {
                            console.log(response);
                            roomsList.html('');
                            data.rooms.forEach(element => {
                                let peopleQtt = 0;
                                element.passengers.forEach(passenger => {
                                    if (passenger.name && passenger.name != '') {
                                        peopleQtt++;
                                    }
                                });
                                roomsList.append(
                                    '<li class="popup-accom-reserve__item pb-1"><div class="popup-accom-reserve__item-inner p-2 d-flex flex-column"><span class="popup-accom-reserve__item-title">' + element.name + '</span><span class="popup-accom-reserve__item-subtitle">(for ' + peopleQtt + ' persons)</span></div></li>'
                                );
                            });
                            loader.fadeOut();
                            popup.fadeIn();
                            $errorsOutput.hide();
                        } else {
                            loader.fadeOut();
                            $errorsOutput.html('Error.');
                            $errorsOutput.show();
                        }

                    },
                    error: function (error) {
                        console.log(error);
                        loader.fadeOut();
                        $errorsOutput.html('Error.');
                        $errorsOutput.show();
                    }
                });
            }
        });
    });


    $('#api-extra-form').each(function () {
        let form = $(this);
        let $orderNum = form.find('#order-number').val();
        let loader = form.find('.book-accommodation__form-loading');
        let popupAdded = form.find('#popup-extra-added');
        let popupRemoved = form.find('#popup-extra-removed');
        let popupInsurance = form.find('#popup-extra-insurance');
        let popupInsuranceEmpty = form.find('#popup-extra-insurance-empty');
        let bypassInsurance = false;


        form.find('.extras-card').each(function () {
            let $extra = $(this);
            let $title = $(this).find('.js-extra-title').val();
            let $locator = $(this).find('.extra-locator').html();
            let $extraID = $(this).find('.extra-id').val();
            let $qtt = $(this).find('.js-extra-qtt');
            let $personsSelected = $(this).find('.js-person-selected');
            let $personsSelectedVal = $(this).find('.js-person-selected-val');
            let $insurance = $(this).find('.insurance-checkbox');
            let $travellersList = $(this).find('.extras-card__travellers');
            let $selectAll = $(this).find('.js-select-all');
            let $clearAll = $(this).find('.js-clear-all');
            let $saveButton = $(this).find('.js-save-extras');

            
            $selectAll.on('click', function () {
                $extra.find('.extras-card__travellers-item').each(function () {
                    $(this).find('.person').prop("checked", true);
                });
            });
            $clearAll.on('click', function () {
                $extra.find('.extras-card__travellers-item').each(function () {
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

            form.find('.extras-card').each(function () {
                let $extra = $(this);
                let $title = $(this).find('.js-extra-title').val();
                let $locator = $(this).find('.extra-locator').html();
                let $extraID = $(this).find('.extra-id').val();
                let $insurance = $(this).find('.insurance-checkbox');
                let data = {};
                data.id = $extraID;
                data.order = $orderNum;
                data.locator = $locator;
                data.name = $title;
                data.price = 0;
                data.travellers = [];
                $extra.find('.extras-card__travellers-item').each(function () {
                    let traveller = {};
                    traveller.ref_number = $(this).find('.person').val();
                    traveller.type = $(this).find('.type').val();
                    traveller.price = $(this).find('.price').val();
                    traveller.selected = $(this).find('.person').prop("checked") ? 1 : 0;
                    if (traveller.selected == 1) {
                        data.price += parseFloat(traveller.price);
                        data.travellers.push(traveller);
                    }
                });
                if(data.travellers.length){
                    extrasDataArray.data.push(data);
                }

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
                //console.log(extrasDataArray);
                popupInsuranceEmpty.fadeOut();
                popupInsurance.fadeOut();
                $.ajax({
                    url: customjs_ajax_object.ajax_url,
                    type: 'post',
                    data: {
                        action: 'ajax_reserve_extras',
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

                    }
                });
                
            }

            if (insuranceExists && insuranceEmpty && !bypassInsurance) {
                bypassInsurance = true;
                popupInsuranceEmpty.fadeIn();
            }
            
        });
    });

}

function summaryButton() {
    $('.button--booking-summary').each(function () {
        const $button = $(this)
        const $sidebar = $('.booking-summary')

        $(window).on('click', function () {
            $sidebar.removeClass('active')
            $button.removeClass('hidden')
        })

        $button.on('click', function (e) {
            e.stopPropagation()

            $sidebar.toggleClass('active')
            $button.toggleClass('hidden')
        })

        $sidebar.on('click', function (e) {
            e.stopPropagation()
        })
    })
}

export { apiForm, extrasCard, bookDatesFormValidation, reserveAccomForm, summaryButton };
