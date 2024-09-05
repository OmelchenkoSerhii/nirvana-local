(function() {
	tinymce.PluginManager.add('true_mce_shortcodes', function( editor, url ) { 
		editor.addButton( 'true_mce_shortcodes', {
			icon: 'shortcodes', 
			type: 'menubutton',
			title: 'Insert shortcode',
			menu: [
				{
                        text: 'Button',
                        onclick: function() {
                            editor.windowManager.open( {
                                title: 'Button',
                                body: [
                                    {
                                        type: 'textbox',
                                        name: 'href',
                                        label: 'URL',
                                        value: ''
                                    },
                                    {
                                        type: 'textbox', 
                                        name: 'label',
                                        label: 'Label',
                                        value: ''
                                    },
                                    {
                                        type: 'checkbox', 
                                        name: 'target',
                                        label: 'Open in new window',
                                        value: ''
                                    }
                                ],
                                onsubmit: function( e ) {
                                    editor.insertContent( '[button href="' + e.data.href + '" label="' + e.data.label + '" target="' + (e.data.target?'_blank':'') + '"]');
                                }
                            });
                        }
                }
			]
		});
	});
})();

jQuery(document).ready(function($){
    $(document).on('click', '.mce-my_upload_button', upload_image_tinymce);

    function upload_image_tinymce(e) {
        e.preventDefault();
        var $input_field = $('.mce-my_input_image');
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Add Image',
            button: {
                text: 'Add Image'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $input_field.val(attachment.url);
        });
        custom_uploader.open();
    }
});