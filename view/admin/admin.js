// Prevent bootstrap dialog from blocking focusin
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});

// Load tinymce for textareas
$('textarea.tinymce').tinymce({
	theme: 'modern',
	height: 500
});
