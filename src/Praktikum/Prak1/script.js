document.addEventListener('DOMContentLoaded', function() {
    const cart = [];
    const totalElement = document.getElementById('total');
    const cartItemsElement = document.querySelector('.warenkorb_items ul');
    const delete_button = document.getElementById('deleteBtn');
    const bestellen_button = document.getElementById('bestellenBtn')

    function updateCartAndTotal() {
        cartItemsElement.innerHTML = '';
        let total = 0;

        for (const item of cart) {
            const listItem = document.createElement('li');
            listItem.textContent = item.name;
            cartItemsElement.appendChild(listItem);
            total += item.price;
        }

        totalElement.textContent = total;
    }

    const pizzaContainers = document.querySelectorAll('.pizza_container');
    pizzaContainers.forEach((container, index) => {
        container.addEventListener('click', () => {
            const pizzaName = Object.keys(pizzaDetails)[index];
            const pizzaPrice = pizzaDetails[pizzaName].price;

            cart.push({ name: pizzaName, price: pizzaPrice });

            updateCartAndTotal();
        });
    });

    // Add a click event to the Delete button
    delete_button.addEventListener('click', () => {
        // Clear the cart and update the display
        cart.length = 0;
        updateCartAndTotal();
    });

    bestellen_button.addEventListener('click', () => {
        
    })
});
