const ItemList = require("../ItemList");
const Utils = require("../Utils");
const QueryString = require("query-string");
const ParameterSearch = require("./ParameterSearch");

const ProductList = function(options) {
    ItemList.call(this, options);

    this.filtrationContainer = options.filtrationContainer;
    this.activeFiltersContainer = options.activeFiltersContainer;

    const self = this;

    this.initFiltration();

    // stisknuto tlacitko zpet v prohlizeci
    window.onpopstate = function (e) {
        if (e.state != null && e.state.hasOwnProperty("query")) {
            // nacti polozky v seznamu
            self.loadItems(e.state["query"]);
        }
    };
};

ProductList.prototype = Object.create(ItemList.prototype);

ProductList.prototype.initFiltration = function () {
    const self = this;

    // inicializace vyhledavani v hodnotach parametru filtrace
    this.initFilterSearch();

    // rozbaleni/sbaleni filtrace (mobile)
    self.filtrationContainer.on("click", ".js-filtration-toggle", function () {
        $(".js-filtration").toggleClass("is-active");
    });

    // rozbaleni/sbaleni vsech options filtru
    self.filtrationContainer.on("click", ".js-toggle-all", function () {
        const filterParam = $(this).closest(".js-filter-parameter");
        filterParam.find(".js-filter-option.js-extra").toggle();
    });

    // rozabeli/sbaleni jednoho parametru ve filtru
    self.filtrationContainer.on("click", ".js-filter-parameter-toggle", function () {
        const filterParam = $(this).closest(".js-filter-parameter");
        filterParam.toggleClass("is-active");
        filterParam.find(".js-filter-content").slideToggle(150, "linear");
    });

    // klik na checkbox filtrace parametru
    self.filtrationContainer.on("change", ".js-filter-option-checkbox", function () {
        // pri zmene options smaz obsah inputu pro interval
        const param = $(this).closest(".js-filter-parameter");
        param.find(".js-range-input").val("");

        self.submitFilter();
    });

    // povoleni jen ciselnych retezcu do inputu rozsahu
    self.filtrationContainer.on("input", ".js-range-input", function () {
        $(this).val($(this).val()
            .replace(/,/g, ".") // "," -> "."
            .replace(/^(.+)-/g, "$1") // minus pouze na zacatku
            .replace(/[^0-9.-]/g, "") // odstraneni neciselnych hodnot
            .replace(/(\..*)\./g, "$1")); // odstraneni vice des. tecek
    });

    // submit formulare filtrace podle intervalu
    self.filtrationContainer.on("click", ".js-range-submit", function () {
        const param = $(this).closest(".js-filter-parameter");
        param.find(".js-filter-option-checkbox").prop("checked", false);

        self.submitFilter();
    });

    // odstraneni vybrane option ze selectu
    self.activeFiltersContainer.on("click", ".js-remove-filter", function () {
        const parameterId = $(this).data("param");
        const value = $(this).data("value");

        if (String(value).indexOf("-") !== -1) {
            // range
            $(`.js-filter-parameter[data-id='${parameterId}']`).find(".js-range-input").val("");
        } else {
            // option
            $(`.js-filter-option-checkbox[value='${value}']`).prop("checked", false);
        }

        self.submitFilter();
    });

    // odstraneni vsech aktivnich filtru
    self.activeFiltersContainer.on("click", ".js-remove-all-filters", function () {
        $("js-filter-parameter").find(".js-range-input").val("");
        $(".js-filter-option-checkbox").prop("checked", false);

        self.submitFilter();
    });
};

ProductList.prototype.submitFilter = function () {
    // filtrace podle parametru
    let filterParts = [];

    $.each($(".js-filter-parameter"), function () {

        let options = [];
        if ($(this).data("range")) {
            const from = $(this).find(".js-range-from");
            const to = $(this).find(".js-range-to");

            let range = "";
            if (parseFloat(from.val())) {
                range += parseFloat(from.val());
            }
            range += "-";
            if (parseFloat(to.val())) {
                range += parseFloat(to.val())
            }

            if (range !== "-") {
                options.push(range);
            }
        }

        if (!options.length) {
            // pokud neni range, tak poskladej options
            $.each($(this).find(".js-filter-option-checkbox"), function () {
                if ($(this).is(":checked")) {
                    options.push($(this).val());
                }
            });
        }

        if (options.length) {
            filterParts.push($(this).data("id") +":" + options.join(","));
        }
    });

    let filter_object = QueryString.parse(window.location.search, {arrayFormat: "bracket"});

    filterParts = filterParts.join(";");
    if (filterParts) {
        filter_object.filter = filterParts;
    } else {
        filter_object.filter = undefined;
    }
    const query = this.getUpdatedQuery(filter_object);
    this.loadItems(query);
    this.pushNewBrowserHistoryState(query);
}

/**
 * Událost změny filtrů dle parametrů
 * @param {CustomEvent} e
 * @returns {undefined}
 */
ProductList.prototype.parameterFilterChanged = function (e) {
    //Aktuální filtry
    const filter_data = decodeURI(e.detail.serialize());
    const filter_object = QueryString.parse(filter_data, {arrayFormat: "bracket"});
    const query = this.getUpdatedQuery(filter_object, true);
    this.loadItems(query);
    this.pushNewBrowserHistoryState(query);
};

/**
 * Vyhledani hodnoty parametru podle textu.
 */
ProductList.prototype.initFilterSearch = function () {
    ParameterSearch.init(this.filtrationContainer.find(".js-filter-option-search"));
}

/**
 * Vrati aktualizovany query string s novymi hodnotami strankovani, filtrace nebo razeni.
 */
ProductList.prototype.getUpdatedQuery = function (options, clear_filters) {
    let parsedQuery = QueryString.parse(ItemList.prototype.getUpdatedQuery(options), {arrayFormat: "bracket"});
    //Bohužel parametry se ukládají pod klíčem ve formátu p[2] - není to pole pod klíčem p
    let property;
    if (clear_filters === true) {
        //Odstraním všechny staré hodnoty
        for (property in parsedQuery) {
            if (Object.prototype.hasOwnProperty.call(parsedQuery, property) && property.substring(0, 2) === "p[") {
                parsedQuery[property] = undefined;
            }
        }
    }
    //Musím tedy projít všechny property co začínají na p[ a přidat je
    for (property in options) {
        if (Object.prototype.hasOwnProperty.call(options, property) && property.substring(0, 2) === "p[") {
            parsedQuery[property] = options[property];
        }
    }
    return QueryString.stringify(parsedQuery, {encode: false, arrayFormat: "bracket"});
};
/**
 * Vlozi pocatecni state vypisu polozek do browser history.
 * Pročistí filtry parametrů a odstraní ty neplatné
 */
ProductList.prototype.initBrowserHistoryState = function () {
    const href = window.location.pathname + window.location.search;
    const state = {
        query: window.location.search.substr(1), // odstrani "?" na zacatku
        order: $(".js-order.active").data("order"),
        filter: $(".js-filter.active").data("filter")
    };
    history.replaceState(state, "", href);
};

/**
 * Nacte polozky seznamu AJAXem z API.
 *
 * @param query
 * @param append Pokud je true, seznam polozek se pripoji za ten stavajici.
 */
ProductList.prototype.loadItems = function (query, append) {
    if (this.container.data("category")) {
        query = QueryString.parse(query, {arrayFormat: "bracket"});
        query.category = this.container.data("category");
        query = QueryString.stringify(query, {encode: false, arrayFormat: "bracket"});
    }

    const url = Utils.createUrl(this.container.data("load-url"), query);

    const self = this;

    let newContent;
    this.itemLoader.load(url, append, function (response) {
        if (response.status === "success") {
            if (typeof self.filtrationContainer !== "undefined") {
                //Nahrazeni filtrace parametru
                //Musíme zachovat rozbalené skupiny
                newContent = $(response.html.filtration);
                self.filtrationContainer.html(newContent);
                self.initFilterSearch();
            }

            if (typeof self.activeFiltersContainer !== "undefined") {
                //Nahrazeni aktivnich filtru parametru
                newContent = $(response.html.activeFilters);
                self.activeFiltersContainer.html(newContent);
            }

            let scrollOffset;
            if (window.innerWidth >= 1080 ) { // desktop layout hranice
                scrollOffset = self.container.offset().top - 350;
            } else {
                scrollOffset = self.filtrationContainer.offset().top - 10;
            }
            Utils.scrollBody(scrollOffset, 300);
        }
    });
};

$(document).ready(function () {
    const mainContainer = $(".js-product-list");
    if (mainContainer.length === 0) {
        return;
    }

    new ProductList({
        mainContainer: mainContainer,
        listContainer: mainContainer.find(".js-product-container"),
        filtrationContainer: $(".js-filtration-container"),
        activeFiltersContainer: $(".js-active-filters-container")
    });
});