<template>
  <div>
    <div class="demo-navbar">
      <div
          class="header"
          :class="{ tiny: token }"
      >
        <h1>Vidus Demo</h1>
        <p class="subtitle">WebRTC Video Conference</p>
      </div>

      <div>
        <div
            v-if="!token"
            class="text-field"
            :class="{ error: !userEmailIsValid }"
        >
          <label>E-mail</label>
          <input
              id="usernameField"
              v-model="username"
              type="email"
          />
        </div>
        <div v-else class="username-label">
          <label>{{ username }}</label>
        </div>
      </div>

      <button
          v-if="!token"
          @click="getUserToken"
          :disabled="!userEmailIsValid || loading"
      >
        Get Token
      </button>

      <button
          v-if="token"
          @click="clearUserToken"
      >
        Remove Token
      </button>

      <button v-if="1===2" @click="showRooms = !showRooms">
        {{ (!showRooms)? 'Show Rooms' : 'Hide Rooms'}}
      </button>

    </div>

    <div class="divider"></div>

    <div
        v-if="!token"
        class="card welcome-box"
    >
      <div class="title">
        Welcome to the Vidus WebRTC Video Conference Demo Module, a powerful and intuitive component built using the
        <span>Vue.js</span> library.
      </div>
      <div>
        <ol>
          <li>Enter your desired e-mail address and get access demo token</li>
          <li>Create your first room in project</li>
          <li>Get rooms list from server</li>
          <li>Select created room, join & enjoy!</li>
        </ol>
      </div>
      <p>
        <span>Vidus</span> is a JavaScript-based video conferencing toolkit designed to seamlessly integrate real-time video communication into your web and mobile applications with minimal effort. Whether you're developing for browsers or mobile platforms, Vidus empowers you to embed high-quality <span>video conferencing</span> capabilities in just a matter of minutes.
      </p>
      <p>
        With Vidus, you have the flexibility to tailor and enhance your video conferencing experience by easily adjusting a wide range of setup parameters. From <span>customizing layouts</span> and controls to enabling advanced features like <span>screen sharing</span> and more, Vidus provides the tools you need to create a fully personalized and professional video conferencing solution tailored to your specific requirements.
      </p>
      <p class="slogan">
        Dive in and explore the endless possibilities Vidus has to offer!
      </p>
      <div>
        <a href="https://www.codenidus.com" target="_blank" class="btn btn-outline">Documentation</a>
      </div>
    </div>
    <div v-else>
      <VCRooms
          v-if="!roomId"
          @onSelectRoom="setRoomId"
      />

      <VCRoomJoin
          v-else
          :room-id="roomId"
          close-url="/"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'App',
  inject: ['webrtc'],
  computed: {
    userEmailIsValid() {
      return (!!this.username && this.validateEmail(this.username))
    }
  },
  data() {
    return {
      roomId: null,
      username: 'test@test.com',
      showRooms: false,
      token: null,
      loading: false,
    }
  },
  methods: {
    setRoomId(room_id) {
      this.roomId = room_id
      this.showRooms = false
    },
    getUserToken() {
      this.loading = true

      this.webrtc.getUserToken(token => {
        this.token = token
        this.loading = false
      }, error => {
        console.log(error)
      })
    },
    clearUserToken() {
      this.webrtc.Room.left(this.roomId, {})
      this.webrtc.helpers.userToken.removeToken()
      this.token = null
    },
    validateEmail(email) {
      return email.match(
          /^(([^<>()[\]\\.,;:\s@\\"]+(\.[^<>()[\]\\.,;:\s@\\"]+)*)|(\\".+\\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      )
    },

  },
}
</script>

<style lang="scss">
.demo-navbar {
  display: flex;
  width: 100%;
  font-size: 1rem;
  justify-content: flex-start;
  align-items: flex-end;
  gap: 20px;

  .header {
    padding: 0 50px;

    h1 {
      font-size: 2em;
      margin: 0;
      transition: .4s all;
    }

    .subtitle {
      font-size: .9em;
      margin: 0;
      transition: .4s all;
    }

    &.tiny {
      h1 {
        font-size: 1.5em;
      }

      .subtitle {
        font-size: 0.7em;
      }
    }
  }

  .username-label {
    margin: 10px 0;
  }
}

.welcome-box {
  max-width: 850px;
  margin: 50px auto 0 auto;

  .title {
    font-size: 1.5rem;
    font-weight: bold;

    span {
      color: #41B883;
    }
  }

  ol {
    li {
      list-style: decimal;
    }
  }

  p {
    span {
      font-weight: bold;
      font-style: italic;
    }

    &.slogan {
      font-weight: bold;
    }
  }


}

</style>