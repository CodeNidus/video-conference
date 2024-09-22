import { createApp } from "vue"
import App from "./App-video-conference.vue"
import { VideoConferenceCreator } from "cnidus-videoconference-vue"

const videoconference = VideoConferenceCreator()

const app = createApp(App)

app.use(videoconference)
	.mount("#app")
