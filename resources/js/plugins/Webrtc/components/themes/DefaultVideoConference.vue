<template>
  <div>
    <slot name="actions"></slot>
    <div id="video-meeting">
      <div
        id="video-conference-room"
        :class="{
          'with-canvas': canvasEnabled,
          'with-screen-share': screenShareEnabled
        }"
      >
        <button
            v-if="isPortrait && canvasEnabled && screenShareEnabled"
            id="area-switcher"
            @click="toggleArea"
        >
          Switch
        </button>
        <div id="modules-area" ref="modulesArea">
          <slot name="canvas"></slot>
          <slot name="screenShare"></slot>
        </div>
        <div id="users-area">
          <div id="video-conference-users" ref="usersArea">
            <div
                class="user-item current-user"
                :class="{ 'room-creator': props.userSettings.isCreator , 'room-user': !props.userSettings.isCreator}"
            >
              <div v-if="props.userSettings.micDisable" class="microphone-mute">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mic-mute" viewBox="0 0 16 16">
                  <path d="M13 8c0 .564-.094 1.107-.266 1.613l-.814-.814A4.02 4.02 0 0 0 12 8V7a.5.5 0 0 1 1 0v1zm-5 4c.818 0 1.578-.245 2.212-.667l.718.719a4.973 4.973 0 0 1-2.43.923V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 1 0v1a4 4 0 0 0 4 4zm3-9v4.879l-1-1V3a2 2 0 0 0-3.997-.118l-.845-.845A3.001 3.001 0 0 1 11 3z"/>
                  <path d="m9.486 10.607-.748-.748A2 2 0 0 1 6 8v-.878l-1-1V8a3 3 0 0 0 4.486 2.607zm-7.84-9.253 12 12 .708-.708-12-12-.708.708z"/>
                </svg>
              </div>
              <div class="user-footer-actions">
                <div @click="blurUserMediaBackground(!blur)" class="action-item">
                  <div v-if="!blur">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
                      <path fill="currentColor" d="M391.3 213.4C343.4 134.8 296.4 106.7 258.5 0c-38.1 106.7-84.9 134.8-132.8 213.4c-43.3 71-56.9 170.7 0 234.7c37.9 42.7 76 64.1 132.8 63.9c57.1.2 94.9-21.2 132.8-63.9c56.9-64 43.3-163.7 0-234.7z"/>
                    </svg>
                  </div>
                  <div v-else>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                      <path fill="currentColor" d="M5.432 6.843L1.394 2.805L2.808 1.39l19.799 19.8l-1.415 1.414l-3.038-3.04A9 9 0 0 1 5.432 6.845Zm2.811-2.817L12 .269l6.364 6.364a9.002 9.002 0 0 1 2.05 9.564L8.244 4.026Z"/>
                    </svg>
                  </div>
                </div>
              </div>
              <div class="user-video">
                <div v-if="props.userSettings.camDisable" class="camera-mute">
                  <p>Camera off</p>
                </div>
                <video
                    id="video-item"
                    class="video-content video-item"
                    :data-peerId="props.userSettings.peerJsId"
                ></video>
              </div>
            </div>

            <div
                v-for="(connection, index) in connections"
                :key="'connection_' + index"
                class="user-item"
                :class="{ 'room-creator': connection.isCreator , 'room-user': !connection.isCreator }"
            >
                <div class="user-name">
                  <span>{{ connection.name }}</span>
                </div>
                <div v-if="connection.micMute" class="microphone-mute">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mic-mute" viewBox="0 0 16 16">
                    <path d="M13 8c0 .564-.094 1.107-.266 1.613l-.814-.814A4.02 4.02 0 0 0 12 8V7a.5.5 0 0 1 1 0v1zm-5 4c.818 0 1.578-.245 2.212-.667l.718.719a4.973 4.973 0 0 1-2.43.923V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 1 0v1a4 4 0 0 0 4 4zm3-9v4.879l-1-1V3a2 2 0 0 0-3.997-.118l-.845-.845A3.001 3.001 0 0 1 11 3z"/>
                    <path d="m9.486 10.607-.748-.748A2 2 0 0 1 6 8v-.878l-1-1V8a3 3 0 0 0 4.486 2.607zm-7.84-9.253 12 12 .708-.708-12-12-.708.708z"/>
                  </svg>
                </div>
                <div
                    v-if="props.userSettings.isCreator && !connection.micMute"
                    class="microphone-unmute"
                    @click="muteUserMic(connection.peerJsId)"
                >
                  [mute]
                </div>
                <div class="user-video">
                  <div v-if="connection.camMute" class="camera-mute">
                    <p>Camera off</p>
                  </div>
                  <video
                      v-show="connection.active"
                      :id="'remote-video-' + connection.id"
                      class="peer-content video-item"
                      :class="'peer-content-' + connection.id"
                      :data-peerid="connection.id"
                  ></video>
                  <audio autoplay
                         :id="'remote-audio-' + connection.id"
                         :data-peerid="connection.id"
                  ></audio>
                </div>
            </div>

          </div>
        </div>
      </div>
      <slot name="commandsDeck"></slot>
    </div>
    <slot name="modules"></slot>
  </div>
</template>

<script setup>
import {ref, defineProps, inject, onUnmounted, onMounted, watch, computed} from 'vue'

const webrtc = inject('webrtc')

const props = defineProps({
  commands: {
    type: Object,
    required: true
  },
  connections: {
    type: Array,
    required: true
  },
  userSettings: {
    type: Object,
    required: true
  }
})

const blur = ref(false)

const muteUserMic = (peerJsId) => {
  props.commands.run('muteUserMic', {
    peerJsId: peerJsId
  })
}

const isPortrait = computed(() => {
  return window.innerHeight > window.innerWidth
})

const blurUserMediaBackground = (status) => {
  blur.value = status
  webrtc.Media.blurBackground(status)
}

const clearAllIntervals = () => {
  for (let i = 1; i < 99999; i++)
    window.clearInterval(i)
}

const toggleArea = () => {
  if (enabledArea.value === 'modules') {
    enabledArea.value = 'users'
    modulesArea.value.style.display = 'none'
    usersArea.value.style.display = 'flex'
  } else {
    enabledArea.value = 'modules'
    modulesArea.value.style.display = 'flex'
    usersArea.value.style.display = 'none'
  }
}

const enableAreaSwitcher = () => {
  enabledArea.value = 'modules'
  usersArea.value.style.display = 'none'
}

const disableAreaSwitcher = () => {
  usersArea.value.style.display = 'flex'
}

const enabledArea = ref('modules')
const modulesArea = ref()
const usersArea = ref()
const margin = ref(10)
const ratio = ref(3 / 4) /* ratios 4:3 16:9 1:1 1:2 */
const width = ref(0)
const height = ref(0)
const canvasEnabled = ref(false)
const screenShareEnabled = ref(false)

watch(usersArea, (val) => {
  resize()
})

watch(props.connections, (val) => {
  resize()
})

watch(canvasEnabled, (val) => {
  resize()
})

watch(screenShareEnabled, (val) => {
  resize()
})

const resize = () => {
  setTimeout(() => {
    if (isPortrait.value) {
      if (screenShareEnabled.value  && canvasEnabled.value) {
        enableAreaSwitcher()
      } else {
        disableAreaSwitcher()
      }
    }

    width.value = usersArea.value.offsetWidth - (margin.value * 2)
    height.value = usersArea.value.offsetHeight - (margin.value * 2)

    let i = 1
    let max = 0
    let minWidth = (window.innerWidth <= 600)? 240 : 360
    let maxWidth = window.innerWidth / ((window.innerWidth <= 600)? 1.4 : 2.2)
    let phoneMinWidth = window.innerWidth / 2.6
    let minWidthWithMargin = minWidth + (margin.value * 2)

    if (canvasEnabled.value || screenShareEnabled.value) {
      minWidth = phoneMinWidth
    }

    while (i < window.innerWidth) {
      let area = calculateArea(i)

      if (area === false) {
        max =  (i < minWidthWithMargin)? minWidth : i - 1
        max = (max > maxWidth)? maxWidth : max
        break;
      }

      i++
    }

    resizer(max);
  }, 100)
}

const calculateArea = (increment) => {
  let i = 0
  let w = 0
  let h = increment * ratio.value + (margin.value * 2)
  let count = usersArea.value.childElementCount

  while (i < count) {
    if ((w + increment) > width.value) {
      w = 0
      h = h + (increment * ratio.value) + (margin.value * 2)
    }
    w = w + increment + (margin.value * 2)
    i++
  }

  return (h > height.value || increment > width.value)? false : increment;
}

const resizer = (_width) => {
  let count = usersArea.value.childElementCount

  for (let i = 0; i < count; i++) {
    let element = usersArea.value.children[i]
    element.style.margin = margin.value + "px"
    element.style.width = _width + "px"
    element.style.height = (_width * ratio.value) + "px"
  }
}

const reGenerateCanvasActionCanvas = () => {
  const canvasItem = document.getElementById('canvas-text-action-card')
  const canvas = document.getElementById('canvas-text-scroll-section')
  modulesArea.value.prepend(canvasItem)
  canvas.setAttribute('height', window.innerHeight / 4)
}

const setCanvasActionEventListener = () => {
  window.addEventListener('onCanvasTextAction', receivedCanvasTextAction)
}
const setScreenShareEventListener = () => {
  window.addEventListener('onScreenShareModule', receivedScreenShareModuleEvent)
}

const receivedCanvasTextAction = (e) => {
  canvasEnabled.value = (e.detail.play)
}

const receivedScreenShareModuleEvent = (e) => {
  screenShareEnabled.value = (e.detail.status)
}


onMounted(() => {
  resize()
  setCanvasActionEventListener()
  setScreenShareEventListener()
  reGenerateCanvasActionCanvas()

  window.addEventListener("resize", function () {
    resize()
  })
})


onUnmounted(() => {
  // clear canvas text render interval
  clearAllIntervals()
})

</script>

<style lang="scss">
#video-meeting {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  flex-direction: column;
  padding: 20px 20px 60px 20px;
  gap: 20px;

  #video-conference-room {
    position: relative;
    display: flex;
    flex: 1;
    border-radius: 10px;
    gap: 20px;
    max-height: 100%;
    max-width: 100%;

    @media only screen and (max-width: 600px) {
      flex-direction: column;
    }

    #area-switcher {
      position: absolute;
      z-index: 1000;
      bottom: 25px;
      right: 0;
    }

    &.with-canvas:not(.with-screen-share) {
      #modules-area {
      //  height: 33%;
      }

    }

    &.with-canvas,
    &.with-screen-share {
      #modules-area {
        display: flex;
      }

      #users-area {
        @media only screen and (max-width: 600px) {
          align-items: flex-start!important;
        }

        #video-conference-users {
          align-content: flex-start!important;

          @media only screen and (max-width: 600px) {
            flex-wrap: nowrap!important;
            justify-content: flex-start!important;
          }
        }
      }
    }

    &.with-canvas.with-screen-share {
      #users-area {
        #video-conference-users {
          align-content: flex-start!important;

          @media only screen and (max-width: 600px) {
            flex-wrap: wrap!important;
            justify-content: flex-start!important;
          }
        }
      }
    }

    #modules-area {
      width: auto;
      flex-direction: column;

      @media only screen and (max-width: 600px) {
        width: unset;
      }

      #screen-sharing {
        display: none;
        flex: 2;
        width: 60vw;
        background: #000;
        opacity: 0.8;
        border-radius: 10px;
        margin-bottom: 15px;

        @media only screen and (max-width: 600px) {
          width: unset;
        }

        video {
          padding: 15px 0;
        }
      }

      #canvas-text-action-card {
        display: none;
      //  flex: 1;
        width: 60vw;
        background: #161e57;
        opacity: 0.8;
        border-radius: 10px;
        margin-bottom: 10px;
        font-size: 16px;

        @media only screen and (max-width: 600px) {
          width: unset;
        }

        canvas {
          width: 100%;
        }
      }
    }

    #users-area {
      display: flex;
      flex: 1;
      width: 33vw;

      @media only screen and (max-width: 600px) {
        width: unset;
        overflow: scroll;
      }

      #video-conference-users {
        overflow: scroll;
        display: flex;
        flex: 1;
        flex-wrap: wrap;
        align-content: flex-start;
        justify-content: center;
        align-items: center;
        vertical-align: middle;

        @media only screen and (max-width: 960px) {
          align-content: flex-start!important;
        }

        @media only screen and (max-width: 600px) {
          //flex-wrap: wrap !important;
          //justify-content: center !important;
        }

        .user-item {
          position: relative;
          vertical-align: middle;
          align-self: center;
          border-radius: 10px;
          overflow: hidden;
          display: inline-block;
          animation: show 0.4s ease;
          background: #333;

          &.room-creator {
            order: 1;
          }

          &.current-user {
            order: 2;
          }

          &.room-user {
            order: 3;
          }

          &:hover:after {
            content: ' ';
            position: absolute;
            z-index: 1;
            top: 0;
            width: 100%;
            height: 100%;
            background: #000;
            opacity: 0.3;
          }

          &:hover {
            .microphone-mute,
            .microphone-unmute,
            .user-footer-actions {
              display: inline-block;
            }
          }

          @media only screen and (max-width: 600px) {
            flex: 0 0 auto;
          }

          .user-name {
            position: absolute;
            z-index: 3;
            left: 0px;
            bottom: 0px;
            display: inline-block;
            color: #fff;
            padding: 5px 10px;
            overflow: hidden;
            border-top-right-radius: 8px;
            border-bottom-left-radius: 8px;

            span {
              position: relative;
              z-index: 1;
            }

            &:after {
              content: ' ';
              position: absolute;
              top: 0;
              left: 0;
              bottom: 0;
              right: 0;
              opacity: 0.6;
              background: #000;
            }
          }

          .user-footer-actions {
            display: none;
            position: absolute;
            z-index: 3;
            right: 15px;
            bottom: 15px;
            color: #fff;

            .action-item {
              cursor: pointer;
            }
          }

          .microphone-mute {
            position: absolute;
            z-index: 5;
            display: none;
            color: #fff;
            top: 25px;
            right: 25px;

            @media only screen and (max-width: 600px) {
              top: 10px;
              right: 10px;
            }

            svg {
              width: 32px;
              height: 32px;

              @media only screen and (max-width: 600px) {
                width: 20px;
                height: 20px;
              }
            }
          }

          .microphone-unmute {
            position: absolute;
            z-index: 5;
            display: none;
            color: #fff;
            cursor: pointer;
            bottom: 25px;
            right: 25px;
          }

          .user-video {

            video {
              position: absolute;
              z-index: 1;
              height: 100%;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
            }

            .camera-mute {
              position: absolute;
              z-index: 4;
              display: flex;
              align-items: center;
              justify-content: center;
              width: 100%;
              height: 100%;
              color: #fff;
              background: #333;
            }

            canvas {
              z-index: 2;
              width: 100%;
              height: 100%;
            }
          }
        }
      }
    }
  }
}

@keyframes show {
  0% {
    opacity: 0;
    transform: scale(0.4) translateY(20px);
  }

  100% {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

</style>
