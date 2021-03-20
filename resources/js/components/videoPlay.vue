<template>
  <div style="height: 100%;">
    <video ref="myVideo" autoplay controls width="100%" height="100%" src="" id="videoElement"></video>
    <div v-show="linkIsErr" class="errMessage">"{{link}}" - 当前链接无效</div>
  </div>
</template>

<script>
import flvjs from 'flv.js'
export default {
  name: 'videoPlay',
  props: {
    src: 'http://1011.hlsplay.aodianyun.com/demo/game.flv',
    link: String
  },
  data () {
    return {
      flvPlayer: null,
      linkIsErr: false
    }
  },
  mounted () {
    if(this.link) {
      this.createVideo(this.link)
    } else {
      this.linkIsErr = true
    }
    // console.log('要播放视频啦');
    // console.log(this.link);
  },
  methods: {
    createVideo (link) {
      console.log(link);
      if (flvjs.isSupported()) {
        let videoElement = this.$refs.myVideo;
        this.flvPlayer = flvjs.createPlayer({
          type: 'flv',
          isLive: true,
          hasAudio: false,
          url: link
        })
        console.log('加载');
        this.flvPlayer.attachMediaElement(videoElement)
        this.flvPlayer.load()
        this.flvPlayer.play()
      }
    },
    destruction() {
      //销毁对象
      if (this.flvPlayer) {
        // console.log('销毁视频对象');
        this.flvPlayer.pause();
        this.flvPlayer.destroy();
        this.flvPlayer = null;
      }
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
