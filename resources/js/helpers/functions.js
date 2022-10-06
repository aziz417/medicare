import React from 'react';
import ReactDOM from 'react-dom';
import Toaster from './Toaster';

Audio.prototype.stop = function() {
    this.pause();
    this.currentTime = 0;
};

window.playNotificationSound = function(type = 'alert', options={}) {
    let sounds = {
        call: new Audio('/assets/sounds/call-alert.mp3'),
        default: new Audio('/assets/sounds/simple-alert.mp3'),
        message: new Audio('/assets/sounds/message-alert.mp3'),
        notification: new Audio('/assets/sounds/notification-alert.mp3')
    };
    let audio = sounds[type] || sounds['default'];
    for(let index in options){
        audio[index] = options[index];
    }
    audio.play();
    return audio;
}

window.showToaster = function(message, props) {
    let { time, title, timeout = 5000 } = props||{};
    let component = document.getElementById('toaster');
    if ( ! component ) {
        component = document.createElement("div");
        component.id = "toaster";
        document.body.append(component);
    }
    if( timeout !== false ){
        setTimeout(()=>{
            component.remove();
        }, timeout);
    }
    let onClose = ()=>{component.remove()}
    ReactDOM.render(
        <Toaster close={onClose} message={message} title={title} time={time} />, component
    );
}
