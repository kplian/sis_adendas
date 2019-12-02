var utils = {
    serialize: function (response) {
        var res = Ext.util.JSON.decode(Ext.util.Format.trim(response.responseText));
        return res;
    }
};
