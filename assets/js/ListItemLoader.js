const Utils = require("./Utils");

const ListItemLoader = function (options) {
    this.mainContainer = options.mainContainer;
    this.listContainer = options.listContainer;

    this.load = function (url, append, callback) {
        const self = this;

        $.ajax({
            url: url,
            method: "GET",
            success: function (response) {
                if (response.status === "success") {
                    self.insertHtml(response, append, callback);
                }

                if (typeof callback === "function") {
                    callback(response);
                }
            }
        });
    };

    this.insertHtml = function (response, append, callback) {
        if (typeof append === "undefined") {
            append = false;
        }

        const items = $(response.html.items);
        if (append === true) {
            items.hide().appendTo(this.listContainer).fadeIn(200);
        } else {
            this.listContainer.hide().html(items).fadeIn(200);
        }

        this.mainContainer.find(".js-pagination-wrapper").html(response.html.pagination);

        if (items.length && typeof callback !== "function") {
            Utils.scrollBody(items.offset().top - 100, 100);
        }
    };
};

module.exports = ListItemLoader;