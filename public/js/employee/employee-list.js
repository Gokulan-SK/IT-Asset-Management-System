/**
 * Employee List Management JavaScript
 * Handles search, filter, sort, export, and pagination functionality
 */

class EmployeeListManager {
    constructor() {
        this.baseUrl = window.BASE_URL || '/asset_management/';
        this.currentParams = this.parseUrlParams();
        console.log('EmployeeListManager initialized with baseUrl:', this.baseUrl);
        console.log('Current params:', this.currentParams);
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateDeleteModalActions();
    }

    parseUrlParams() {
        const urlParams = new URLSearchParams(window.location.search);
        return {
            search: urlParams.get('search') || '',
            filter: urlParams.get('filter') || '',
            sort: urlParams.get('sort') || 'emp_id',
            order: urlParams.get('order') || 'ASC',
            page: parseInt(urlParams.get('page')) || 1
        };
    }

    bindEvents() {
        console.log('Binding events...');
        
        // Search functionality with debounce
        const searchInput = document.getElementById('table-search');
        if (searchInput) {
            console.log('Search input found, binding events');
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    console.log('Search triggered:', e.target.value);
                    this.handleSearch(e.target.value);
                }, 500); // 500ms debounce
            });
        } else {
            console.error('Search input not found!');
        }

        // Clear search button
        const clearSearchBtn = document.getElementById('clear-search');
        if (clearSearchBtn) {
            console.log('Clear search button found');
            clearSearchBtn.addEventListener('click', () => {
                console.log('Clear search clicked');
                searchInput.value = '';
                this.handleSearch('');
            });
        } else {
            console.error('Clear search button not found!');
        }

        // Filter functionality
        const filterSelect = document.getElementById('designation-filter');
        if (filterSelect) {
            console.log('Filter select found');
            filterSelect.addEventListener('change', (e) => {
                console.log('Filter changed:', e.target.value);
                this.handleFilter(e.target.value);
            });
        } else {
            console.error('Filter select not found!');
        }

        // Sort functionality
        const sortableHeaders = document.querySelectorAll('.sortable');
        console.log('Found sortable headers:', sortableHeaders.length);
        sortableHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const sortField = header.getAttribute('data-sort');
                console.log('Sort header clicked:', sortField);
                this.handleSort(sortField);
            });
        });

        // Export functionality
        const exportBtn = document.getElementById('export-csv');
        if (exportBtn) {
            console.log('Export button found');
            exportBtn.addEventListener('click', () => {
                console.log('Export button clicked');
                this.handleExport();
            });
        } else {
            console.error('Export button not found!');
        }

        // Reset filters functionality
        const resetBtn = document.getElementById('reset-filters');
        if (resetBtn) {
            console.log('Reset button found');
            resetBtn.addEventListener('click', () => {
                console.log('Reset button clicked');
                this.resetAllFilters();
            });
        } else {
            console.error('Reset button not found!');
        }

        // Delete button functionality
        this.bindDeleteButtons();
    }

    handleSearch(searchTerm) {
        this.currentParams.search = searchTerm;
        this.currentParams.page = 1; // Reset to first page
        this.updateUrl();
    }

    handleFilter(filterValue) {
        this.currentParams.filter = filterValue;
        this.currentParams.page = 1; // Reset to first page
        this.updateUrl();
    }

    handleSort(sortField) {
        if (this.currentParams.sort === sortField) {
            // Toggle order if same field
            this.currentParams.order = this.currentParams.order === 'ASC' ? 'DESC' : 'ASC';
        } else {
            // New field, default to ASC
            this.currentParams.sort = sortField;
            this.currentParams.order = 'ASC';
        }
        this.currentParams.page = 1; // Reset to first page
        this.updateUrl();
    }

    handleExport() {
        // Create export URL with current filters but no pagination
        const exportParams = new URLSearchParams();
        if (this.currentParams.search) {
            exportParams.set('search', this.currentParams.search);
        }
        if (this.currentParams.filter) {
            exportParams.set('filter', this.currentParams.filter);
        }
        exportParams.set('export', 'csv');

        const exportUrl = `${this.baseUrl}employee/view?${exportParams.toString()}`;
        console.log('Export URL:', exportUrl);
        
        // Show loading state
        const exportBtn = document.getElementById('export-csv');
        const originalText = exportBtn.textContent;
        exportBtn.textContent = 'Exporting...';
        exportBtn.disabled = true;

        // Navigate to the export URL directly - let the server handle the download
        window.location.href = exportUrl;

        // Reset button state
        setTimeout(() => {
            exportBtn.textContent = originalText;
            exportBtn.disabled = false;
        }, 2000);
    }

    resetAllFilters() {
        this.currentParams = {
            search: '',
            filter: '',
            sort: 'emp_id',
            order: 'ASC',
            page: 1
        };
        this.updateUrl();
    }

    updateUrl() {
        const params = new URLSearchParams();
        
        if (this.currentParams.search) {
            params.set('search', this.currentParams.search);
        }
        if (this.currentParams.filter) {
            params.set('filter', this.currentParams.filter);
        }
        if (this.currentParams.sort !== 'emp_id') {
            params.set('sort', this.currentParams.sort);
        }
        if (this.currentParams.order !== 'ASC') {
            params.set('order', this.currentParams.order);
        }
        if (this.currentParams.page > 1) {
            params.set('page', this.currentParams.page);
        }

        const newUrl = `${this.baseUrl}employee/view${params.toString() ? '?' + params.toString() : ''}`;
        console.log('Updating URL to:', newUrl);
        window.location.href = newUrl;
    }

    bindDeleteButtons() {
        const deleteButtons = document.querySelectorAll('.delete-button');
        console.log('Found delete buttons:', deleteButtons.length);
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const employeeId = e.target.getAttribute('data-id');
                console.log('Delete button clicked for employee:', employeeId);
                this.showDeleteModal(employeeId);
            });
        });
    }

    showDeleteModal(employeeId) {
        const modal = document.getElementById('delete-modal');
        const form = document.getElementById('delete-form');
        const idInput = document.getElementById('delete-item-id');
        
        if (modal && form && idInput) {
            idInput.value = employeeId;
            form.action = `${this.baseUrl}employee/delete`;
            modal.style.display = 'block';
            console.log('Modal shown for employee:', employeeId);
        } else {
            console.error('Modal elements not found:', { modal, form, idInput });
        }
    }

    updateDeleteModalActions() {
        const modal = document.getElementById('delete-modal');
        if (!modal) return;

        // Close modal functionality
        const closeBtn = modal.querySelector('.modal-closebtn');
        const cancelBtn = modal.querySelector('.cancel-button');
        
        [closeBtn, cancelBtn].forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    modal.style.display = 'none';
                });
            }
        });

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
}

// Global function for pagination (called from PHP template)
function navigateToPage(page) {
    const manager = window.employeeListManager;
    if (manager) {
        manager.currentParams.page = page;
        manager.updateUrl();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing EmployeeListManager...');
    window.employeeListManager = new EmployeeListManager();
    console.log('EmployeeListManager initialized successfully');
});

// Alert close functionality
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const closeBtn = alert.querySelector('.closebtn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                alert.style.display = 'none';
            });
        }
    });
});
