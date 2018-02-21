# Divi Cinematic

A WordPress child theme based on [Divi][1]. Commissioned for a local Canadian theatre. It has a robust movie listing post type that automatically retrieves movie details, poster, etc.

### Features
- Automatic movie detail propagation in Movie editor
  - Enter the movie title or keyword, click **Search** and that's it!
    - Runtime, cast, synopsis, YouTube trailer and official website are all fetched
    - Genres are automatically assigned, or created if necessary, in the Genres taxonomy
    - The movie poster is automatically assigned as the Featured Image
- Automatic updates from this GitHub repository
	- Using the native WordPress update system
- Interactive tutorial for creating movie listings (using [Intro.js][2])

### Screenshots
**Layout of the thumbnails on the "Movies" page template**

Beautiful, mobile-friendly layout of movie poster thumbnails. On smaller devices they display in a grid.

![](https://user-images.githubusercontent.com/6977140/33417382-1d387372-d556-11e7-9221-63ad55a904ff.png)

**Movie details when you click on a thumbnail**

The gallery supports linking directly to a movie listing pop-up and generates movie-specific metadata for the Facebook and Twitter sharing APIs.

![](https://user-images.githubusercontent.com/6977140/33417470-6711bc2e-d556-11e7-9b62-d11063ac68a5.png)

**Searching for movie details in the edit screen is super easy**

You can even search for partial keywords and it will display a window of possible matches (e.g. searching 'guardians' will bring up all of the 'Guardians of the Galaxy' movies).

![](https://user-images.githubusercontent.com/6977140/33417735-84387120-d557-11e7-9bb4-bff3d6bcdc62.png)

**Movies details are automatically populated**

All you have to do is add the showtimes and make sure the trailer and website addresses are correct.

![](https://user-images.githubusercontent.com/6977140/33417683-4ebe5776-d557-11e7-9934-415fcbd0b4e2.png)

**Disclaimer:** This theme has been designed with a specific use case in mind. Canadian film ratings are hard coded in, none of the text supports localization, certain assumptions have been made about how this theme will be used, etc. I actually encourage you **not** to use it, for the simple fact that I provide support to my client first, and this repository second. You're more than welcome to use it, but I offer no warranty or support.

**License:** Apache-2.0

<sup>1. [Newsletter][4] plugin is required.</sup>

[1]:	http://www.elegantthemes.com/gallery/divi/ "Divi"
[2]:	http://usablica.github.io/intro.js/ "Intro.js"
[4]:	https://wordpress.org/plugins/newsletter/
