var content = document.getElementById("file-upload-preview");
var image = document.getElementById("image");
const acceptedImageTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
content.addEventListener("click", function() {
    image.click();
})
var data;
var listCourse;
var filterCourse;

$("#btn-updateCourse").click(function(event){
    if(data==undefined && listCourse ==undefined) {
        var formClass = event.target.id;
        let form_datas = new FormData();
        form_datas.append('id', $(this).val());
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        handle(false,'http://laravel.test/person/api', form_datas, viewCourse, 'update');
        handleGet('http://laravel.test/course/get', getCourse);
    }
})

function getCourse(res){
    listCourse = JSON.parse(res);
    filterCourse = listCourse.filter(item => !data.some(obj => obj.id === item.id));
    fillCourse();
}
function fillCourse(){
    let html = '<option selected disabled value="">Choose...</option>';
    for(let i = 0; i < filterCourse.length; i++){
        html += `<option value="${filterCourse[i].id}">${filterCourse[i].name}, ${filterCourse[i].start_time}, ${filterCourse[i].end_time}</option>`;
    }
    $("#course").html(html);
}
$("#updatePerson").submit(function(event){
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    event.preventDefault();
    let name = $("#name").val();
    let email = $("#email").val();
    let birthday = $("#birthday").val();
    let phone_number = $("#phone_number").val();
    let file = image.files[0];
    var filter_email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var filter_string = /^[\p{L}\s.]+$/u;
    var filter_phone_number = /^0[1-9]\d{8}$/;
    const acceptedImageTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/bmp', 'image/svg', 'image/webp'];
    var check = 0;
    if(email == "" || !filter_email.test(email)){
        isInvalid('email');
        $("#email_error").text("Email không được trống và phải đúng định dạng.");
        changeInput('email');
    }else{
        if(old_email == email)  check++;
        else {
            let form_datas = new FormData();
            form_datas.append('email', email);
            $.ajax({
                async: false,
                url: 'http://laravel.test/person/mail',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_datas,
                type: 'post',
                success: function (res) {
                    if (res == 1) {
                        check++;
                    } else {
                        isInvalid("email");
                        $("#email_error").text("Email đã tồn tại trong hệ thống rồi.");
                        changeInput("email");
                    }
                }
            });
        }
    }
    if(name == "" || !filter_string.test(name)){
        isInvalid('name');
        $("#name_error").text("Họ và tên không được trống và chỉ chứa ký tự chữ cái.");
        changeInput('name');
    }else   check++;
    if(phone_number == "" || !filter_phone_number.test(phone_number)){
        isInvalid('phone_number');
        $("#phone_error").text("Số điện thoại không được để trống và phải nhập đúng định dạng.");
        changeInput('phone_number');
    }else   check++;
    if(birthday == "" || new Date(birthday) >= new Date()){
        isInvalid('birthday');
        $("#birthday_error").text("Ngày sinh không được để trống và không được vượt quá hiện tại.");
        changeInput('birthday');
    }else   check++;
    if(file != null) {
        if (!acceptedImageTypes.includes(file.type) || file.size > 10 * 1024 * 1024) {
            $("#error-image").removeClass('hide');
            if (!$("#success-image").hasClass("hide")) {
                $("#success-image").addClass('hide');
            }
        }else{
            $("#error-image").addClass('hide');
            if ($("#success-image").hasClass("hide")) {
                $("#success-image").removeClass('hide');
            }
            check++;
        }
    }else{
        check++;
    }
    if(check==5)    $("#updatePerson").off("submit").submit();
})

function removeCourse(event){
    if (confirm("Bạn có chắc chắn muốn xóa khóa học này khỏi người dùng?")) {
        var course_person = event.target.closest('button').value;
        let form_datas = new FormData();
        let course_id = course_person.split('_')[0];
        let person_id = course_person.split('_')[1];
        form_datas.append('course_id', course_id);
        form_datas.append('person_id', person_id);
        handle(true,'http://laravel.test/course_person/remove', form_datas, handleRemove, event, course_id);
    } else {

    }
}

function handleRemove(res, event, course_id){
    event.target.closest('tr').remove();
    data = data.filter(item => item.id !== parseInt(course_id));
    setTable(data, 'update');
    filterCourse = listCourse.filter(item => !data.some(obj => obj.id === item.id));
    fillCourse();
    toastShow('Xóa thành công!', 'Đã xóa thành công khóa học khỏi danh sách khóa học của người dùng!');
}

$("#insertCoursePerson").click(function(){
    let course_id = $("#course").val();
    if(course_id != null) {
        let form_datas = new FormData();
        form_datas.append('course_id', course_id);
        form_datas.append('person_id', id);
        handle(true,'http://laravel.test/course_person/store', form_datas, updateInsertCoursePerson)
    }
})

function updateInsertCoursePerson(res){
    let new_course = JSON.parse(res);
    data.push(new_course);
    data.sort((a, b) => a.id - b.id);
    setTable(data, 'update');
    filterCourse = listCourse.filter(item => !data.some(obj => obj.id === item.id));
    fillCourse();
    toastShow('Thêm thành công!', 'Đã thêm thành công khóa học vào danh sách khóa học của người dùng!');
}
