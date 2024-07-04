let cartItems = [];
function redirectToCart(){
    populateProducts();
    document.getElementById("products").style.display = "none"; 
    document.getElementById("cart").style.display = "block";
}
function updateButton(productId) {
    const button = document.getElementById('cart_'+productId);
    button.textContent = "Go to Cart";
    button.onclick = redirectToCart;
    
}
function addToCart(product){
    cartItems.push(product);
    updateButton(product?.id);
}

function populateProducts() {
    const productTable = document.getElementById('productTable').getElementsByTagName('tbody')[0];
    const shippingForm = document.getElementById("shippingForm");
    cartItems.forEach(product => {
        const row = productTable.insertRow();
        row.innerHTML = `
            <td class="py-4 px-6 border-b border-gray-200">${product.name}</td>
            <td class="py-4 px-6 border-b border-gray-200">1</td>
        `;

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'product[]';
        input.value = product.id;
        shippingForm.appendChild(input)
    });

    
}

$.validator.addMethod("validPostalCode", function(value, element) {
    return /^\d{6}$/.test(value); // Postal code should be exactly 6 digits
}, "Please enter a valid 6-digit postal code.");

$('#shippingForm').validate({
    rules: {
        email: {
            required: true,
            email: true
        },
        shipping_address_1: {
            required: true
        },
        city: {
            required: true
        },
        country_code: {
            required: true
        },
        zip_postal_code: {
            required: true,
            validPostalCode: true
        }
    },
    messages: {
        email: {
            required: "Please enter your email address",
            email: "Please enter a valid email address"
        },
        shipping_address_1: {
            required: "Please enter your shipping address"
        },
        city: {
            required: "Please enter your city"
        },
        country_code: {
            required: "Please enter your country code"
        },
        zip_postal_code: {
            required: "Please enter your postal code",
            validPostalCode: "Please enter a valid 6-digit postal code"
        }
    },
    submitHandler: function(form) {
        form.submit();
    }
});