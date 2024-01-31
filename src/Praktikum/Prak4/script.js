"use strict";
document.addEventListener('DOMContentLoaded', function() {
    const cart = [];
    const totalElement = document.getElementById('total');
    const cartItemsElement = document.querySelector('.warenkorb_items ul');
    const delete_btn = document.querySelector('.delete_btn');
    const bestellenBtn = document.getElementById('bestellenBtn'); // Get the bestellen button
    let selectedPizzasInput;

    function updateSelectedPizzasInput() {
        selectedPizzasInput = document.getElementById('selected_pizzas_input');
        selectedPizzasInput.value = JSON.stringify(cart);
    }

    function updateCartAndTotal() {
        while (cartItemsElement.firstChild) {
            cartItemsElement.removeChild(cartItemsElement.firstChild);
        }
    
        let total = 0;
    
        cart.forEach((item, index) => {
            const listItem = document.createElement('li');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = index;
            checkbox.name = 'deleteCheckbox';
            listItem.appendChild(checkbox);
            const itemNameSpan = document.createElement('span');
            itemNameSpan.textContent = item.name;
            listItem.appendChild(itemNameSpan);
            cartItemsElement.appendChild(listItem);
            total += item.price;
        });
    
        totalElement.textContent = total;
        updateSelectedPizzasInput(); 
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

    document.addEventListener('change', function(event) {
        if (event.target.name === 'deleteCheckbox') {
            const index = parseInt(event.target.value, 10);
            cart.splice(index, 1);
            updateCartAndTotal();
        }
    });

    delete_btn.addEventListener('click', function() {
        cart.length = 0;
        updateCartAndTotal();
    });

    // Add event listener to the bestellen button
    bestellenBtn.addEventListener('click', function(event) {
        // Check if cart is empty before submitting
        if (cart.length === 0) {
            event.preventDefault(); // Prevent form submission
            alert('Please select at least one pizza before ordering.');
        }
    });

});
