define('package/quiqqer/faq/bin/Controls/backend/SettingDependency', [
    'qui/controls/Control'
], function (QUIControl) {
    "use strict";

    return new Class({
        Extends: QUIControl,
        Type: 'package/quiqqer/faq/bin/Controls/backend/SettingDependency',

        Binds: [
            '$onImport',
            '$onChange'
        ],

        initialize: function (options) {
            this.parent(options);

            this.$Input = null;
            this.$Options = [];
            this.$ColumnsRow = null;

            this.addEvents({
                onImport: this.$onImport
            });
        },

        $onImport: function () {
            this.$Input = this.getElm();

            if (!this.$Input) {
                return;
            }

            const ParentTable = this.$Input.closest('table');

            if (!ParentTable) {
                return;
            }

            const target = 'faq-' + this.$Input.name;

            this.$Options = Array.from(
                ParentTable.querySelectorAll('[data-dependency="' + target + '"]')
            );

            this.$ColumnsRow = this.$getColumnsRow(ParentTable);

            this.$Input.addEventListener('change', this.$onChange);
            this.$applyState();
        },

        $onChange: function () {
            this.$applyState();
        },

        $applyState: function () {
            const input = this.$Input;

            if (!input) {
                return;
            }

            const value = input.value || '';

            this.$Options.forEach(function (Option) {
                const dependencyOptions = (Option.getAttribute('data-dependency-options') || '')
                    .split(',')
                    .map(function (entry) {
                        return entry.trim();
                    })
                    .filter(function (entry) {
                        return entry !== '';
                    });

                if (!dependencyOptions.length || dependencyOptions.contains(value)) {
                    this.$showOption(Option);
                    return;
                }

                this.$hideOption(Option);
            }.bind(this));

            if (this.$ColumnsRow) {
                this.$ColumnsRow.setStyle('display', value === 'accordion' ? null : 'none');
            }
        },

        $getColumnsRow: function (ParentTable) {
            const ColumnsField = ParentTable.querySelector(
                '[name="quiqqer.faq.settings.accordion.columns"]'
            );

            if (!ColumnsField) {
                return null;
            }

            return ColumnsField.closest('tr');
        },

        $showOptions: function () {
            this.$Options.forEach(function (Option) {
                this.$showOption(Option);
            }.bind(this));
        },

        $hideOptions: function () {
            this.$Options.forEach(function (Option) {
                this.$hideOption(Option);
            }.bind(this));
        },

        $showOption: function (Option) {
            const Row = Option.closest('tr');

            if (!Row) {
                return;
            }

            Row.setStyle('display', null);
        },

        $hideOption: function (Option) {
            const Row = Option.closest('tr');

            if (!Row) {
                return;
            }

            Row.setStyle('display', 'none');
        }
    });
});
