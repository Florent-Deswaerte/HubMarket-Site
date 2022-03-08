    var card = elements.create('card', {
        iconStyle: 'solid',
        style: {
            base: {
                iconColor: '#8898AA',
                color: 'black',
                lineHeight: '36px',
                fontWeight: 300,
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSize: '19px',

                '::placeholder': {
                    color: '#8898AA',
                },
            },
            invalid: {
                iconColor: '#e85746',
                color: '#e85746',
            }
        },
        classes: {
            focus: 'is-focused',
            empty: 'is-empty',
        },
    });
    card.mount('#card-element');

    var inputs = document.querySelectorAll('input.field');
    Array.prototype.forEach.call(inputs, function(input) {
        input.addEventListener('focus', function() {
            input.classList.add('is-focused');
        });
        input.addEventListener('blur', function() {
            input.classList.remove('is-focused');
        });
        input.addEventListener('keyup', function() {
            if (input.value.length === 0) {
                input.classList.add('is-empty');
            } else {
                input.classList.remove('is-empty');
            }
        });
    });

    function setOutcome(result) {
        var successElement = document.querySelector('.success');
        var errorElement = document.querySelector('.error');
        successElement.classList.remove('visible');
        errorElement.classList.remove('visible');

        if (result.token) {
            // Use the token to create a charge or a customer
            // https://stripe.com/docs/charges
            successElement.querySelector('.token').textContent = ''; //A changer <=====================================================
            successElement.classList.add('visible');
        } else if (result.error) {
            errorElement.textContent = result.error.message;
            errorElement.classList.add('visible');
        }
    }

    card.on('change', function(event) {
        setOutcome(event);
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        //Stop le chargement de la page
        e.preventDefault();

        stripe.handleCardPayment(
                clientSecret,
                card, {
                    payment_method_data: {
                        billing_details: {
                            email: cardHolderEmail
                        }
                    }
                }
            ).then((result) => {
                if (result.error) {

                } else if ('paymentIntent' in result) {
                    //console.log('Result : ', result);
                    stripeTokenHandler(result.paymentIntent);
                    //console.log('Result intentpayement : ', result.paymentIntent);
                }
            }) //Retour réponse paiement
    });

    function stripeTokenHandler(intent) {
        var form = document.getElementById("payment-form");
        //Information à envoyer dans notre formulaire en post
        var InputIntentId = document.createElement('input');
        var InputIntentPaymentMethod = document.createElement('input');
        var InputIntentStatus = document.createElement('input');
        var InputIntentSubscription = document.createElement('input');

        InputIntentId.setAttribute('type', 'hidden');
        InputIntentId.setAttribute('name', 'stripeIntentId');
        InputIntentId.setAttribute('value', intent.id);

        InputIntentPaymentMethod.setAttribute('type', 'hidden');
        InputIntentPaymentMethod.setAttribute('name', 'stripeIntentPaymentMethod');
        InputIntentPaymentMethod.setAttribute('value', intent.payment_method);

        InputIntentStatus.setAttribute('type', 'hidden');
        InputIntentStatus.setAttribute('name', 'stripeIntentStatus');
        InputIntentStatus.setAttribute('value', intent.status);

        InputIntentSubscription.setAttribute('type', 'hidden');
        InputIntentSubscription.setAttribute('name', 'subscription');
        InputIntentSubscription.setAttribute('value', subscription);

        //Mettre tous dans le form
        form.appendChild(InputIntentId);
        form.appendChild(InputIntentPaymentMethod);
        form.appendChild(InputIntentStatus);
        form.appendChild(InputIntentSubscription);
        form.submit();
    }