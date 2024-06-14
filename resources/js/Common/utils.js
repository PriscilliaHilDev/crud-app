
// // Fonction pour mettre à jour le message
// export default {

//     updateTitleHead : function (route) {
//         switch(route) {
//             case 'post.list':
//                 return 'Les différents articles du blog';
//             case 'post.edit':
//                 return 'Edition de votre article';
//             case 'post.add':
//                 return 'Creation de votre article';
//             default:
//                 return 'Bienvenue';
//         }
//     }
// } 

export function truncateByWords(text, maxWords) {
    const words = text.split(' '); // Séparer le texte en mots
    if (words.length <= maxWords) {
      return text; // Si le nombre de mots est inférieur ou égal à maxWords, retourner le texte entier
    }
    return words.slice(0, maxWords).join(' ') + '...'; // Tronquer et ajouter des points de suspension
  }

  export function formatDate(isoDate) {
    const date = new Date(isoDate);
    return date.toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: 'long',
      year: 'numeric'
    });
}
