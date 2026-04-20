/**
 * AUSR CMS - Simple Cards JavaScript
 * Basic functionality for simple cards layout
 */

(function () {
    'use strict';

    // ============================================================
    // Initialize Simple Cards
    // ============================================================
    document.addEventListener('DOMContentLoaded', () => {
        console.log('=== SIMPLE CARDS INIT ===');
        initSimpleCards();
    });

    function initSimpleCards() {
        console.log('Initializing Simple Cards...');
        updateCardStatuses();
        initCardInteractions();
    }

    // ============================================================
    // Update Card Statuses
    // ============================================================
    function updateCardStatuses() {
        document.querySelectorAll('.ausr-simple-card').forEach(card => {
            const section = card.dataset.section;
            const items = card.querySelectorAll('.ausr-field-status');
            
            items.forEach(status => {
                status.textContent = 'Ready';
                status.style.background = 'rgba(16,185,129,0.1)';
                status.style.color = '#10b981';
            });
        });
    }

    // ============================================================
    // Card Interactions
    // ============================================================
    function initCardInteractions() {
        document.querySelectorAll('.ausr-simple-card').forEach(card => {
            card.addEventListener('click', (e) => {
                if (!e.target.classList.contains('ausr-btn')) {
                    const section = card.dataset.section;
                    console.log('Card clicked:', section);
                    editSection(section);
                }
            });
        });
    }

    // ============================================================
    // Section Actions
    // ============================================================
    function editSection(section) {
        console.log('Editing section:', section);
        
        // Switch to classic editor for this section
        const classicPanel = document.getElementById('ausr-panel-texts');
        const visualPanel = document.getElementById('ausr-panel-visual');
        const classicBtn = document.getElementById('ausr-classic-editor');
        const visualBtn = document.getElementById('ausr-visual-editor');

        if (classicPanel && visualPanel && classicBtn && visualBtn) {
            classicPanel.style.display = 'block';
            visualPanel.style.display = 'none';
            classicBtn.classList.add('active');
            visualBtn.classList.remove('active');
            
            // Navigate to the specific section
            setTimeout(() => {
                const navItem = document.querySelector(`.ausr-nav-item[data-section="${section}"]`);
                if (navItem) {
                    navItem.click();
                }
            }, 100);
        }
        
        console.log(`Switched to edit ${section} section`);
    }

    function saveAllChanges() {
        console.log('=== SAVE ALL CHANGES FROM SIMPLE CARDS ===');
        
        // Trigger the main save function
        if (typeof saveAll === 'function') {
            saveAll();
        } else {
            console.error('saveAll function not found');
        }
    }

    // ============================================================
    // Global Functions
    // ============================================================
    window.editSection = editSection;
    window.saveAllChanges = saveAllChanges;

})();
