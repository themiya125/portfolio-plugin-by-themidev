jQuery(document).ready(function ($) {

    $(document).on('click', '.tech-svg-upload', function (e) {
        e.preventDefault();

        const button = $(this);
        const frame = wp.media({
            title: 'Select SVG Icon',
            library: { type: 'image/svg+xml' },
            button: { text: 'Use SVG' },
            multiple: false
        });

        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();

            button.prev('#tech_svg_id').val(attachment.id);
            button.next('.tech-svg-preview').html(
                `<img src="${attachment.url}" style="width:40px;height:auto;" />`
            );
        });

        frame.open();
    });

});
