/**
 * Created by Thomas Bourrely on 28/03/18.
 */
(function($) {
    /*
        errors
    */
    // -> click on cross
    $('.errors__message__close__cross').click(function(e) {
        e.preventDefault();
        e.stopPropagation();

        $error_parent = this.parentNode.parentNode;
        $($error_parent).hide('slow');
    });

    // -> show errors
    $('.errors__message.hidden').show('slow', function() {
        $(this).removeClass('hidden');
    });

})(jQuery);