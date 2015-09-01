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
	          element:  '#movie_schedule',
	          intro: 	  'Enter the movie schedule details.<br><br><strong>Note:</strong> If you need to add a second showtime to the matinees, enter it like this:<br><strong>2:00 PM, 4:00PM</strong><br><br>If you need to specify that a showing is in 3D, enter it like this:<br><strong>6:30 PM (3D)</strong>',
						position: 'right'
	        },
	        {
	          element: 	'.fetch-details',
	          intro: 		'Click this button to fetch the movie details. The page <em>should</em> reload with everything filled out correctly.<br><br><strong>Note:</strong> This feature depends on Google to work and it can be... <em>unpredictable</em>. If it isn\'t working for a movie, you\'ll have to search for the information on Google and enter it manually.<br><br>The API status light gives us a way to see if the source for <strong>Fetch Movie Details</strong> is online. If the light is amber or red, you might be on your own.',
						position: 'left'
	        },
	        {
	          element:  '#movie_details',
	          intro: 	  'Check the movie details and fill in anything missing or inaccurate.<br><br><strong>Note:</strong> The rating and rating description will likely never be filled in because the American film ratings don\'t line up with Canada\'s. Always check to make sure the ratings used are the proper Canadian ratings.',
						position: 'right'
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
	          element:  'label[for="trailer_confirm"]',
	          intro: 	  'Check this box again.<br><br><strong>Note:</strong> If you\'re cloning a movie there\'s no need to actually confirm if the trailer and website are correct. That should have been done when the movie listing was created the first time. Instead, just click the checkbox and move on.',
						position: 'bottom'
	        },
	        {
	          element:  'label[for="website_confirm"]',
	          intro: 	  'Check this box again.<br><br><strong>Note:</strong> If you\'re cloning a movie there\'s no need to actually confirm if the trailer and website are correct. That should have been done when the movie listing was created the first time. Instead, just click the checkbox and move on.',
						position: 'bottom'
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
		var button = '<button class="page-title-action" id="tutorial" style="margin-left:4px;background:' + color + ';color:#fff;cursor:pointer;">How to Clone a Movie</button>';
		// Insert our tutorial button at the top of the page
		$(button).insertAfter(target);

		$('#tutorial').click(function(e) {
			// Make the first set of row actions visible so we can target them
			$('.row-actions').first().css('visibility','visible');

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
				$('.row-actions').first().css('visibility','');
			}).onexit(function() {
				// Hide the row actions so they behave normally after the tutorial has exited
				$('.row-actions').first().css('visibility','');
			});

		});
	}

	// Introduction tutorial for the Newsletters index page
	if($('body').hasClass('newsletter_page_newsletter_emails_index')) {
		var target = 'input[value="Trigger the delivery engine"]';
		var button = '<input class="button-primary" type="button" value="Tutorial" id="tutorial" style="margin-left:4px"> ';
		// Insert our tutorial button at the top of the page
		$(button).insertAfter(target);

		$('#tutorial').click(function(e) {
	  	var intro = introJs();
	    intro.setOptions({
				showProgress: false,
				showBullets: true,
	      steps: [
	        {
	          element:	'input[value="Copy"]:first-of-type',
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
		var button = '<input class="button-primary" type="button" value="Tutorial" id="tutorial" style="margin-left:4px"> ';
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
