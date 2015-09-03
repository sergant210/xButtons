var xButtons = function (config) {
	config = config || {};
	xButtons.superclass.constructor.call(this, config);
};
Ext.extend(xButtons, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {},
	getFiles:function(){
		MODx.Ajax.request({
			url: xButtons_config.connector_url,
			params: {
				action: 'mgr/file/getfiles',
				element: xButtons_config.element
			},
			listeners: {
				success: {fn: function(response) {
					this.files = response.message;
					if (this.xbtnFileWindow) this.xbtnFileWindow.el.remove();
					this.xbtnFileWindow = MODx.load({
						xtype: 'xbuttons-files-window',
						id: 'xbuttons-files-window',
						listeners: {
							success: {
								fn: function (response) {
									if (response.a.result.success) {
										var code = response.a.result.message ? response.a.result.message : '<?php\n';
										var aceEditor = document.getElementsByClassName('ace_editor')[0];
										if (aceEditor) {
											Ext.getCmp(aceEditor.id).setValue(code);
										}
										document.getElementById(xButtons_config.field).value = code;
									}
								}, scope: this
							},
							failure: {
								fn: function(r){}, scope: this
							}
						}
					});
					this.xbtnFileWindow.setValues({element:xButtons_config.element});
					this.xbtnFileWindow.show(Ext.EventObject.target);
				}, scope: this},
				failure: {fn: function(response) {}, scope: this}
			}
		});
	},
	save2File:function(){
		if (this.saveObjectWindow) this.saveObjectWindow.el.remove();
		this.saveObjectWindow = MODx.load({
			xtype: 'xbuttons-savecode-window',
			id: Ext.id(),
			listeners: {
				success: {
					fn: function (response) {
						if (response.a.result.success) {
						}
					}, scope: this
				},
				failure: {
					fn: function(r){}, scope: this
				}
			}
		});
		this.saveObjectWindow.show(Ext.EventObject.target);
	}
});
Ext.reg('xbuttons', xButtons);

xButtons = new xButtons();

Ext.onReady(function() {
	/*
	 var lsbtns_form = document.createElement('form');
	 lsbtns_form.name = 'lsbtns_form';
	 lsbtns_form.action = 'console.php';
	 lsbtns_form.method = 'post';
	 lsbtns_form.enctype="multipart/form-data";
	 lsbtns_form.innerHTML = '<input type="file" name="lsbtns_form_file" style="display:none">';
	 lsbtns_form.onsubmit = 'alert(document.lsbtns_form.lsbtns_form_file.value)';
	 document.body.appendChild(lsbtns_form);
	 document.lsbtns_form.lsbtns_form_file.onchange = 'alert("Here");';
	 */
//console.log(document.lsbtns_form);

	var tb = Ext.getCmp("modx-action-buttons");

	var myBtn = new Ext.Button({
		text: "<i class=\"icon icon-file-code-o icon-large\"></i>",
		cls: "x-btn-text bmenu",
		style: "marginRight: 5px",
		menu: {
			id: 'xbuttons-x-menu',
			items: [{
				text: _('xbuttons_load_from_file'),
				handler: function() {

					xButtons.getFiles();
					/*
					//document.lsbtns_form.lsbtns_form_file.click();
					 tb.browser = MODx.load({
						 xtype: 'modx-browser'
						 ,closeAction: 'close'
						 ,id: Ext.id()
						 ,multiple: true
						 ,source: MODx.config.default_media_source
						 ,hideFiles: false
						 ,rootVisible: false
						 ,allowedFileTypes: ''
						 ,wctx: 'web'
						 ,openTo: ''
						 ,rootId: '/'
						 ,hideSourceCombo: false
						 ,listeners: {
							 'select': {fn: function(data) {
							 //		this.setValue(data.relativeUrl);
							 //		this.fireEvent('select',data);
							 },scope:this}
						 }
					 }).show();
					*/
					//Ext.getCmp("modx-plugin-plugincode").setValue('Test');
					//Ext.getCmp('modx-ace-texteditor').setValue('Test);
				},
				scope: this
			}, {
				text: _('xbuttons_save_to_file'),
				handler: function() {
					xButtons.save2File();
				},
				scope: this
			}]
		},
		handler: function(){return false;}
	});
	tb.addButton(myBtn);
	tb.doLayout();
});

/************************************************************/
xButtons.window.SaveCode = function (config) {
	config = config || {};

	var aceEditor = document.getElementsByClassName('ace_editor')[0], code;
	if (aceEditor) {
		code = Ext.getCmp(aceEditor.id).getValue();
	} else {
		code = document.getElementById(xButtons_config.field).value;
	}
	switch (xButtons_config.element) {
		case 'snippets':
			name = document.getElementById('modx-snippet-name').value;
			break;
		case 'plugins':
			name = document.getElementById('modx-plugin-name').value;
			break;
		case 'chunks':
			name = document.getElementById('modx-chunk-name').value;
			break;
		case 'templates':
			name = document.getElementById('modx-template-templatename').value;
			break;
	}
	Ext.applyIf(config, {
		width: 300,
		title: _('xbuttons_file_name'),
		autoHeight: true,
		modal: true,
		url: xButtons_config.connector_url,
		action: 'mgr/file/savefile',
		fields: [{
			xtype: 'hidden',
			name: 'code',
			value: code
		}, {
			xtype: 'hidden',
			name: 'element',
			value: xButtons_config.element
		}, {
			xtype: 'textfield',
			name: 'name',
			allowBlank: false,
			value: name || '',
			fieldLabel: _('xbuttons_enter_file_name'),
			anchor: '100%'
		}, {
			xtype: 'checkbox',
			name: 'overwrite',
			boxLabel: _('xbuttons_overwrite_file'),
			checked: false
		}],
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
	xButtons.window.SaveCode.superclass.constructor.call(this, config);
};
Ext.extend(xButtons.window.SaveCode, MODx.Window);
Ext.reg('xbuttons-savecode-window', xButtons.window.SaveCode);


xButtons.window.SelectFiles = function (config) {
	config = config || {};

	Ext.applyIf(config, {
		title: _('xbuttons_choose_file'),
		width: 300,
		url: xButtons_config.connector_url,
		action: 'mgr/file/loadfile',
		fields: [{
			xtype: 'hidden',
			name: 'element'
		}, {
			xtype: 'xbuttons-combo-files',
			name: 'file',
			emptyText: _('xbuttons_choose_file'),
			id: config.id + '-filename-field'
			,anchor: '100%'
		}],
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}],
		buttons: [{
			text: _('xbuttons_close'),
			id: config.id + '-close-btn',
			handler: function () {
				this.hide();
			},
			scope: this
		}, {
			text: _('xbuttons_load'),
			id: config.id + '-load-btn',
			handler: function () {
				this.submit();
			},
			scope: this
		}]
	});
	xButtons.window.SelectFiles.superclass.constructor.call(this, config);
};
Ext.extend(xButtons.window.SelectFiles, MODx.Window);
Ext.reg('xbuttons-files-window', xButtons.window.SelectFiles);

xButtons.combo.Files = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		triggerAction: 'all',
		mode: 'local',
		hideMode: 'offsets',
		autoScroll: true,
		maxHeight: 200,
		store: xButtons.files,
		hiddenName: 'file',
		editable: false
	});
	xButtons.combo.Files.superclass.constructor.call(this,config);
};
Ext.extend(xButtons.combo.Files,MODx.combo.ComboBox);
Ext.reg('xbuttons-combo-files',xButtons.combo.Files);