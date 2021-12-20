import { Slider, DatePicker } from 'element-ui';
Nova.booting((Vue, router, store) => {
  Vue.component('index-video-play', require('./components/IndexField'))
  Vue.component('detail-video-play', require('./components/DetailField'))
  Vue.component('form-video-play', require('./components/FormField'))

  Vue.use(Slider);
  Vue.use(DatePicker);
})