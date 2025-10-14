</main>

       <footer class="bg-[#f5f5fa] dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-sm">
  <div class="max-w-7xl mx-auto px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Logo + Description -->
      <div>
        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white h-7">
          VTU <span class="text-2xl font-extrabold text-blue-600" style="font-family: 'Marck Script', cursive;">wise.</span>
        </h2>
        <p class="mt-2">
          VTU WISE: Your comprehensive academic companion. 📚 Prepare effectively with expertly curated resources, thoughtfully created by students to support student success.
        </p>
        <div class="flex space-x-4 mt-4">
          <a href="https://whatsapp.com/channel/0029Vav2A1CEwEk0N2paBj3X" target="_blank" class="text-black dark:text-white hover:text-green-500 transition">
            <i class="fab fa-whatsapp fa-lg"></i><span class="sr-only">WhatsApp</span>
          </a>
          <a href="https://t.me/VTUWISE" target="_blank" class="text-black dark:text-white hover:text-blue-500 transition">
            <i class="fab fa-telegram fa-lg"></i><span class="sr-only">Telegram</span>
          </a>
          <a href="https://instagram.com/vtuwise.in" target="_blank" class="text-black dark:text-white hover:text-pink-500 transition">
            <i class="fab fa-instagram fa-lg"></i><span class="sr-only">Instagram</span>
          </a>
        </div>
        <div class="flex space-x-4 mt-4">
          <a href="<?php echo BASE_URL; ?>upload.php" class="bg-black text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 dark:hover:bg-gray-700 transition">Upload Notes</a>
        </div>
      </div>

      <!-- Footer Links (Desktop) -->
      <div class="hidden lg:block">
        <h3 class="font-bold uppercase text-gray-600 dark:text-gray-400 mb-3">Policy details</h3>
        <ul class="space-y-2">
          <li><a href="about.php" class="hover:text-indigo-600 dark:hover:text-indigo-400">About</a></li>
          <li><a href="Terms&Condition.php" class="hover:text-indigo-600 dark:hover:text-indigo-400">Terms and Conditions</a></li>
          <li><a href="privacy.php" class="hover:text-indigo-600 dark:hover:text-indigo-400">Privacy Policy</a></li>
          <li><a href="disclaimer.php" class="hover:text-indigo-600 dark:hover:text-indigo-400">Disclaimer</a></li>
          <li><a href="faqs.php" class="hover:text-indigo-600 dark:hover:text-indigo-400">FAQs</a></li>
          <li><a href="contactus.php" class="hover:text-indigo-600 dark:hover:text-indigo-400">Contact Us</a></li>
        </ul>
      </div>


      <div class="hidden lg:block">
        <h3 class="font-bold uppercase text-gray-600 dark:text-gray-400 mb-3">University Links</h3>
        <ul class="space-y-2">
          <li><a href="https://vtu.ac.in/academic-calendar/" class="hover:text-indigo-600 dark:hover:text-indigo-400">Academic Calendar</a></li>
          <li><a href="https://results.vtu.ac.in/" class="hover:text-indigo-600 dark:hover:text-indigo-400">VTU Result</a></li>
          <li><a href="https://vtu.ac.in/model-question-paper-b-e-b-tech-b-arch/" class="hover:text-indigo-600 dark:hover:text-indigo-400">VTU Model Paper</a></li>
          <li><a href="https://vtu.ac.in/en/category/examination/" class="hover:text-indigo-600 dark:hover:text-indigo-400">VTU Examination</a></li>
        </ul>
      </div>

      <!-- Accordion Footer (Mobile) -->
      <div class="lg:hidden space-y-4 mt-8">
        <details class="group">
          <summary class="flex justify-between items-center cursor-pointer font-bold uppercase text-gray-600 dark:text-gray-400">
            Policy details <span class="transition-transform group-open:rotate-180">▼</span>
          </summary>
          <ul class="mt-2 ml-4 space-y-1">
            <li><a href="about.php">About</a></li>
            <li><a href="Terms&Condition.php">Terms and Conditions</a></li>
            <li><a href="privacy.php">Privacy Policy</a></li>
            <li><a href="disclaimer.php">Disclaimer</a></li>
            <li><a href="faqs.php">FAQs</a></li>
            <li><a href="contactus.php">Contact Us</a></li>
          </ul>
        </details>


        <details class="group">
          <summary class="flex justify-between items-center cursor-pointer font-bold uppercase text-gray-600 dark:text-gray-400">
            University Links <span class="transition-transform group-open:rotate-180">▼</span>
          </summary>
          <ul class="mt-2 ml-4 space-y-1">
            <li><a href="https://vtu.ac.in/academic-calendar/">Academic Calendar</a></li>
            <li><a href="https://results.vtu.ac.in/">VTU Result</a></li>
            <li><a href="https://vtu.ac.in/model-question-paper-b-e-b-tech-b-arch/">VTU Model Paper</a></li>
            <li><a href="https://vtu.ac.in/en/category/examination/">VTU Examination</a></li>
          </ul>
        </details>
      </div>
    </div>

    <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-gray-500 dark:text-gray-400">
      <p>&copy; <?php echo date("Y"); ?> VTU WISE. All Rights Reserved.</p>
      <p class="text-sm mt-1">A student-focused initiative for educational resources.</p>
    </div>
</footer>

 <div id="email-popup-overlay" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center">
        <div id="email-popup" class="bg-white dark:bg-slate-800 rounded-lg shadow-xl p-8 w-full max-w-md m-4 text-center transform scale-95 opacity-0 transition-all duration-300">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Join Our Community!</h2>
            <p class="text-slate-600 dark:text-slate-400 mt-2 mb-6">Get the latest notes, updates, and exam tips delivered right to your inbox.</p>
            <form id="subscribe-form">
                <input type="email" id="popup-email-input" placeholder="Enter your email address" class="w-full p-3 border rounded-lg dark:bg-slate-700 dark:border-slate-600 mb-4" required>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700">Subscribe Now</button>
            </form>
            <button id="close-popup-btn" class="mt-4 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">No, thanks</button>
            <div id="popup-message" class="mt-4 text-green-600 font-semibold"></div>
        </div>
    </div>

     <button id="back-to-top-btn" 
            class="hidden fixed bottom-5 right-5 bg-blue-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-opacity duration-300">
        <i class="fas fa-angle-up"></i>
    </button>
    <script>
      
        // --- GLOBAL SCRIPT ---
        // We still use DOMContentLoaded as a best practice to organize the code.
        document.addEventListener('DOMContentLoaded', function() {
            // --- Mobile Menu Sidebar Logic ---
            const menuButton = document.getElementById('mobile-menu-button');
            const closeButton = document.getElementById('mobile-menu-close');
            const menuOverlay = document.getElementById('mobile-menu-overlay');
            const menu = document.getElementById('mobile-menu');

            function toggleMenu() {
                menu.classList.toggle('translate-x-full');
                menuOverlay.classList.toggle('hidden');
            }

            if (menuButton) menuButton.addEventListener('click', toggleMenu);
            if (closeButton) closeButton.addEventListener('click', toggleMenu);
            if (menuOverlay) menuOverlay.addEventListener('click', toggleMenu);
            
            menu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', toggleMenu);
            });

            // --- Header Search Toggle Logic ---
            const searchIconButton = document.getElementById('search-icon-button');
            const searchOverlay = document.getElementById('search-overlay');
            const searchCloseButton = document.getElementById('search-close-button');
            const headerSearchInput = document.getElementById('header-search-input');

            function toggleSearch() {
                searchOverlay.classList.toggle('hidden');
                if (!searchOverlay.classList.contains('hidden')) {
                    headerSearchInput.focus();
                }
            }

            if(searchIconButton) searchIconButton.addEventListener('click', toggleSearch);
            if(searchCloseButton) searchCloseButton.addEventListener('click', toggleSearch);

            // --- REUSABLE AJAX SEARCH FUNCTION ---
            const performAjaxSearch = async (query, resultsContainer) => {
                if (query.length < 2) {
                    resultsContainer.innerHTML = '';
                    resultsContainer.classList.add('hidden');
                    return;
                }

                const formData = new FormData();
                formData.append('action', 'search');
                formData.append('query', query);
                
                const searchUrl = '<?php echo BASE_URL; ?>subjects.php';

                try {
                    const response = await fetch(searchUrl, { method: 'POST', body: formData });
                    const subjects = await response.json();
                    
                    let html = '';
                    if (subjects.length > 0) {
                        html += '<ul class="divide-y divide-slate-100">';
                        subjects.forEach(subject => {
                            html += `
                                <li class="hover:bg-slate-50">
                                    <a href="<?php echo BASE_URL; ?>notes.php?subject_id=${subject.id}" class="p-4 flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-slate-800">${subject.subject_name}</p>
                                            <p class="text-sm text-slate-500">${subject.subject_code} - ${subject.short_name}</p>
                                        </div>
                                        <i class="fa fa-chevron-right text-slate-400"></i>
                                    </a>
                                </li>`;
                        });
                        html += '</ul>';
                    } else {
                        html = '<p class="p-4 text-center text-slate-500">No subjects found.</p>';
                    }
                    resultsContainer.innerHTML = html;
                    resultsContainer.classList.remove('hidden');
                } catch (error) {
                    console.error('Search error:', error);
                    resultsContainer.innerHTML = '<p class="p-4 text-center text-red-500">Error fetching results.</p>';
                    resultsContainer.classList.remove('hidden');
                }
            };

            // Attach search to HEADER search bar
            const headerSearchResults = document.getElementById('header-search-results');
            if (headerSearchInput) {
                headerSearchInput.addEventListener('keyup', () => {
                    performAjaxSearch(headerSearchInput.value.trim(), headerSearchResults);
                });
            }

            // Attach search to HOMEPAGE hero search bar (if it exists on the page)
            const homepageSearchInput = document.getElementById('search-input');
            const homepageSearchResults = document.getElementById('search-results');
            if (homepageSearchInput) {
                homepageSearchInput.addEventListener('keyup', () => {
                    performAjaxSearch(homepageSearchInput.value.trim(), homepageSearchResults);
                });
            }

            // Disable other interactions globally
            document.addEventListener('contextmenu', event => event.preventDefault());
        });

        function toggleFooterSection(id) {
    const content = document.getElementById(`content-${id}`);
    const icon = document.getElementById(`icon-${id}`);
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
  }

              // --- NEW: POPUP SCRIPT ---
            const popupOverlay = document.getElementById('email-popup-overlay');
            const popup = document.getElementById('email-popup');
            const subscribeForm = document.getElementById('subscribe-form');
            const closePopupBtn = document.getElementById('close-popup-btn');
            const emailInput = document.getElementById('popup-email-input');
            const popupMessage = document.getElementById('popup-message');

            const showPopup = () => {
                popupOverlay.classList.remove('hidden');
                setTimeout(() => { // For transition effect
                    popup.classList.remove('scale-95', 'opacity-0');
                    popup.classList.add('scale-100', 'opacity-100');
                }, 50);
            };

            const hidePopup = () => {
                popup.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    popupOverlay.classList.add('hidden');
                }, 300);
                // Mark popup as shown so it doesn't appear again in this session
                sessionStorage.setItem('emailPopupShown', 'true');
            };

            // Show popup after 7 seconds if it hasn't been shown in this session
            setTimeout(() => {
                if (sessionStorage.getItem('emailPopupShown') !== 'true') {
                    showPopup();
                }
            }, 7000);

            // Close button functionality
            closePopupBtn.addEventListener('click', hidePopup);
            popupOverlay.addEventListener('click', (e) => {
                if (e.target === popupOverlay) {
                    hidePopup();
                }
            });

            // Handle form submission with AJAX
            subscribeForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const email = emailInput.value;
                const formData = new FormData();
                formData.append('email', email);

                const response = await fetch('<?php echo BASE_URL; ?>subscribe.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                popupMessage.textContent = result.message;
                if(result.success) {
                    popupMessage.classList.remove('text-red-600');
                    popupMessage.classList.add('text-green-600');
                    // Hide popup after 2 seconds on success
                    setTimeout(hidePopup, 2000);
                } else {
                    popupMessage.classList.remove('text-green-600');
                    popupMessage.classList.add('text-red-600');
                }
            });
                 // --- NEW: BACK TO TOP SCRIPT ---
            const backToTopBtn = document.getElementById('back-to-top-btn');

            // Show or hide the button based on scroll position
            window.addEventListener('scroll', () => {
                if (window.scrollY > 200) { // Show button after scrolling 200px
                    backToTopBtn.classList.remove('hidden');
                } else {
                    backToTopBtn.classList.add('hidden');
                }
            });

            // Smoothly scroll to top on click
            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

  
    </script>
</body>
</html>