window.jQuery( function ( $ ) {

    // ace editor
    var $code_editors = $( '.code-editor' );

    if ( $code_editors.length > 0 ) {

        $code_editors.each(function () {
            var $self = $( this );
            var data_holder = document.getElementById( $self.data( 'ace-for' ) );

            data_holder.style.display = 'none';

            var editor = window.ace.edit(this);
            var mode = $self.data( 'ace-mode' );
            var theme = $self.data( 'ace-theme' );
            var height = $self.data( 'ace-height' );

            editor.session.setMode( "ace/mode/" + mode );
            editor.setTheme( "ace/theme/" + theme );
            editor.$blockScrolling = Infinity;

            this.style.height = height + 'px';

            editor.getSession().on('change', function ( evt ) {
                data_holder.value = editor.getValue();
            });

            editor.setValue( data_holder.value );
            editor.gotoLine( 0 );

            $( document.body ).trigger( 'better-wp-admin-api-ace-editor-loaded', [ editor ] );
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
