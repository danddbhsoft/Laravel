$("#insertCourse").submit(function(event) {
    event.preventDefault();
    var check = 0;
    let name = $("#name").val();
    let description = $("#description").val();
    let start_time = new Date($("#start_time").val());
    let end_time = new Date($("#end_time").val());
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
    if(start_time != '' && start_time >= now){
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
        $("#insertCourse").off("submit").submit();
    }
})
