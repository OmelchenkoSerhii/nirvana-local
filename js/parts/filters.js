import $ from 'jquery';

function filters() {
    $('.filters-feed').each(function () {

        let filtersWrapper = $(this);
        let container = $(this).find('.filters-feed__result');
        let pagination = $(this).find('.ajax-pagination');
        let clear = $(this).find('.js-clear');
        let elementsAjaxHide = $(this).find('.js-ajax-hide');
        let qtt = $(this).find('.filter-qtt');

        //function to change the label of selected filter
        function changeLabel(type, label) {
            let labelWrapper = filtersWrapper.find('*[data-filterTag="' + type + '"]');
            if (labelWrapper.length) {
                let labelWrapperDefault = labelWrapper.data('filterTagLabel');
                if (label && label != '') {
                    labelWrapper.text(label);
                } else {
                    labelWrapper.text(labelWrapperDefault);
                }
            }
        }

        //function to handle all selected filters
        function getQuery() {
            let filtersObject = {};
            let url = new URL(window.location.href);

            filtersObject.post_type = filtersWrapper.data('post');
            filtersObject.paged = filtersWrapper.data('page');
            filtersObject.posts_per_page = filtersWrapper.data('ppp');
            filtersObject.template = filtersWrapper.data('template');
            filtersObject.pretaxname = filtersWrapper.data('pretaxname');
            filtersObject.pretax = filtersWrapper.data('pretax');
            if (filtersWrapper.data('ids')) {
                let ids = filtersWrapper.data('ids');
                filtersObject.post__in = ids.split(',');
            }

            //url.searchParams.delete('page');
            //url.searchParams.set('page' , filtersObject.paged);

            filtersWrapper.find('.filter')?.each(function () {
                let filter = $(this);
                let type = $(this).data('type');

                switch (type) {
                    case 'taxonomy':
                        let taxonomyName = filter.data('taxonomy');
                        let cats = '';
                        let labels = '';
                        let catsArray = [];

                        //url.searchParams.delete(taxonomyName);

                        filter.find('.js-filter-val[type="checkbox"]:checked , .js-filter-val[type="radio"]:checked')?.each(function (index) {
                            var value = parseInt($(this).val());
                            var label = $(this).data('label');
                            if (index != 0) {
                                cats += ',';
                                labels += ',';
                            }
                            if (value != -1) {
                                cats += value;
                                catsArray.push(value);
                                labels += label;
                            }
                        });

                        if (cats != '') {
                            if (!('tax_query' in filtersObject)) {
                                filtersObject['tax_query'] = [];
                                filtersObject['tax_query']['relation'] = 'AND';
                            }
                            let termsObj = {};
                            termsObj.taxonomy = taxonomyName;
                            termsObj.field = 'term_id';
                            termsObj.terms = catsArray;

                            //add predefined taxonomy
                            if (filtersObject.pretax && filtersObject.pretaxname && filtersObject.pretaxname == taxonomyName) {
                                termsObj.terms.push(filtersObject.pretax);
                            }

                            filtersObject['tax_query'].push(termsObj);
                            //url.searchParams.set(taxonomyName , cats);
                        }

                        if (taxonomyName != '') {
                            changeLabel(taxonomyName, labels);
                        } else {
                            changeLabel(taxonomyName, '');
                        }

                        break;
                    case 'search':
                        let searchValue = filter.find('.js-filter-val').val();
                        //url.searchParams.delete('search');
                        if (searchValue != '') {
                            //filtersObject['s'] = searchValue;
                            filtersObject['search_prod_title'] = searchValue;
                            //url.searchParams.set('search', searchValue);

                            changeLabel('search', searchValue);
                        } else {
                            changeLabel('search', '');
                        }
                        break;
                    case 'month':
                        //url.searchParams.delete('month');
                        if (filter.find('.js-filter-val[type="checkbox"]:checked , .js-filter-val[type="radio"]:checked').length) {
                            let month = filter.find('.js-filter-val[type="checkbox"]:checked , .js-filter-val[type="radio"]:checked').val();
                            if (month != 'all') {
                                filtersObject['monthnumber'] = month;


                                //url.searchParams.set('month' , month);
                                let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                                changeLabel('month', months[month - 1]);
                            }
                        }
                        break;
                }
            });

            //add predefined taxonomy
            if (filtersObject.pretax && filtersObject.pretaxname) {
                if (!('tax_query' in filtersObject)) {
                    filtersObject['tax_query'] = [];
                    filtersObject['tax_query']['relation'] = 'AND';
                }
                let termsObj = {};
                termsObj.taxonomy = filtersObject.pretaxname;
                termsObj.field = 'term_id';
                termsObj.terms = [filtersObject.pretax];
                filtersObject['tax_query'].push(termsObj);
            }

            window.history.replaceState(null, null, url);
            console.log(filtersObject);
            return filtersObject;
        }

        //ajax requst based on taxonomies
        function getPosts() {
            let query = getQuery();
            console.log(query);
            elementsAjaxHide.hide();
            container.show();
            container.addClass('loading');
            console.log(query);
            $.ajax({
                url: customjs_ajax_object.ajax_url,
                type: 'post',
                data: {
                    action: 'filter_loop',
                    query: JSON.stringify(query),
                },
                success: function (response) {
                    console.log(response);
                    if (response !== '') {
                        container.html('');
                        pagination.html('');
                        container.html(response.data.loop);
                        pagination.html(response.data.pagination);
                        qtt.html(response.data.total_posts);
                        
                    } else {
                        container.html('Nothing was found.');
                    }
                    container.removeClass('loading');
                    $([document.documentElement, document.body]).animate({
                        scrollTop: container.offset().top - 200
                    }, 1000);
                }
            });

        }

        //Init filters front-end
        filtersWrapper.find('.filter')?.each(function () {

            let filter = $(this);
            let filterType = $(this).data('type');

            if (filter.hasClass('filter-dropdown-list')) {
                filter.find('.option-parent')?.each(function () {
                    let item = $(this);
                    let btn = $(this).find('.option-toggle');
                    let list = $(this).find('.options-child');
                    btn.on('click', function () {
                        list.slideToggle();
                        item.toggleClass('opened');
                    });
                    $(document).on("click", function(event){
                        var $trigger = item;
                        if($trigger !== event.target && !$trigger.has(event.target).length){
                            list.slideUp();
                        }            
                    });
                });

                filter.find('.js-filter-val').on('change', function () {
                    filtersWrapper.data('page', 1);
                    getPosts();
                });

                

            }

            if (filter.hasClass('filter-dropdown')) {
                let btn = filter.find('.filter-dropdown__list__title');
                let list = filter.find('.options');
                let checkbox = filter.find('input');
                let clear = filter.find('.filter-dropdown__list__clear');
                clear.hide();
                btn.on('click', function () {
                    list.slideToggle();
                    btn.toggleClass('opened');
                });
                
                checkbox.on('change', function () {
                    btn.text(filter.find('input:checked').data('label'));
                    if($(this).val() == '-1' || $(this).val() == 'all'){
                        clear.hide();
                    } else{
                        clear.show();
                    }
                    list.slideUp();
                    btn.removeClass('opened');
                    filtersWrapper.data('page', 1);
                    getPosts();
                });

                clear.on('click' , function(){
                    filter.find('input[value="all"]').prop( "checked", true ).trigger('change');
                    filter.find('input[value="-1"]').prop( "checked", true ).trigger('change');
                });

                $(document).on("click", function(event){
                    var $trigger = filter;
                    if($trigger !== event.target && !$trigger.has(event.target).length){
                        list.slideUp();
                    }            
                });
            }

            if (filter.hasClass('filter-search')) {
                let form = filter.find('.filter-search__form');
                let text = filter.find('.filter-search__input');
                form.on('submit', function (e) {
                    e.preventDefault();
                    filtersWrapper.data('page', 1);
                    getPosts();
                });
            }


        });

        //Pagination
        filtersWrapper.on('click', '.js-pagination-page', function (e) {
            e.preventDefault();

            var page = parseInt($(this).data('page'));
            filtersWrapper.data('page', page);
            getPosts();

        });

    });
}

export { filters };
