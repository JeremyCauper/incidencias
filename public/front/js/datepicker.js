!function (n) {
    "function" == typeof define && define.amd ? define(["jquery"], n) : "object" == typeof module && module.exports ? module.exports = function (e, t) {
        return void 0 === t && (t = "undefined" != typeof window ? require("jquery") : require("jquery")(e)),
            n(t),
            t
    }
        : n(jQuery)
}(function ($) {
    $.fn.alertas = function (options) {
        var settings = $.extend({
            title: "Default Title",
            message: "Default Message"
        }, options);

        return this.each(function () {
            var $this = $(this);
            $this.on('click', function () {
                alert(settings.title + "\n" + settings.message);
            });
        });
    };
});