$(document).ready(function(){
    $(".btn-ViewPeople").click(function(){
        let link = $(this).val().split('!');
        let course_id = link[1];
        let url = link[0];
        data = course_person[course_id];
        setTable(data, 'index');
        $('#link-export-person').attr('href', url);
    })
})
