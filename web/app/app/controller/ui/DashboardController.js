/*
 * File: app/controller/ui/DashboardController.js
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

Ext.define('App.controller.ui.DashboardController', {
    extend: 'Ext.app.Controller',

    activeModule: '',
    startModule: 'panel',
    routeAlias: 'dashboard',
    routes: {
        
    },

    refs: [
        {
            ref: 'uiViewport',
            selector: 'uiviewport',
            xtype: 'Ext.container.Viewport'
        },
        {
            ref: 'changePassWindow',
            selector: 'changepasswindow',
            xtype: 'Ext.window.Window'
        },
        {
            ref: 'dashboardPanel',
            selector: 'dashboardpanel',
            xtype: 'Ext.panel.Panel'
        },
        {
            ref: 'ctModuleWrap',
            selector: 'dashboardpanel #ctModuleWrap',
            xtype: 'Ext.container.Container'
        },
        {
            ref: 'ctModules',
            selector: 'dashboardpanel #ctModules',
            xtype: 'Ext.container.Container'
        }
    ],

    exitClick: function(button, e, eOpts) {
        Ext.Msg.confirm('Confirme', 'Deseja realmente sair do sistema?', function(btn){
            if ('yes' == btn){
                Actions.NetonApp_Security.logout({}, function(response){
                location.reload();
            });
        }

    },this);
    },

    onDashboardShow: function(component, eOpts) {
        var session = this.application.getSession();

        component.down('#btnUsuario').setText(session['user.name']).doComponentLayout();
    },

    onTrocaSenhaClick: function(button, e, eOpts) {
        var win = Ext.widget('changepasswindow');

        win.show();
    },

    onChangePassShow: function(component, eOpts) {
        component.down('#txtSenha').focus(false, 250);
    },

    changePassFocusNextField: function(textfield, e, eOpts) {
        if (e.getKey() == 13){
            try{
                textfield.nextNode().focus();
            } catch(e){
                if (textfield.up('form').isValid())
                this.requestChangePass();
            }
        }
    },

    changePassConfirmarTrocaClick: function(button, e, eOpts) {
        this.requestChangePass();
    },

    changePassCancelarClick: function(button, e, eOpts) {
        this.getChangePassWindow().close();
    },

    onCtModulesRender: function(component, eOpts) {
        component.getEl().on('mouseleave', this.registerCloseDelay, this);
        component.getEl().on('mouseover', this.registerOpenDelay, this);
    },

    buttonModuleClick: function(button, e, eOpts) {
        // se o botão tem definida a propriedade module
        if (button.module){
            //this.activateModule(button.module);
            Ext.ux.Router.redirect(button.module);
        }
    },

    onModuleWrapRender: function(component, eOpts) {
        this.setupRouter();

        if (!Ext.ux.Router.hasToken)
        Ext.ux.Router.redirect(this.startModule);

    },

    showDashboardPanel: function() {
        var viewport = this.getUiViewport();

        Actions.NetonApp_Security.isLogged({}, function(response){

        if (response != false){
            this.application.setSession(response);

            if (!viewport.down('#panelDashboard')){    
                viewport.add({
                    xtype: 'dashboardpanel',
                    itemId: 'panelDashboard'
                });    
            }

            this.getUiViewport().getLayout().setActiveItem('panelDashboard');            
        }
    },this);
    },

    requestChangePass: function() {
        var me = this,
            win = me.getChangePassWindow();

        win.setLoading('Aguarde...');

        Actions.NetonApp_Security.changePass(win.down('#form').getValues(), me.responseChangePass, me);
    },

    responseChangePass: function(response) {
        var me = this,
            win = me.getChangePassWindow();

        // se a troca de senha ocorreu com sucesso
        if (response){
            // fecha a janela
            win.close();

            // exibe mensagem de sucesso na troca de senha
            Neton.Msg.flash({
                type: 'success',
                msg: 'Senha alterada com sucesso!',
                width: 250
            });
        } else { // se não foi possível trocar a senha
            // fecha a máscara de carregamento
            win.setLoading(false);

            // exibe mensagem de erro
            Neton.Msg.flash({
                type: 'error',
                msg: 'A senha atual está incorreta!',
                width: 250,
                callback: function(){
                    win.down('#txtSenha').focus(true);
                }
            });
        }
    },

    registerCloseDelay: function() {
        var runner = new Ext.util.TaskRunner()
        me = this,
        ctModules = me.getDashboardPanel().down('#ctModules');


        // define a propriedade que avalia o fechamento do menu
        me.closeMenu = true;

        // cria a tarefa de fechamento do menu
        var task = runner.newTask({
            run: function(){
                if (me.closeMenu){
                    // ativa a toolbar de localização
                    ctModules.getLayout().setActiveItem(0);

                    me.openedMenu = false;
                }
            },
            interval: 1000,
            scope: me,
            repeat: 1
        });

        // inicia a execução da tarefa
        task.start();

    },

    registerOpenDelay: function() {
        var runner = new Ext.util.TaskRunner(),
            me = this,
            ctModules = me.getDashboardPanel().down('#ctModules'),
            activeButton = ctModules.down('button[module="'+me.activeModule+'"]');


        // define a propriedade que avalia o fechamento do menu
        me.closeMenu = false;

        // cria a tarefa de fechamento do menu
        var task = runner.newTask({
            run: function(){
                if (!me.closeMenu){            			
                    if (!me.openedMenu){
                        ctModules.down('#tabModule').setActiveTab(activeButton.up('panel'));                
                    }
                    ctModules.getLayout().setActiveItem(1);
                    me.openedMenu = true;
                }
            },
            interval: 400,
            scope: me,
            repeat: 1
        });

        // inicia a execução da tarefa
        task.start();

    },

    activateModule: function(module) {
        var me = this,
            ctModuleWrap = me.getCtModuleWrap(),
            ctModules = me.getCtModules();

        // se o módulo ativo for diferente do módulo clicado
        if (me.activeModule != module){

            // seta o módulo ativo
            me.activeModule = module;    

            // se o painel do módulo não estiver presente no container de módulos
            if (!ctModuleWrap.down('#' + module)){
                try{
                    // adiciona o painel ao container de módulos
                    ctModuleWrap.add({
                        xtype: module,
                        itemId: module
                    });                
                } catch(e) {
                    console.log('Módulo: ' + module + ' não localizado!');
                }
            }

            // ativa o módulo atual
            ctModuleWrap.getLayout().setActiveItem(module);

            // pressiona o botão
            ctModules.down('#tabModule').down('button[module="'+module+'"]').toggle(true);

            // seta a localização
            this.setLocation(module);    
        }
    },

    setLocation: function(module) {
        var me = this,
            menuLocation = me.getCtModules().down('#menuLocation'),
            activeButton = me.getCtModules().down('#tabModule').down('button[module="'+module+'"]');

        menuLocation.down('#ctModule').update(activeButton.getText());
        menuLocation.down('#ctApp').update(activeButton.up('panel').title);
        menuLocation.down('#ctIcon').removeCls(menuLocation.down('#ctIcon').cls);
        menuLocation.down('#ctIcon').addCls('locator-menu-icon ' + activeButton.iconCls);
    },

    setupRouter: function() {
        Ext.ux.Router.init(this.application);

        Ext.ux.Router.on({    
            routemissed: function(token) {
                Ext.Msg.show({
                    title:'Error 404',
                    msg: 'Rota não encontrada para: ' + token,
                    buttons: Ext.Msg.OK,
                    icon: Ext.Msg.ERROR
                });
            }
        });
    },

    init: function(application) {
        this.control({
            "dashboardpanel #btnSair": {
                click: this.exitClick
            },
            "dashboardpanel": {
                show: this.onDashboardShow
            },
            "dashboardpanel #btnTrocaSenha": {
                click: this.onTrocaSenhaClick
            },
            "changepasswindow": {
                show: this.onChangePassShow
            },
            "changepasswindow #form field": {
                keypress: this.changePassFocusNextField
            },
            "changepasswindow #btnConfirmar": {
                click: this.changePassConfirmarTrocaClick
            },
            "changepasswindow #btnCancelar": {
                click: this.changePassCancelarClick
            },
            "dashboardpanel #ctModules": {
                render: this.onCtModulesRender
            },
            "dashboardpanel #tabModule button": {
                click: this.buttonModuleClick
            },
            "dashboardpanel #ctModuleWrap": {
                render: this.onModuleWrapRender
            }
        });
    }

});