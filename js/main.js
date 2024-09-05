import $  from 'jquery';

// import { lottie } from './lottie';

import { header } from './template-parts/header/header';

//animations
import { scrollToAnchor,scrollToHash, requestQuoteLink } from './animations/scroll-to-anchor';
import { appearence } from './animations/appearence';

//blocks
import { videoBlock } from './template-parts/blocks/video';
import { testimonialsSlider } from './template-parts/blocks/testimonials-slider';
import { imagesSlider } from './template-parts/blocks/images-slider';
import { partnersCarousel } from './template-parts/blocks/partners-carousel';
import { eventTimeToStart } from './template-parts/blocks/event-time-to-start';
// import { tabs } from './parts/tabs';

// API
import { hotelGallery } from './template-parts/api/hotel-gallery';
import { hotelSimilarList } from './template-parts/api/hotel-similar-list';
import { hotelRoomsList , hotelRoomsListSearch , hotelBedbanksRoomsListSearch , hotelRoomQttSelect , roomsSelectFormValidation} from './template-parts/api/hotel-rooms-list';
import { hotelsListGallery , hotelsListSearch} from './template-parts/api/hotels-list';
import { basketRemoveItem } from './template-parts/api/remove-items';
import { apiForm , extrasCard , bookDatesFormValidation ,  reserveAccomForm, summaryButton } from './parts/api-form';
import { orderFlights } from './template-parts/api/order-flights';
import { downloadDoc } from './template-parts/api/download-docs';
import { notifyMeForm } from './template-parts/api/notify-me-form';
import { packageRoomsList , packageReserveForms , packageListSearch } from './template-parts/api/package-hotels';


// Parts
import { initPopups } from './parts/popups';
import { maps } from './parts/maps';
import { basicSliders } from './parts/slider';
import { numericSlider } from './parts/slider-numeric';
import { buttonScrollDown } from './parts/button-scroll-down';
import { tickCounter } from './parts/tick-counter'

import { filters } from './parts/filters';


header();
// lottie();

//animations
appearence();

//blocks
videoBlock();
testimonialsSlider();
partnersCarousel();
eventTimeToStart();
// tabs();

// API
hotelGallery();
hotelSimilarList();

hotelsListGallery();
hotelsListSearch();

hotelRoomsList();
hotelRoomsListSearch();
hotelBedbanksRoomsListSearch();
hotelRoomQttSelect();
roomsSelectFormValidation();

//tours
packageRoomsList();
packageReserveForms();
packageListSearch();



apiForm();
extrasCard();
summaryButton();

//validation forms
bookDatesFormValidation();
reserveAccomForm();
orderFlights();

// Parts
initPopups();
basicSliders();
numericSlider();
buttonScrollDown();
filters();
tickCounter();
basketRemoveItem();
downloadDoc();
notifyMeForm();
imagesSlider();

$(document).ready(function () {
    maps();
});
