<?php
/**
 * URL Helper Functions for SEO-friendly URLs
 */

function base_url() {
    return '/vtu_notes/';
}

function seo_url($page, $params = []) {
    $base = base_url();
    
    $urls = [
        // Static pages
        'home' => '',
        'about' => 'about',
        'contact' => 'contact-us',
        'privacy' => 'privacy-policy',
        'terms' => 'terms-conditions',
        'disclaimer' => 'disclaimer',
        'faqs' => 'faqs',
        'sitemap' => 'sitemap',
        
        // Calculators
        'cgpa_calculator' => 'cgpa-calculator',
        'sgpa_calculator' => 'sgpa-calculator',
        'sgpa_calculators' => 'sgpa-calculators',
        
        // Content
        'notes' => 'notes',
        'upload' => 'upload-notes',
        'view_note' => 'view-note/' . ($params['id'] ?? ''),
        
        // Dynamic
        'branches' => 'branches',
        'semesters' => 'branch/' . ($params['branch_slug'] ?? $params['branch_id'] ?? ''),
        'subjects' => 'branch/' . ($params['branch_slug'] ?? $params['branch_id'] ?? '') . '/semester-' . ($params['semester'] ?? $params['sem_id'] ?? ''),
        'notes_subject' => 'branch/' . ($params['branch_slug'] ?? '') . '/semester-' . ($params['semester'] ?? '') . '/subject/' . ($params['subject_slug'] ?? ''),
        
        // Admin
        'admin' => 'admin',
        'admin_login' => 'admin/login',
        'admin_notes' => 'admin/notes',
        'admin_subjects' => 'admin/subjects',
        'admin_uploads' => 'admin/uploads',
    ];
    
    $url = $urls[$page] ?? '';
    return $base . $url;
}

function redirect_old_urls() {
    // This function can be used to handle old URL redirects
    $current_url = $_SERVER['REQUEST_URI'];
    
    // Example redirect rules
    $redirects = [
        '/about.php' => '/about',
        '/contactus.php' => '/contact-us',
        '/privacy.php' => '/privacy-policy',
        '/Terms&Condition.php' => '/terms-conditions',
        '/cgpa_calculator.php' => '/cgpa-calculator',
        '/sgpa_calculator.php' => '/sgpa-calculator',
    ];
    
    foreach ($redirects as $old => $new) {
        if (strpos($current_url, $old) !== false) {
            header("Location: " . base_url() . ltrim($new, '/'), true, 301);
            exit();
        }
    }
}
?>
