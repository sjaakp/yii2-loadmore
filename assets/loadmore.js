/*global FileReader, Image, jQuery */
/*jslint plusplus: true, white: true */
/**
 * MIT license
 * Version 1.0
 * Sjaak Priester, Amsterdam 29-04-2019.
 */

(function ($) {
    "use strict";
    $.fn.loadmore = function (options) {
        return this.each(function () {

            this.options = options;
            this.page = 1;

            $(this).click(function (e) {
                e.preventDefault();

                let url = new URL(document.location);
                url.searchParams.set(this.options.pageParam, ++this.page);

                let that = this;

                $(this.options.indicator).addClass('show');

                $.ajax({
                    url: url.href,
                    dataType: 'html',
                    error: function (x, s, err) { console.log(err); },
                    success: function (data, s, x) {
                        let loader = $(e.target),
                            list = loader.parent();

                        list.children('[data-key]').last().after($(data).find('#' + e.target.id).parent().children('[data-key]'));
                        list.children(".summary-end").text(list.find('[data-key]').length);

                        if (that.page >= that.options.pageCount) {
                            loader.remove();
                        }
                    },
                    complete: function (x, s) {
                        $(that.options.indicator).removeClass('show');
                    }
                });
            });
            return this;
        });
    };
}(jQuery));
