/**
 * Jednoduchy loader, ktery se zobrazi pres cely viewport pro signalizaci probihajici
 * AJAXove operace.
 *
 * @type {{init, hide, show}}
 */
const Loader = (function () {
    let container;

    // failsafe timeout, aby uzivatel nebyl zamceny na strance loaderem
    // prekryvajicim pozadi v pripade chyby
    let timeoutHandle;
    const timeoutDuration = 15000;

    const toggle = function () {
        if (container.length !== 0) {
            container.toggleClass("is-active");
        }
    };

    const hide = function () {
        if (container.hasClass("is-active")) {
            toggle();
        }

        clearTimeout(timeoutHandle);
    };

    const show = function () {
        if (!container.hasClass("is-active")) {
            toggle();
        }

        clearTimeout(timeoutHandle);
        // po 10 vterinach loader skryj, do te doby musi vsechny pozadavky dobehnout
        timeoutHandle = setTimeout(function () {
            hide();
        }, timeoutDuration);
    };

    return {
        init: function () {
            container = $(".js-fullsize-loader");
        },
        show: show,
        hide: hide
    }
})();

$(document).ready(function () {
    Loader.init();
});

module.exports = Loader;