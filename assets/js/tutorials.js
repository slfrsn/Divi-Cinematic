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
	          element:	'#movie_search',
	          intro: 	 	'Enter the title of the movie here.<br><br><strong>Note:</strong> You can treat this like a Google search. If a movie has been re-made, for example, you can add the year of the remake (e.g. "vacation 2015").',
						position: 'bottom-right-aligned'
	        },
	        {
	          element: 	'#load_movie',
	          intro: 		'Click <strong>Search</strong>. The page should reload with everything filled out correctly.<br><br><strong>Note:</strong> The API status light gives us a way to see if the <strong>Search</strong> feature is working. If the light is amber or red, you might be on your own.',
						position: 'left'
	        },
	        {
	          element:  '#movie_schedule',
	          intro: 	  'Enter the movie schedule details.',
						position: 'right'
	        },
	        {
	          element:  '#movie_details',
	          intro: 	  'Check the movie details and fill in anything missing or inaccurate.<br><br><strong>Note:</strong> If <strong>Search</strong> failed to assign a film rating, you can check for one by clicking <strong>Confirm Film Rating</strong>.',
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
	          intro: 	  'If <strong>Search</strong> failed to assign a movie poster, click <strong>Set Featured Image</strong> to add one manually. The preferred poster dimensions are <strong>600 x 900</strong>.',
						position: 'left'
	        },
	        {
	          element:  '#genrediv',
	          intro: 	  'If <strong>Search</strong> failed to assign the genres, select them manually here.',
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
	}
});
