<?php require_once 'common/header.php'; ?>




<?php
// --- SLIDER DATA ---
// You can easily add or change slides by editing this array

$slides = [
    [
        'image' => 'images/banner design.png',
        'title' => 'Access All Your Notes',
        'subtitle' => 'Comprehensive, module-wise notes for all subjects and branches.',
        'button_text' => 'Browse Branches',
        'button_link' => '#branches' // This is a local anchor link
    ],
    [
        'image' => 'images/banner2.0.png',
        'title' => 'Contribute & Help Others',
        'subtitle' => 'Have useful notes? Share them with the community and help fellow students.',
        'button_text' => 'Upload Now',
        'button_link' => 'upload.php' // This links to a page
    ],
    [
        'image' => 'images/banner3.0.png',
        'title' => 'Calculate Your SGPA',
        'subtitle' => 'Use our accurate SGPA calculator to check your semester performance.',
        'button_text' => 'Open Calculator',
        'button_link' => 'sgpa_calculator.php' // This links to a page
    ]
];


// --- BRANCH COVER IMAGES & DETAILS ---
// You can change the image and features for each branch here.
$branch_meta_data = [
    'CSE' => [
        'image' => 'images/computer science.jpg',
        'features' => ['Module-wise Notes', 'Question Papers', 'Lab Manuals Included']
    ],
    'ISE' => [
        'image' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop',
        'features' => ['Module-wise Notes', 'Question Papers', 'Lab Manuals Included']
    ],
    'ECE' => [
        'image' => 'images/E&C engg.jpg',
        'features' => ['Module-wise Notes', 'Question Papers', 'Lab Manuals Included']
    ],
    'EEE' => [
        'image' => 'images/electronics and comm engg.jpg',
        'features' => ['Module-wise Notes', 'Question Papers', 'Lab Manuals Included']
    ],
    'MECH' => [
        'image' => 'images/mech engg.jpg',
        'features' => ['Module-wise Notes', 'Question Papers', 'Lab Manuals Included']
    ],
    'CIVIL' => [
        'image' => 'images/civil engg.jpg',
        'features' => ['Module-wise Notes', 'Question Papers', 'Lab Manuals Included']
    ],
    // A default for any other branches you might add
    'DEFAULT' => [
        'image' => 'images/Screenshot 2025-07-27 221447.png',
        'features' => ['Module-wise Notes', 'Question Papers', 'Lab Manuals Included']
    ]
];

// --- NEW BRANCH STYLES TO MATCH SCREENSHOT ---
// --- BRANCH STYLES ---
$branch_styles = [
    'CSE' => 'from-purple-800 to-indigo-600',
    'ISE' => 'from-purple-800 to-indigo-600', // Grouped with CSE
    'ECE' => 'from-indigo-800 to-fuchsia-600',
    'EEE' => 'from-blue-800 to-indigo-600',
    'CIVIL' => 'from-gray-700 to-gray-500',
    'MECH' => 'from-sky-800 to-teal-600',
    'DEFAULT' => 'from-gray-500 to-gray-700'
];
?>


<section id="hero-slider" class="relative w-full h-[40vh] md:h-[50vh] overflow-hidden rounded-3xl">
     <div class="relative h-full">
        <?php foreach ($slides as $index => $slide): ?>
        <div class="slide absolute inset-0 w-full h-full bg-cover bg-center transition-opacity duration-1000 ease-in-out <?php echo $index === 0 ? 'opacity-100' : 'opacity-0'; ?>" 
             style="background-image: url('<?php echo $slide['image']; ?>');">
            <div class="absolute inset-0 rounded-2xl"></div>
            <div class="relative h-full flex flex-col items-center justify-center text-center text-white p-4">
                <h1 class="text-4xl md:text-6xl font-extrabold drop-shadow-md"><?php echo $slide['title']; ?></h1>
                <p class="mt-4 text-lg font-semibold md:text-xl max-w-2xl drop-shadow-sm"><?php echo $slide['subtitle']; ?></p>
                <?php
                    // THIS IS THE FIX:
                    // Create the correct, full URL for each button link.
                    // If the link starts with '#', it's an anchor link, so we don't add the BASE_URL.
                    // Otherwise, we add the BASE_URL to make the link absolute and reliable.
                    $link = (str_starts_with($slide['button_link'], '#') ? '' : BASE_URL) . str_replace('.php', '', $slide['button_link']);
                ?>
                <a href="<?php echo $link; ?>" class="mt-8 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full transition-transform duration-300 hover:scale-105">
                    <?php echo $slide['button_text']; ?>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <button id="prev-slide" class="absolute top-1/2 left-4 -translate-y-1/2 text-white/70 hover:text-white text-3xl p-2 z-10">
        &#10094;
    </button>
    <button id="next-slide" class="absolute top-1/2 right-4 -translate-y-1/2 text-white/70 hover:text-white text-3xl p-2 z-10">
        &#10095;
    </button>

    <div id="slider-dots" class="absolute bottom-5 left-1/2 -translate-x-1/2 flex space-x-3 z-10">
        <?php for ($i = 0; $i < count($slides); $i++): ?>
        <button class="slider-dot h-3 w-3 rounded-full transition-colors <?php echo $i === 0 ? 'bg-white' : 'bg-white/50'; ?>" data-index="<?php echo $i; ?>"></button>
        <?php endfor; ?>
    </div>
</section>

<!-- New Section: First Year & Branches -->
<section id="first-year-branches" class="container mx-auto px-4 mt-8 md:mt-16 max-w-7xl p-8">
    <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-6 md:mb-10 text-center">Select Your Stream</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <!-- Card 1: FIRST YEAR -->
        <div class="card relative bg-gradient-to-br from-indigo-800 to-purple-600 rounded-2xl shadow-lg p-6 overflow-hidden text-white hover:shadow-xl transition-shadow flex flex-col justify-between">
            <div class="absolute inset-0 opacity-20">
                <svg class="w-full h-full" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="10" y="10" width="10" height="10" stroke="white" stroke-width="0.5"/>
                    <rect x="30" y="30" width="10" height="10" stroke="white" stroke-width="0.5"/>
                    <rect x="50" y="50" width="10" height="10" stroke="white" stroke-width="0.5"/>
                    <rect x="70" y="70" width="10" height="10" stroke="white" stroke-width="0.5"/>
                    <rect x="90" y="90" width="10" height="10" stroke="white" stroke-width="0.5"/>
                    <rect x="110" y="10" width="10" height="10" stroke="white" stroke-width="0.5"/>
                    <rect x="130" y="30" width="10" height="10" stroke="white" stroke-width="0.5"/>
                    <rect x="150" y="50" width="10" height="10" stroke="white" stroke-width="0.5"/>
                    <rect x="170" y="70" width="10" height="10" stroke="white" stroke-width="0.5"/>
                    <rect x="190" y="90" width="10" height="10" stroke="white" stroke-width="0.5"/>
                </svg>
            </div>
            <a href="<?php echo BASE_URL; ?>first-year.php" class="card-link">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <span class="bg-white/20 text-white text-xs font-bold px-2 py-1 rounded-full mb-2 inline-block">P AND C CYCLE</span>
                        <h4 class="text-5xl md:text-6xl font-extrabold mt-4">FIRST YEAR</h4>
                        <p class="text-sm opacity-80 mt-2">1 & 2 Sem</p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm mt-4">
                        <i class="fas fa-eye"></i>
                        <span>8.9K views</span>
                    </div>
                </div>
            </a>
        </div>

        <?php
        $branches_result = $conn->query("SELECT * FROM branches ORDER BY name ASC");
        if ($branches_result->num_rows > 0):
            while($branch = $branches_result->fetch_assoc()):
                $style = $branch_styles[$branch['short_name']] ?? $branch_styles['DEFAULT'];
        ?>
        <!-- Card for <?php echo $branch['short_name']; ?> -->
        <div class="card relative bg-gradient-to-br <?php echo $style; ?> rounded-2xl shadow-lg p-6 overflow-hidden text-white hover:shadow-xl transition-shadow flex flex-col justify-between">
            <div class="absolute inset-0 opacity-20">
                <!-- You can add unique SVG patterns for each branch here if you like -->
                <svg class="w-full h-full" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="8" stroke="white" stroke-width="0.5"/>
                    <circle cx="80" cy="50" r="8" stroke="white" stroke-width="0.5"/>
                    <circle cx="150" cy="90" r="8" stroke="white" stroke-width="0.5"/>
                    <circle cx="180" cy="30" r="8" stroke="white" stroke-width="0.5"/>
                </svg>
            </div>
            <a href="<?php echo BASE_URL; ?>semesters.php?branch_id=<?php echo $branch['id']; ?>" class="card-link">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                       <span class="bg-white/20 text-white text-xs font-bold px-2 py-1 rounded-full mb-2 inline-block"><?php echo htmlspecialchars($branch['short_name']); ?></span>
                       <h4 class="text-5xl md:text-6xl font-extrabold mt-4"><?php echo htmlspecialchars($branch['short_name']); ?></h4>
                    </div>
                    <div class="flex items-center space-x-2 text-sm mt-auto pt-4">
                        <i class="fas fa-eye"></i>
                        <span><?php echo rand(5, 20); ?>.<?php echo rand(1, 9); ?>K views</span>
                    </div>
                </div>
            </a>
        </div>
        <?php 
            endwhile;
        endif;
        ?>
    </div>
</section>







<style>
    @keyframes scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(calc(-250px * 7)); } /* Slide width * number of slides */
    }
    .animate-scroll {
        animation: scroll 40s linear infinite;
    }
</style>


<section class="py-16 bg-gradient-to-r from-[#f4ecff] via-[#f1e6ff] to-[#f4ecff]">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-10 text-slate-800 ">What Our Users Say</h2>
        
        <div id="slider-container" class="relative w-full overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_128px,_black_calc(100%-200px),transparent_100%)]">
            <div id="slider-track" class="flex w-max animate-scroll">
                <?php
                // Placeholder testimonial data
                $testimonials = [
                    ['name' => 'Priya Sharma', 'branch' => 'CSE, 5th Sem', 'quote' => 'This site is a lifesaver during exams. All the notes are in one place!', 'img' => 'images/empty profile.jpg'],
                    ['name' => 'Rahul Verma', 'branch' => 'MECH, 7th Sem', 'quote' => 'The previous year question papers are incredibly helpful. Highly recommended.', 'img' => 'images/empty profile.jpg'],
                    ['name' => 'Anjali Singh', 'branch' => 'ECE, 3rd Sem', 'quote' => 'As a junior, finding quality notes was tough. This website made it easy.', 'img' => 'images/empty profile.jpg'],
                    ['name' => 'Vikram Rathod', 'branch' => 'CIVIL, 8th Sem', 'quote' => 'The SGPA calculator is accurate and the UI is very clean. Great work!', 'img' => 'images/empty profile.jpg'],
                    ['name' => 'Sneha Patil', 'branch' => 'ISE, 6th Sem', 'quote' => 'I contributed my notes and it felt great to help other students.', 'img' => 'images/empty profile.jpg'],
                    ['name' => 'Amit Kumar', 'branch' => 'CSE, 4th Sem', 'quote' => 'Simple, fast, and has everything I need. Better than searching in multiple groups.', 'img' => 'images/empty profile.jpg'],
                    ['name' => 'Deepika Rathod', 'branch' => 'ECE, 7th Sem', 'quote' => 'The dark mode is a great feature for late-night study sessions. Thank you!', 'img' => 'images/empty profile.jpg'],
                ];

                // Duplicate the array to create the seamless loop effect
                $slides = array_merge($testimonials, $testimonials);

                foreach ($slides as $testimonial):
                ?>
                <div class="flex-shrink-0 w-[250px] p-4">
                    <div class="bg-white p-6 rounded-lg shadow-md h-full flex flex-col items-center text-center">
                        <img class="w-20 h-20 rounded-full object-cover mb-4 border-4 border-slate-200" src="<?php echo $testimonial['img']; ?>" alt="<?php echo $testimonial['name']; ?>">
                        <p class="font-bold text-lg text-slate-800 "><?php echo $testimonial['name']; ?></p>
                        <p class="text-sm text-blue-500 font-semibold mb-3"><?php echo $testimonial['branch']; ?></p>
                        <p class="text-slate-600 dark:text-slate-400 text-sm italic">"<?php echo $testimonial['quote']; ?>"</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
    
<section class="w-full flex justify-center px-4 py-10">
    <div class="bg-yellow-100 rounded-xl p-8 text-center max-w-4xl w-full shadow-md">
      <h2 class="text-2xl font-bold text-yellow-500 mb-4">Disclaimer :</h2>
      <p class="text-black font-semibold ">
         VTU Wise is an independent educational platform and is not affiliated with VTU or any official academic body.
         All study materials, guidance, and resources provided here are for educational purposes only.
      </p>
    </div>
  </section>







<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- HERO SLIDER SCRIPT - UPDATED ---
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('prev-slide');
    const nextBtn = document.getElementById('next-slide');

    let currentSlide = 0;
    let slideInterval;

    const showSlide = (n) => {
        if (slides.length === 0) return;
        currentSlide = (n + slides.length) % slides.length; // Wraps around if n is too high or low

        // This is the second part of the fix:
        // The script now also controls the z-index and pointer-events.
        slides.forEach((slide, index) => {
            slide.classList.replace('opacity-100', 'opacity-0');
            slide.classList.replace('z-10', 'z-0');
            slide.classList.add('pointer-events-none');
        });
        dots.forEach(dot => dot.classList.replace('bg-white', 'bg-white/50'));

        // Activate the correct slide
        slides[currentSlide].classList.replace('opacity-0', 'opacity-100');
        slides[currentSlide].classList.replace('z-0', 'z-10');
        slides[currentSlide].classList.remove('pointer-events-none'); // Only the active slide can be clicked

        // Activate the correct dot
        dots[currentSlide].classList.replace('bg-white/50', 'bg-white');
    };

    const nextSlide = () => { showSlide(currentSlide + 1); };
    const prevSlide = () => { showSlide(currentSlide - 1); };

    const startSlideShow = () => {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    };

    // Event Listeners
    nextBtn.addEventListener('click', () => { nextSlide(); startSlideShow(); });
    prevBtn.addEventListener('click', () => { prevSlide(); startSlideShow(); });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            showSlide(parseInt(dot.dataset.index));
            startSlideShow();
        });
    });

    startSlideShow();
});



// This script pauses the slider animation when the user hovers over it.
    document.addEventListener('DOMContentLoaded', function() {
        const sliderTrack = document.getElementById('slider-track');
        const sliderContainer = document.getElementById('slider-container');

        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => {
                sliderTrack.style.animationPlayState = 'paused';
            });

            sliderContainer.addEventListener('mouseleave', () => {
                sliderTrack.style.animationPlayState = 'running';
            });
        }
    });
</script>

<?php require_once 'common/footer.php'; ?>
