$(function() {
    $("body").on("click", "#notesContentSH", function () {
      $("#notesContent").slideToggle("slow");
    });

    $("body").on("submit", '#savePatientForm', function(event) {
        /* stop form from submitting normally */
        event.preventDefault();

        // $(this).children('button').button('loading');
        personId = $('#savePatientForm').attr('class').replace('personId','');
        $.post(
            $('#savePatientForm').attr('action'),
            $('#savePatientForm').serialize(),
            function(data) {
                $('#editPatientForm').html(data);

                $('#show').load(basePath + '/reloadPatientContent/' + personId, function() {
                        $('#legendName').html($('#patientName').html());
                    }
                );

            }
        );
    });
});