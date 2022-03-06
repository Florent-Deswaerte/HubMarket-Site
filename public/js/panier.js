//============== Création variable ==============
//Variable bouton valider panier
//var validerPanier = document.getElementById("validerPanier");

//============== Liaison fonction ==============
//validerPanier.onclick = ValiderLePanier;

//====================== Quantité Prix Panier ======================
//Récupère la classe .pro-qty-2 (La quantité du produit)


//Variables

const buttonsQuantity = document.querySelectorAll('.quantity i');
const textQuantity = document.querySelectorAll('.quantity .qty_item');
const totalGlobal = document.querySelector('#totalGolbal');
const totalGlobalInput = document.querySelector('#totalGolbalInput');
const dataCommande = document.querySelector('#dataCommande');

//Implémente les données de quantité
textQuantity.forEach(texte => {
    let quantiteProduit = parseInt(texte.dataset.quantite);
    texte.innerText = quantiteProduit;
});

//Initialise le total global
comptage();

//Pour boucler sur un array et prendre tous les boutons t écouter les click
buttonsQuantity.forEach(button => {
    button.addEventListener('click', function() {
        //récupère quantity (e est l'événement donc écoute tout et target.parentElement c'est quel bouton est cliqué)
        //const quantity = e.target.parentElement;

        //Récupération des données stockés
        let action = button.dataset.action;
        //Récupère le texte dont l'id du texte === à l'id du button
        let texte = Array.from(textQuantity).find(texte => texte.dataset.id === button.dataset.id);
        let texteTotal = document.querySelector('#total-' + button.dataset.id);
        let textePrixTotal = document.querySelector('#prix-' + button.dataset.id);
        let quantityValue = texte.dataset.quantite;

        //Initialisation des variable quantité et total
        // let total = document.getElementById('total-' + idProduit).innerText;
        // var proPrix = $('.pro-qty-prix')[0].innerText;

        //Condition pour ajouter 1 et supprimer 1
        //3 égales vérifie la valeur et le typage
        if (action === "plus") {
            quantityValue++;
        } else if (action === "moins") {
            quantityValue--;
        }

        if (quantityValue <= 0) {
            quantityValue = 1;
        }
        //Met le texte à jour
        texte.innerText = quantityValue;
        //Met à jour la data de la quantité
        texte.dataset.quantite = quantityValue;
        //Calculer et mettre le total du produit
        prixProduit = parseFloat((quantityValue * textePrixTotal.dataset.prix)).toFixed(2) + " €";
        texteTotal.innerHTML = prixProduit;

        comptage();
    })
});

function comptage() {
    let texteTotal = document.querySelectorAll('.totalPrice');
    let datas = {};

    textQuantity.forEach(texte => {
        datas[texte.dataset.id] = parseFloat(texte.textContent);
    });

    dataCommande.value = JSON.stringify(datas);

    let prixGlobal = 0;
    //Boucle sur le texteTotal et ajoute les éléments (les prix)
    texteTotal.forEach(prix => {
        prixGlobal += parseFloat(prix.textContent);
    });
    //Affiche le total du prix
    totalGlobal.textContent = parseFloat(prixGlobal).toFixed(2) + " €";
    totalGlobalInput.value = parseFloat(prixGlobal).toFixed(2);
};














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