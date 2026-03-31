jQuery(document).ready(function($){
    var mediaUploader;

    $('#category-image-button').click(function(e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: { text: 'Choose Image' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#category-image-id').val(attachment.id);
            $('#category-image-wrapper').html('<img src="' + attachment.url + '" style="max-width:100px;" />');
        });

        mediaUploader.open();
    });
});