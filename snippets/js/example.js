/**
 * Example JavaScript file demonstrating versioned and non-versioned code
 * 
 * This file shows how to structure your JavaScript to work with the hybrid
 * versioning approach of the Arsol WP Snippets plugin.
 */

// Versioned code - core functionality that doesn't change often
const CoreFunctionality = {
    init() {
        this.setupEventListeners();
        this.initializeComponents();
    },

    setupEventListeners() {
        // Core event listeners that are unlikely to change
        document.addEventListener('DOMContentLoaded', () => {
            this.initializeComponents();
        });
    },

    initializeComponents() {
        // Core component initialization
        console.log('Core components initialized');
    }
};

// Non-versioned code - dynamic functionality that changes frequently
const DynamicFunctionality = {
    init() {
        this.loadUserPreferences();
        this.setupDynamicContent();
    },

    loadUserPreferences() {
        // Load user preferences that might change
        const preferences = localStorage.getItem('userPreferences');
        if (preferences) {
            this.applyUserPreferences(JSON.parse(preferences));
        }
    },

    setupDynamicContent() {
        // Setup dynamic content that might change
        console.log('Dynamic content setup complete');
    },

    applyUserPreferences(preferences) {
        // Apply user preferences that might change
        document.documentElement.style.setProperty('--dynamic-bg-color', preferences.backgroundColor);
        document.documentElement.style.setProperty('--dynamic-text-color', preferences.textColor);
    }
};

// Initialize both versioned and non-versioned functionality
CoreFunctionality.init();
DynamicFunctionality.init();
