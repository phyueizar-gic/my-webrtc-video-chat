var base_url = 'https://my-webrtc-video-chat.herokuapp.com/';
var userid = $(".u-p-id").data('userid');
var profileid = $(".u-p-id").data('profileid');

conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    console.log(e.data);
    var data = JSON.parse(e.data);
    switch(data.type){
        case "CONNECTION_ESTABLISHED":
            loadConnectedPeers();
            $(".user-status").html(data.status);
            break;
        case "CONNECTION_DISCONNECTED":
            loadConnectedPeers();
            $(".user-status").html(data.status);
            break;
        case "NEW_USER_CONNECTION":
            loadConnectedPeers();
            break;
        case "NEW_USER_DISCONNECTED":
            loadConnectedPeers();
            break;
        case "client-is-ready":
            clientProcess(data.success);
            break;
        case "offer":
            $("#conf-int").addClass("hidden-status");
            $("#call-status").removeClass("hidden-status");
            $("#call-status").html('<div class="user-connected-img"> <img src="'+base_url+data.profileImage+'" alt="'+data.name+'"> </div> <div class="user-status-text"> <div class="user-connected-name">'+data.name+'</div> <div class="user-calling-status">  is Calling </div> </div> <div class="calling-action"> <div class="call-accept" data-profileid="'+data.sender+'" data-userid="'+data.receiver+'"> <i class="fa fa-phone audio-icon"></i> </div> <div class="call-reject" data-profileid="'+data.sender+'" data-userid="'+data.receiver+'"> <i class="fa fa-close close-icon"></i> </div> </div>');
            var call_accept = document.querySelector(".call-accept");
            call_accept.addEventListener("click",function(){
                $("#local-video").css({
                    "z-index":2
                });
                acceptCall(data.sender);
                offerProcess(data.offer,data.sender);
                $("#conf-int").addClass("hidden-status");
                $("#call-status").removeClass("hidden-status");
                $("#call-status").html(' <div class="calling-wrap"> <div class="call-hang-action"> <div class="videcam-on"> <i class="fa fa-camera video-toggle"></i> </div> <div class="audio-on"> <svg class="audio-toggle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"> <path fill="#fff" d="M11.999 14.942c2.001 0 3.531-1.53 3.531-3.531V4.35c0-2.001-1.53-3.531-3.531-3.531S8.469 2.35 8.469 4.35v7.061c0 2.001 1.53 3.531 3.53 3.531zm6.238-3.53c0 3.531-2.942 6.002-6.237 6.002s-6.237-2.471-6.237-6.002H3.761c0 4.001 3.178 7.297 7.061 7.885v3.884h2.354v-3.884c3.884-.588 7.061-3.884 7.061-7.885h-2z"></path> </svg> </div> <div class="call-cancel"> <i class="fa fa-phone call-cancel-icon"></i> </div> </div> </div>');
            })
            var call_reject = document.querySelector(".call-reject");
            call_reject.addEventListener("click",function(){
                rejectedCall(data.sender);
                $("#conf-int").removeClass("hidden-status");
                $("#call-status").addClass("hidden-status");
            })
            break;
        case "answer":
            // alert(data.receiver);
            answerProcess(data.answer);
            break;
        case "candidate":
            candidateProcess(data.candidate);
            break;
        case "reject":
            rejectProcess();
            break;
        case "accept":
            acceptProcess();
            break;
        case "leave":
            $("#local-video").css({
                "z-index":0
            });
            $("#conf-int").removeClass("hidden-status");
            $("#call-status").addClass("hidden-status");
            $("#call-status").html('');
            leaveProcess();
            break;
    }
};

var local_video = document.querySelector("#local-video");
var remote_video = document.querySelector("#remote-video");
var stream;
var peerConnection;

setTimeout(function(){
    if(conn.readyState===1) {
        if(userid != null) {
            sendToServer('client-is-ready',null,userid);
        }
    }else {
        console.log("Connection has not been established!");
    }
},3000);

function sendToServer(type,data,target) {
    conn.send(JSON.stringify({
        type:type,
        data:data,
        target:target
    }))
}

function clientProcess(success){
    if(success===true){
        const constrain={
            audio:true,
            video:true
        }
        navigator.mediaDevices.getUserMedia(constrain).then(myStream=>{
            stream=myStream;
            console.log("Got mediaStream:",myStream);
            local_video.srcObject=stream;
            const configuration = {
                "iceServers":[{
                    "url":"stun:stun2.1.google.com:19302"

                }]
            }
            peerConnection = new RTCPeerConnection(configuration);
            console.log('Peer Connection:',peerConnection);
            peerConnection.addStream(stream);
            peerConnection.ontrack = function(event) {
                remote_video.srcObject = event.streams[0];
                $("#conf-int").addClass("hidden-status");
                $("#call-status").removeClass("hidden-status");
                $("#call-status").html(' <div class="calling-wrap"> <div class="call-hang-action"> <div class="videcam-on"> <i class="fa fa-camera video-toggle"></i> </div> <div class="audio-on"> <svg class="audio-toggle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"> <path fill="#fff" d="M11.999 14.942c2.001 0 3.531-1.53 3.531-3.531V4.35c0-2.001-1.53-3.531-3.531-3.531S8.469 2.35 8.469 4.35v7.061c0 2.001 1.53 3.531 3.53 3.531zm6.238-3.53c0 3.531-2.942 6.002-6.237 6.002s-6.237-2.471-6.237-6.002H3.761c0 4.001 3.178 7.297 7.061 7.885v3.884h2.354v-3.884c3.884-.588 7.061-3.884 7.061-7.885h-2z"></path> </svg> </div> <div class="call-cancel"> <i class="fa fa-phone call-cancel-icon"></i> </div> </div> </div>');
                var video_toggle = document.querySelector(".videcam-on");
                video_toggle.onclick = function(){
                    stream.getVideoTracks()[0].enabled = !(stream.getVideoTracks()[0].enabled);
                }
                var audio_toggle = document.querySelector(".audio-on");
                audio_toggle.onclick = function(){
                    stream.getAudioTracks()[0].enabled = !(stream.getAudioTracks()[0].enabled);
                }
                hangup();
            }
            peerConnection.onicecandidate = function(event) {
                if(event.candidate) {
                    sendToServer('candidate', event.candidate, profileid);
                }
            }
        }).catch(error=>{
            console.error("Error accessing media devices.",error);
        })
    }
}

function offerProcess(offer,sender){
    peerConnection.setRemoteDescription(new RTCSessionDescription(offer));
    // alert(sender);
    peerConnection.createAnswer(function(answer){
        sendToServer('answer', answer, sender);
        peerConnection.setLocalDescription(answer);
    },function(error){
        alert("Answer has not created!");
    })
}

function answerProcess(answer){
    peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
}

function candidateProcess(candidate){
    if(candidate){
        try {
            peerConnection.addIceCandidate(candidate);
        } catch(error) {
            console.error('Error Adding received ice candidate',error);
        }
    }
}

function acceptCall(caller_id){
    sendToServer('accept',null,caller_id);
}

function acceptProcess(){
    $("#local-video").css({
        "z-index":2
    });
    $("#conf-int").addClass("hidden-status");
    $("#call-status").removeClass("hidden-status");
    $("#call-status").html(' <div class="calling-wrap"> <div class="call-hang-action"> <div class="videcam-on"> <i class="fa fa-camera video-toggle"></i> </div> <div class="audio-on"> <svg class="audio-toggle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"> <path fill="#fff" d="M11.999 14.942c2.001 0 3.531-1.53 3.531-3.531V4.35c0-2.001-1.53-3.531-3.531-3.531S8.469 2.35 8.469 4.35v7.061c0 2.001 1.53 3.531 3.53 3.531zm6.238-3.53c0 3.531-2.942 6.002-6.237 6.002s-6.237-2.471-6.237-6.002H3.761c0 4.001 3.178 7.297 7.061 7.885v3.884h2.354v-3.884c3.884-.588 7.061-3.884 7.061-7.885h-2z"></path> </svg> </div> <div class="call-cancel"> <i class="fa fa-phone call-cancel-icon"></i> </div> </div> </div>');
}

function rejectedCall(rejected_caller_or_callee){
    sendToServer('reject', null, rejected_caller_or_callee);
}

function rejectProcess(){
    $("#conf-int").removeClass("hidden-status");
    $("#call-status").addClass("hidden-status");
}

function hangup(){
    var call_cancel = document.querySelector(".call-cancel");
    call_cancel.addEventListener("click",function(event){
        $("#local-video").css({
            "z-index":0
        });
        $("#conf-int").removeClass("hidden-status");
        $("#call-status").addClass("hidden-status");
        $("#call-status").html('');
        sendToServer('leave',null,profileid);
        leaveProcess();
    })
}

function leaveProcess(){
    peerConnection.close();
    peerConnection=null;
    remote_video.src=null;
}

$(document).on("click",".video-call", function(){
    var receiver = $(this).data('profileid');
    if(receiver != null) {
        $.post(base_url + 'core/ajax/calleedata.php',{receiver:receiver},function(data) {
            var data = JSON.parse(data);
            $("#conf-int").addClass("hidden-status");
            $("#call-status").removeClass("hidden-status");
            $("#call-status").html(' <div class="user-connected-img"> <img src="'+base_url+data.profileImage+'" alt="'+data.name+'"> </div> <div class="user-status-text"> <div class="user-calling-status">Calling </div> <div class="user-connected-name">'+data.name+'</div> </div> <div class="calling-action"> <div class="call-reject" data-profileid="'+data.receiver+'" data-userid="'+data.sender+'"> <i class="fa fa-close close-icon"></i> </div> </div> </div>');
            var call_reject = document.querySelector(".call-reject");
            call_reject.addEventListener("click",function(){
                alert("Call rejected");
                $("#conf-int").removeClass("hidden-status");
                $("#call-status").addClass("hidden-status");
                rejectedCall(receiver);
            })
        })
        peerConnection.createOffer(function(offer){
            alert("Creating offer!")
            sendToServer('offer', offer, receiver);
            peerConnection.setLocalDescription(offer);
        },function(error){
            alert("Offer has not created!");
        })
    }
})
