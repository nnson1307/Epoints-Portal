var translate = {
    json : null,
    lang: null,
    _init: function () {
        var resultJson = localStorage.getItem('tranlate');
        if(!resultJson){
            console.log('get json lang');
            $.getJSON(laroute.route('translate'), function (json) {
                translate.json = json;
                translate.lang = json;
                localStorage.setItem('tranlate', JSON.stringify(json));
            });
        }
        translate.json = resultJson;
        translate.lang = resultJson;
    },
    // lang: function () {
    //     console.log('translate');
    //     var resultJson = localStorage.getItem('tranlate');
    //     console.log(resultJson);
    //     if(!resultJson){
    //         console.log('get json lang');
    //         $.getJSON(laroute.route('translate'), function (json) {
    //             localStorage.setItem('tranlate', JSON.stringify(json));
    //             return json;
    //         });
    //     }
    //     return resultJson;
    // }
}



