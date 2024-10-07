
export default [
  {
    path: "/video-conference/rooms",
    name: "videoConferenceRooms",
    component: VCRooms,
  },
  {
    path: "/video-conference/rooms/:roomId/join",
    name: "videoConferenceJoinRoom",
    component: VCRoomJoin,
  },
];
