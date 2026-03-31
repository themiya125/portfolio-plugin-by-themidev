jQuery(function ($) {

    $('.td-upload-author').on('click', function (e) {
        e.preventDefault();

        const frame = wp.media({
            title: 'Select Author Photo',
            multiple: false
        });

        frame.on('select', function () {
            const img = frame.state().get('selection').first().toJSON();
            $('#author_image').val(img.id);
            $('.td-author-preview').html(
                '<img src="' + img.url + '" style="max-width:100px;">'
            );
        });

        frame.open();
    });

});
