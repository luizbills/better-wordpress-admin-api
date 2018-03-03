window.jQuery( function ( $ ) {
    var wp = window.wp;

    // ace editor
    var $code_editors = $( '.code-editor' );

    $code_editors.each(function () {
        var element = this;
        var $self = $( element );

        var data_prefix ='code-editor-';
        var code_holder_id = $self.data( data_prefix + 'for' )
        var mode = $self.data( data_prefix + 'lang' );
        var theme = $self.data( data_prefix + 'theme' );
        var font_size = $self.data( data_prefix + 'font-size' ) + 'px';
        var tab_size = $self.data( data_prefix + 'tab-size' );
        var soft_tab = !!$self.data( data_prefix + 'soft-tab' );
        var read_only = !!$self.data( data_prefix + 'read-only' );
        var show_print_margin = !!$self.data( data_prefix + 'show-print-margin' );

        var editor = null;
        var code_holder = document.getElementById(code_holder_id);

        code_holder.style.display = 'none';

        editor = window.ace.edit(element);
        editor.setReadOnly(read_only);
        editor.session.setMode( "ace/mode/" + mode );
        editor.setTheme( "ace/theme/" + theme );
        editor.getSession().setTabSize(tab_size);
        editor.getSession().setUseSoftTabs(soft_tab);
        editor.setShowPrintMargin(show_print_margin);
        editor.getSession().setUseWrapMode(true);

        editor.$blockScrolling = Infinity;

        editor.getSession().on('change', function ( evt ) {
            code_holder.value = editor.getValue();
        });

        editor.setValue( code_holder.value );
        editor.gotoLine( 0 );

        $( document.body ).trigger( 'better-wp-admin-api-code-editor-loaded', [ editor ] );
    } );

    // color picker
    var $colorpickers = $('.field-color');

    if ( $colorpickers.length > 0 ) {
        $colorpickers.hide().wpColorPicker().show();
    }

    // wp media uploader
    var file_frame = false;
    var $image_fields = $('.image_data_field');

    $image_fields.each(function () {
        var $self = $( this );
        var $container = $self.parent();
        var btn_delete = $container.find('.image_delete_button');
        var btn_upload = $container.find('.image_upload_button');
        var image_preview = $container.find('.image_preview');

        if ( '' === $self.val() ) {
            image_preview.hide();
            btn_delete.hide();
        }

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
          file_frame.open();
          return;
        }

        btn_upload.on('click', function () {
            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: $( this ).data( 'uploader_title' ),
                button: {
                    text: $( this ).data( 'uploader_button_text' ),
                },
                multiple: false
            });

            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();
                $self.val( attachment.id );
                image_preview.show();
                btn_delete.show();
                image_preview.attr( 'src', attachment.sizes.thumbnail.url );
                file_frame = false;
            });

            // Finally, open the modal
            file_frame.open();
        });

        btn_delete.on('click', function() {
            $self.val( '' );
            image_preview.attr('src', '').hide();
            btn_delete.hide();
            return false;
        });

    });

} );
