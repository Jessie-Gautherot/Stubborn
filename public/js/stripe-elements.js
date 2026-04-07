// Initialisation Stripe
const stripe = Stripe(stripePublicKey);
const elements = stripe.elements();

// Crée un champ de carte
const cardElement = elements.create('card');
cardElement.mount('#card-element');

// Gestion du clic sur "Payer"
const payButton = document.getElementById('pay');
payButton.addEventListener('click', async () => {
    payButton.disabled = false;
    const {error, paymentIntent} = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
            card: cardElement,
        }
    });

    const messageDiv = document.getElementById('payment-message');
    if (error) {
        messageDiv.style.color = 'red';
        messageDiv.textContent = `Erreur: ${error.message}`;
        payButton.disabled = false;
   } else if (paymentIntent.status === 'succeeded') {
        messageDiv.style.color = 'green';
        messageDiv.textContent = 'Paiement réussi !';

        // Vider le panier
        fetch('/cart/clear', { method: 'POST' })
            .then(response => {
                if (!response.ok) throw new Error('Impossible de vider le panier');
                // Redirection vers le panier vide
                window.location.href = '/cart';
            })
            .catch(err => console.error(err));
    }
});