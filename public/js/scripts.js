/**
 * Custom checkbox
 */
$(function () {
    var chks = $('input[type=checkbox].icon-checkbox');

    chks.each(function () {
        $(this).wrap('<div class="icon-checkbox"></div>');
        $(this).before('<span class="glyphicon"></span>');
        td = $(this).parent().parent().parent();

        if ($(this).is(':checked')) {
            $(this).parent().addClass('checked');
            $(this).parent().children('span').addClass('glyphicon-ok');
            if (td.attr('data-select')) {
                td.parent().addClass('warning');
            }
        }

        if ($(this).is(':disabled')) {
            $(this).parent().addClass('disabled');
        }
    });

    chks.change(function () {
        sel = false;
        td = $(this).parent().parent().parent();

        if ($(this).is(':checked')) {
            $(this).parent().addClass('checked');
            $(this).parent().children('span').addClass('glyphicon-ok');
            if (td.attr('data-select')) {
                td.parent().addClass('warning');
            }
        } else {
            $(this).parent().removeClass('checked');
            $(this).parent().children("span").removeClass('glyphicon-ok');
            if (td.attr('data-select')) {
                td.parent().removeClass('warning');
            }
        }

        chks.each(function () {
            if ($(this).is(':checked')) {
                sel = true;
            }
        });

        if (td.attr('data-button')) {
            if (sel) {
                $(td.attr('data-button')).show();
            } else {
                $(td.attr('data-button')).hide();
            }
        }
    });
});

/**
 * Calendar table
 */
$(function () {
    var cths = $('.table-calendar th.clickable-column');
    var tds = $('.table-calendar td');

    cths.each(function () {
        $(this).click(function () {
            cth = $(this);
            tds.each(function () {
                if ($(this).index() === cth.index()) {
                    $(this).find('input').click();
                }
            });
        });
    });

    var rths = $('.table-calendar th.clickable-row');
    rths.each(function () {
        $(this).click(function () {
            $(this).parent().find('input').each(function () {
                $(this).click();
            });
        });
    });

    $('.table-calendar th.clickable-table').click(function () {
        tds.each(function () {
            $(this).find('input').click();
        });
    });

    var chks = $('input[type=checkbox].date-checkbox');

    chks.each(function () {
        $(this).wrap('<div class="date-checkbox">' + $(this).val() + '</div>');

        if ($(this).is(':checked')) {
            $(this).parent().addClass('checked');
        }

        if ($(this).is(':disabled')) {
            $(this).parent().addClass('disabled');
        }
    });

    chks.change(function () {
        sel = false;

        if ($(this).is(':checked')) {
            $(this).parent().addClass('checked');
        } else {
            $(this).parent().removeClass('checked');
        }

        chks.each(function () {
            if ($(this).is(':checked')) {
                sel = true;
            }
        });

    });
});

/**
 * Add link for table row
 */
$(function () {
    var trs = $('tr[data-lnk]');

    trs.each(function () {
        var lnk = $(this).attr('data-lnk');
        var tds = $(this).children('td');

        tds.each(function () {
            if (!$(this).attr('data-select')) {
                $(this).click(function () {
                    location.href = lnk;
                });
            }
        });
    });
});


/**
 * Clock
 */
$(function () {
    initClock();

    function initClock() {
        var canvas = document.getElementById("clock");
        var ctx = canvas.getContext("2d");
        var radius = canvas.height / 2;
        ctx.translate(radius, radius);
        radius = radius * 0.90;
        drawClock(ctx, radius);
        setInterval(function () {
            drawClock(ctx, radius);
        }, 1000);
    }

    function drawClock(ctx, radius) {
        ctx.clearRect(-radius, -radius, 2 * radius, 2 * radius);
        drawTime(ctx, radius);
    }

    function drawTime(ctx, radius) {
        var now = new Date();
        var hour = now.getHours();
        var minute = now.getMinutes();
        var second = now.getSeconds();
        //hour
        hour = hour % 12;
        hour = (hour * Math.PI / 6) +
                (minute * Math.PI / (6 * 60)) +
                (second * Math.PI / (360 * 60));
        drawHand(ctx, hour, radius * 0.5, radius * 0.07, '#6d3702', 0);
        //minute
        minute = (minute * Math.PI / 30) + (second * Math.PI / (30 * 60));
        drawHand(ctx, minute, radius * 0.65, radius * 0.07, '#6d3702', 0);
        // second
        second = (second * Math.PI / 30);
        drawHand(ctx, second, radius * 0.8, radius * 0.03, '#ec3f00', radius * 0.06);
        ctx.arc(0, 0, radius * 0.08, 0, 2 * Math.PI);
        ctx.fill();
    }

    function drawHand(ctx, pos, length, width, color, arc) {
        ctx.beginPath();
        ctx.strokeStyle = color;
        ctx.lineWidth = width;
        ctx.lineCap = "round";
        ctx.moveTo(0, 0);
        ctx.rotate(pos);
        ctx.lineTo(0, -length);
        ctx.stroke();
        ctx.beginPath();
        if (arc !== 0) {
            ctx.fillStyle = color;
            ctx.arc(0, -(length * 0.7), arc, 0, 2 * Math.PI);
        }
        ctx.fill();
        ctx.rotate(-pos);
    }
});
Number.prototype.formatFloat = function (d, p = 2) {
    return this.toFixed(p).replace('.', d);
};
function formatFloatInput(i, d, p = 2) {
    var f = parseFloat(i.value.replace(d, '.'));
    i.value = f.formatFloat(d, p);
}
