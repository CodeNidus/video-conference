<script setup>
  import { ref, defineExpose } from 'vue'
  import { useRoute } from 'vue-router'
  import { v4 as uuidv4 } from 'uuid'
  import axios from '../../utils/Webrtc/Axios'
  import configs from '../../configs/webrtc'

  const route = useRoute()
  const webrtcStorageKey = 'cnidus.videoconference.user.token'
  const webrtcToken = ref({
    username: null,
    token: null,
    roomId: null,
    expired: null,
  })

  const data = JSON.parse(localStorage.getItem(webrtcStorageKey))
  if (!!data) {
    webrtcToken.value = data
  }


  const webrtcGetUserToken = async (next, error = (e) => console.log(e)) => {
    try {
      if (!webrtcToken.value || !webrtcToken.value.token || webrtcToken.value.expired < Date.now()) {
        await webrtcReGenerateUserToken()
      }
      next(webrtcToken.value.token)
    } catch(err) {
      error(err)
    }
  }

  const webrtcForceGetUserToken = async (next) => {
    await webrtcReGenerateUserToken()
    next(webrtcToken.value.token);
  }

  const webrtcReGenerateUserToken = async () => {
    return new Promise((resolve, reject) => {
      const apiClient = axios.getInstance('/')
      const laravelToken = localStorage.getItem('cnidus.videoconference.laravel.token');

      if(!laravelToken) {
        reject('It seems that your Laravel token is not stored in local storage.')
      }

      apiClient({
        method: 'get',
        url: configs.api_token_url,
        headers: {
          Authorization: `Bearer ${laravelToken}`
        }
      }).then(response => {
        setUserVideoConferenceToken(response.data.data)
        resolve(true)
      }, error => {
        reject('Error happened! ' + error.response.data.message)
      })
    })
  }

  const webrtcRemoveUserToken = () => {
    localStorage.setItem(webrtcStorageKey, JSON.stringify({
      username: null,
      token: null,
      roomId: null,
      expired: null,
    }));
  }

  const setUserVideoConferenceToken = (tokenData) => {
    return new Promise((resolve) => {
      webrtcToken.value = {
        username: tokenData.username,
        token: tokenData.token,
        roomId: tokenData.room_id,
        expired: Date.now() + 12 * 60 * 60 * 1000
      }

      localStorage.setItem(webrtcStorageKey, JSON.stringify(webrtcToken.value))
      resolve(true)
    })
  }

  defineExpose({
    webrtcGetUserToken,
    webrtcForceGetUserToken,
    webrtcRemoveUserToken,
    setUserVideoConferenceToken,
  })


</script>

<template></template>
