<?php
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aperture Vision | Photography Portfolio & Showcase</title>
    <meta name="description" content="A premium photography portfolio showcase featuring nature, portraits, landscapes, and street photography.">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Main Custom CSS (Root Relative Path for Vercel & Localhost) -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <!-- Sticky Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/index.php" class="brand-logo">
                <i class="fa-solid fa-camera-retro"></i>
                <div class="brand-text">Aperture<span>Vision</span></div>
            </a>
            
            <ul class="nav-links">
                <li><a href="/index.php#gallery" class="active">Gallery</a></li>
                <li><a href="/index.php#about">About</a></li>
                <li><a href="/index.php#contact">Contact</a></li>
            </ul>

            <div class="nav-actions">
                <button id="themeToggle" class="btn-icon" title="Toggle Light/Dark Theme">
                    <i class="fa-solid fa-moon"></i>
                </button>
                <a href="/admin/login.php" class="btn-admin">
                    <i class="fa-solid fa-lock"></i> Admin Panel
                </a>
            </div>
        </div>
    </nav>
