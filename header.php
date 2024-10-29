<nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-lg font-bold text-gray-800">
                <a href="#">Term Tracker</a>
            </div>
            <div class="hidden md:flex space-x-6">
                <a href="home.php" class="text-gray-600 hover:text-gray-800">Home</a>
                <a href="add_form.php" class="text-gray-600 hover:text-gray-800">Add</a>
                <a href="delete.php" class="text-gray-600 hover:text-gray-800">Delete</a>
                <a href="view.php" class="text-gray-600 hover:text-gray-800">View</a>
                <a href="login.php" class="text-gray-600 hover:text-gray-800">Sign Out</a>
            </div>
            <div class="md:hidden">
                <button id="menu-btn" class="text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="flex flex-col px-6 py-4 space-y-2">
                <a href="#" class="block text-gray-600 hover:text-gray-800">Home</a>
                <a href="#" class="block text-gray-600 hover:text-gray-800">About</a>
                <a href="#" class="block text-gray-600 hover:text-gray-800">Services</a>
                <a href="#" class="block text-gray-600 hover:text-gray-800">Contact</a>
            </div>
        </div>
    </nav>