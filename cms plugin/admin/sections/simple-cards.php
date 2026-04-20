<?php
/**
 * AUSR CMS - Simple Cards Layout
 * Basic cards layout using existing CSS
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<!-- Simple Cards Container -->
<div id="ausr-simple-cards" class="ausr-simple-cards">
    
    <!-- Cards Header -->
    <div class="ausr-cards-header">
        <h2 class="ausr-cards-title">Sections Management</h2>
        <p class="ausr-cards-subtitle">Click on any section to edit its content</p>
    </div>

    <!-- Simple Cards Grid -->
    <div class="ausr-simple-grid">
        
        <!-- Home Section Card -->
        <div class="ausr-simple-card" data-section="home">
            <div class="ausr-card-header">
                <h3 class="ausr-card-title">Home Page</h3>
                <p class="ausr-card-description">Main landing page content</p>
            </div>
            <div class="ausr-card-content">
                <div class="ausr-field-list">
                    <div class="ausr-field-item">
                        <label>Hero Title</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                    <div class="ausr-field-item">
                        <label>Hero Subtitle</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                    <div class="ausr-field-item">
                        <label>Statistics</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                </div>
            </div>
            <div class="ausr-card-actions">
                <button class="ausr-btn ausr-btn-primary" onclick="editSection('home')">
                    Edit Section
                </button>
            </div>
        </div>

        <!-- Programs Section Card -->
        <div class="ausr-simple-card" data-section="programs">
            <div class="ausr-card-header">
                <h3 class="ausr-card-title">Programs</h3>
                <p class="ausr-card-description">Academic programs and courses</p>
            </div>
            <div class="ausr-card-content">
                <div class="ausr-field-list">
                    <div class="ausr-field-item">
                        <label>Hero Title</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                    <div class="ausr-field-item">
                        <label>Programs List</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                </div>
            </div>
            <div class="ausr-card-actions">
                <button class="ausr-btn ausr-btn-primary" onclick="editSection('programs')">
                    Edit Section
                </button>
            </div>
        </div>

        <!-- Events Section Card -->
        <div class="ausr-simple-card" data-section="events">
            <div class="ausr-card-header">
                <h3 class="ausr-card-title">Events</h3>
                <p class="ausr-card-description">Upcoming events and activities</p>
            </div>
            <div class="ausr-card-content">
                <div class="ausr-field-list">
                    <div class="ausr-field-item">
                        <label>Hero Title</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                    <div class="ausr-field-item">
                        <label>Featured Event</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                </div>
            </div>
            <div class="ausr-card-actions">
                <button class="ausr-btn ausr-btn-primary" onclick="editSection('events')">
                    Edit Section
                </button>
            </div>
        </div>

        <!-- About Section Card -->
        <div class="ausr-simple-card" data-section="about">
            <div class="ausr-card-header">
                <h3 class="ausr-card-title">About</h3>
                <p class="ausr-card-description">About us information</p>
            </div>
            <div class="ausr-card-content">
                <div class="ausr-field-list">
                    <div class="ausr-field-item">
                        <label>Hero Title</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                    <div class="ausr-field-item">
                        <label>Story Content</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                </div>
            </div>
            <div class="ausr-card-actions">
                <button class="ausr-btn ausr-btn-primary" onclick="editSection('about')">
                    Edit Section
                </button>
            </div>
        </div>

        <!-- Settings Section Card -->
        <div class="ausr-simple-card" data-section="settings">
            <div class="ausr-card-header">
                <h3 class="ausr-card-title">Settings</h3>
                <p class="ausr-card-description">Global settings and configuration</p>
            </div>
            <div class="ausr-card-content">
                <div class="ausr-field-list">
                    <div class="ausr-field-item">
                        <label>Site Title</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                    <div class="ausr-field-item">
                        <label>Contact Email</label>
                        <span class="ausr-field-status">Ready</span>
                    </div>
                </div>
            </div>
            <div class="ausr-card-actions">
                <button class="ausr-btn ausr-btn-primary" onclick="editSection('settings')">
                    Edit Section
                </button>
            </div>
        </div>

    </div>

    <!-- Quick Actions Bar -->
    <div class="ausr-quick-actions">
        <button class="ausr-btn ausr-btn-success" onclick="saveAllChanges()">
            Save All Changes
        </button>
    </div>
</div>
