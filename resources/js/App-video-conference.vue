<template>
  <div>
    <p>Video Conference Test</p>
    <Rooms/>
  </div>
</template>

<script>
import Rooms from "./components/webrtc/Rooms.vue";

export default {
  components: {
    Rooms,
  }
}
</script>

