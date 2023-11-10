$(document).ready(function () {
    to_nav();
});
function to_nav(){
    $.post("dashboard.php", {}, function (data) {
       $("Contents").html (data); 
        });
}