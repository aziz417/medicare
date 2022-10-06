import React from 'react';

const ChatHeader = (props) => {
    return (
        <div className="chat-topbar">
            <span title={props.online?'Active':'Offline'} className={`active-light ${props.online ? 'active':''}`}></span>
            <h3 title={props.title || "Chatbox"}><a href={props.link || '#'}>{props.title || "Chatbox"}</a></h3>
            <button type="button" onClick={props.close} className="close"aria-label="Close"><span className="icofont-close-line"></span></button>
        </div>
    )
}

export default ChatHeader;
