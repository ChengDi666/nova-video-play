<template>
  <div style="height: 100%;">
    <div>
      <EasyPlayer
          ref="EasyPlayer"
          :videoUrl="videoUrl"
          live
          :fluent="true"
          :decodeWasm="true"
          :autoplay="true"
      ></EasyPlayer>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import EasyPlayer from '@easydarwin/easyplayer'
export default {
  name: 'easyPlay',
  props: {
    field: Object,
    isautoplay: {
      type: Boolean,
      default: true
    }
  },
  components: { EasyPlayer },
  data () {
    return {
      flvPlayer: null,
      linkIsErr: false,
      videoUrl: '',
      aliveID: null, // 保活定时器ID
      videoLink: null, // 视频播放链接
      aliveVideoLink: null // 保活链接
    }
  },
  mounted () {
    if (!this.isautoplay) return;
    console.log('field: ', this.field)
    if (this.field.playType == "playback") {
      console.log('回放')
      return;
    }
    if(this.field.value && typeof(this.field.value)=='string') {
      if (!this.field.value.indexOf('http')) { // 完整链接
        this.getVidoeLink(url, true)
        return
      }
      this.getVidoeLink(JSON.parse(this.field.value))
      return
    }
  },
  methods: {
    getVidoeLink(url, isLink) {
      let apiUrl = '/api/getVideoLink?deviceId='
      if (isLink) {
        const id = url.split('=')[1]
        apiUrl += id
      } else {
        apiUrl = `https://live.ljfl.ltd/api/v1/devices/channelstream?device=${url.deviceID}&channel=${url.channelID}&protocol=FLV`
      }
      axios.get(apiUrl).then(res => {
        if (isLink) {
          if (res.data.data[0].status === 'offline') {
            this.$toasted.show('当前设备 - ' + res.data.data[0].name + ' 不在线', { type: 'error' })
            this.destruction()
            return
          }
          const url = res.data.data[0].streamUrl
          this.videoUrl = url
        } else {
          if (res.data.EasyDarwin.Header.ErrorNum !== '200') {
            this.$toasted.show(res.data.EasyDarwin.Header.ErrorString, { type: 'error' })
            console.log(res.data.EasyDarwin.Header.ErrorString)
            return
          }
          const url = 'https://live.ljfl.ltd' + res.data.EasyDarwin.Body.URL
          this.videoUrl = url
          this.aliveVideoLink = apiUrl
          this.keepAlive(apiUrl)
        }
      }).catch(err => {
        // console.log(err.request.responseText)
        if (!err.request.responseText) {
          this.$toasted.show('当前设备连接失败', { type: 'error' })
          return
        }
        this.$toasted.show(err.request.responseText, { type: 'error' })
      })
    },
    playVideo(url) {
      this.videoUrl = url
    },
    keepAlive(liveLink) { // 保活
      this.getLiveVideos(liveLink)
      this.aliveID = setInterval(() => {
        this.getLiveVideos(liveLink)
      }, 30000);
    },
    destruction() {
      if(this.aliveID) { // 关闭定时器
        clearInterval(this.aliveID)
        this.aliveID = null
      }
      this.videoUrl = ''
    },
    getLiveVideos(liveLink) {
      console.log(this.aliveVideoLink);
      axios.get(liveLink).then(response => {
        // console.log(response)
      }, err => {
        // console.log(err.request);
        if (!err.request.responseText) {
          this.$toasted.show('当前设备连接失败', { type: 'error' })
          return
        }
        this.$toasted.show(err.request.responseText, { type: 'error' })
      })
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
