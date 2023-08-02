define([
    'underscore',
    'uiLayout',
    'Magento_Ui/js/dynamic-rows/dynamic-rows',
    'mage/translate'
], function (_, layout, dynamicRows, $t) {
    'use strict';

    return dynamicRows.extend({
        defaults: {
            toTableLabel: $t('Switch to table input'),
            toRawLabel: $t('Switch to textarea input (for mass insert)'),
            tableVisible: true,
            error: '',
            rawInputObservable: false,
            rawConfig: {
                name: '${ $.name }_raw',
                component: 'Leeto_RegionManager/js/dynamic-rows/textarea',
                template: 'Leeto_RegionManager/textarea',
                recordsProvider: '${ $.name }',
                provider: '${ $.provider }',
                visible: false
            },
            listens: {
                tableVisible: 'setVisible'
            },
            modules: {
                rawInput: '${ $.rawConfig.name }'
            }
        },

        initialize: function () {
            this._super();

            this.rawInput(function (rawInput) {
                this.rawInputObservable(rawInput);
            }.bind(this));

            return this;
        },

        /**
         * Calls 'initObservable' of parent
         *
         * @returns {Object} Chainable.
         */
        initObservable: function () {
            this._super()
                .observe('tableVisible error rawInputObservable');

            return this;
        },

        /**
         * Init Raw module
         *
         * @returns {Object} Chainable.
         */
        initModules: function () {
            this._super();
            layout([this.rawConfig]);

            return this;
        },

        /**
         * Set visibility to dynamic-rows child
         *
         * @param {Boolean} state
         */
        setVisible: function (state) {
            this._super();

            if (!this.visible()) {
                this.rawInput(function (component) {
                    component.setVisible(false);
                });
            } else {
                this.rawInput(function (component) {
                    component.setVisible(!this.tableVisible());
                }.bind(this));
            }
        },

        switchToRaw: function () {
            var dataArray = [],
                elementData = '',
                regionIdsArray = [],
                regionData = '';
            _.each(this.recordData(), function (elem) {
                if (elem[this.deleteProperty] !== this.deleteValue && elem['code']) {
                    elementData = elem['code'];
                    regionData = '';
                    if (elem['default_name']) {
                        elementData += '-' + elem['default_name'];
                    }
                    if (elem['region_id']) {
                        regionData += elem['region_id'] ? elem['region_id'] : '';
                    }
                    
                    dataArray.push(elementData);
                    regionIdsArray.push(regionData);
                }
            }.bind(this));
            var resultData = dataArray.join(',');
            var regionsIdsResult = regionIdsArray.join(',');
            this.rawInput().default = resultData;
            this.rawInput().initialValue = resultData;
            this.rawInput().value(resultData);
            this.rawInput().regions = regionsIdsResult;
            this.rawInput().setDifferedFromDefault();

            this.tableVisible(false);
            this.rawInput().visible(true);
            this.rawInput().focused(true);
        }
    });
});
