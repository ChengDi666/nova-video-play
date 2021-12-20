<template>
<!--  <panel-item :field="field" >-->
<!--    <div slot="value">-->
<!--      <span @click="showVideo = true" style="cursor: pointer;">-->
<!--        <svg t="1616320985973" class="icon fill-current" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6326" width="22" height="22"><path d="M954.066 439.7a181.176 181.176 0 0 1-49.844 64.146 25.936 25.936 0 0 1 5.917 30.043l-87.854 188.377a25.985 25.985 0 0 1-34.531 12.566l-74.657-34.81a77.974 77.974 0 0 1-88.605 16.023l-149.2-69.564-50.517 101.011a77.534 77.534 0 0 1-69.729 43.083H223.391v25.982a78.036 78.036 0 0 1-77.952 77.943h-51.97a25.982 25.982 0 0 1-25.984-25.98V608.707a25.983 25.983 0 0 1 25.983-25.982h51.969a78.076 78.076 0 0 1 73.485 51.964h87.941l27.067-54.118L200.593 518.4c-64.927-30.273-93.118-107.718-62.841-172.637l65.89-141.284c30.277-64.919 107.731-93.1 172.655-62.835l565.2 263.523a25.985 25.985 0 0 1 12.569 34.533zM145.439 634.687h-25.985v207.85h25.983a26.012 26.012 0 0 0 25.985-25.982V660.669a26.012 26.012 0 0 0-25.983-25.982z m640.727 42.071l65.894-141.282-2.421-1.127a182 182 0 0 1-59.26 10.429L739.067 654.8z m-463.246 9.89h-99.529v51.962h131.655a25.845 25.845 0 0 0 23.246-14.36l49.884-99.742-47.128-21.973-34.887 69.752a25.984 25.984 0 0 1-23.241 14.361zM217.795 297.083l-32.944 70.641a78.03 78.03 0 0 0 37.7 103.581L646.455 668.95a25.972 25.972 0 0 0 34.53-12.566l55.683-119.4a184.083 184.083 0 0 1-24.326-9.311z m136.539-108.344a78.041 78.041 0 0 0-103.593 37.7l-10.981 23.548 494.544 230.584a130.038 130.038 0 0 0 159.336-40.378z m266.985 411.155l-94.2-43.921a25.982 25.982 0 1 1 21.966-47.094l94.2 43.921a25.982 25.982 0 1 1-21.966 47.094z m-271.626-129.32a25.982 25.982 0 1 1 25.984-25.981 25.983 25.983 0 0 1-25.984 25.981z m-94.184-43.926a25.982 25.982 0 1 1 25.984-25.982 25.984 25.984 0 0 1-25.984 25.982z" fill="" p-id="6327"></path></svg>-->
<!--        <span v-if="field.indexLabel"> {{field.indexLabel}} </span>-->
<!--      </span>-->
<!--      <div v-show="showVideo" class="videoBox">-->
<!--        <div class="videoClear" @click="clearVideo">-->
<!--          <span>X</span>-->
<!--        </div>-->
<!--        <easy-play v-if="showVideo" ref="indexVideo" :field="field"></easy-play>-->
<!--      </div>-->
<!--      <div v-show="showVideo" style="width: 100%;height: 100%; background: rgba(0, 0, 0, 0.5);position: fixed;top: 0;left: 0;z-index: 99"></div>-->
<!--    </div>-->
<!--  </panel-item>-->

  <div>
    <div style="text-align: right;display: flex;justify-content: flex-end;">
      <div class="block, video-playback-current-time">
        当前播放时间:  <span style="margin-left: 1rem;"> {{ getEtime(String(showValue), '00000000000000') | formatDate('hm') }}</span>
      </div>
      <div class="block">
        <el-date-picker
            v-model="value1"
            type="date"
            @change="dataChanges"
            :editable="false"
            :picker-options="pickerOptions"
            placeholder="选择日期">
        </el-date-picker>
      </div>
    </div>
    <div style="display: flex;justify-content: center;">
      <easy-play ref="detailVideo" :field="field" :isautoplay="false" style="margin:2rem 4rem;width: 100%;min-width: 800px;max-width: 1000px;"></easy-play>
    </div>
    <div v-if="showVideo" style="margin: 2rem;">
      <div class="block">
        <el-slider
            v-model="value"
            :marks="marks"
            :min="oldVideoSTime"
            :max="oldVideoETime"
            step="60"
            @input="input"
            @change="changess"
        >
        </el-slider>
      </div>
    </div>
  </div>

</template>

<script>
import easyPlay from './easyPlay'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    components: {easyPlay},
  data() {
    return {
      value1: new Date(),
      showVideo: false,
      videoList: [],
      oldVideoSTime: '00000000000000',
      oldVideoETime: '00000000000000',
      selectDate: null,
      value: 0,
      period: '',
      pickerOptions: {
        disabledDate(time) {
          return time.getTime() > Date.now();
        }
      },
      marks: {
      },
      showValue: '00000000000000'
    }
  },
  mounted () {
    console.log(this.field);
    this.period = this.$options.filters['period'](this.value1)
    if(this.field.value && typeof(this.field.value)=='string') {
      if (!this.field.value.indexOf('http')) { // 完整链接
        return
      }
      this.deviceMes = JSON.parse(this.field.value)
    }
    this.getVideoList()
  },
  methods: {
    dataChanges (e) {
      this.clearVideo()
      this.period = this.$options.filters['period'](e)
      this.getVideoList()
      this.$refs.detailVideo.playVideo('')
    },
    changess (value) {
      let selectDate = null
      this.videoList.forEach((val) => {
        const etime = this.getEtime(val.startAt, val.duration)
        if (value >= val.startAt && value < etime) {
          selectDate = val
          this.toPlayBack(selectDate)
          return
        }
      })
      if (!selectDate) {
        for (let index = 0; index < this.videoList.length; index++) {
          const element = this.videoList[index];
          if (value <= element.startAt) {
            this.value = element.startAt
            this.toPlayBack(element)
            return
          }
        }
      }
    },
    getVideoList () {
      axios.get(`https://live.ljfl.ltd/api/v1/record/querydaily?id=${this.deviceMes.channelID}&recordType=Device&period=${this.period}`).then(response => {
        // console.log(response.data)
        if (response.data.list.length === 0) {
          this.$toasted.show('当前设备暂无记录', { type: 'error' })
          return
        }
        response.data.list.sort(function (a, b) { return a.startAt - b.startAt })
        this.videoList = response.data.list
        this.setOldVideoTime(this.videoList)
        this.addTime()
        this.showVideo = true
      })
    },
    addTime() {
      for (let index = 0; index < 24; index++) {
        let hos = index > 9 ? index : `0${index}`
        const time = `${this.period}${hos}0000`
        this.marks[time] = `${hos}:00`
      }
    },
    formatDate (date) {
      return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`
    },
    toPlayBack (data) {
      let that = this
      clearTimeout(this.timeout)
      this.timeout = setTimeout(() => {
        that.palyOldVideo(data)
      }, 1500)
    },
    palyOldVideo (data) {
      data.etime = this.getEtime(data.startAt, data.duration)
      axios.get(`https://live.ljfl.ltd/api/v1/devices/record/playback?file=${data.name}&channel=${this.deviceMes.channelID}&duration=${data.duration}&stime=${data.startAt}&etime=${data.etime}&protocol=flv`).then(response => {
        // console.log(response.data)
        if (response.data.EasyDarwin.Header.ErrorNum !== '200') {
          this.$toasted.show(response.data.EasyDarwin.Header.ErrorString, { type: 'error' })
          console.log(response.data.EasyDarwin.Header.ErrorString)
          return
        }
        const url = 'https://live.ljfl.ltd' + response.data.EasyDarwin.Body.URL
        console.log(url)
        this.$refs.detailVideo.playVideo(url)
      })
    },
    setOldVideoTime (data) {
      this.oldVideoSTime = Number(data[0].startAt)
      this.value = Number(data[0].startAt)
      this.oldVideoETime = Number(data[data.length - 1].startAt)
    },
    getEtime (date1, date2) { // 播放结束时间
      date2 = this.$options.filters['duration'](date2, true)
      let s = (date1.substring(12, 14) - 0) + (date2.substring(4, 6) - 0)
      const m1 = parseInt(s / 60)
      s = (s % 60) < 10 ? '0' + (s % 60) : (s % 60) // 取余并补0
      let m = (date1.substring(10, 12) - 0) + (date2.substring(2, 4) - 0) + m1
      const h1 = parseInt(m / 60)
      m = (m % 60) < 10 ? '0' + (m % 60) : (m % 60)
      let h = (date1.substring(8, 10) - 0) + (date2.substring(0, 2) - 0) + h1
      h = h < 10 ? '0' + h : h
      return `${date1.substring(0, 8)}${h}${m}${s}`
    },
    input (e) {
      let a = String(e).substring(10, 14)
      let b = String(parseInt(a * 0.6))
      if (b.length < a.length) {
        for (let i = 0; i < (a.length - b.length); i++) { b = `0${b}` }
      }
      this.showValue = `${String(e).substring(0, 10)}${b}`
    },
    clearVideo () {
      // console.log('关闭视频');
      this.$refs.detailVideo.destruction()
    }
  },
  filters: {
    formatDate: function (val, type) {
      const year = val.substring(0, 4)
      const month = val.substring(4, 6)
      const day = val.substring(6, 8)
      const hour = val.substring(8, 10)
      const minutes = val.substring(10, 12)
      const seconds = val.substring(12, 14)
      if (type === 'ymd') return year + '-' + month + '-' + day
      if (type === 'hms') return hour + ':' + minutes + ':' + seconds
      if (type === 'hm') return hour + ':' + minutes
      return year + '-' + month + '-' + day + ' ' + hour + ':' + minutes + ':' + seconds
    },
    duration: (val, is) => {
      let hour = parseInt(val / 3600)
      let minutes = parseInt(val / 60) % 60
      let seconds = val % 60
      hour = hour < 10 ? '0' + hour : hour
      minutes = minutes < 10 ? '0' + minutes : minutes
      seconds = seconds < 10 ? '0' + seconds : seconds
      if (is) return hour + minutes + seconds
      return hour + ':' + minutes + ':' + seconds
    },
    period (val) {
      const year = val.getFullYear()
      let month = val.getMonth() + 1
      let day = val.getDate()
      month = month < 10 ? '0' + month : month
      day = day < 10 ? '0' + day : day
      return `${year}${month}${day}`
    }
  },
}
</script>

<style>
.el-slider__bar{
  background-color: transparent !important;
}
.video-playback-current-time {
  border-radius: 4px;
  background-color: #FFF;
  background-image: none;
  box-sizing: border-box;
  color: #606266;
  display: inline-block;
  font-size: inherit;
  height: 40px;
  line-height: 40px;
  outline: 0;
  padding: 0 15px;
  margin-right: 1rem;
  border: 1px solid #DCDFE6;
}
</style>
