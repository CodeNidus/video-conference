<template>
  <div>
    <p>Video Conference Test</p>

    <button @click="showRooms = !showRooms ">
      {{ (!showRooms)? 'Show Rooms' : 'Hide Rooms'}}
    </button>

    <VCRooms
        v-if="showRooms"
    />

    <VCRoomJoin
        v-else
        room-id="66d5d218a77436496c8df8f3"
    />

  </div>
</template>

<script setup>
import { ref, inject } from "vue";

const webrtc = inject('webrtc')

const showRooms = ref(false)


</script>


