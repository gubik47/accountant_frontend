const Utils = (function () {
    return {
        /**
         * Vytvori URL s query stringem.
         */
        createUrl: function (baseUrl, query) {
            if (query !== "") {
                baseUrl += "?" + query;
            }
            return baseUrl;
        },

        /**
         * Scrollne stranku k pozadovanemu offsetu shora.
         *
         * @param offset
         * @param duration
         */
        scrollBody: function (offset, duration) {
            duration = duration || 0;
            let offsetCorrection = 0;
            let header = $(".js-page-header");
            if(header.css("position") == "sticky") {
                offsetCorrection = header.outerHeight();
            }
            $("html, body").animate({
                scrollTop: offset - offsetCorrection
            }, duration);
        },
    }
})();

module.exports = Utils;