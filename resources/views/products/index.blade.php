<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .required::after {
            content: "*";
            color: red;
        }
        .error{
            color: red;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
            animation: fadeOut 5s forwards;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
            animation: fadeOut 5s forwards;
        }

@keyframes fadeOut {
    0% { opacity: 1; }
    100% { opacity: 0; display: none; }
}

    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include jQuery Validate plugin -->
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    
</head>
<body class="bg-gray-100 p-4">
    
    <div class="container mx-auto">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products">
            
            <!-- Product Card -->
            @if(isset($products) && count($products) > 0)
                @foreach($products AS $product)
                    <div class="bg-white rounded-lg overflow-hidden shadow-md">
                        <img src="{{asset('productImages/'.$product->image_url)}}" alt="Product Image" class="w-full h-64 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold mb-2">{{ $product->name }}</h2>
                            <div class="flex justify-between items-center">
                                
                                <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" id="{{'cart_'.$product->id}}" onclick="addToCart({{$product}})">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md" id="cart" style="display: none;">
            <h1 class="text-2xl font-bold mb-8">Your Shopping Cart</h1>

            <!-- Product List -->
            <div class="mb-8">
                <table class="w-full" id="productTable">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border-b border-gray-200">Product Name</th>
                            <th class="py-2 px-4 border-b border-gray-200">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

            <!-- Shipping Information Form -->
            <form method="POST" class="space-y-4" id="shippingForm" action="{{route('order')}}">
                @csrf
                <h2 class="text-xl font-bold mb-4">Shipping Information</h2>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 required">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <!-- Shipping Address 1 -->
                <div>
                    <label for="shipping_address_1" class="block text-sm font-medium text-gray-700 required">Shipping Address 1</label>
                    <input type="text" id="shipping_address_1" name="shipping_address_1" placeholder="Enter your shipping address"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <!-- Shipping Address 2 -->
                <div>
                    <label for="shipping_address_2" class="block text-sm font-medium text-gray-700">Shipping Address 2</label>
                    <input type="text" id="shipping_address_2" name="shipping_address_2" placeholder="Apartment, suite, etc. (optional)"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <!-- Shipping Address 3 -->
                <div>
                    <label for="shipping_address_3" class="block text-sm font-medium text-gray-700">Shipping Address 3</label>
                    <input type="text" id="shipping_address_3" name="shipping_address_3" placeholder="Apartment, suite, etc. (optional)"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 required">City</label>
                    <input type="text" id="city" name="city" placeholder="Enter your city" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <!-- country_code -->
                <div>
                    <label for="country_code" class="block text-sm font-medium text-gray-700 required">Country Code</label>
                    <input type="text" id="country_code" name="country_code" placeholder="Enter your country code" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <!-- Postal Code -->
                <div>
                    <label for="zip_postal_code" class="block text-sm font-medium text-gray-700 required">Postal Code</label>
                    <input type="number" id="zip_postal_code" name="zip_postal_code" placeholder="Enter your postal code" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                            class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
<script src="{{ asset('js/product.js') }}"></script>
</body>
</html>