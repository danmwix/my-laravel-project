import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'f4429a91661aa429f6c1', // Your actual Pusher app key
    cluster: 'mt1', // Your actual Pusher cluster
    forceTLS: true // Ensures encryption
});

