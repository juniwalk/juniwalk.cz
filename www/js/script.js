
/**
 * Initialize client scripts.
 */
function client_init()
{
    $(document).click(function (e) {
        $('.fb-field').removeClass('active');

        var $target = $(e.target).closest('.fb-field');
        var $handle = $(e.target).closest('.fb-toolbar.vertical');

        if (!$target.length || $handle.length) {
            return;
        }

        $target.addClass('active');
    });

    $('select[data-autosubmit], input[data-autosubmit]').on('change', function() {
        return $(this).closest('form').find('button[name="save"]').click();
    });
}

$(function () {

    client_init();
/*
    $.nette.ext('snippets').after(function() {
        client_init();
    });

    $.nette.ext('fb-spinner', {
        start: function (xhr, settings) {
            $('.fb-overlay').removeClass('invisible');
        },
        complete: function (xhr, status, settings) {
            $('.fb-overlay').addClass('invisible');
        }
    });

    $.nette.init();
*/
});
