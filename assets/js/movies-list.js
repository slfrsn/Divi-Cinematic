jQuery(document).ready(function($){
	$('a.editinline').live('click', function() {
		var id = inlineEditPost.getId(this);
		var start_date = $('#post-' + id + ' > .column-start_date > span').data('meta');
		var end_date = $('#post-' + id + ' > .column-end_date > span').data('meta');
		var showtime_override = $('#post-' + id + ' > .column-showtime_override > span').data('meta');
		var listing_label = $('#post-' + id + ' > .column-listing_label > span').data('meta');
		var listing_type = $('#post-' + id + ' > .column-listing_type > span').data('meta');
		var showtimes = $('#post-' + id + ' > .column-showtimes > span').data('meta');
		var notes = $('#post-' + id + ' > .column-notes > span').data('meta');
		$('input[name=start_date]').val(start_date);
		$('input[name=end_date]').val(end_date);
		$('input[name=showtime_override]').val(showtime_override);
		$('input[name=listing_label]').val(listing_label);
		$("input[name=listing_type][value=" + listing_type + "]").attr('checked', 'checked');
		$('textarea[name=showtimes]').val(showtimes);
		$('textarea[name=notes]').val(notes);
	});

	// Hide the 'Screen Options' checkboxes so the invisible columns remain fully hidden
	$('.metabox-prefs label:contains("Showtime Override")').hide();
	$('.metabox-prefs label:contains("Special Listing Label")').hide();
	$('.metabox-prefs label:contains("Special Listing Type")').hide();
	$('.metabox-prefs label:contains("Notes")').hide();

	// 'Copy to Clipboard' for trailer address
	$('.copy_to_clipboard').live('click', function() {
		var input = $(this).parent().find('input'),
				succeeded;
		input.select();
		try {
			succeeded = document.execCommand("copy");
		} catch (e) {
			succeeded = false;
		}
		if (succeeded) {
			$(this).attr('title','Successfully copied to clipboard').delay(3000).queue(function(){
				$(this).attr('title','Copy to clipboard')
			});
			$(this).find('span').removeClass('dashicons-admin-links').addClass('dashicons-yes').delay(3000).queue(function(){
				$(this).removeClass('dashicons-yes').addClass('dashicons-admin-links');
			});
		} else {
			$(this).addClass('disabled').attr('title','Error copying to clipboard');
			$(this).find('span').removeClass('dashicons-admin-links').addClass('dashicons-warning');
		}
	});
});
