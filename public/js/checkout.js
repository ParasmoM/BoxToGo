// Initialisation de Stripe
const stripe = Stripe(document.querySelector("#payment-form").dataset.apiPublicKey);
const clientSecret = document.querySelector("#payment-form").dataset.clientSecret;
const succesUrl = document.querySelector("#payment-form").dataset.returnUrl;
console.log(succesUrl); 
const appearance = { /* appearance */ };
const options = { mode: 'billing' };

// Création des éléments de formulaire
const elements = stripe.elements({
    clientSecret,
    appearance
});

const linkAuthElement = elements.create('linkAuthentication');
linkAuthElement.mount('#link-authentication-element');

const paymentElement = elements.create('payment', {
    fields: {
        billingDetails: {
            name: 'never',
            email: 'never',
        }
    }
});
paymentElement.mount('#payment-element');

const addressElement = elements.create('address', {
    mode: "shipping",
});
addressElement.mount('#address-element');

addressElement.on('change', (event) => {
    if (event.complete){
      // Extract potentially complete address
        const address = event.value.address;
        const name = event.value.name;
        console.log(address, name);
    }
})

// Gestion de la soumission du formulaire
const form = document.getElementById('payment-form');





async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);

    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: "https://127.0.0.1:8000/",
            receipt_email: emailAddress,
        },
    });

    // This point will only be reached if there is an immediate error when
    // confirming the payment. Otherwise, your customer will be redirected to
    // your `return_url`. For some payment methods like iDEAL, your customer will
    // be redirected to an intermediate site first to authorize the payment, then
    // redirected to the `return_url`.
    if (error.type === "card_error" || error.type === "validation_error") {
        showMessage(error.message);
    } else {
        showMessage("An unexpected error occurred.");
    }

    setLoading(false);
}

// Fetches the payment intent status after payment submission
async function checkStatus() {
    const clientSecret = new URLSearchParams(window.location.search).get(
      "payment_intent_client_secret"
    );
  
    if (!clientSecret) {
      return;
    }
  
    const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);
  
    switch (paymentIntent.status) {
      case "succeeded":
        showMessage("Payment succeeded!");
        break;
      case "processing":
        showMessage("Your payment is processing.");
        break;
      case "requires_payment_method":
        showMessage("Your payment was not successful, please try again.");
        break;
      default:
        showMessage("Something went wrong.");
        break;
    }
  }
  
  // ------- UI helpers -------
  
  function showMessage(messageText) {
    const messageContainer = document.querySelector("#payment-message");
  
    messageContainer.classList.remove("hidden");
    messageContainer.textContent = messageText;
  
    setTimeout(function () {
      messageContainer.classList.add("hidden");
      messageContainer.textContent = "";
    }, 4000);
  }
  
  // Show a spinner on payment submission
  function setLoading(isLoading) {
    if (isLoading) {
      // Disable the button and show a spinner
      document.querySelector("#submit").disabled = true;
      document.querySelector("#spinner").classList.remove("hidden");
      document.querySelector("#button-text").classList.add("hidden");
    } else {
      document.querySelector("#submit").disabled = false;
      document.querySelector("#spinner").classList.add("hidden");
      document.querySelector("#button-text").classList.remove("hidden");
    }
  }
// // Création des éléments de formulaire
// const elements = stripe.elements();

// const style = {
//     base: {
//         color: '#32325d',
//         fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
//         fontSmoothing: 'antialiased',
//         fontSize: '16px',
//         '::placeholder': {
//             color: '#aab7c4'
//         }
//     },
//     invalid: {
//         color: '#fa755a',
//         iconColor: '#fa755a'
//     }
// };

// // Monter le form a l'objet stripe
// const card = elements.create('card', { style: style });
// card.mount('#card-element');

// // Gestion de la soumission du formulaire
// const form = document.getElementById('payment-form');

// form.addEventListener('submit', function(event) {
//     event.preventDefault();

//     stripe.confirmCardPayment(clientSecret, {

//         payment_method: {
//             card: card
//         }

//     }).then(function(result) {

//         if (result.error) {
//             // Afficher l'erreur
//             console.log(result.error.message);
//         } else {
//             if (result.paymentIntent.status === 'succeeded') {
//                 // Le paiement a été effectué !
//                 console.log('Paiement réussi', result);
//                 stripeTokenHandler(result.paymentIntent);
//                 console.log(result.paymentIntent);
//             }
//         }

//     });
// });

// // Gestion des erreurs 
// const displayError = document.querySelector("#card-errors");
// card.addEventListener('change', function(event) {
//     if (event.error) {
//         displayError.textContent = event.error.message;
//     } else {
//         displayError.textContent
//     }
// });