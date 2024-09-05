import $ from 'jquery';

function downloadDoc() {
    $('.js-download-doc').each(function () {
        let loader = $('.book-accommodation__form-loading');
        let block = $(this);
        $(this).on('click',function(){
            let data = {};
            data.type = $(this).data('type');
            data.event = $(this).data('event');
            data.booking = $(this).data('booking');
            loader.fadeIn();
            $.ajax({
                url: customjs_ajax_object.ajax_url,
                type: 'post',
                data: {
                    action: 'ajax_download_doc',
                    data: JSON.stringify(data),
                },
                success: function (response) {
                    if (response !== '' && response.data) {
                        loader.fadeOut();
                        //console.log(response);
                        const base64Data = response.data;
                        const decodedData = atob(base64Data);
                        const dataArray = new Uint8Array(decodedData.length);

                        for (let i = 0; i < decodedData.length; i++) {
                            dataArray[i] = decodedData.charCodeAt(i);
                        }
                        const pdfBlob = new Blob([dataArray], { type: 'application/pdf' });

                        // Create a download link for the PDF file
                        const url = window.URL.createObjectURL(pdfBlob);
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'invoice.pdf');

                        // Add the link to the document body and trigger the download
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });
}

export { downloadDoc };
