window._ = require('lodash');
window.moment = require('moment');

if( typeof window.jQuery === 'undefined' ){
    window.jQuery = require('jquery');
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
window.axios.defaults.baseURL = '/common';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Socketio from 'socket.io-client';
// Initialize the socket connection.
window.Echo = new Echo({
    broadcaster: 'socket.io',
    client: Socketio,
    host: window.location.hostname + ':6001',
    namespace: 'App.Events.Broadcast'
});
