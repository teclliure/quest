$(function() {
    $('.tooltiplink').popover({trigger: 'hover', placement: 'top'});

    $("body").on("submit", '#filterQuestionaryNameForm', function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        // $(this).children('button').button('loading');
        $("#searchLoading").fadeIn();
        form = $('#filterQuestionaryNameForm');
        $.post(
            form.attr('action'),
            form.serialize(),
            function(data) {
                $('#selectQuestionary').html(data);
                $("#searchLoading").fadeOut('fast');
            }
        );
    });

    $("body").on("click", '#resetSearch', function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        // $(this).children('button').button('loading');
        $('#form_name').val('');
        $("#searchLoading").fadeIn();

        form = $('#filterQuestionaryNameForm');
        $.post(
            form.attr('action'),
            '',
            function(data) {
                $('#selectQuestionary').html(data);
                $("#searchLoading").fadeOut('fast');
            }
        );
    });
});