/* 
    Lapp.js For HTML5 Webapp
    You must install Lapp Framework on your server before use this javascript
*/

var lapp = [];
lapp.server = "http://localhost/lapp/";
lapp.api = function (api_string, data_array, callback, error) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: lapp.server,
        timeout: 10000,
        cache: false,
        data: {
            api: api_string,
            data: data_array
        },
        success: callback(result),
        error: error(xhr, type)
    });
}