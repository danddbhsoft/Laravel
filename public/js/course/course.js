function setTable(data, index){
    var container = $('#myList');
    container.pagination({
        dataSource: data,
        totalNumber: data.length,
        pageSize: 10,
        formatResult: function (data) {
            var result = [];
            var button = ``;
            for (var i = 0, len = data.length; i < len; i++) {
                if(index === 'update' && role === 'admin')   button = `<td><button onclick="removePerson(event)" value='${data[i]['pivot'].course_id+'_'+data[i]['pivot'].person_id}' style="border:0; width:24px; height:24px; position: relative;" data-toggle="tooltip" title="Xóa khóa học" class="btn-delete"><span class="material-icons bg-danger text-white btn-tmp-delete" style="position: absolute; left:0; top: 0">delete_outline</span></button></td>`;
                result.push(`<tr>
                            <th scope="row">${data[i].id}</th>
                            <td><img class="avatars" style="cursor:pointer" src="${data[i].image_link}" width="32" height="24"></td>
                            <td>${data[i].name}</td>
                            <td>${data[i].email}</td>
                            <td>${data[i].birthday}</td>
                            <td>${data[i].phone_number.slice(0, 4)} ${data[i].phone_number.slice(4, 7)} ${data[i].phone_number.slice(7, 10)}</td>
                            `+button+`
                        </tr>`);
                button = ``;
            }
            return result;
        },
        callback: function (data, pagination) {
            var action = '';
            if(index === 'update'&& role === 'admin')   action = '                        <th scope="col">Action</th>\n';
            var dataHtml = '<table class="table table-bordered">\n' +
                '                    <thead>\n' +
                '                    <tr>\n' +
                '                        <th scope="col">#</th>\n' +
                '                        <th scope="col">Ảnh</th>\n' +
                '                        <th scope="col">Tên</th>\n' +
                '                        <th scope="col">Email</th>\n' +
                '                        <th scope="col">Ngày sinh</th>\n' +
                '                        <th scope="col">Số điện thoại</th>\n' +
                action +
                '                    </tr>\n' +
                '                    </thead>\n' +
                '                    <tbody>';
            var pageStart = (pagination.pageNumber - 1) * pagination.pageSize;
            var pageEnd = pageStart + pagination.pageSize;
            var pageItems = data.slice(pageStart, pageEnd);
            $.each(data, function (index, item) {
                dataHtml += item;
            });
            dataHtml += '</tbody>\n' +
                '                </table>';
            container.prev().html(dataHtml);
        }
    })
}

function viewStudent(res, event){
    data =  JSON.parse(res);
    setTable(data, event);
}

function toastShow(title, body) {
    $('.toast .toast-header .toast-title').text(title);
    $('.toast .toast-body').text(body);
    $('.toast').toast({
        delay: 5000,
        autohide: true
    }).toast('show');
}
