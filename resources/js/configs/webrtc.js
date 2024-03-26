export default {
  debug: false,
  api_token_url: process.env.MIX_WEBRTC_TOKEN_URL || 'localhost:8000/api/videoconference/userToken',
  webrtc_url: 'https://fitkala.com',
  webrtc_connection: 'fitkala.com',
  peer_secure: true,
  peer_host: 'rapithwin.com',
  peer_port: '443',
  videoconference_theme: process.env.MIX_WEBRTC_THEME || 'default',
  mediapipe: {
    models: {
      faceDetector: '/models/face',
      bodySegmentation: '/models/selfie'
    },
    fps: 30
  },
  axios: {
    headers: {}
  }
}
