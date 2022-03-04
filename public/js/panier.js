//============== Création variable ==============
//Variable bouton valider panier
//var validerPanier = document.getElementById("validerPanier");

//============== Liaison fonction ==============
//validerPanier.onclick = ValiderLePanier;

//====================== Quantité Prix Panier ======================
//Récupère la classe .pro-qty-2 (La quantité du produit)


//Variable bouton
const buttonsQuantity = document.querySelectorAll('.quantity i');

//Pour boucler sur un array et prendre tous les boutons t écouter les click
buttonsQuantity.forEach(button => {
    button.addEventListener('click', (e) => {
        //récupère quantity (e est l'événement donc écoute tout et target.parentElement c'est quel bouton est cliqué)
        //const quantity = e.target.parentElement;

        //Récupération des données stockés
        let idProduit = button.dataset.id;
        let action = button.dataset.action;

        //Initialisation des variable quantité et total
        let quantity = document.getElementById('qte-' + idProduit).innerText;
        let total = document.getElementById('total-' + idProduit).innerText;
        var proPrix = $('.pro-qty-prix')[0].innerText;

        //Condition pour ajouter 1 et supprimer 1
        if (action = "plus") {
            var incrementation = parseFloat(quantity) + 1;
            total = proPrix * incrementation;
            console.log(incrementation);
        } else if (action = "moins") {
            if (parseFloat(quantity) != 1) {
                var decrementation = parseFloat(quantity) - 1;
                total = proPrix * decrementation;
                console.log(decrementation);
            }
        }
    })
});














// var proQty = $('.pro-qty-2');
// //Span bouton pour enlever 1

// //Function clique des boutons
// proQty.on('click', '.qtybtn', function() {
//     var $button = $(this);
//     var oldValue = $button.parent().find('input').val();

//     //Récupère la classe .pro-qty-prix (Le prix du produit)
//     var proPrix = $('.pro-qty-prix')[0].innerText;

//     //Récupère la classe .cart__price (Le total du produit)
//     var proTotal = $('.cart__price')[0].innerText;

//     if ($button.hasClass('inc')) {
//         var newVal = parseFloat(oldValue) + 1;
//         var proTotalCalcul = proPrix * newVal;
//         $('.cart__price')[0].innerText = proTotalCalcul;
//     } else {
//         // Pas décrémenter en dessous de 0
//         if (oldValue > 0) {
//             var newVal = parseFloat(oldValue) - 1;
//             var proTotalCalcul = proPrix * newVal;
//             $('.cart__price')[0].innerText = proTotalCalcul;
//         } else {
//             newVal = 0;
//         }
//     }
//     $button.parent().find('input').val(newVal);
// });
// /*------------------
//     Fin Quantité Prix
// --------------------*/