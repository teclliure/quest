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
    $('textarea.wysiwyg').wysiwyg({
        rmUnusedControls: true,
        rmUnwantedBr: true,
        controls: {
            bold: { visible : true },
            italic: { visible : true },
            h1: { visible : true },
            h2: { visible : true },
            h3: { visible : true },
            html: { visible : true },
            insertOrderedList: { visible : true },
            removeFormat: { visible : true }
        }
    });


});