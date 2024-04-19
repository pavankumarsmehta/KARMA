import PhotoSwipeLightbox from 'https://unpkg.com/photoswipe/dist/photoswipe-lightbox.esm.js';

const lightbox = new PhotoSwipeLightbox({
  gallery:'.my-gallery',
  children: 'a',
  scaleMode: 'fit',  
  mouseMovePan: true,
  initialZoomLevel: 1,
  secondaryZoomLevel: 'fit',
  maxZoomLevel: 4,
  pswpModule: () => import('https://unpkg.com/photoswipe')
});
lightbox.init();



