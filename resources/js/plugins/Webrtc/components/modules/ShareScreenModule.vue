<template>
  <div id="screen-sharing">
    <div id="screen-sharing-area">
      <video id="screen-sharing-video"></video>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineExpose } from 'vue'

const props = defineProps({
  webrtc: {
    type: Object,
    required: true
  }
})

const open = async (room) => {
  if (!props.webrtc.userSettings.share) {
    const shareStatus = props.webrtc.People.findOne('share', true)

    if (shareStatus) {
      props.webrtc.notify('Screen Share', 'Some one use this feature now.')
      return
    }

    props.webrtc.Media.screenShare.startShareScreen()
  } else {
    props.webrtc.Media.screenShare.stopShareScreen()
  }
}

defineExpose({
  open,
})
</script>

<style lang="scss">
#screen-sharing {
  display: none;
  position: relative!important;

  #screen-sharing-area {
    position: relative;
    padding: 0 15px;

    video {
      width: 100%;
      height: 80vh;
    }
  }
}
</style>
