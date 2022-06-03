/*! Select2 4.1.0-rc.0 | https://github.com/select2/select2/blob/master/LICENSE.md */

!(function () {
    if (jQuery && jQuery.fn && jQuery.fn.select2 && jQuery.fn.select2.amd) var n = jQuery.fn.select2.amd;
    n.define("select2/i18n/ua", [], function () {
        function n(n, e, r, u) {
            return (n % 10 < 5 && n % 10 > 0 && n % 100 < 5) || n % 100 > 20 ? (n % 10 > 1 ? r : e) : u;
        }
        return {
            errorLoading: function () {
                return "Неможливо завантажити результати";
            },
            inputTooLong: function (e) {
                var r = e.input.length - e.maximum,
                    u = "Будь-ласка, введіть на " + r + " символ";
                return (u += n(r, "", "a", "ов")), (u += " менше");
            },
            inputTooShort: function (e) {
                var r = e.minimum - e.input.length,
                    u = "Будь-ласка, введіть ще хоча б " + r + " символ";
                return (u += n(r, "", "a", "ов"));
            },
            loadingMore: function () {
                return "Завантаження даних…";
            },
            maximumSelected: function (e) {
                var r = "Ви можете обрати не більше " + e.maximum + " елемент";
                return (r += n(e.maximum, "", "a", "ов"));
            },
            noResults: function () {
                return "Збігів не знайдено";
            },
            searching: function () {
                return "Пошук…";
            },
            removeAllItems: function () {
                return "Видалити всі елементи";
            },
        };
    }),
        n.define,
        n.require;
})();
