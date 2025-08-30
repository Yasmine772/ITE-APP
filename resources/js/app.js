// resources/js/app.js
import './bootstrap';
import '../css/app.css';


import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel('chat')
    .listen('.message.sent', (e) => {
        Livewire.emit('refreshChat', e.message);
    });
