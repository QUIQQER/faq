/**
 * Category handling
 * Set events and scroll effects
 *
 * @module package/quiqqer/faq/bin/Category
 * @author www.pcsg.de (Henning Leutz)
 *
 * @require qui/QUI
 * @require qui/controls/Control
 */
define('package/quiqqer/faq/bin/Category', [

    'qui/QUI',
    'qui/controls/Control'

], function (QUI, QUIControl) {
    "use strict";

    return new Class({

        Extends: QUIControl,
        Type   : 'package/quiqqer/faq/bin/Category',

        options: {
            offset: null // if nav sticks to top use nav height to no hide the content by scroll to element
        },

        Binds: [
            '$onImport',
            'scrollToTop',
            '$scrollToClick'
        ],

        initialize: function (options) {
            this.parent(options);

            this.FAQList = null;
            this.offest  = 0;

            this.addEvents({
                onImport: this.$onImport
            });
        },

        /**
         * event : on import
         */
        $onImport: function () {
            var Elm     = this.getElm(),
                links   = Elm.getElements('.quiqqer-faq-list li a'),
                topList = Elm.getElements('.quiqqer-faq-list-linkToTop');

            this.FAQList = Elm.getElement('.quiqqer-faq-list');
            this.offset  = Elm.get('data-qui-options-offset');

            for (var i = 0, len = links.length; i < len; i++) {
                links[i].addEvent('click', this.$scrollToClick);
            }

            topList.addEvent('click', this.scrollToTop);

            // check location
            if (!window.location.hash || window.location.hash === '') {
                return;
            }

            var Article = Elm.getElement(window.location.hash);

            if (Article) {
                new Fx.Scroll(window, {
                    offset: {
                        y: -this.offset
                    }
                }).toElement(Article);
            }
        },

        /**
         * event : on click at a faq entry
         *
         * @param {DOMEvent} event - click event
         */
        $scrollToClick: function (event) {
            if (typeOf(event) === 'domevent') {
                event.stop();
            }

            var Target = event.target,
                href   = Target.get('href');

            href = href.split('#');

            if (typeof href[1] === 'undefined') {
                return;
            }

            var Article = this.getElm().getElement('#' + href[1]);

            if (!Article) {
                return;
            }

            new Fx.Scroll(window, {
                offset    : {
                    y: -this.offset
                },
                onComplete: function () {
                    if (window.location.hash === '#' + href[1]) {
                        return;
                    }

                    window.location = '#' + href[1];
                }
            }).toElement(Article, 'y');
        },

        /**
         * Scroll the window to top and clear the anchor
         *
         * @param {DOMEvent} [event] - (optional) click dom event
         */
        scrollToTop: function (event) {
            var self = this;
            if (typeOf(event) === 'domevent') {
                event.stop();
            }

            new Fx.Scroll(window, {
                offset    : {
                    y: -this.offset
                },
                onComplete: function () {
                    if (window.location.hash === '#' + self.FAQList.getAttribute('id')) {
                        return;
                    }

                    window.location = '#' + self.FAQList.getAttribute('id');
                }
            }).toElement(this.FAQList);
        }
    });
});
