$(function() {
    $('.date').datepicker({ dateFormat: 'dd-mm-yy' });
    $("a.confirmDialog").click(function(e) {
        e.preventDefault();
        urlDelete = $(this).attr('href')
        bootbox.confirm("Are you sure ?", function(confirmed) {
            if (confirmed == true) {
                window.location = urlDelete;
            }
        });
    });
});