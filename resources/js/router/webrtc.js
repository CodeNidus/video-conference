import Rooms from "../plugins/Webrtc/components/Rooms.vue";
import RoomJoin from "../plugins/Webrtc/components/RoomJoin.vue";

export default [
  {
    path: "/webrtc/rooms",
    name: "webrtcRooms",
    component: Rooms,
  },
  {
    path: "/webrtc/rooms/:roomId/join",
    name: "webrtcJoinRoom",
    component: RoomJoin,
  },
];
