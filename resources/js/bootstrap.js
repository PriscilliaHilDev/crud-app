

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

// const url = "http://127.0.0.1:8000"; // Assurez-vous que cette variable est définie dans .env

// var pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
//   cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, 
//   channelAuthorization: {
//     endpoint: `${url}/broadcasting/auth`, // Point de terminaison de votre serveur pour l'autorisation
//   }
// });

// // Utilisez votre canal privé
// var channel = pusher.subscribe('private-user.31');

// // Écoutez les événements sur le canal
// channel.bind('my-event', function(data) {
//   console.log('Received data: ', data);
//   alert('Received data: ' + data);
// });

// const userId = document.head.querySelector('meta[name="user-id"]').content;

// if(userId){
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
      });
      
    //   if(window.Echo){
    //       window.Echo.private(`user.${userId}`)
    //       .listen('.comment-added', (e) => {
    //         console.log('Received event:', e);
    //     });
    //   }      
// }


window.Pusher.logToConsole = true;
