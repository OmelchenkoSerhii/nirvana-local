import $  from 'jquery';

function searchBar(){
    

    var elementsAjaxHide = $('.js-ajax-hide');
    var elementsAjaxShow = $('.js-ajax-show');
    var container = $('.js-archive-container');
    
    if ($('.search-bar .filters').length) {

        $('.filters__item').each(function(){
            let item = $(this);
            let header = $(this).find('.filters__item__header');
            let dropdown = $(this).find('.filters__item__dropdown');
            let selected = $(this).find('.filters__item__header__txt--selected');
            header.click(function(){
                let active = item.hasClass('filter-active');
                if(active){
                    item.removeClass('filter-active');
                    dropdown.slideUp();
                } else{
                    $('.filters__item.filter-active').find('.filters__item__dropdown').slideUp();
                    $('.filters__item.filter-active').removeClass('filter-active');
                    item.addClass('filter-active');
                    dropdown.slideDown(); 
                }
            });

            item.find('.js-filter-val').on('click', function(){
                let categoryList = item.find('.options');
                let catsNum = 0;

                categoryList.find('input[type="checkbox"]:checked,input[type="radio"]:checked').each(function () {
                    catsNum++;
                });

                selected.text('('+ catsNum +')');
            });
        });

        $(document).click(function() {
            $('.filters__item.filter-active .filters__item__dropdown').slideUp();
            $('.filters__item.filter-active').removeClass('filter-active');
        });
          
        $('.filters__item').click(function(event){
            event.stopPropagation();
        });

        //search
        let form = $('#searchForm');
        let redirectLink = $('#searchForm').attr('action');

        form.on('submit',function(e){
            e.preventDefault();


            let taxonomiesObject = {};
            $('.js-filter').each(function () {
                let taxonomy = $(this).data('taxonomy');

                let categoryList = $(this).find('.options');
                let cats = '';
                let counter = 0;
                categoryList.find('input[type="checkbox"]:checked').each(function () {
                    if(counter!=0){
                        cats += ',';
                    }
                    var value = $(this).val();
                    cats += value;
                    counter ++;
                });

                if (cats != '') {
                    taxonomiesObject[taxonomy] = '';
                    taxonomiesObject[taxonomy] = cats;
                }
            });

            redirectLink += '?';
            for (const [key, value] of Object.entries(taxonomiesObject)) {
                redirectLink+= key + '=' + value + '&';
            }

            $(location).prop('href', redirectLink)
        });
    }


}

export { searchBar };