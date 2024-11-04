let cartItems = [];

function addToCheckout(productName, productImage, productPrice) {
    // Add the product to the cart items array
    cartItems.push({
        name: productName,
        image: productImage,
        price: productPrice
    });

    // Update the checkout page
    updateCheckoutPage();
}

function updateCheckoutPage() {
    // Get the checkout order product list
    const checkoutOrderProduct = document.querySelector(".checkout__order__product ul");
    checkoutOrderProduct.innerHTML = "";

    // Add each item to the checkout order product list
    cartItems.forEach(item => {
        const listItem = document.createElement("li");
        listItem.innerHTML = `
            <span class="top__text">
                <img src="${item.image}" alt="${item.name}">
                ${item.name}
            </span>
            <span class="top__text__right">${item.price.toFixed(2)} TND</span>
        `;
        checkoutOrderProduct.appendChild(listItem);
    });

    // Update the total
    const total = cartItems.reduce((acc, item) => acc + item.price, 0);
    document.querySelector(".checkout__order__total ul li:last-child span").textContent = `${total.toFixed(2)} TND`;
}