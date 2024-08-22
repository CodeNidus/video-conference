<template>
  <div id="screen-sharing">
    <div id="screen-sharing-area">
      <div
        class="screen-share-full-screen"
        @click="fullscreen"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M5 5h5v2H7v3H5zm9 0h5v5h-2V7h-3zm3 9h2v5h-5v-2h3zm-7 3v2H5v-5h2v3z"/></svg>
        <div class="screen-share-full-screen-background"></div>
      </div>
      <video
        ref="video"
        id="screen-sharing-video"
      />
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineExpose, ref } from 'vue'

const props = defineProps({
  webrtc: {
    type: Object,
    required: true
  }
})

const video = ref()

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

const fullscreen  = () => {
  video.value.requestFullscreen()
}

defineExpose({
  open,
  fullscreen,
})
</script>

<style lang="scss">
#screen-sharing {
  display: none;
  position: relative!important;

  #screen-sharing-area {
    position: relative;
    padding: 0 15px;

    .screen-share-full-screen {
      display: none;
    }

    &:hover {
      .screen-share-full-screen {
        display: flex;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        color: #fff;
        justify-content: center;
        align-items: center;
        font-size: 3em;
        cursor: pointer;

        svg {
          z-index: 2;
        }

        .screen-share-full-screen-background {
          position: absolute;
          z-index: 1;
          left: 0;
          right: 0;
          top: 0;
          bottom: 0;
          background: #000;
          opacity: 0.3;
        }

      }
    }

    video {
      width: 100%;
    }
  }
}
</style>
