import React, { useState } from 'react';
axios = require('axios');

const ChatForm = (props) => {
    const handleEachKeyPress = (event)=>{
        if(event.keyCode === 13 && !event.shiftKey){
            return props.handleSubmit(event);
        }
    }

    const uploadImage = (event)=>{
        const formData = new FormData();
        let file = event.target.files[0];
            event.target.value = null;
        formData.append('document', file);
        window.axios.post(`/message/${props.room_id}/upload`, formData)
            .then(response=>{
                props.updateMsg(response.data.data);
            })
    }
    return (
        <form onSubmit={props.handleSubmit} className="form">
            <div className="input-group">
                {/*<input ref={(node)=>props.inpuNode(node)} className="message-input" type="text" onChange={props.inputChange} value={props.message} placeholder="Write Something..." autoFocus={true}/>*/}
                <textarea ref={(node)=>props.inpuNode(node)} className="message-input" type="text" onChange={props.inputChange} onKeyDown={handleEachKeyPress} placeholder="Write Something..." autoFocus={true} value={props.message} cols="30" rows="1" />
                {props.message === "" ? (
                <div className="image-upload"><label className="icon icofont-image"><input onChange={uploadImage} type="file" accept="image/*,application/pdf" className="upload-image"/></label></div>
                ):null}
                <button className="send-btn" type="submit"><span className="icon icofont-paper-plane"></span></button>
          </div>
        </form>
    )
}

export default ChatForm;
