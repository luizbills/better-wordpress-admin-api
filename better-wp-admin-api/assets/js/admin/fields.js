window.jQuery( function ( $ ) {

    // ace editor
    var $code_editors = $( '.code-editor' );

    if ( $code_editors.length > 0 ) {

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
    }

    // color picker
    var $colorpickers = $('.color-picker-field .color');

    if ( $colorpickers.length > 0 ) {
        $colorpickers.hide().wpColorPicker().show();
    }

    // wp media manager
    // TODO
} );
