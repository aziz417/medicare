import React from 'react';
import ReactDOM from 'react-dom';
import ChatPopup from './ChatPopup';

class ChatApp extends React.Component {
    render(){
        return <ChatPopup {...this.props} />
    }
}

export default ChatApp;

if (document.getElementById('chat-box')) {
    const component = document.getElementById('chat-box');
    const props = Object.assign({}, component.dataset);
    ReactDOM.render(<ChatApp {...props} />, component);
}