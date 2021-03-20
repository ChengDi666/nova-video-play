<template>
  <div>
    <span @click="xianshi" style="cursor: pointer;">
      <span v-if="field.indexLabel"> {{field.indexLabel}} </span>
      <svg t="1616226453634" class="icon fill-current" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2180" width="22" height="22"><path d="M768 128a128 128 0 0 1 128 128v512a128 128 0 0 1-128 128H256a128 128 0 0 1-128-128V256a128 128 0 0 1 128-128h512z m0 64H256a64 64 0 0 0-63.84 59.2L192 256v512a64 64 0 0 0 59.2 63.84L256 832h512a64 64 0 0 0 63.84-59.2L832 768V256a64 64 0 0 0-59.2-63.84L768 192z m-347.008 137.088a64 64 0 0 1 25.248 5.216l5.824 2.88 214.016 118.88a64 64 0 0 1 5.056 108.736l-5.056 3.136-214.016 118.88a64 64 0 0 1-94.72-49.44l-0.352-6.496v-237.76a64 64 0 0 1 64-64z m0 64v237.792L635.008 512l-214.016-118.912z" p-id="2181"></path></svg>
    </span>
    <div v-show="show" class="videoBox">
      <div class="videoClear" @click="clearVideo">
        <span>X</span>
      </div>
      <video-play v-if="show" ref="indexVideo" :link="field.value"></video-play>
    </div>
    <div v-show="show" style="width: 100%;height: 100%; background: rgba(0, 0, 0, 0.5);position: fixed;top: 0;left: 0;z-index: 99"></div>
  </div>
</template>

<script>
import videoPlay from './videoPlay'

export default {
    props: ['resourceName', 'field'],
    name: 'indexField',
    components: {videoPlay},
    data() {
      return {
        show: false
      }
    },
    mounted () {
      console.log(this.field);
    },
    methods: {
      xianshi() {
        this.show = true
        // console.log('显示播放');
      },
      clearVideo () {
        // console.log('关闭视频');
        this.$refs.indexVideo.destruction()
        this.show = false
      }
    }
}
</script>

<style>
.videoBox{
  width: 80%;
  height: 80%;
  background: #000;
  position: fixed;
  top: 10%;
  left: 10%;
  z-index: 100;
  text-align: center;
}
.videoClear{
  position: absolute;
  top: 2rem;
  right: 2rem;
  cursor: pointer;
  z-index: 100;
}
.videoClear span{
  color: #fff;
  display: block;
  font-size: 1.3rem;
  width: 2rem;
  height: 2rem;
  line-height: 2rem;
  border-radius: 3rem;
  border: 1px solid #fff;
}
</style>
