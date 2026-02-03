/**
 * AJAX Auto-Refresh Utility for LGU Facility Reservation System
 * Provides real-time data updates without page refresh
 */
class AjaxRefresh {
    constructor(options = {}) {
        this.endpoint = options.endpoint || null;
        this.interval = options.interval || 5000; // 5 seconds default
        this.tableBodyId = options.tableBodyId || 'data-tbody';
        this.statsIds = options.statsIds || {};
        this.renderRow = options.renderRow || null;
        this.onUpdate = options.onUpdate || null;
        this.lastCount = options.initialCount || 0;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        this.timer = null;
        this.isRunning = false;
    }

    start() {
        if (!this.endpoint) {
            console.error('AjaxRefresh: No endpoint specified');
            return;
        }
        this.isRunning = true;
        this.timer = setInterval(() => this.refresh(), this.interval);
        console.log(`AjaxRefresh: Started polling ${this.endpoint} every ${this.interval}ms`);
    }

    stop() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
        this.isRunning = false;
        console.log('AjaxRefresh: Stopped polling');
    }

    async refresh() {
        try {
            const response = await fetch(this.endpoint, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const data = await response.json();
            this.updateUI(data);
            
        } catch (error) {
            console.log('AjaxRefresh error:', error.message);
        }
    }

    updateUI(data) {
        // Update stats if provided
        if (data.stats && this.statsIds) {
            for (const [key, elementId] of Object.entries(this.statsIds)) {
                const element = document.getElementById(elementId);
                if (element && data.stats[key] !== undefined) {
                    const newValue = typeof data.stats[key] === 'number' 
                        ? data.stats[key].toLocaleString() 
                        : data.stats[key];
                    if (element.textContent !== String(newValue)) {
                        element.textContent = newValue;
                    }
                }
            }
        }

        // Update table if data changed
        const items = data.data || data.requests || data.items || data.records || [];
        const currentCount = items.length;
        
        if (currentCount !== this.lastCount || this.shouldForceUpdate(data)) {
            this.updateTable(items);
            this.lastCount = currentCount;
            
            // Reinitialize Lucide icons if available
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            // Call custom onUpdate callback
            if (this.onUpdate) {
                this.onUpdate(data);
            }
        }
    }

    shouldForceUpdate(data) {
        // Check if any item status changed
        return data.forceUpdate || false;
    }

    updateTable(items) {
        const tbody = document.getElementById(this.tableBodyId);
        if (!tbody) return;

        // Remove empty row if exists
        const emptyRow = document.getElementById('empty-row');
        if (emptyRow) emptyRow.remove();

        if (items.length === 0) {
            tbody.innerHTML = '<tr id="empty-row"><td colspan="10" class="text-center py-8 text-gray-500">No data available</td></tr>';
            return;
        }

        if (this.renderRow) {
            tbody.innerHTML = items.map(item => this.renderRow(item)).join('');
        }
    }

    // Utility function to truncate text
    static truncate(str, len) {
        if (!str) return '';
        return str.length > len ? str.substring(0, len) + '...' : str;
    }

    // Utility to escape HTML
    static escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Utility to format date
    static formatDate(dateStr, format = 'short') {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        if (format === 'short') {
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    // Utility to format time
    static formatTime(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    }
}

// Make it globally available
window.AjaxRefresh = AjaxRefresh;
