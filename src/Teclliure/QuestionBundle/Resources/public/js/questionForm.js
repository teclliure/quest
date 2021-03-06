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

    $("body").on("click", ".editQuestion", function(event) {
        event.preventDefault();

        if ($('#showQuestionForm').children('i').hasClass('icon-chevron-right')) {
            $('#showQuestionForm').children('i').removeClass('icon-chevron-right');
            $('#showQuestionForm').children('i').addClass('icon-chevron-down');
            $('#new_question_form').slideDown();
        }

        actionUrl = $(this).attr('href');

        $.ajax({
            url: actionUrl,
            success: function(data) {
                $('#new_question_form').html(data);
                $('html, body').animate({scrollTop: $('#alerts').offset().top-5}, 1000);
            }
        });
    });



    $("body").on("click", ".answersListShow", function(event) {
        questionId = $(this).attr('id').replace('questionId','').replace('answersListShow','')
        $(this).children('i').toggleClass('icon-chevron-right');
        $(this).children('i').toggleClass('icon-chevron-down');
        $('#questionId' + questionId + 'answersListUl').slideToggle();
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
                makeAnswersSortable();
            }
        );
    });

    // Show answer form
    $("body").on("click", ".answerShowForm", function(event) {
        event.preventDefault();

        questionShowButtonClass = $(this).attr('id');

        questionId = $(this).attr('id').replace('questionId','');
        questionId = questionId.replace('showForm','');

        if ($.trim($('#questionId' + questionId + 'AnswerForm').html()) == '' || $(this).hasClass('editAnswer')) {
            $.ajax({
                url: $(this).attr('href'),
                success: function(data) {
                    $('#questionId' + questionId + 'AnswerForm').html(data);
                }
            });
        }

        if ($(this).hasClass('editAnswer')) {
            if ($('.' + questionShowButtonClass).children('i').hasClass('icon-chevron-right')) {
                $('.' + questionShowButtonClass).children('i').removeClass('icon-chevron-right');
                $('.' + questionShowButtonClass).children('i').addClass('icon-chevron-down');
                $('#questionId' + questionId + 'AnswerForm').slideDown();
            }
        }
        else {
            $(this).children('i').toggleClass('icon-chevron-right');
            $(this).children('i').toggleClass('icon-chevron-down');
            $('#questionId' + questionId + 'AnswerForm').slideToggle();
        }
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
                        makeAnswersSortable();
                    }
                });
            }
        });
    });

    makeQuestionsSortable();

    $("body").on("click", "#showValidationForm", function(event) {
        $(this).children('i').toggleClass('icon-chevron-right');
        $(this).children('i').toggleClass('icon-chevron-down');
        $('#new_validation_form').slideToggle();
    });

    $("body").on("submit", "#saveValidationForm", function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        // $(this).children('button').button('loading');

        $.post(
            $("#saveValidationForm").attr('action'),
            $("#saveValidationForm").serialize(),
            function(data) {
                $('#validations').html(data);
                makeValidationsSortable();
            }
        );
    });

    // Delete validation
    $("body").on("click", ".deleteValidation", function(event) {
        event.preventDefault();
        actionUrl = $(this).attr('href');

        // alert('Hola'.$(this).attr('href'));
        bootbox.confirm(" {{ 'Are you sure do you want to delete validation ?' | trans }} ", function(result) {
            if (result) {
                $.ajax({
                    url: actionUrl,
                    success: function(data) {
                        $('#validationsList').html(data);
                        makeValidationsSortable();
                    }
                });
            }
        });
    });

    $("body").on("click", ".editValidation", function(event) {
        event.preventDefault();

        if ($('#showValidationForm').children('i').hasClass('icon-chevron-right')) {
            $('#showValidationForm').children('i').removeClass('icon-chevron-right');
            $('#showValidationForm').children('i').addClass('icon-chevron-down');
            $('#new_validation_form').slideDown();
        }

        actionUrl = $(this).attr('href');

        $.ajax({
            url: actionUrl,
            success: function(data) {
                $('#new_validation_form').html(data);
                $('html, body').animate({scrollTop: $('#alerts').offset().top-5}, 1000);
            }
        });
    });

    // Show answer form
    $("body").on("click", ".ruleShowForm", function(event) {
        event.preventDefault();

        ruleShowButtonClass = $(this).attr('id');

        validationId = $(this).attr('id').replace('validationId','');
        validationId = validationId.replace('showForm','');

        if ($.trim($('#validationId' + validationId + 'RuleForm').html()) == '' || $(this).hasClass('editRule')) {
            $.ajax({
                url: $(this).attr('href'),
                success: function(data) {
                    $('#validationId' + validationId + 'RuleForm').html(data);
                }
            });
        }

        if ($(this).hasClass('editRule')) {
            if ($('.' + ruleShowButtonClass).children('i').hasClass('icon-chevron-right')) {
                $('.' + ruleShowButtonClass).children('i').removeClass('icon-chevron-right');
                $('.' + ruleShowButtonClass).children('i').addClass('icon-chevron-down');
                $('#validationId' + validationId + 'RuleForm').slideDown();
            }
        }
        else {
            $(this).children('i').toggleClass('icon-chevron-right');
            $(this).children('i').toggleClass('icon-chevron-down');

            $('#validationId' + validationId + 'RuleForm').slideToggle();
        }

    });

    $("body").on("submit", ".saveRuleForm", function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        validationId = $(this).attr('id').replace('validationId','');
        validationId = validationId.replace('saveRuleForm','');

        $.post(
            $(this).attr('action'),
            $(this).serialize(),
            function(data) {
                $('#validationId' + validationId + 'rules').html(data);
            }
        );
    });

    // Delete answer
    $("body").on("click", ".deleteRule", function(event) {
        event.preventDefault();
        actionUrl = $(this).attr('href');
        listId = $(this).parents('div .validationRulesList').attr('id');

        // alert('Hola'.$(this).attr('href'));
        bootbox.confirm("{{ 'Are you sure do you want to delete rule ?' | trans }}", function(result) {
            if (result) {
                $.ajax({
                    url: actionUrl,
                    success: function(data) {
                        $('#'+listId).html(data);
                        makeAnswersSortable();
                    }
                });
            }
        });
    });

    makeValidationsSortable();

    // Validation questions
    $("body").on("click", ".selectValidationQuestion", function(event) {
        event.preventDefault();

        actionUrl = $(this).attr('href');

        if (actionUrl != '') {
            validationDivId = $(this).attr('data-target');

            $.ajax({
                url: actionUrl,
                success: function(data) {
                    $(validationDivId).html(data);
                }
            });
            $(this).attr('href','');
        }
    });

    $('.validationQuestionsModal').on('hidden', function () {
        idNumber = $(this).attr('id').replace('validationQuestionsModal','');
        $.ajax({
            url: basePath + '/admin/questionary/validation/getQuestionsNumber/' + idNumber,
            success: function(data) {
                if (data > 0) {
                    $('#validationQuestionsNumber'+idNumber).addClass('badge-success');
                }
                else {
                    $('#validationQuestionsNumber'+idNumber).removeClass('badge-success');
                }
                $('#validationQuestionsNumber'+idNumber).html(data);
            }
        });

    });


    $("body").on("submit", ".validationsQuestionsForm", function(event) {
        event.preventDefault();

        actionUrl = $(this).attr('action');
        validationDivId = $(this).parents('div .validationQuestionsModal').attr('id');

        $.post(
            actionUrl,
            $(this).serialize(),
            function(data) {
                $('#'+validationDivId).html(data);
            }
        );
    });

    // Answer questions
    $("body").on("click", ".selectAnswerQuestion", function(event) {
        event.preventDefault();

        actionUrl = $(this).attr('href');

        if (actionUrl != '') {
            answerDivId = $(this).attr('data-target');

            $.ajax({
                url: actionUrl,
                success: function(data) {
                    $(answerDivId).html(data);
                }
            });
            $(this).attr('href','');
        }
    });

    $('.answerQuestionsModal').on('hidden', function () {
        idNumber = $(this).attr('id').replace('answerQuestionsModal','');
        $.ajax({
            url: basePath + '/admin/questionary/answer/getQuestionsNumber/' + idNumber,
            success: function(data) {
                if (data > 0) {
                    $('#answerQuestionsNumber'+idNumber).addClass('badge-success');
                }
                else {
                    $('#answerQuestionsNumber'+idNumber).removeClass('badge-success');
                }
                $('#answerQuestionsNumber'+idNumber).html(data);
            }
        });

    });


    $("body").on("submit", ".answersQuestionsForm", function(event) {
        event.preventDefault();

        actionUrl = $(this).attr('action');
        answerDivId = $(this).parents('div .answerQuestionsModal').attr('id');

        $.post(
            actionUrl,
            $(this).serialize(),
            function(data) {
                $('#'+answerDivId).html(data);
            }
        );
    });
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
    makeAnswersSortable();
}

function makeAnswersSortable() {
    // Sortable question behaviour
    $( ".answersListUl" ).sortable({
        // connectWith: ".itemsList",
        placeholder: "ui-state-highlight",
        update: function(event, ui) {
            $.ajax({
                url: basePath + '/admin/questionary/sortAnswer/' + ui.item.attr('id') + '/' + ui.item.index()
            })

        }
    });
    $( ".answersListUl" ).disableSelection();
}

function makeValidationsSortable() {
    $( "#validationsListSortable" ).sortable({
        placeholder: "ui-state-highlight",
        update: function(event, ui) {
            $.ajax({
                url: basePath + '/admin/questionary/sortValidation/' + ui.item.attr('id') + '/' + ui.item.index()
            });
        }
    });
    $( "#validationsListSortable" ).disableSelection();
}