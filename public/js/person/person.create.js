var content = document.getElementById("file-upload-preview");
var image = document.getElementById("image");
const acceptedImageTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
content.addEventListener("click", function() {
    image.click();
})
var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

$("#insertPerson").submit(function(event){
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
        $("#error-image").removeClass('hide');
        if (!$("#success-image").hasClass("hide")) {
            $("#success-image").addClass('hide');
        }
    }
    if(check==5)    $("#insertPerson").off("submit").submit();
})
