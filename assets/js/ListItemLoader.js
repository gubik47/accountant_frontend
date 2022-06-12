const Loader = require("./Loader");
const Utils = require("./Utils");

/**
 * Nacita AJAXem polozky pro vypis a vklada HTML do DOMu.
 *
 * @param options
 * @constructor
 */
const ListItemLoader = function (options) {
    this.mainContainer = options.mainContainer;
    this.listContainer = options.listContainer;

    /**
     * Nacte polozky ze zadane URL
     *
     * @param url
     * @param append Pokud je true, HTML s polozkami se pouze pripise za ostatni, jinak dojde k nahrazeni.
     * @param callback
     */
    this.load = function (url, append, callback) {
        const self = this;

        $.ajax({
            url: url,
            method: "GET",
            beforeSend: function() {
                Loader.show();
            },
            success: function (response) {
                if (response.status === "success") {
                    self.insertHtml(response, append, callback);
                }

                if (typeof callback === "function") {
                    callback(response);
                }
            },
            complete: function () {
                Loader.hide();
            }
        });
    };

    /**
     * Vlozi HTML s produkty do DOMu.
     *
     * @param response
     * @param append  Pokud je true, pripoji seznam s polozkami za jiz zobrazeny.
     * @param callback
     */
    this.insertHtml = function (response, append, callback) {
        if (typeof append === "undefined") {
            append = false;
        }

        // vlozeni polozek do DOMu
        const items = $(response.html.items);
        if (append === true) {
            items.hide().appendTo(this.listContainer).fadeIn(200);
        } else {
            this.listContainer.hide().html(items).fadeIn(200);
        }

        // nahrazeni strankovace novym
        this.mainContainer.find(".js-pagination-wrapper").html(response.html.pagination);

        if (items.length && typeof callback !== "function") {
            // scroll na seznam pri nacteni dalsi stranky nebo prechodu zpet
            // pouze, pokud neexistuje callback, tam je predpoklad, ze si scroll resi sam callback
            Utils.scrollBody(items.offset().top - 350, 500);
        }
    };
};

module.exports = ListItemLoader;