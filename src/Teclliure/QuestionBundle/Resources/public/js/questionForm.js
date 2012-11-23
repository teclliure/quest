$(function() {
    $("#saveQuestionForm").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        $.post(
            $("#saveQuestionForm").attr('action'),
            $("#saveQuestionForm").serialize(),
            function(data) {
                $('#questions').html(data);
            }
        );
    });


    // Sortable question behaviour
    $( "#questionsList" ).sortable({
        // connectWith: ".itemsList",
        placeholder: "ui-state-highlight"
    });
    $( "#questionsList" ).disableSelection();
});