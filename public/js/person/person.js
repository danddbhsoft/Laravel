function setTable(data, index){
    var container = $('#myList');
    container.pagination({
        dataSource: data,
        totalNumber: data.length,
        pageSize: 3,
        formatResult: function (data) {
            var result = [];
            var button = ``;
            for (var i = 0, len = data.length; i < len; i++) {
                if(index === 'update' && role === 'admin')   button = `<td><button onclick="removeCourse(event)" value='${data[i]['pivot'].course_id+'_'+data[i]['pivot'].person_id}' style="border:0; width:24px; height:24px; position: relative;" data-toggle="tooltip" title="Xóa khóa học" class="btn-delete"><span class="material-icons bg-danger text-white btn-tmp-delete" style="position: absolute; left:0; top: 0">delete_outline</span></button></td>`;
                result.push(`<tr>
                            <th scope="row">${data[i].id}</th>
                            <td>${data[i].name}</td>
                            <td style="width: 50%">${data[i].description}</td>
                            <td>${data[i].start_time}</td>
                            <td>${data[i].end_time}</td>
                            `+button+`

                        </tr>`);
                button = ``;
            }
            return result;
        },
        callback: function (data, pagination) {
            var action = '';
            if(index === 'update' && role === 'admin')   action = '                        <th scope="col">Action</th>\n';
            var dataHtml = '<table class="table table-bordered">\n' +
                '                    <thead>\n' +
                '                    <tr>\n' +
                '                        <th scope="col">#</th>\n' +
                '                        <th scope="col">Tên</th>\n' +
                '                        <th scope="col">Mô tả</th>\n' +
                '                        <th scope="col">Ngày bắt đầu</th>\n' +
                '                        <th scope="col">Ngày kết thúc</th>\n' +
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

function viewCourse(res, event){
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
