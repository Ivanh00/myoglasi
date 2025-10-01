import './bootstrap';

// Alpine.js global store for search filters
document.addEventListener('alpine:init', () => {
    Alpine.store('searchFilters', {
        activeCount: 0,

        updateCount(count) {
            this.activeCount = count;
        },

        getCount() {
            return this.activeCount;
        }
    });
});
