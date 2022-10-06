import React from 'react';
import ChatHeader from './ChatHeader';
import ChatList from './ChatList';
import ChatForm from './ChatForm';

class ChatPopup extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            messages: [],
            previous: false,
            loading: true,
            user: JSON.parse(this.props.user),
            room: JSON.parse(this.props.room),
            text: '',
            showPopup: false,
            newMsg: 0,
            typing: false,
            online: false,
        }
        this.chatRoom = window.Echo.join(`room.${this.state.room.id}`);
        // this.chatRoom = window.Echo.private(`room.${this.state.room.id}`);
    }
    loadExistingMessages(){
        window.axios.get(`/message/${this.state.room.id}`)
            .then(response=>{
                if( response.data?.status === true ){
                    this.setState({
                        messages: response.data.data||[],
                        previous: response.data.previous,
                        loading: false
                    }, ()=>{
                        this.scrollToBottom();
                    });
                }else{
                    this.setState({
                        loading: false
                    })
                }
            });
    }
    handleLoadMoreButtonClick(){
        if( ! this.state.previous ){
            return ;
        }
        window.axios.get(this.state.previous)
            .then(response=>{
                if( response.data?.status === true ){
                    this.setState({
                        messages: [...response.data.data].concat(this.state.messages),
                        previous: response.data.previous,
                        noScroll: true
                    }, ()=>{
                        let items = response.data.data.length ?? response.data.perpage;
                        this.scrollToBottom(`li:nth-child(${items})`)
                    });
                }
            });
    }
    componentDidMount(){
        this.loadExistingMessages();
    
        this.chatRoom.listen('MessageSent', (event) => {
            console.log(event);
            this.setState({
                messages: this.state.messages.concat(event.message),
                typing: false
            }, ()=>{
                this.newMessage(event);
            });
        })
        .here((users)=>{
            let other = users.find(item=>item.id !== this.state.user.id);
            if( other ){
                this.setState({ online: true });
            }
        })
        .joining((user) => {
            if( this.state.user.id !== event.user?.id ){
                this.setState({ online: true });
            }
        })
        .leaving((user) => {
            if( this.state.user.id !== event.user?.id ){
                this.setState({ online: false });
            }
        });
        this.chatRoom.listenForWhisper('typing', (event) => {
            if( this.state.user.id !== event.user.id ){
                this.setState({ typing: true });
                clearTimeout(this.userTypingTimeout);
            }
            this.userTypingTimeout = setTimeout( () => { this.setState({ typing: false }); } , 1500);
        });
    }
    newMessage(event){
        if( ! this.state.showPopup || document.activeElement !== this.textInput ){
            window.playNotificationSound('message');
            this.setState({
                newMsg: this.state.newMsg + 1
            });
        }
    }
    toggleChatbox(){
        this.setState({
            showPopup: ! this.state.showPopup,
            newMsg: 0
        });
    }
    handleFormSubmit(event){
        let message = this.state.text.trim();
        if( ! message ){
            event.preventDefault();
            if(this.textInput){this.textInput.focus();}
            return;
        }
        this.setState({text: ''});
        window.axios.post(`/message/${this.state.room.id}`, {
            message: message,
            room_id: this.state.room.id,
        }).then(response=>{
            if( response.data?.status === true ){
                this.setState({
                    messages: this.state.messages.concat(response.data.data),
                    text: ''
                });
            }
        }).catch(error=>{
            this.setState({text: message});
            if( window.navigator.onLine ){
                showToaster(error.response?.data?.message ?? 'Something is wrong!', {title: "Chat Box"})
            }else{
                showToaster(error.response?.data?.message ?? 'Something is wrong with your internet connection!', {title: "Offline"})
            }

        })
        if(this.textInput){this.textInput.focus();}
        event.preventDefault();
    }
    updateNewMessage(message){
        if( ! message.message ){
            return false;
        }
        this.setState({
            messages: this.state.messages.concat(message),
            text: ''
        }, ()=>{
            this.scrollToBottom();
        });
    }
    componentDidUpdate() {
        this.scrollToBottom();
    }
    setMessageListNode(node){
        this.messageList = node;
    }
    setInputBoxNode(node){
        this.textInput = node;
    }
    scrollToBottom(element = false) {
        if( ! this.messageList ){return false;}
        if( element ){
            this.messageList.querySelector(element).scrollIntoView({behavior: "auto"});
            return true;
        }
        const scrollHeight = this.messageList.scrollHeight + this.messageList.clientHeight;
        this.messageList.scrollTop = scrollHeight > 0 ? scrollHeight : 0;
        setTimeout(()=>{
            this.messageList.querySelector(`li:last-child`).scrollIntoView({behavior: "smooth"})
        }, 500)
    }
    inputChangeHandler(event){
        this.setState({
            text: event.target.value
        });
        setTimeout( () => {
            this.chatRoom.whisper('typing', {user: this.state.user});
        }, 300);
    }

    render() {
        let chatBoxType = `chat-box-${this.props.type||'popup'}`;
        let serializeId = `#${this.state.room?.id}${this.state.room.doctor_id}${this.state.room.user_id}`;
        let ID = (this.state.room?.appointment_code ?? serializeId);
        if( ! this.state.showPopup && (this.props.type||'popup')==='popup' ){
            return (
                <div className="chat-box-button" onClick={this.toggleChatbox.bind(this)}>
                    {this.state.newMsg > 0 ? (<span className="msg-badge">{this.state.newMsg}</span>):null}
                    <div className="icon icofont-paper-plane"></div>
                </div>
            )
        }
        return (
            <div className={`chat-box-module ${chatBoxType} chatbox-${this.state.room?.id}`}>
                <ChatHeader
                    title={`Appointment ${ID}`}
                    link={this.props.link}
                    online={this.state.online}
                    close={this.toggleChatbox.bind(this)}/>
                <ChatList 
                    loading={this.state.loading}
                    listNode={this.setMessageListNode.bind(this)}
                    messages={this.state.messages}
                    user={this.state.user}
                    typing={this.state.typing}
                    previous={this.state.previous}
                    loadMore={this.handleLoadMoreButtonClick.bind(this)}
                    />
                <ChatForm 
                    inpuNode={this.setInputBoxNode.bind(this)}
                    message={this.state.text}
                    user={this.state.user}
                    updateMsg={this.updateNewMessage.bind(this)}
                    room_id={this.state.room.id}
                    handleSubmit={this.handleFormSubmit.bind(this)} 
                    inputChange={this.inputChangeHandler.bind(this)} 
                    />
            </div>
        );
    }
}
export default ChatPopup;