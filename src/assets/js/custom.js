/**
 * Created by Thomas Bourrely on 28/03/18.
 */
(function($) {
    /*
        errors
    */
    // -> click on cross
    $('.message__close__cross').click(function(e) {
        e.preventDefault();
        e.stopPropagation();

        $error_parent = this.parentNode.parentNode;
        $($error_parent).hide('slow');
    });

    // -> click on message
    $('.message').click(function (e) {
        e.preventDefault();
        e.stopPropagation();

        $(this).hide('slow');
    });

    // -> show errors
    $('.message.hidden').show('slow', function() {
        $(this).removeClass('hidden');
    });




    // file uploader (add animal)
    $('.add-animal-form__photo').on('change', function (e) {
        var label	 = this.nextElementSibling;

        var fileName = '';
        if( this.files && this.files.length === 1 ) {
            fileName = e.target.value.split( '\\' ).pop();
        }

        var extension = getExtension(fileName);

        if (isImage(extension)) {
            label.innerHTML = fileName;
            $(this).addClass('uploaded');
        } else {
            alert("Format de fichiers autorisés : jpg, jpeg, png");
        }
    });

    function getExtension (filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isImage(extension) {
        switch (extension.toLowerCase()) {
            case 'jpg':
            case 'png':
            case 'jpeg':
                return true;
        }

        return false;
    }

})(jQuery);