import React, { useState } from 'react';

const MsgItem = (props) => {
    let [time, setTime] = useState(0), itsMe = props.item.user.id===props.user.id;
    return (
        <li className={`msg-item ${itsMe ? 'right': 'left'}`}>
            {itsMe ? null : (<div className="avatar"><img src={props.item.user.picture} /></div>)}
            <div className="msg-content"><div onClick={()=>setTime(!time)} className={`msg-text ${itsMe ? 'bg-primary text-white': 'bg-success'}`}>{msgText(props.item.message)}</div>
            {time ? (<div className="msg-time text-muted">{ window.moment().diff(props.item.created, 'days') > 2 ? window.moment(props.item.created).format("DD MMM, YYYY, h:mm A") : window.moment(props.item.created).fromNow()}</div>):null}</div>
        </li>
    )
}

const msgText = (message) => {
    let file_url = "/assets/content/file.png";
    if( typeof(message)==='object' ){
        let isImage = (message.data.type||"").indexOf('image/') !== -1;
        return message.type==='file' ? (
            <a target="_blank" href={message.data.link}>{isImage ? (<img className="image-preview" src={message.data.link } alt={message.data.name} />): message.data.name}</a>
        ):null;
    }
    return message;
}

const ChatList = (props) => {
    if( props.loading ){
        return (
            <div className="loading-container">
                <div className="loading-messages">
                    <div className="spinner-grow" role="status">
                        <span className="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        );
    }
    return (
        <React.Fragment>
            {props.messages.length > 0 ? (
                <ul className="message-list" ref={(node) => { props.listNode(node); }}>
                    {props.previous ? (
                        <li className="load-more-container"><button onClick={props.loadMore} className="btn btn-outline-light btn-sm text-dark">Load More</button></li>
                    ):null}
                    {[...props.messages].map((item, index)=>(
                        <MsgItem item={item} user={props.user} key={index} />
                    ))}
                    {props.typing ? (
                        <li className="msg-item left">
                            <div className="avatar"><span className="icofont-user-alt-3 icon"></span></div>
                            <div className="msg-content typing bg-warning font-italic msg-text py-1" title="typing...">
                                <div className="msg-typing"><span></span><span></span><span></span></div>
                            </div>
                        </li>
                    ):null}
                </ul>
            ):(
                <div className="new-msg">
                    <p>Write something to start the conversation.</p>
                </div>
            )}
        </React.Fragment>
    )
}

export default ChatList;
