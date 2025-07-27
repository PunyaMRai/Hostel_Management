const initialScreen = document.getElementById("initial-screen");
const videoScreen = document.getElementById("video-screen");
const meetingInfo = document.getElementById("meeting-info");
const meetingIdDisplay = document.getElementById("meeting-id-display");
const copyLinkButton = document.getElementById("copy-link");
const createMeetingButton = document.getElementById("create-meeting");
const joinMeetingButton = document.getElementById("join-meeting");
const meetingIdInput = document.getElementById("meeting-id-input");
const localVideo = document.getElementById("local-video");
const remoteVideo = document.getElementById("remote-video");
const toggleMicButton = document.getElementById("toggle-mic");
const toggleCameraButton = document.getElementById("toggle-camera");
const endCallButton = document.getElementById("end-call");
const chatInput = document.getElementById("chat-input");
const sendChatButton = document.getElementById("send-chat");
const chatMessages = document.getElementById("chat-messages");

let localStream;
let peerConnection;
let meetingId;
let isMicOn = true;
let isCameraOn = true;

const servers = {
    iceServers: [{ urls: "stun:stun.l.google.com:19302" }]
};

function showVideoScreen(id) {
    meetingId = id;
    meetingIdDisplay.textContent = `Meeting ID: ${meetingId}`;
    initialScreen.classList.add("hidden");
    videoScreen.classList.remove("hidden");
}

async function startLocalVideo() {
    localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    localVideo.srcObject = localStream;
}

createMeetingButton.addEventListener("click", async () => {
    meetingId = Math.random().toString(36).substring(2, 10);
    showVideoScreen(meetingId);
    await startLocalVideo();
});

joinMeetingButton.addEventListener("click", async () => {
    const id = meetingIdInput.value.trim();
    if (id) {
        showVideoScreen(id);
        await startLocalVideo();
    } else {
        alert("Please enter a valid Meeting ID.");
    }
});

copyLinkButton.addEventListener("click", () => {
    const meetingLink = `${window.location.href}?meetingId=${meetingId}`;
    navigator.clipboard.writeText(meetingLink).then(() => {
        alert("Meeting link copied!");
    });
});

toggleMicButton.addEventListener("click", () => {
    isMicOn = !isMicOn;
    localStream.getAudioTracks()[0].enabled = isMicOn;
    toggleMicButton.textContent = isMicOn ? "Mic On" : "Mic Off";
});

toggleCameraButton.addEventListener("click", () => {
    isCameraOn = !isCameraOn;
    localStream.getVideoTracks()[0].enabled = isCameraOn;
    toggleCameraButton.textContent = isCameraOn ? "Camera On" : "Camera Off";
});

endCallButton.addEventListener("click", () => {
    localStream.getTracks().forEach((track) => track.stop());//WEBRTC web real time communication
    remoteVideo.srcObject = null;
    alert("Call ended.");
    location.reload();
});

sendChatButton.addEventListener("click", () => {
    const message = chatInput.value.trim();
    if (message) {
        const chatBubble = document.createElement("div");
        chatBubble.textContent = `You: ${message}`;
        chatMessages.appendChild(chatBubble);
        chatInput.value = "";
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

// WebRTC Peer Connection and Signal Handling
// This would involve using socket.io or other signaling mechanism for real-time communication.
