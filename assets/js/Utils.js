const Utils = (function () {
    return {
        createUrl: function (baseUrl, query) {
            if (query !== "") {
                baseUrl += "?" + query;
            }
            return baseUrl;
        },

        scrollBody: function (offset, duration) {
            duration = duration || 0;
            $("html, body").animate({
                scrollTop: offset
            }, duration);
        },
    }
})();

module.exports = Utils;