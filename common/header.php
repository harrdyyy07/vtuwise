<?php 
require_once 'config.php';

$branches_for_header = $conn->query("SELECT id, name, short_name FROM branches ORDER BY name ASC");
$semesters_for_header = $conn->query("SELECT id, sem_number FROM semesters WHERE sem_number > 2 ORDER BY sem_number ASC");

$semesters_array = [];
if ($semesters_for_header->num_rows > 0) {
    while($sem = $semesters_for_header->fetch_assoc()) {
        $semesters_array[] = $sem;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <!-- SEO Meta Tags -->
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' | VTU wise' : 'VTU wise - Your VTU Study Companion'; ?></title>
    <meta name="description" content="<?php echo isset($meta_description) ? htmlspecialchars($meta_description) : 'Your one-stop solution for VTU notes, question papers, lab manuals, and academic resources for all branches and semesters.'; ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Marck+Script&display=swap" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/png">
    <script>
      if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
      }
    </script>
    <style>
        body {
            -webkit-user-select: none; -ms-user-select: none; user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        #mobile-menu {
            transition: transform 0.3s ease-in-out;
        }
        .group:hover .group-hover\:block {
            display: block;
        }

         @media (prefers-color-scheme: dark) {
    body {
      background-color: #0f0f0f;
      color: #e0e0e0;
    }

    section,
    .bg-white,
    .text-slate-800,
    .text-slate-700,
    .text-slate-600,
    .text-slate-500,
    .bg-yellow-100 {
      background-color: #1e1e1e !important;
      color: #e0e0e0 !important;
    }

    .text-black {
      color: #ccc !important;
    }

    .bg-yellow-100 {
      background-color: #333 !important;
    }

    .border-slate-200 {
      border-color: #444 !important;
    }

    .shadow-md, .shadow-lg {
      box-shadow: 0 2px 10px rgba(255, 255, 255, 0.05) !important;
    }

    .hover\:bg-gradient-to-r:hover {
      background-image: linear-gradient(to right, #2c2c2c, #3a3a3a, #2c2c2c) !important;
    }

    .bg-gradient-to-r {
      background-image: none !important;
      background-color: #1c1c1c !important;
    }

    .text-yellow-500 {
      color: #ffd54f !important;
    }

    .slider-dot {
      background-color: rgba(255, 255, 255, 0.4) !important;
    }

    .slider-dot.bg-white {
      background-color: white !important;
    }

    .dark\:text-slate-400 {
      color: #aaa !important;
    }
  }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-black dark:text-white">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white dark:bg-gray-900 shadow-md sticky top-0 z-40">
            <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex-shrink-0">
                        <a href="<?php echo BASE_URL; ?>index.php" class="flex items-center space-x-2">
                            <img src="<?php echo BASE_URL; ?>images/logo1.0.png" alt="Logo" class="h-8 w-8 ">
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white h-7">
                                VTU <span class="text-2xl font-extrabold text-blue-600" style="font-family: 'Marck Script', cursive;">wise.</span>
                            </h2>
                        </a>
                    </div>

                    <div class="hidden md:block">
                        <div class="ml-10 flex items-center space-x-4">
                            <a href="<?php echo BASE_URL; ?>index.php" class="text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-bold">Home</a>
                            
                            <div class="relative">
    <button onclick="toggleDropdown()" id="notesButton" class="text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-0 rounded-md text-sm font-bold flex items-center">
        <span>Notes</span>
        <i class="fas fa-chevron-down ml-1 text-xs"></i>
    </button>

    <div id="notesDropdown" class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50 hidden">
        <div class="py-1" role="menu">
            <hr class="my-1 border-gray-300 dark:border-gray-700">
            <?php if (mysqli_num_rows($branches_for_header) > 0): mysqli_data_seek($branches_for_header, 0); ?>
                <?php while($branch = $branches_for_header->fetch_assoc()): ?>
                    <div class="relative group">
                        <a href="semesters.php?branch_id=<?php echo $branch['id']; ?>" class="w-full flex justify-between items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <span><?php echo htmlspecialchars($branch['short_name']); ?></span>
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                        <!-- Future submenu here -->
                        <div class="absolute left-full top-0 ml-1 w-40 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 hidden group-hover:block">
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const dropdown = document.getElementById('notesDropdown');
    const button = document.getElementById('notesButton');

    function toggleDropdown() {
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const isClickInside = button.contains(event.target) || dropdown.contains(event.target);
        if (!isClickInside) {
            dropdown.classList.add('hidden');
        }
    });
</script>

                            <a href="<?php echo BASE_URL; ?>sgpa_calculator.php" class="text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-bold">SGPA Cal</a>
                            <a href="<?php echo BASE_URL; ?>blog.php" class="text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-bold">Blogs</a>
                            <a href="https://results.vtu.ac.in/" target="_blank" class="text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-bold">Results</a>
                            <a href="<?php echo BASE_URL; ?>upload.php"  class="inline-block text-center font-bold text-white  px-4 py-2 rounded-lg text-sm bg-gradient-to-b from-blue-400 to-blue-700 hover:from-blue-500 hover:to-blue-800  transition">Upload Notes</a>

                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button id="search-icon-button" class="text-slate-600 dark:text-gray-300 hover:text-blue-600 p-2">
                            <i class="fas fa-search fa-lg"></i>
                        </button>

                        <!-- "Join Now!" button - hidden on mobile -->
                   <button 
                        onclick="window.open('https://whatsapp.com/channel/0029Vav2A1CEwEk0N2paBj3X', '_blank')"
                        class="hidden md:block relative px-6 py-3 rounded-full font-semibold text-white 
                            bg-black overflow-hidden group transition-all duration-300">
                        
                        <span class="absolute inset-0 p-[2px] rounded-full bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500"></span>
                        <span class="absolute inset-[2px] bg-black rounded-full group-hover:bg-gray-900"></span>
                        <span class="relative z-10">🌈 Join Now!</span>
                   </button>

                        <div class="md:hidden">
                            <button id="mobile-menu-button" class="text-slate-600 dark:text-gray-300 hover:text-blue-600 p-2">
                                <i class="fas fa-bars fa-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <div id="search-overlay" class="hidden fixed inset-0 bg-white/95 dark:bg-black/95 backdrop-blur-sm z-50">
            <div class="container mx-auto px-4 pt-4">
                <div class="relative max-w-3xl mx-auto">
                    <input type="search" id="header-search-input" placeholder="Enter Subject Code or Name..." class="w-full p-4 pl-5 pr-14 text-lg bg-transparent border-b-2 border-slate-400 dark:border-gray-600 focus:border-blue-600 focus:outline-none text-black dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                    <button id="search-close-button" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-500 dark:text-gray-400 hover:text-red-500 p-2">
                        <i class="fas fa-times fa-2x"></i>
                    </button>
                </div>
                <div id="header-search-results" class="relative max-w-3xl mx-auto mt-2 bg-white dark:bg-gray-900 rounded-lg shadow-lg max-h-[70vh] overflow-y-auto text-black dark:text-white"></div>
            </div>
        </div>

        <div id="mobile-menu-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
        <div id="mobile-menu" class="fixed top-0 right-0 h-full w-64 bg-white dark:bg-gray-900 shadow-lg z-50 transform translate-x-full">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white h-7">
                    VTU <span class="text-2xl font-extrabold text-blue-600" style="font-family: 'Marck Script', cursive;">wise.</span>
                </h2>
                <button id="mobile-menu-close" class="text-slate-500 dark:text-gray-300 hover:text-red-500">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>
            <div class="p-4">
                <a href="<?php echo BASE_URL; ?>index.php" class="block py-2 px-4 text-slate-700 dark:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-md">Home</a>
                <a href="<?php echo BASE_URL; ?>index.php#branches" class="block py-2 px-4 text-slate-700 dark:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-md">Branches</a>
                <a href="<?php echo BASE_URL; ?>sgpa_calculator.php" class="block py-2 px-4 text-slate-700 dark:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-md">SGPA Cal</a>
                <a href="<?php echo BASE_URL; ?>blog.php" class="block py-2 px-4 text-slate-700 dark:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-md">Blogs</a>
                <a href="https://results.vtu.ac.in/" target="_blank" class="block py-2 px-4 text-slate-700 dark:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-md">Results</a>
               <a href="<?php echo BASE_URL; ?>upload.php" class="inline-block mt-2 text-center font-bold text-white px-4 py-2 rounded-lg text-sm bg-gradient-to-b from-blue-400 to-blue-700 hover:from-blue-500 hover:to-blue-800 transition">Upload Notes</a>

                <hr class="my-4 border-gray-300 dark:border-gray-700">

                <button 
                    onclick="window.open('https://whatsapp.com/channel/0029Vav2A1CEwEk0N2paBj3X', '_blank')"
                    class="w-full sm:w-auto relative px-6 py-3 rounded-full font-semibold text-white 
                        bg-black overflow-hidden group transition-all duration-300">

                    <!-- Gradient border -->
                    <span class="absolute inset-0 p-[2px] rounded-full bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500"></span>

                    <!-- Inner background -->
                    <span class="absolute inset-[2px] bg-black rounded-full group-hover:bg-gray-900"></span>

                    <!-- Button text -->
                    <span class="relative z-10">🌈 Join Now!</span>
                </button>


                <div class="flex justify-center space-x-5 mt-3">
                    <a href="https://whatsapp.com/channel/0029Vav2A1CEwEk0N2paBj3X" target="_blank" class="text-black dark:text-white hover:text-green-500 transition">
                        <i class="fab fa-whatsapp fa-lg"></i><span class="sr-only">WhatsApp</span>
                    </a>
                    <a href="#" target="_blank" class="text-black dark:text-white hover:text-blue-500 transition">
                        <i class="fab fa-telegram fa-lg"></i><span class="sr-only">Telegram</span>
                    </a>
                    <a href="https://instagram.com/vtuengineering.notes" target="_blank" class="text-black dark:text-white hover:text-pink-500 transition">
                        <i class="fab fa-instagram fa-lg"></i><span class="sr-only">Instagram</span>
                    </a>
                </div>
            </div>
        </div>

        <main class="flex-grow">
