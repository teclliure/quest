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

    $('textarea.wysiwyg').css('height','300px').css('width', '350px');
    $('textarea.wysiwyg').wysihtml5({
        "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
        "emphasis": true, //Italics, bold, etc. Default true
        "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
        "html": false //Button which allows you to edit the generated HTML. Default false
    });


});