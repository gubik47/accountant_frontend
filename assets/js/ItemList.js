const QueryString = require("query-string");
const ListItemLoader = require("./ListItemLoader");
const Utils = require("./Utils");

const ItemList = function (options) {
    this.container = options.mainContainer;

    if (this.container.length === 0) {
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

    this.container.on("click", ".js-change-page", function (e) {
        e.preventDefault();
        const currentPage = parseInt($(".js-pagination").data("current"));
        if (currentPage === parseInt($(this).data("page"))) {
            return;
        }

        self.setPage($(this).data("page"));
    });

    this.initBrowserHistoryState();

    window.onpopstate = function (e) {
        if (e.state != null && e.state.hasOwnProperty("query")) {
            self.loadItems(e.state["query"]);
        }
    };
};

ItemList.prototype.pushNewBrowserHistoryState = function (query) {
    const
        href = Utils.createUrl(window.location.pathname, query),
        state = {
            query: query
        };

    history.pushState(state, "", href);
};

ItemList.prototype.getUpdatedQuery = function (options) {
    let parsedQuery = QueryString.parse(location.search);
    if (Object.prototype.hasOwnProperty.call(options, "page")) {
        parsedQuery.page = options.page;
    }
    return QueryString.stringify(parsedQuery);
};

ItemList.prototype.loadItems = function (query, append) {
    const url = Utils.createUrl(this.container.data("load-url"), query);

    this.itemLoader.load(url, append);
};

ItemList.prototype.loadMore = function (page) {
    const query = this.getUpdatedQuery({"page": page});
    this.loadItems(query, true);
    this.pushNewBrowserHistoryState(query);
};

ItemList.prototype.setPage = function (page) {
    const query = this.getUpdatedQuery({"page": page});
    this.loadItems(query);
    this.pushNewBrowserHistoryState(query);
};

ItemList.prototype.initBrowserHistoryState = function () {
    const
        href = window.location.pathname + window.location.search,
        state = {
            query: window.location.search.substr(1)
        };

    history.replaceState(state, "", href);
};

module.exports = ItemList;