const QueryString = require("query-string");
const ListItemLoader = require("./ListItemLoader");
const Utils = require("./Utils");

const ItemList = function (options) {
    this.container = options.mainContainer;

    if (this.container.length === 0) {
        // na strance se nenachazi pozadovany seznam polozek
        return;
    }

    this.itemLoader = new ListItemLoader({
        "mainContainer": this.container,
        "listContainer": options.listContainer
    });

    const self = this;
    this.container.on("click", ".js-load-more", function () {
        self.loadMore($(this).data("page"));
    });

    // zmena stranky
    this.container.on("click", ".js-change-page", function (e) {
        e.preventDefault();
        const currentPage = parseInt($(".js-pagination").data("current"));
        if (currentPage === parseInt($(this).data("page"))) {
            // zabran zbytecnemu nacteni jiz nactene stranky
            return;
        }

        self.setPage($(this).data("page"));
    });

    // razeni - zobrazeni
    this.container.on("click", ".js-order-toggle", function () {
        $(".js-item-order-container").slideToggle();
    });

    // razeni
    this.container.on("click", ".js-order", function (e) {
        e.preventDefault();
        if ($(this).hasClass("is-active")) {
            // zabran zbytecnemu nacteni stejneho razeni
            return;
        }

        self.setOrder($(this).data("order"));
    });

    // filtrace - zobrazeni
    this.container.on("click", ".js-filter-toggle", function () {
        $(".js-filter-container").slideToggle();
    });

    // filtrace
    this.container.on("click", ".js-filter", function (e) {
        e.preventDefault();
        if ($(this).hasClass("is-active")) {
            // zabran zbytecnemu nacteni stejne fitrace
            return;
        }

        self.setFilter($(this).data("filter"));
    });

    this.initBrowserHistoryState();

    // stisknuto tlacitko zpet v prohlizeci
    window.onpopstate = function (e) {
        if (e.state != null && e.state.hasOwnProperty("query")) {
            // nacti polozky v seznamu
            self.loadItems(e.state["query"]);
            // nastav aktivni filtr a razeni
            self.setActiveOrder(e.state["order"]);
            self.setActiveFilter(e.state["filter"]);
        }
    };
};

/**
 * Vlozi novy state vypisu polozek do browser history.
 *
 */
ItemList.prototype.pushNewBrowserHistoryState = function (query) {
    const
        href = Utils.createUrl(window.location.pathname, query),
        state = {
            query: query,
            order: $(".js-order.is-active").data("order"),
            filter: $(".js-filter.is-active").data("filter")
        };

    history.pushState(state, "", href);
};

/**
 * Vrati aktualizovany query string s novymi hodnotami strankovani, filtrace nebo razeni.
 */
ItemList.prototype.getUpdatedQuery = function (options) {
    let parsedQuery = QueryString.parse(location.search);
    if (Object.prototype.hasOwnProperty.call(options, "page")) {
        parsedQuery.page = options.page;
    }
    if (Object.prototype.hasOwnProperty.call(options, "order")) {
        parsedQuery.order = options.order;
        // strankovani se zrusi
        parsedQuery.page = undefined;
    }
    if (Object.prototype.hasOwnProperty.call(options, "filter")) {
        parsedQuery.filter = options.filter;
        // strankovani se zrusi
        parsedQuery.page = undefined;
    }
    return QueryString.stringify(parsedQuery);
};

/**
 * Nacte polozky seznamu AJAXem z API.
 *
 * @param query
 * @param append Pokud je true, seznam polozek se pripoji za ten stavajici.
 */
ItemList.prototype.loadItems = function (query, append) {
    const url = Utils.createUrl(this.container.data("load-url"), query);

    this.itemLoader.load(url, append);
};

/**
 * Nastavi nove zvoleny filtr.
 * @param filter
 */
ItemList.prototype.setActiveFilter = function (filter) {
    const newFilter = $(".js-filter[data-filter=" + filter + "]");
    if (newFilter.length !== 0) {
        $(".js-filter").removeClass("is-active");
        newFilter.addClass("is-active");
    }
};

/**
 * Nastavi nove zvolene razeny.
 * @param order
 */
ItemList.prototype.setActiveOrder = function (order) {
    const newOrder = $(".js-order[data-order=" + order + "]");
    if (newOrder.length !== 0) {
        $(".js-order").removeClass("is-active");
        newOrder.addClass("is-active");
    }
};

/**
 * Nacte dalsi polozky (tlacitko "Nacist dalsi")
 * @param page
 */
ItemList.prototype.loadMore = function (page) {
    const query = this.getUpdatedQuery({"page": page});
    this.loadItems(query, true);
    this.pushNewBrowserHistoryState(query);
};

/**
 * Zobrazi konretni stranku (strankovac)
 * @param page
 */
ItemList.prototype.setPage = function (page) {
    const query = this.getUpdatedQuery({"page": page});
    this.loadItems(query);
    this.pushNewBrowserHistoryState(query);
};

/**
 * Vlozi pocatecni state vypisu polozek do browser history.
 */
ItemList.prototype.initBrowserHistoryState = function () {
    const
        href = window.location.pathname + window.location.search,
        state = {
            query: window.location.search.substr(1), // odstrani "?" na zacatku
            order: $(".js-order.is-active").data("order"),
            filter: $(".js-filter.is-active").data("filter")
        };

    history.replaceState(state, "", href);
};

/**
 * Nacte serazene polozky dle vybraneho razeni.
 * @param order
 */
ItemList.prototype.setOrder = function (order) {
    const options = {"order": order};
    const query = this.getUpdatedQuery(options);

    this.loadItems(query);
    this.setActiveOrder(order);
    this.pushNewBrowserHistoryState(query, options);
};

/**
 * Nacte filtrovane polozky dle vybraneho filtru.
 * @param filter
 */
ItemList.prototype.setFilter = function (filter) {
    const options = {"filter": filter};
    const query = this.getUpdatedQuery(options);

    this.loadItems(query);
    this.setActiveFilter(filter);
    this.pushNewBrowserHistoryState(query, options);
};

module.exports = ItemList;