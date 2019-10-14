var adendas = {
    dialog: {
        title: conf.appName,
        message: conf.messages.default_error_message,
        alert: function (title, message) {
            var self = this;
            Ext.Msg.alert(self.title, message);
        },
        prompt: function (message, callback) {
            var self = this;
            Ext.Msg.prompt(self.title, message, callback);
        },
        error: function (message, callback) {
            var self = this;
            Ext.Msg.show({
                title: self.title,
                msg: message ? message : this.message,
                buttons: Ext.Msg.OK,
                fn: callback,
                icon: Ext.MessageBox.ERROR
            });
        },
        info: function (message, callback) {
            var self = this;
            Ext.Msg.show({
                title: self.title,
                msg: message,
                buttons: Ext.Msg.OK,
                fn: callback,
                icon: Ext.MessageBox.INFO
            });
        },
        warn: function (message, callback) {
            var self = this;
            Ext.Msg.show({
                title: self.title,
                msg: message,
                buttons: Ext.Msg.OK,
                fn: callback,
                icon: Ext.MessageBox.WARNING
            });
        },
        confirm: function (message, callback) {
            var self = this;
            Ext.Msg.show({
                title: self.title,
                msg: message,
                buttons: Ext.Msg.YESNO,
                fn: callback,
                icon: Ext.MessageBox.QUESTION
            });
        }
    }
};

