import { createRouter, createWebHistory } from 'vue-router'
import videoConferenceRoutes from './video-conference'

let routes = []

routes = routes.concat(videoConferenceRoutes)

const router = createRouter({
  history: createWebHistory('/'),
  routes,
})

export default router
