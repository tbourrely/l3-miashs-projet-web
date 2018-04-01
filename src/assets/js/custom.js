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

})(jQuery);