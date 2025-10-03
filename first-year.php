<?php require_once 'common/header.php'; ?>

<div class="container mx-auto py-12 px-4">
    <!-- Page Title -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-slate-800 dark:text-white">First Year Cycles</h1>
        <p class="text-lg text-slate-500 dark:text-slate-400 mt-2">Please select your cycle</p>
    </div>
    
    <!-- Cycle Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <!-- P-Cycle Card -->
        <a href="<?php echo BASE_URL; ?>subjects.php?sem_id=1" class="relative block p-8 h-48 rounded-2xl shadow-lg text-white bg-gradient-to-br from-purple-600 to-indigo-700 overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
            <div class="relative z-10">
                <h3 class="text-5xl font-extrabold tracking-wide">P-Cycle</h3>
                <p class="mt-2 font-semibold">Physics Cycle Subjects</p>
            </div>
        </a>

        <!-- C-Cycle Card -->
        <a href="<?php echo BASE_URL; ?>subjects.php?sem_id=2" class="relative block p-8 h-48 rounded-2xl shadow-lg text-white bg-gradient-to-br from-teal-500 to-cyan-600 overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
            <div class="relative z-10">
                <h3 class="text-5xl font-extrabold tracking-wide">C-Cycle</h3>
                <p class="mt-2 font-semibold">Chemistry Cycle Subjects</p>
            </div>
        </a>
    </div>
</div>

<?php require_once 'common/footer.php'; ?>