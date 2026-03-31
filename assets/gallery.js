jQuery(document).ready(function ($) {

    let frame;

    $('.td-image-upload').on('click', function (e) {
        e.preventDefault();

        const button  = $(this);
        const input   = button.prev('input');
        const preview = button.next('.td-image-preview');

        frame = wp.media({
            title: 'Select Image',
            button: { text: 'Use image' },
            multiple: false
        });

        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();

            input.val(attachment.id);
            preview.html(`<img src="${attachment.sizes.thumbnail.url}" style="width:100px;border-radius:4px;">`);
        });

        frame.open();
    });

});
