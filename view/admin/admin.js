// Prevent bootstrap dialog from blocking focusin
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});

// Load tinymce for textareas
$('textarea.tinymce').tinymce({
	theme: 'modern',
	height: 500,
    plugins: [ 'autolink lists link image charmap hr searchreplace wordcount',
               'visualblocks visualchars code fullscreen media nonbreaking',
               'contextmenu paste'
    ],
    image_advtab: true
});

$('.datetimepicker').datetimepicker({
	pickSeconds: false
});

$('.entry-delete').click(function () {
	$('#entry_id').val($(this).data('id'));
	$('#modal-entry-delete').modal('show');
});

$('#modal-entry-delete .btn-primary').click(function () {
	$('#entry-delete').submit();
});