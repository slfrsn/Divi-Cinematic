jQuery(document).ready(function($){
	// Introduction tutorial for the Movies edit page
	if($('body').hasClass('post-type-movies') && ($('body').hasClass('post-php') || $('body').hasClass('post-new-php'))) {
		// Tutorial for creating a movie listing
		$('#new-tutorial').click(function(e) {
	  	var intro = introJs();
	    intro.setOptions({
				showProgress: false,
				showBullets: true,
	      steps: [
	        {
	          element:	'#title',
	          intro: 	 	'Enter the title of the movie here.<br><br><strong>Note:</strong> You can treat this like a Google search. If a movie has been re-made, for example, you can add the year of the remake (e.g. "vacation 2015").',
						position: 'bottom-right-aligned'
	        },
	        {
	          element: 	'.fetch-details',
	          intro: 		'Click <strong>Fetch Movie Details</strong>. The page should reload with everything filled out correctly.<br><br><strong>Note:</strong> The API status light gives us a way to see if the source for <strong>Fetch Movie Details</strong> is online. If the light is amber or red, you might be on your own.',
						position: 'left'
	        },
	        {
	          element:  '#movie_schedule',
	          intro: 	  'Enter the movie schedule details.',
						position: 'right'
	        },
	        {
	          element:  '#movie_details',
	          intro: 	  'Check the movie details and fill in anything missing or inaccurate.<br><br><strong>Note:</strong> If <strong>Fetch Movie Details</strong> failed to assign a film rating, you can check for one by clicking <strong>Confirm Film Rating</strong>.',
						position: 'right'
	        },
	        {
	          element:  '#advisorydiv',
	          intro: 	  'Select any applicable film advisories here.',
						position: 'left'
	        },
	        {
	          element:  '#movie_links',
	          intro: 	  'When the movie details have been fetched, this section will have two boxes allowing you to preview the trailer and website links.<br><br>Check the YouTube and website links to make sure they\'re accurate.<br><br><strong>Note:</strong> If the checkboxes are left unchecked, the movie listing will <strong>not</strong> have a website or trailer attached.',
						position: 'right'
	        },
	        {
	          element:  '#postimagediv',
	          intro: 	  'If <strong>Fetch Movie Details</strong> failed to assign a movie poster, click <strong>Upload Poster Image</strong> to add one manually. The preferred poster dimensions are <strong>600 x 900</strong>.',
						position: 'left'
	        },
	        {
	          element:  '#genrediv',
	          intro: 	  'If <strong>Fetch Movie Details</strong> failed to assign the genres, select them manually here.',
						position: 'left'
	        },
	        {
	          element:  '#featurediv',
	          intro: 	  'If the movie is showing in 3D, select it here.',
						position: 'left'
	        },
	        {
	          element:  '#post-preview',
	          intro: 	  'Click this button to preview the movie listing before making it live.',
						position: 'left'
	        },
	        {
	          element:  '#publish',
	          intro: 	  'Click this button to publish the movie listing.',
						position: 'left'
	        }
	      ]
	    });
	    intro.start();
		});
		// Tutorial for editing a cloned listing
		$('#clone-tutorial').click(function(e) {
	  	var intro = introJs();
	    intro.setOptions({
				showProgress: false,
				showBullets: true,
	      steps: [
	        {
	          element:	'input[name=start_date]',
	          intro: 	 	'Correct the start date.',
						position: 'right'
	        },
	        {
	          element:	'input[name=end_date]',
	          intro: 	 	'Correct the end date.',
						position: 'right'
	        },
	        {
	          element:  '#publish',
	          intro: 	  'Click this button to publish the movie listing.',
						position: 'left'
	        }
	      ]
	    });
	    intro.start();
		});
	}

	// Introduction tutorial on the 'All Movies' page for cloning a movie
	if($('body').hasClass('post-type-movies') && $('body').hasClass('edit-php') && $('.clone').length) {
		var target = '.page-title-action';
		var color = $('.button-primary').css('background');
		var button = '<a href="#" class="page-title-action" id="tutorial">How to Clone a Movie</a>';
		// Insert our tutorial button at the top of the page
		$(button).insertAfter(target);

		$('#tutorial').click(function(e) {
			e.preventDefault();
			// Make the first set of row actions visible so we can target them
			$('.row-actions').first().css('left','0');

	  	var intro = introJs();
	    intro.setOptions({
				showProgress: false,
				showBullets: true,
	      steps: [
	        {
	          element:	'.row-actions',
	          intro: 	 	'Click <strong>Clone</strong> to duplicate the movie listing. The page will reload and you\'ll see a new movie listing with <strong>- Draft</strong> beside it.',
						position: 'right'
	        },
	        {
	          element:	'.row-actions',
	          intro: 	 	'Click <strong>Edit</strong> on the newly created movie listing to open it for editing.<br><br>After clicking <strong>Edit</strong>, click the <strong>How to Edit a Cloned Movie</strong> button on the following page to continue this tutorial.',
						position: 'bottom-left-aligned'
	        }

	      ]
	    });
	    intro.start().oncomplete(function() {
				// Hide the row actions so they behave normally after the tutorial is complete
				$('.row-actions').first().css('left','-9999em');
			}).onexit(function() {
				// Hide the row actions so they behave normally after the tutorial has exited
				$('.row-actions').first().css('left','-9999em');
			});

		});
	}

	// Introduction tutorial for the Newsletters index page
	if($('body').hasClass('newsletter_page_newsletter_emails_index')) {
		var target = 'input[value="Trigger the delivery engine"]';
		var button = '<input class="button-secondary" type="button" value="Tutorial" id="tutorial" style="margin-left:4px"> ';
		// Insert our tutorial button at the top of the page
		$(button).insertAfter(target);

		$('#tutorial').click(function(e) {
	  	var intro = introJs();
	    intro.setOptions({
				showProgress: false,
				showBullets: true,
	      steps: [
	        {
	          element:	'.widefat tbody tr:first-of-type td:nth-child(10) button',
	          intro: 	 	'Click <strong>Copy</strong> on the most recent newsletter to duplicate it.',
						position: 'left'
	        },
	        {
	          element:  'a[href*="newsletter_emails_edit"]:first-of-type',
	          intro: 	  'Click <strong>Edit</strong> to open the newsletter for editing.<br><br>After clicking <strong>Edit</strong>, click the <strong>Tutorial</strong> button on the following page to continue this tutorial.',
						position: 'right'
	        }
	      ]
	    });
	    intro.start();
		});
	}

	// Introduction tutorial for the Newsletter edit page
	if($('body').hasClass('admin_page_newsletter_emails_edit')) {
		var target = '.submit input[type="button"]:last-of-type';
		var button = '<input class="button-secondary" type="button" value="Tutorial" id="tutorial" style="margin-left:4px"> ';
		// Insert our tutorial button at the top of the page
		$(button).insertAfter(target);

		$('#tutorial').click(function(e) {
	  	var intro = introJs();
	    intro.setOptions({
				showProgress: false,
				showBullets: true,
	      steps: [
	        {
	          element:	'#tabs-a input[type="text"]:first-of-type',
	          intro: 	 	'Change the dates in the email subject field.',
						position: 'bottom'
	        },
	        {
	          element:  '.mceIframeContainer',
	          intro: 	  'Change the dates in the heading and copy the showtimes into the body of the email.<br><br><strong>Pro-Tip:</strong> Using a plain-text editor like Notepad will allow you to copy and paste without changing the way the text looks in the email.',
						position: 'top'
	        },
	        {
	          element:	'input[value="Send"]',
	          intro: 	 	'Click <strong>Send</strong> to save and send the newsletter to subscribers.',
						position: 'bottom-right-aligned'
	        }
	      ]
	    });
	    intro.start();
		});
	}
});
