require("bootstrap/dist/css/bootstrap.min.css");
require("@fortawesome/fontawesome-free/css/all.css");
require("./styles/app.css");

require("bootstrap/dist/js/bootstrap.bundle.min");

(function () {
    $(".js-user-card").click(function () {
        $(".js-user-card").not($(this)).removeClass("selected");

        $(this).toggleClass("selected");

        const form = $(this).closest("form");
        const userInput = form.find(":input[name='user']");
        console.log(userInput);

        if ($(this).hasClass("selected")) {
            userInput.val($(this).data("id"));
            form.find(".js-submit-button").removeClass("disabled");
        } else {
            userInput.val("");
            form.find(".js-submit-button").addClass("disabled");
        }
    });
})();
