Ext.apply(Ext.form.field.VTypes,{   
	password : function(val, field) {
	    if (field.initialPassField) {
    	    var pwd = field.up('form').down('#' + field.initialPassField);
        	return (val == pwd.getValue());
    	}
	    return true;
	},

	passwordText : 'Nova senha e confirmação não conferem'
});