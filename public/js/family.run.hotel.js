/**
 * Link for element
 */
$(function () {
    $('[data-href]').each(function () {
        $(this).css('cursor', 'pointer');
        $(this).click(function () {
            location.href = $(this).attr('data-href');
        });
    });
});
/**
 * Change buttons state
 */
$(function () {
    $('[data-toggle="checkbox"]').each(function () {
        $(this).click(function () {
            $($(this).attr('data-target')).each(function () {
                $(this).click();
            });
        });
    });
});
/**
 * Change button visibility
 */
$(function () {
    var button = $('[data-hide]');
    var checkboxes = $(button.attr('data-hide'));
    var toggle = function () {
        checked = false;
        checkboxes.each(function () {
            if ($(this).is(':checked')) {
                checked = true;
                return true;
            }
        });
        if (checked) {
            button.show();
        } else {
            button.hide();
        }
    };
    toggle();
    checkboxes.click(function () {
        toggle();
    });
});
/**
 * IconFont picker
 */
$(function () {
    var picker = $('.if-picker[role=select]');
    var input = picker.find('input');
    var options = picker.find('[role=option]');
    options.each(function () {
        if ($(this).text() === input.val()) {
            $(this).addClass('selected');
        }
    });
    options.click(function () {
        options.each(function () {
            $(this).removeClass('selected');
        });
        $(this).addClass('selected');
        input.val($(this).text());
    });
});
