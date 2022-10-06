import React from 'react';
import ReactDOM from 'react-dom';

class Call extends React.Component {
    constructor(props){
        super(props);
        this.channel = null;
        this.state = {
            user: this.props?.user || __App.user,
            valid: false,
            data: null,
            audio: null
        }
    }
    componentDidMount(){
        window.Echo.private('User.Notifications.' + this.state.user.id)
        .notification((notification) => {
            if( this.state.data || this.state.audio ){
                this.state.audio.stop();
                window.jQuery('#calling').modal('hide');
                this.setState({ valid: false, data: null, audio: null });
            }
            if( notification.type==="App\\Notifications\\StartCall"){
                let audio = window.playNotificationSound('call', {autoplay:true, playsinline:true, loop: true});
                this.setState({
                    data: notification,
                    valid:true,
                    audio: audio
                });
                setTimeout(()=>{
                    window.jQuery('#calling').modal('show');
                }, 1000);
                setTimeout(()=>{
                    audio.stop();
                }, 60 * 1000) // ringing off after 1 minutes
            }
        });
    }
    handleRejectCall(){
        window.jQuery('#calling').modal('hide');
        this.state.audio.stop();
    }
    handleJoinCall(){
        this.state.audio.stop();
        window.open(this.state.data?.result?.join_url, "", "width=960,height=590,left=100,top=100");
        window.jQuery('#calling').modal('hide');
    }

    render() {
        if( ! this.state.data ){
            return null;
        }
        const user = this.state.data.result[this.state.data?.start_by || 'doctor'];
        return (
            <div className="modal fade call-modal" id="calling" data-backdrop="static">
                <div className="modal-dialog modal-dialog-centered" role="document">
                    <div className="modal-content">
                        <div className="modal-body">
                            <div className="call-box incoming-box">
                                <div className="call-wrapper">
                                    <div className="call-inner">
                                        <div className="call-user">
                                            <img alt={user.name} src={user.picture || "/assets/content/user.png"} className="call-avatar"/>
                                            <h4>{user.name}</h4>
                                            <span>Ask to join...</span>
                                        </div>                            
                                        <div className="call-items">
                                            <button onClick={this.handleRejectCall.bind(this)} className="btn call-item call-end" data-dismiss="modal" aria-label="Close">End</button>
                                            <button onClick={this.handleJoinCall.bind(this)} className="btn call-item call-start">Join</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Call;

if (document.getElementById('start-call')) {
    const component = document.getElementById('start-call');
    const props = Object.assign({}, component.dataset);
    ReactDOM.render(<Call {...props} />, component);
}