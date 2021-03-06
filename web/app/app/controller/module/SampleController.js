/*
 * File: app/controller/module/SampleController.js
 *
 * This file was generated by Sencha Architect version 2.2.2.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 4.2.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 4.2.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('App.controller.module.SampleController', {
    extend: 'Ext.app.Controller',

    routeAlias: 'panel',
    routes: {
        panel: 'list',
        container: 'container'
    },

    list: function() {
        this.getController('ui.DashboardController').activateModule('panel');
    },

    container: function() {
        this.getController('ui.DashboardController').activateModule('container');
    }

});
