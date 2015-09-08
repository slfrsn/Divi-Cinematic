jQuery(document).ready(function($){
	// Use Divi's 'magnific' to popup the movie details
	$details = $('.details_popup');
	if ($details.length) {
		$details.magnificPopup({
			type:'inline',
			midClick: true,
			gallery: {
				enabled: true,
				navigateByImgClick: true
			}
		});
	}
});
