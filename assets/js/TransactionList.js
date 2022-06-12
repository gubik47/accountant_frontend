const QueryString = require("query-string");
const ItemList = require("./ItemList");
const Utils = require("./Utils");

const TransactionList = function(options) {
    ItemList.call(this, options);
};

TransactionList.prototype = Object.create(ItemList.prototype);

TransactionList.prototype.loadItems = function (query, append) {
    if (this.container.data("account")) {
        query = QueryString.parse(query, {arrayFormat: "bracket"});
        query.account = this.container.data("account");
        query = QueryString.stringify(query, {encode: false, arrayFormat: "bracket"});
    }

    const url = Utils.createUrl(this.container.data("load-url"), query);

    this.itemLoader.load(url, append);
};

$(document).ready(function () {
    const mainContainer = $(".js-transactions-list");
    if (mainContainer.length === 0) {
        return;
    }

    new TransactionList({
        mainContainer: mainContainer,
        listContainer: mainContainer.find(".js-transactions-container"),
    });
});