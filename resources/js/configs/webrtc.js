export default {
  debug: false,
  base_utl: process.env.MIX_WEBRTC_BASE_URL || 'http://localhost',
  api_url: process.env.MIX_WEBRTC_BASE_URL + '/api',
  api_token_url: process.env.MIX_WEBRTC_TOKEN_URL || 'localhost/api/token',
  webrtc_url: 'https://fitkala.com',
  webrtc_connection: 'fitkala.com',
  peer_secure: true,
  peer_host: 'rapithwin.com',
  peer_port: '443',
  videoconference_theme: process.env.MIX_WEBRTC_THEME || 'default',
  mediapipe: {
    models: {
      'faceDetector': process.env.MIX_WEBRTC_BASE_URL + '/models/face',
      'bodySegmentation': process.env.MIX_WEBRTC_BASE_URL + '/models/selfie'
    },
    fps: 30
  },
  axios: {
    headers: {}
  }
}
