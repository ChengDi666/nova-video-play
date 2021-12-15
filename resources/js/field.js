Nova.booting((Vue, router, store) => {
  Vue.component('index-video-play', require('./components/IndexField'))
  Vue.component('detail-video-play', require('./components/DetailField'))
  Vue.component('form-video-play', require('./components/FormField'))
})
const videoPlay = document.createElement('script');
videoPlay.charset = 'utf-8';
videoPlay.src = '/EasyPlayer-lib.min.js';
document.head.appendChild(videoPlay);