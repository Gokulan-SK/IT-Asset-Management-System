/**
 * Asset Ledger Management JavaScript
 * Handles search, filter, sort, export, and pagination functionality
 */

class AssetLedgerManager {
  constructor() {
    this.searchTimer = null;
    this.searchDelay = 500; // 500ms delay for search
    this.currentFilters = this.getFiltersFromURL();
    this.baseUrl = BASE_URL || "";
    this.init();
  }

  init() {
    this.bindEvents();
    this.updateUIFromFilters();
  }

  getFiltersFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return {
      search: urlParams.get("search") || "",
      statusFilter: urlParams.get("statusFilter") || "",
      sort: urlParams.get("sort") || "ledger_id",
      order: urlParams.get("order") || "DESC",
      page: parseInt(urlParams.get("page")) || 1,
      limit: parseInt(urlParams.get("limit")) || 10,
    };
  }

  buildURL(filters = {}) {
    const params = { ...this.currentFilters, ...filters };

    // Remove empty parameters
    Object.keys(params).forEach((key) => {
      if (params[key] === "" || params[key] === "all") {
        delete params[key];
      }
    });

    // Always reset to page 1 when filters change (except when specifically changing page)
    if (!filters.hasOwnProperty("page")) {
      params.page = 1;
    }

    const url = new URL(window.location.href);
    url.search = new URLSearchParams(params).toString();
    return url.toString();
  }

  updateFilters(newFilters) {
    this.currentFilters = { ...this.currentFilters, ...newFilters };
    const newURL = this.buildURL();
    window.location.href = newURL;
  }

  bindEvents() {
    console.log("Binding events for AssetLedgerManager...");

    // Search functionality with debouncing
    const searchInput = document.getElementById("table-search");
    if (searchInput) {
      console.log("Search input found");
      searchInput.addEventListener("input", (e) => {
        clearTimeout(this.searchTimer);
        this.searchTimer = setTimeout(() => {
          console.log("Search triggered:", e.target.value);
          this.handleSearch(e.target.value);
        }, this.searchDelay);
      });
    } else {
      console.error("Search input not found!");
    }

    // Clear search button
    const clearSearchBtn = document.getElementById("clear-search");
    if (clearSearchBtn) {
      console.log("Clear search button found");
      clearSearchBtn.addEventListener("click", () => {
        console.log("Clear search clicked");
        searchInput.value = "";
        this.handleSearch("");
      });
    } else {
      console.error("Clear search button not found!");
    }

    // Status filter functionality
    const statusFilterSelect = document.getElementById("status-filter");
    if (statusFilterSelect) {
      console.log("Status filter select found");
      statusFilterSelect.addEventListener("change", (e) => {
        console.log("Status filter changed:", e.target.value);
        this.handleStatusFilter(e.target.value);
      });
    } else {
      console.error("Status filter select not found!");
    }

    // Sort functionality
    const sortableHeaders = document.querySelectorAll(".sortable");
    console.log("Found sortable headers:", sortableHeaders.length);
    sortableHeaders.forEach((header) => {
      header.addEventListener("click", () => {
        const sortField = header.getAttribute("data-sort");
        console.log("Sort header clicked:", sortField);
        this.handleSort(sortField);
      });
    });

    // Export functionality
    const exportBtn = document.getElementById("export-csv");
    if (exportBtn) {
      console.log("Export button found");
      exportBtn.addEventListener("click", () => {
        console.log("Export button clicked");
        this.handleExport();
      });
    } else {
      console.error("Export button not found!");
    }

    // Reset filters functionality
    const resetBtn = document.getElementById("reset-filters");
    if (resetBtn) {
      console.log("Reset button found");
      resetBtn.addEventListener("click", () => {
        console.log("Reset button clicked");
        this.resetAllFilters();
      });
    } else {
      console.error("Reset button not found!");
    }

    // Page size functionality
    const pageSizeSelect = document.getElementById("page-size");
    if (pageSizeSelect) {
      console.log("Page size select found");
      pageSizeSelect.addEventListener("change", (e) => {
        console.log("Page size changed:", e.target.value);
        this.updatePageSize(parseInt(e.target.value));
      });
    } else {
      console.error("Page size select not found!");
    }

    // Reset filters inline button
    const resetInlineBtn = document.getElementById("reset-filters-inline");
    if (resetInlineBtn) {
      resetInlineBtn.addEventListener("click", () => {
        this.resetAllFilters();
      });
    }
  }

  handleSearch(searchTerm) {
    this.updateFilters({ search: searchTerm, page: 1 });
  }

  handleStatusFilter(statusValue) {
    this.updateFilters({ statusFilter: statusValue, page: 1 });
  }

  handleSort(sortField) {
    if (this.currentFilters.sort === sortField) {
      // Toggle order if same field
      const newOrder = this.currentFilters.order === "ASC" ? "DESC" : "ASC";
      this.updateFilters({ order: newOrder });
    } else {
      // New field, default to ASC
      this.updateFilters({ sort: sortField, order: "ASC" });
    }
  }

  resetAllFilters() {
    console.log("Resetting all filters");
    // Keep only the base URL
    window.location.href = this.baseUrl + "asset-ledger/view";
  }

  handleExport() {
    // Create export URL with current filters but no pagination
    const exportParams = new URLSearchParams();
    if (this.currentFilters.search) {
      exportParams.set("search", this.currentFilters.search);
    }
    if (this.currentFilters.statusFilter) {
      exportParams.set("statusFilter", this.currentFilters.statusFilter);
    }
    exportParams.set("export", "csv");

    const exportURL = `${this.baseUrl}asset-ledger/view?${exportParams.toString()}`;
    console.log("Exporting to:", exportURL);
    window.location.href = exportURL;
  }

  updateUIFromFilters() {
    // Update search input
    const searchInput = document.getElementById("table-search");
    if (searchInput) {
      searchInput.value = this.currentFilters.search;
    }

    // Update status filter
    const statusFilterSelect = document.getElementById("status-filter");
    if (statusFilterSelect) {
      statusFilterSelect.value = this.currentFilters.statusFilter;
    }

    // Update page size selector
    const pageSizeSelect = document.getElementById("page-size");
    if (pageSizeSelect) {
      pageSizeSelect.value = this.currentFilters.limit;
    }
  }

  changePage(page) {
    console.log("changePage called with page:", page);
    this.updateFilters({ page: page });
  }

  updatePageSize(newSize) {
    console.log("updatePageSize called with size:", newSize);
    this.updateFilters({ limit: newSize, page: 1 });
  }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  console.log("DOM loaded, initializing AssetLedgerManager...");
  window.AssetLedgerManager = new AssetLedgerManager();
  console.log("AssetLedgerManager initialized successfully");

  // Alert close functionality
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    const closeBtn = alert.querySelector(".closebtn");
    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        alert.style.display = "none";
      });
    }
  });
});
