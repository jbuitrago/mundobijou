
$(function() {

    //<input type="radio" name="group1" value="page1.php">
    $("input[@name='group1']").click(function() {
        $("#my_form").attr("action", $(this).val());
    });

});
