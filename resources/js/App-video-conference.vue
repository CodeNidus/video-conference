<template>
  <div>
    <p>Video Conference Test</p>

    <VCRooms />

    <VCRoomJoin
        room-id="66d5d218a77436496c8df8f3"
    />
  </div>
</template>

<script setup>
  import {inject} from "vue";

  const webrtc = inject('webrtc')

</script>


