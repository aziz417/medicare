import React from 'react';
import ReactDOM from 'react-dom';

class Notify extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            unread: Number(this.props.unread),
            notifications: JSON.parse(this.props.items),
            user: JSON.parse(this.props.user),
        }
    }
    componentDidMount(){
        window.Echo.private('User.Notifications.' + this.state.user.id)
        .notification((notification) => {
            if( notification.type === "App\\Notifications\\BasicNotification" ){
                this.setState({
                    notifications: [notification].concat(this.state.notifications),
                    unread: this.state.unread + 1
                });
                window.playNotificationSound('notification');
                window.showToaster(notification.data.message, {
                    time: moment(notification.created_at).fromNow(),
                    title: notification?.data?.title || "Notifications"
                });
            }
        });
    }

    markAsRead(){
        axios.post('/notifications', {
            read: true,
        }).then(response=>{
            let notifications = this.state.notifications.map(item=>{
                item.read_at = moment()
                return item;
            });
            if( response.data?.status === true ){
                this.setState({
                    unread: 0,
                    notifications: notifications
                })
            }
        })
    }

    getNotificationItem(item){
        let content = (
            <React.Fragment>
                <span className={`icon icofont-${item.data.icon||'heart'}`}></span>
                <div className="content"><span className="desc">{item.data.message}</span> <span className="date">{moment(item.created_at).fromNow()}</span>
                </div>
            </React.Fragment>
        );
        return (<a className={item.read_at ? '': 'text-danger'} href={item.data?.link||'/'}>{content}</a>)
    }

    render() {
        return (
            <div className="dropdown item">
                <button className="no-style dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="0, 12">
                    <span className="icon icofont-notification"></span>
                    {this.state.unread > 0 ? (
                        <span className="badge badge-danger badge-sm">{this.state.unread}</span>
                    ): null}
                </button>
                <div className="dropdown-menu dropdown-menu-right dropdown-menu-w-280">
                    <div className="menu-header">
                        <h4 className="h5 menu-title mt-0 mb-0">Notifications</h4>
                        <span onClick={this.markAsRead.bind(this)} className="text-danger cursor-pointer">Clear All</span>
                    </div>
                    <ul className="list">
                        {this.state.notifications.map(item=>(
                            <li key={item.id}>{this.getNotificationItem(item)}</li>
                        ))}
                    </ul>
                </div>
            </div>
        );
    }
}

export default Notify;

if (document.getElementById('notifications')) {
    const component = document.getElementById('notifications');
    const props = Object.assign({}, component.dataset);
    ReactDOM.render(<Notify {...props} />, component);
}