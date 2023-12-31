define([
    'underscore',
    'Magento_Ui/js/form/element/textarea'
], function (_, Abstract) {
    'use strict';

    return Abstract.extend({
        defaults: {
            visible: false,
            modules: {
                parentComponent: '${ $.recordsProvider }'
            },
            listens: {
                error: 'onError'
            },
            links: {
                value: ''
            }
        },

        onError: function (result) {
            this.parentComponent().error(result);
            this.parentComponent().bubble('error', result);
        },

        /**
         * Callback that fires when 'value' property is updated.
         */
        onUpdate: function () {
            this._super();
            if (this.isDifferedFromDefault()) {
                var data = this.convertValueToData(),
                    path = this.parentComponent().dataScope + '.' + this.parentComponent().index;
                this.parentComponent().source.set(path, data);
            }
        },

        /**
         * @returns {Array}
         */
        convertValueToData: function () {
            var value = this.value(),
                newData = [],
                elementData,
                regionsData = this.regions,
                regionsIds = [];
            value = value.split(',');
            regionsIds = regionsData.split(',');
            console.log(regionsIds);
            _.each(value, function (element, iteration) {
                elementData = element.split('-');
                if (elementData[0]) {
                    newData.push({
                        textarea: true,
                        code: elementData[0],
                        default_name: elementData[1],
                        region_id: regionsIds[iteration]

                    });
                }
            });

            return newData;
        },

        /**
         * Hide the textarea and show Table of dynamic rows
         */
        switchToTable: function () {
            if (this.isDifferedFromDefault()) {
                this.parentComponent().clear();
                this.parentComponent().initChildren();
            }
            this.parentComponent().tableVisible(true);
            this.visible(false);
            this.error('');
        },

        /**
         * Prepare and import data to table view for save
         */
        importData: function () {
            this.parentComponent().recordData(this.convertValueToData());
            this.parentComponent().clear();
            this.parentComponent().initChildren();
        }
    });
});
