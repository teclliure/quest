$(function() {
    $("body").on("submit", "#saveQuestionForm", function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        // $(this).children('button').button('loading');

        $.post(
            $("#saveQuestionForm").attr('action'),
            $("#saveQuestionForm").serialize(),
            function(data) {
                $('#questions').html(data);
                makeQuestionsSortable();
            }
        );
    });

    $("body").on("click", "#showQuestionForm", function(event) {
        $(this).children('i').toggleClass('icon-chevron-right');
        $(this).children('i').toggleClass('icon-chevron-down');
        $('#new_question_form').slideToggle();
    });

    // Delete question
    $("body").on("click", ".deleteQuestion", function(event) {
        event.preventDefault();
        actionUrl = $(this).attr('href');

        // alert('Hola'.$(this).attr('href'));
        bootbox.confirm(" {{ 'Are you sure do you want to delete question ?' | trans }} ", function(result) {
            if (result) {
                $.ajax({
                    url: actionUrl,
                    success: function(data) {
                        $('#questionsList').html(data);
                        makeQuestionsSortable();
                    }
                });
            }
        });
    });

    $("body").on("submit", ".saveAnswerForm", function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        questionId = $(this).attr('id').replace('questionId','');
        questionId = questionId.replace('saveAnswerForm','');

        $.post(
            $(this).attr('action'),
            $(this).serialize(),
            function(data) {
                $('#questionId' + questionId + 'answers').html(data);
            }
        );
    });

    // Show answer form
    $("body").on("click", ".answerShowForm", function(event) {
        event.preventDefault();

        $(this).children('i').toggleClass('icon-chevron-right');
        $(this).children('i').toggleClass('icon-chevron-down');

        questionId = $(this).attr('id').replace('questionId','');
        questionId = questionId.replace('showForm','');

        if ($.trim($('#questionId' + questionId + 'AnswerForm').html()) == '') {
            $.ajax({
                url: $(this).attr('href'),
                success: function(data) {
                    $('#questionId' + questionId + 'AnswerForm').html(data);
                }
            });
        }
        $('#questionId' + questionId + 'AnswerForm').slideToggle();
    });

    // Delete answer
    $("body").on("click", ".deleteAnswer", function(event) {
        event.preventDefault();
        actionUrl = $(this).attr('href');
        listId = $(this).parents('div .questionAnswersList').attr('id');

        // alert('Hola'.$(this).attr('href'));
        bootbox.confirm("{{ 'Are you sure do you want to delete answer ?' | trans }}", function(result) {
            if (result) {
                $.ajax({
                    url: actionUrl,
                    success: function(data) {
                        $('#'+listId).html(data);
                        makeQuestionsSortable();
                    }
                });
            }
        });
    });

    makeQuestionsSortable();
});

function makeQuestionsSortable() {
    // Sortable question behaviour
    $( "#questionsListSortable" ).sortable({
    // connectWith: ".itemsList",
    placeholder: "ui-state-highlight",
    update: function(event, ui) {
        $.ajax({
            url: basePath + '/admin/questionary/sortQuestion/' + ui.item.attr('id') + '/' + ui.item.index()
        })

    }
    });
    $( "#questionsListSortable" ).disableSelection();
}