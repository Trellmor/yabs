/*
 * TinyMCE
 */

// Prevent bootstrap dialog from blocking focusin
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});

$('textarea.tinymce').tinymce({
	theme: 'modern',
	height: 500,
    plugins: [ 'autolink lists link image charmap hr searchreplace wordcount',
               'visualblocks visualchars code fullscreen media nonbreaking',
               'contextmenu paste'
    ],
    image_advtab: true
});

/*
 * Bootstrap DateTimePicker
 */
$('.datetimepicker').datetimepicker({
	pickSeconds: false
});

/*
 * Entries 
 */
$('.entry-delete').click(function () {
	$('#entry_id').val($(this).data('id'));
	$('#modal-entry-delete').modal('show');
});

$('#modal-entry-delete .btn-primary').click(function () {
	$('#entry-delete').submit();
});
    
/*
 * Categories
 */
$('.category_delete').click(function () {
	$('#category_id').val($(this).data('id'));
	$('#modal-category-delete').modal('show');
});

$('#modal-category-delete .btn-primary').click(function () {
	$('#category-delete').submit();
});

/*
 * Comments
 */
$('.comment-ip').tooltip();

$('.comment-toggle-spam').click(function () {
	var btn = $(this);
	btn.removeClass('color-success').removeClass('color-danger');
	btn.attr('disabled', 'disabled');
		
	var uri = $('body').data('base-uri') + 'admin/api/comment/spam/';
	var data = {
			comment_id: btn.data('id'),
			csrf_token: $('body').data('csrf')
	};	
	$.post(uri, data, function(data) {
		var status = data.status;		
		if (data.status === 'success') {
			$('span', btn).removeClass('glyphicon-fire').removeClass('glyphicon-ok-circle');
			if (data.comment_spam === true) {
				btn.addClass('color-danger');
				$('span', btn).addClass('glyphicon-fire');
			} else {
				btn.addClass('color-success');
				$('span', btn).addClass('glyphicon-ok-circle');
			}
		} else {
			status = 'danger';
		}
		generateAlert(data.message, status);
	}).always(function() {
		btn.removeAttr('disabled', 'disabled').blur();
	});
});

$('.comment-toggle-visible').click(function () {
	var btn = $(this);
	btn.removeClass('color-success').removeClass('color-danger');
	btn.attr('disabled', 'disabled');
		
	var uri = $('body').data('base-uri') + 'admin/api/comment/visible/';
	var data = {
			comment_id: btn.data('id'),
			csrf_token: $('body').data('csrf')
	};	
	$.post(uri, data, function(data) {
		var status = data.status;		
		if (data.status === 'success') {
			$('span', btn).removeClass('glyphicon-eye-open').removeClass('glyphicon-eye-close');
			if (data.comment_visible === true) {
				btn.addClass('color-success');
				$('span', btn).addClass('glyphicon-eye-open');
			} else {
				btn.addClass('color-danger');
				$('span', btn).addClass('glyphicon-eye-close');
			}
		} else {
			status = 'danger';
		}
		generateAlert(data.message, status);
	}).always(function() {
		btn.removeAttr('disabled', 'disabled').blur();
	});
});

$('.comment_delete').click(function () {
	$('#comment_id').val($(this).data('id'));
	$('#modal-comment-delete').modal('show');
});

$('#modal-comment-delete .btn-primary').click(function () {
	$('#comment-delete').submit();
});

/*
 * Various helper functions
 */
function generateAlert(message, level) {
	var alert = [
		'<div class="alert alert-' + level + ' alert-dismissable">',
		'  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>',
		'  ' + message,
		'</div>'
	].join('');
	$('h1').before(alert);
}