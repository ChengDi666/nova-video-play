<template>
  <div style="height: 100%;">
    <video ref="myVideo1" autoplay controls width="100%" height="100%" src="" id="videoElement"></video>
<!--    <video class="videoItem" :class="{'upup': isUp}" ref="myVideo1" autoplay controls width="100%" height="100%" src="" id="videoElement"></video>-->
<!--    <video class="videoItem" :class="{'upup': !isUp}" ref="myVideo2" autoplay controls width="100%" height="100%" src="" id="vid"></video>-->
    <div v-show="linkIsErr" class="errMessage">"{{link}}" - 当前链接无效</div>
  </div>
</template>

<script>
import flvjs from 'flv.js'
import axios from 'axios'
export default {
  name: 'videoPlay',
  props: {
    // src: 'http://1011.hlsplay.aodianyun.com/demo/game.flv',
    field: Object
  },
  data () {
    return {
      flvPlayer: {
        myVideo1: null,
        myVideo2: null
      },
      linkIsErr: false,
      isUp: true,
      aliveID: null,
      deviceId: null,
      liveCookie: null
    }
  },
  mounted () {
    console.log('field: ', this.field)
    this.deviceId = this.field.value
    if(this.deviceId) {
      this.gologin(this.field.deviceVideoLink, this.field.username, this.field.password)
      // this.getVideos(this.deviceId, this.field.deviceVideoLink)
      // this.createVideo(this.deviceId, 'myVideo1', true)
    } else {
      this.linkIsErr = true
    }
    if (this.field.keepAlive && this.field.keepAlive.length) {
      console.log('开启保活')
      this.baohuo()
    }
    // this.qiehuan()
    // setInterval(() => {
    //   console.log('切换视频');
    //   // this.isUp = !this.isUp
    //   this.qiehuan()
    // }, 45000)
  },
  methods: {
    baohuo() {
      this.qiehuan()
      this.aliveID = setInterval(() => {
        this.qiehuan()
      }, 45000);
    },
    createVideo (link, videoName, isPlay) {
      if (flvjs.isSupported()) {
        const videoElement = this.$refs[videoName];
        this.flvPlayer[videoName] = flvjs.createPlayer({
          type: 'flv',
          isLive: true,
          hasAudio: false,
          hasVideo: true,
          url: link
        })
        this.flvPlayer[videoName].attachMediaElement(videoElement)
        this.flvPlayer[videoName].load()
        if(isPlay) {
          this.flvPlayer[videoName].play()
          // console.log('播放 ', videoName);
        }
      }
    },
    destruction(name) {
      //销毁对象
      name = this.isUp ? 'myVideo1' : 'myVideo2'
      if(this.aliveID) clearInterval(this.aliveID)
      if (this.flvPlayer[name]) {
        // console.log('关闭 ', name);
        // console.log('销毁视频对象');
        this.flvPlayer[name].pause();
        this.flvPlayer[name].destroy();
        this.flvPlayer[name] = null;
      }
    },
    qiehuan() {
      // console.log('是否 1 ', this.isUp);
      // const dangqianname = this.isUp ? 'myVideo1' : 'myVideo2'
      // const xianame = !this.isUp ? 'myVideo1' : 'myVideo2'
      // this.flvPlayer[dangqianname].play()
      // if(this.flvPlayer[xianame]) {
      //   this.destruction(xianame)
      // }
      // setTimeout(() => {
      //   this.gengxin(xianame)
      // }, 30000)
      this.gengxin('')
    },
    getAlive() {
      axios.get(this.field.keepAlive)
        .then(response => {
          // console.log(response);
        });
    },
    getVideos(deviceId, deviceVideoLink) {
      axios.get(deviceVideoLink + '/api/v1/devices',{
          // headers: {
          //   "cookie" : this.liveCookie,
          // },
          transformRequest:[function (data1,headers){
            console.log(headers.common)
            delete headers.common['X-XSRF-TOKEN'];
            return data1;
          }]
        }).then(response => {
          console.log(response);
        });
    },
    gologin(deviceVideoLink, username, password) {
      axios.get(`${deviceVideoLink}/api/v1/login?username=${username}&password=${password}`, {
            transformRequest:[function (data1,headers){
              console.log(headers.common)
              delete headers.common['X-XSRF-TOKEN'];
              return data1;
            }]
          })
          .then(response => {
            console.log(response);
            if(response.data.EasyDarwin && response.data.EasyDarwin.Body.Token) {
              axios.defaults.withCredentials=true
              this.liveCookie = `token=${response.data.EasyDarwin.Body.Token}`
              this.getVideos(this.deviceId, this.field.deviceVideoLink)
            }
          });
    },
    gengxin(name) {
      axios.get('https://live.ljfl.ltd:4443/api/v1/devices/channelstream?device=2&channel=2&protocol=FLV')
        .then(response => {
          // console.log(response);
          // console.log('https://live.ljfl.ltd:4443')
          // console.log(response.data.EasyDarwin.Body.URL);
          // const url = 'https://live.ljfl.ltd:4443' + response.data.EasyDarwin.Body.URL
          // console.log(url);
          // console.log('加载 ', name);
          // this.createVideo(url, name, false)
          // this.qiehuan(url)
          // this.createVideo(url, 'myVideo1', true)
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
.videoItem{
  position: absolute;
  left: 0;
  z-index: 110;
}
.upup{
  z-index: 120;
}
</style>
