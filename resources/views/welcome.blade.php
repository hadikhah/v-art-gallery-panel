<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Header -->
<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">3D Art Gallery</h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="#about" class="text-blue-600 hover:underline">About</a></li>
                <li><a href="#gallery" class="text-blue-600 hover:underline">Gallery</a></li>
                <li><a href="#contact" class="text-blue-600 hover:underline">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Hero Section -->
<section class="bg-cover bg-center h-screen" style="background-image: url('path/to/your/hero-image.jpg');">
    <div class="flex items-center justify-center h-full bg-black bg-opacity-50">
        <h2 class="text-white text-4xl font-bold">Experience Art in 3D</h2>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-4">About Us</h2>
        <h4 class="text-3xl font-bold text-center mb-4">Welcome to the 3D Art Gallery!</h4>
        <p class="text-center text-lg max-w-2xl mx-auto">
            Immerse yourself in a vibrant world of creativity where you can curate your very own art exhibitions. Our
            platform empowers you to design and showcase personalized galleries with stunning artworks from a diverse
            collection.

            Whether you're an artist wanting to display your creations or an art enthusiast looking to create a unique
            space, you can easily arrange and interact with artworks in a captivating virtual environment.

            Experience art like never before—connect with fellow art lovers, share your gallery with the world, and
            transform your vision into reality. Join us today and start building your dream gallery!
        </p>
    </div>
</section>

<!-- Gallery Section -->
<section id="popular_gallery" class="py-16 bg-gray-200">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-4">Popular Galleries</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white p-4 shadow rounded">
                <img src="path/to/art1.jpg" alt="Art 1" class="w-full h-48 object-cover rounded">
                <h3 class="mt-2 text-lg font-semibold">Artwork Title 1</h3>
            </div>
            <div class="bg-white p-4 shadow rounded">
                <img src="path/to/art2.jpg" alt="Art 2" class="w-full h-48 object-cover rounded">
                <h3 class="mt-2 text-lg font-semibold">Artwork Title 2</h3>
            </div>
            <div class="bg-white p-4 shadow rounded">
                <img src="path/to/art3.jpg" alt="Art 3" class="w-full h-48 object-cover rounded">
                <h3 class="mt-2 text-lg font-semibold">Artwork Title 3</h3>
            </div>
            <!-- Add more artworks as needed -->
        </div>
    </div>
</section>
<section id="newest_gallery" class="py-16 bg-gray-200">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-4">Popular Galleries</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white p-4 shadow rounded">
                <img src="path/to/art1.jpg" alt="Art 1" class="w-full h-48 object-cover rounded">
                <h3 class="mt-2 text-lg font-semibold">Artwork Title 1</h3>
            </div>
            <div class="bg-white p-4 shadow rounded">
                <img src="path/to/art2.jpg" alt="Art 2" class="w-full h-48 object-cover rounded">
                <h3 class="mt-2 text-lg font-semibold">Artwork Title 2</h3>
            </div>
            <div class="bg-white p-4 shadow rounded">
                <img src="path/to/art3.jpg" alt="Art 3" class="w-full h-48 object-cover rounded">
                <h3 class="mt-2 text-lg font-semibold">Artwork Title 3</h3>
            </div>
            <!-- Add more artworks as needed -->
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-4">Contact Us</h2>
        <p class="text-center text-lg max-w-2xl mx-auto">
            Have any questions? Feel free to reach out to us at <a href="mailto:info@3dartgallery.com"
                                                                   class="text-blue-600">info@3dartgallery.com</a>.
        </p>
    </div>
</section>

<!-- Footer -->
<footer class="bg-white py-6">
    <div class="container mx-auto text-center">
        <p>&copy; 2024 3D Art Gallery. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
