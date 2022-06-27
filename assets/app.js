/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./scss/style.scss";

// start the Stimulus application
import "./bootstrap";

// Node modules
import "bootstrap";

// Pages
import "@assets/js/pages/home";
import "@assets/js/pages/forum";

// Components
import "@assets/js/components/header";
import "@assets/js/components/footer";

// Tiles
// import '@tiles/Articles/New/newarticle';
// import '@tiles/Tools/NewPage/newpage';
// import '@tiles/Tools/NewTile/newtile';

// Shared
import "./js/shared/preload";

// Images
import Favicon from "@assets/svg/icons/favicon.svg";

new Image(Favicon);
