<template>
  <div>
    <p>Video Conference Test</p>

    <button @click="showRooms = !showRooms ">
      {{ (!showRooms)? 'Show Rooms' : 'Hide Rooms'}}
    </button>

    <VCRooms
        v-if="showRooms"
        @onSelectRoom="setRoomId"
    />

    <div
      v-else-if="!roomId"
    >
      Please first create/select a room
    </div>

    <VCRoomJoin
        v-else
        :room-id="roomId"
        close-url="/"
    />

  </div>
</template>

<script setup>
import { ref, inject } from 'vue'

const webrtc = inject('webrtc')

const roomId = ref()
const showRooms = ref(false)

const setRoomId = (room_id) => {
  roomId.value = room_id
  showRooms.value = false
}

</script>


