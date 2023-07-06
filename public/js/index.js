function handle(flag_async, url, form_datas, callback, event, course_id) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    $.ajax({
        async: flag_async,
        url: url,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer '+csrfToken,
        },
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_datas,
        type: 'post',
        success: function (res) {
            callback(res, event, course_id);
        },
        error: function (res){
            console.log(res);
        }
    })
}

function handleGet(url, callback) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    $.ajax({
        async: false,
        url: url,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer '+csrfToken,
        },
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        type: 'get',
        success: function (res) {
            callback(res);
        },
        error: function (res){
            console.log(res);
        }
    })
}
