<template>
  <div style="height: 100%;">
    <video ref="myVideo" autoplay controls width="100%" height="100%" src="" id="videoElement"></video>
    <div v-show="linkIsErr" class="errMessage"> 当前链接无效 </div>
  </div>
</template>

<script>
import flvjs from 'flv.js'
import axios from 'axios'
export default {
  name: 'videoPlay',
  props: {
    field: Object
  },
  data () {
    return {
      flvPlayer: null,
      linkIsErr: false,
      aliveID: null, // 保活定时器ID
      videoLink: null, // 视频播放链接
      aliveVideoLink: null // 保活链接
    }
  },
  mounted () {
    console.log('field: ', this.field)
    if(this.field.value && typeof(this.field.value)=='string') {
      // console.log(this.field.value);
      this.createVideo(this.field.value);
      // console.log('播放链接');
      return
    }
    if(this.field.videoPlayLink) { // 播放链接
      // console.log(this.field.videoPlayLink);
      this.createVideo(this.field.videoPlayLink);
      return
    }
    if(!this.field.deviceVideoLink) {
      this.$toasted.show('缺少地址 —— deviceVideoLink !', { type: 'error' })
      return
    }
    if(this.field.value && this.field.value.deviceID && this.field.value.channelID) {
      console.log('开启保活')
      this.aliveVideoLink = this.field.deviceVideoLink +
        '/api/v1/devices/channelstream?device=' +
        this.field.value.deviceID+'&channel=' +
        this.field.value.channelID + '&protocol=FLV'
      this.keepAlive(this.aliveVideoLink)
    } else {
      this.linkIsErr = true
    }
  },
  methods: {
    keepAlive(liveLink) {
      this.getLiveVideos(liveLink)
      this.aliveID = setInterval(() => {
        this.getLiveVideos(liveLink)
      }, 45000);
    },
    createVideo (link) {
      if (flvjs.isSupported()) {
        const videoElement = this.$refs.myVideo;
        this.flvPlayer = flvjs.createPlayer({
          type: 'flv',
          isLive: true,
          hasAudio: false,
          hasVideo: true,
          url: link
        })
        this.flvPlayer.attachMediaElement(videoElement)
        this.flvPlayer.load()
        this.flvPlayer.play()
      }
    },
    destruction() {
      if(this.aliveID) { // 关闭定时器
        clearInterval(this.aliveID)
        this.aliveID = null
      }
      if (this.flvPlayer) { // 销毁对象
        this.flvPlayer.pause();
        this.flvPlayer.destroy();
        this.flvPlayer = null;
      }
    },
    getLiveVideos(liveLink) {
      axios.get(liveLink).then(response => {
          if(response.status != 200) return
          if (!this.videoLink) { // 播放链接是空
            this.videoLink = this.field.deviceVideoLink + response.data.EasyDarwin.Body.URL
            this.createVideo(this.videoLink)
          }
        });
    }
  }
}
</script>

<style>
video:focus {
  outline: -webkit-focus-ring-color auto 0px;
}
.errMessage{
  position: absolute;
  z-index: 100;
  color: #fff;
  top: 45%;
  left: 0;
  font-size: 1.5rem;
  font-weight: 500;
  width: 100%;
  text-align: center;
}
</style>
