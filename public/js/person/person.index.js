var data;
var listData;

$(document).ready(function(){
    $(".btn-ViewCourse").click(function(){
        let person_id = $(this).val();
        data = course_person[person_id];
        setTable(data, 'index');
    })
})
