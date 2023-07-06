var data;
var listPerson;
var filterPerson;
var choosePerson = [];
var searchPerson = [];
var html_main = '';

$("#btn-updateStudent").click(function(event){
    if(data==undefined && listPerson ==undefined) {
        var formClass = event.target.id;
        let form_datas = new FormData();
        form_datas.append('id', $(this).val());
        handle(false,'http://laravel.test/course/api', form_datas, viewStudent, 'update');
        handleGet('http://laravel.test/person/get', getPeople);
    }
})

function getPeople(res){
    listPerson = JSON.parse(res);
    filterPerson = listPerson.filter(item => !data.some(obj => obj.id === item.id));
    fillPeople(filterPerson, 'base');
}
function fillPeople(filterPerson, type){
    if(type === 'base'){
        if(html_main != '') {
            $("#people").html(html_main);
        }else{
            for (let i = 0; i < filterPerson.length; i++) {
                html_main += `<div class="form-check" style="height:35px">
                        <input class="form-check-input check-box-ps" name="unselected" type="checkbox" value="${filterPerson[i].id}">
                        <label class="form-check-label text-black" style="opacity: 0.8;" for="flexCheckDefault">
                            ${filterPerson[i].name}, ${filterPerson[i].email}, ${filterPerson[i].phone_number}
                        </label>
                    </div>`;
            }
            $("#people").html(html_main);
        }
    }else{
        let html = '';
        for (let i = 0; i < filterPerson.length; i++) {
            html += `<div class="form-check" style="height:35px">
                        <input class="form-check-input check-box-ps" name="unselected" type="checkbox" value="${filterPerson[i].id}">
                        <label class="form-check-label text-black" style="opacity: 0.8;" for="flexCheckDefault">
                            ${filterPerson[i].name}, ${filterPerson[i].email}, ${filterPerson[i].phone_number}
                        </label>
                    </div>`;
        }
        $("#people").html(html);
    }
}

$(document).on('change', 'input[name="unselected"]', function (){
    if ($(this).is(':checked')) {
        let id = parseInt($(this).val());
        let name = $(this).siblings('label').text();
        $('#choosePeople').append(`
        <div class="form-check ms-1 mt-0" style="height:35px">
            <input class="form-check-input check-box-ps" checked name="selected[]" type="checkbox" value="${id}">
            <label class="form-check-label text-black" style="opacity: 0.8;" for="flexCheckDefault">
                ${name}
            </label>
        </div>
        `);
        filterPerson = filterPerson.filter(function (obj){
            if(obj.id === id){
                choosePerson.push(obj);
                return false;
            }
            return true;
        });
        $(this).closest('div').remove();
    }
})

$(document).on('change', 'input[name="selected"]', function (){
    if (!$(this).is(':checked')) {
        let id = parseInt($(this).val());
        let name = $(this).siblings('label').text();
        $('#people').append(`
        <div class="form-check ms-1 mt-0" style="height:35px">
            <input class="form-check-input check-box-ps" name="unselected" type="checkbox" value="${id}">
            <label class="form-check-label text-black" style="opacity: 0.8;" for="flexCheckDefault">
                ${name}
            </label>
        </div>
        `);
        choosePerson = choosePerson.filter(function (obj){
            if(obj.id === id){
                filterPerson.push(obj);
                return false;
            }
            return true;
        });
        $(this).closest('div').remove();
    }
})

$("#updateCourse").submit(function(event) {
    event.preventDefault();
    var check = 0;
    let name = $("#name").val();
    let description = $("#description").val();
    let start_time = new Date($("#start_time").val());
    let end_time = new Date($("#end_time").val());
    let old_date = course.start_time.split('-');
    let old_time = '';
    for (let i = old_date.length - 1; i >= 0; i--) {
        old_time += old_date[i];
        if(i>0) old_time += '-';
    }
    old_time = new Date(old_time);
    let now = new Date();
    now.setHours(7);
    now.setMinutes(0);
    now.setSeconds(0);
    now.setMilliseconds(0);
    if(name != ''){
        check++;
    }
    if(description != ''){
        check++;
    }
    if(start_time != '' && start_time >= old_time || start_time <= old_time && start_time >= now){
        check++;
    }else{
        isInvalid('start_time');
        $("#start_time_error").text("Thời gian bắt đầu không được để trống và phải lớn hơn thời gian hiện tại.");
        changeInput('start_time');
    }
    if(end_time != '' && end_time > start_time){
        check++;
    }else{
        isInvalid('end_time');
        $("#end_time_error").text("Thời gian kết thúc không được để trống và phải lớn hơn thời gian bắt đầu.");
        changeInput('end_time');
    }
    if(check == 4){
        $("#updateCourse").off("submit").submit();
    }
})

$(document).on('click', '#btn-searchPerson', function (){
    let name_search = $("#name_search").val();
    let email_search = $("#email_search").val();
    let phone_number_search = $("#phone_number_search").val();
    let regex_name = new RegExp('.*' + name_search + '.*', 'i');
    let regex_email = new RegExp('.*' + email_search + '.*', 'i');
    let regex_phone_number = new RegExp('.*' + phone_number_search + '.*', 'i');
    if(name_search !== '' || email_search !== '' || phone_number_search !== ''){
        searchPerson = filterPerson.filter(function (obj){
            return regex_name.test(obj.name)  && regex_email.test(obj.email) && regex_phone_number.test(obj.phone_number);
        })
        fillPeople(searchPerson, 'search');
    }else{
        if(searchPerson.length !== filterPerson.length)
            searchPerson = filterPerson;
    }
});

$(document).on('click', '#btn-resetSearch', function (){
    $("#searchForm input").each(function() {
        $(this).val('');
    })
    searchPerson = [];
    if(choosePerson.length === 0){
        fillPeople(filterPerson, 'base');
    }else{
        fillPeople(filterPerson, 'reset');
    }
});

$(document).on('click', '#removeList', function (){
    choosePerson.forEach(function (obj){
        filterPerson.push(obj);
    });
    choosePerson = [];
    fillPeople(filterPerson, 'base');
    $("#choosePeople").html('');
});

function removePerson(event){
    if (confirm("Bạn có chắc chắn muốn xóa học viên này khỏi khóa học?")) {
        var course_person = event.target.closest('button').value;
        let form_datas = new FormData();
        let course_id = course_person.split('_')[0];
        let person_id = course_person.split('_')[1];
        form_datas.append('course_id', course_id);
        form_datas.append('person_id', person_id);
        handle(true,'http://laravel.test/course_person/remove', form_datas, handleRemove, event, person_id);
    } else {

    }
}

function handleRemove(res, event, person_id){
    event.target.closest('tr').remove();
    data = data.filter(item => item.id !== parseInt(person_id));
    setTable(data, 'update');
    listPerson = listPerson.filter(function (obj){
        if(obj.id === parseInt(person_id)){
            filterPerson.push(obj);
            return true;
        }
        return true;
    });
    fillPeople(filterPerson, 'reset');
    toastShow('Xóa thành công!', 'Đã xóa thành công người dùng khỏi danh sách học viên của khóa học!');
}

$("#insertPersonCourse").submit(function(event) {
    event.preventDefault();
    if(choosePerson.length !== 0)
        $("#insertPersonCourse").off("submit").submit();
    else
        toastShow('Notice!', 'Hãy thêm học viên vào danh sách mới thêm vào khóa học được!');
});
