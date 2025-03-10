<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>5S - Responsive Header</title>
    <style>
        .active-nav-item {
            position: relative;
        }
        
        .active-nav-item::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #ff6b6b;
        }
        
        @media (max-width: 768px) {
            .mobile-menu {
                display: none;
            }
            
            .mobile-menu.open {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <header class="fixed top-0 left-0 w-full z-50 bg-opacity-70 backdrop-blur-md bg-gray-900">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="/">
                    <div class="text-white text-3xl font-bold">
                        Company
                      </div>
                </a>
                
                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-white hover:text-gray-300 py-2 active-nav-item">HOME</a>
                    <a href="#" class="text-white hover:text-gray-300 py-2">ABOUT</a>
                    <a href="#" class="text-white hover:text-gray-300 py-2">SERVICES</a>
                    <a href="#" class="text-white hover:text-gray-300 py-2">PORTFOLIO</a>
                    <a href="#" class="text-white hover:text-gray-300 py-2">TEAM</a>
                    <div class="relative group">
                        <button class="text-white hover:text-gray-300 py-2 flex items-center">
                            DROPDOWN
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block">
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Dropdown Item 1</a>
                                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Dropdown Item 2</a>
                                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Dropdown Item 3</a>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="text-white hover:text-gray-300 py-2">CONTACT</a>
                </nav>
                
                <!-- Get Started Button -->
                <a href="/login" class="hidden md:block border-2 border-white text-white hover:bg-white hover:text-gray-900 px-6 py-2 rounded-md transition duration-300">
                    Sign In
                </a>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="mobile-menu md:hidden flex flex-col mt-4 pb-4">
                <a href="#" class="text-white hover:text-gray-300 py-2 px-4 border-b border-gray-700 active-nav-item">HOME</a>
                <a href="#" class="text-white hover:text-gray-300 py-2 px-4 border-b border-gray-700">ABOUT</a>
                <a href="#" class="text-white hover:text-gray-300 py-2 px-4 border-b border-gray-700">SERVICES</a>
                <a href="#" class="text-white hover:text-gray-300 py-2 px-4 border-b border-gray-700">PORTFOLIO</a>
                <a href="#" class="text-white hover:text-gray-300 py-2 px-4 border-b border-gray-700">TEAM</a>
                
                <div class="relative py-2 px-4 border-b border-gray-700">
                    <button id="mobile-dropdown-button" class="text-white hover:text-gray-300 w-full text-left flex justify-between items-center">
                        DROPDOWN
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="mobile-dropdown" class="hidden mt-2 pl-4 border-l border-gray-700">
                        <a href="#" class="block text-white hover:text-gray-300 py-2">Dropdown Item 1</a>
                        <a href="#" class="block text-white hover:text-gray-300 py-2">Dropdown Item 2</a>
                        <a href="#" class="block text-white hover:text-gray-300 py-2">Dropdown Item 3</a>
                    </div>
                </div>
                
                <a href="#" class="text-white hover:text-gray-300 py-2 px-4 border-b border-gray-700">CONTACT</a>
                
                <a href="#" class="text-white hover:bg-white hover:text-gray-900 py-2 px-4 mt-4 border-2 border-white rounded-md text-center transition duration-300">
                    GET STARTED
                </a>
            </div>
        </div>
    </header>
  <div class="mb-20">

  </div>
    
    <script>
        // Toggle mobile menu
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });
        
        // Toggle mobile dropdown
        const mobileDropdownButton = document.getElementById('mobile-dropdown-button');
        const mobileDropdown = document.getElementById('mobile-dropdown');
        
        mobileDropdownButton.addEventListener('click', () => {
            mobileDropdown.classList.toggle('hidden');
        });
    </script>
</body>
</html>