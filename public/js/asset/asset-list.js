class AssetListManager {
  constructor() {
    this.searchTimer = null;
    this.searchDelay = 500; // 500ms delay for search
    this.currentFilters = this.getFiltersFromURL();
    this.currentAssetId = null;
    this.baseUrl = BASE_URL || "";
    this.init();
  }

  init() {
    this.bindEvents();
    this.updateUIFromFilters();
    this.updateDeleteModalActions();
  }

  getFiltersFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return {
      search: urlParams.get("search") || "",
      statusFilter: urlParams.get("statusFilter") || "",
      categoryFilter: urlParams.get("categoryFilter") || "",
      sort: urlParams.get("sort") || "asset_id",
      order: urlParams.get("order") || "ASC",
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
    console.log("Binding events for AssetListManager...");

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

    // Category filter functionality
    const categoryFilterSelect = document.getElementById("category-filter");
    if (categoryFilterSelect) {
      console.log("Category filter select found");
      categoryFilterSelect.addEventListener("change", (e) => {
        console.log("Category filter changed:", e.target.value);
        this.handleCategoryFilter(e.target.value);
      });
    }

    // Sort functionality
    const sortableHeaders = document.querySelectorAll(".sortable");
    console.log("Found sortable headers:", sortableHeaders.length);
    sortableHeaders.forEach((header) => {
      header.addEventListener("click", () => {
        const sortField = header.getAttribute("data-sort");
        console.log("Sort clicked:", sortField);
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
  }

  handleSearch(searchTerm) {
    this.updateFilters({ search: searchTerm, page: 1 });
  }

  handleStatusFilter(statusValue) {
    this.updateFilters({ statusFilter: statusValue, page: 1 });
  }

  handleCategoryFilter(categoryValue) {
    this.updateFilters({ categoryFilter: categoryValue, page: 1 });
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
    window.location.href = this.baseUrl + "asset/view";
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
    if (this.currentFilters.categoryFilter) {
      exportParams.set("categoryFilter", this.currentFilters.categoryFilter);
    }
    exportParams.set("export", "csv");

    const exportUrl = `${this.baseUrl}asset/view?${exportParams.toString()}`;
    console.log("Export URL:", exportUrl);

    // Show loading state
    const exportBtn = document.getElementById("export-csv");
    const originalText = exportBtn.textContent;
    exportBtn.textContent = "Exporting...";
    exportBtn.disabled = true;

    // Create a temporary link to trigger download
    const link = document.createElement("a");
    link.href = exportUrl;
    link.download = `assets_${new Date().toISOString().split("T")[0]}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Reset button state
    setTimeout(() => {
      exportBtn.textContent = originalText;
      exportBtn.disabled = false;
    }, 2000);
  }

  updateUIFromFilters() {
    // Update search input
    const searchInput = document.getElementById("table-search");
    if (searchInput && this.currentFilters.search) {
      searchInput.value = this.currentFilters.search;
    }

    // Update status filter
    const statusFilter = document.getElementById("status-filter");
    if (statusFilter && this.currentFilters.statusFilter) {
      statusFilter.value = this.currentFilters.statusFilter;
    }

    // Update category filter
    const categoryFilter = document.getElementById("category-filter");
    if (categoryFilter && this.currentFilters.categoryFilter) {
      categoryFilter.value = this.currentFilters.categoryFilter;
    }

    // Update page size selector
    const pageSizeSelect = document.getElementById("page-size");
    if (pageSizeSelect && this.currentFilters.limit) {
      pageSizeSelect.value = this.currentFilters.limit.toString();
    }
  }

  bindDeleteButtons() {
    const deleteButtons = document.querySelectorAll(".delete-button");
    console.log("Found delete buttons:", deleteButtons.length);

    deleteButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        e.preventDefault();
        const assetId = button.getAttribute("data-id");
        console.log("Delete button clicked for asset:", assetId);
        this.showDeleteModal(assetId);
      });
    });

    // Reset filters inline button
    const resetInlineBtn = document.getElementById("reset-filters-inline");
    if (resetInlineBtn) {
      resetInlineBtn.addEventListener("click", () => {
        this.resetAllFilters();
      });
    }
  }

  showDeleteModal(assetId) {
    this.currentAssetId = assetId;
    const modal = document.getElementById("delete-modal");
    const form = document.getElementById("delete-form");
    const idInput = document.getElementById("delete-item-id");

    if (modal && form && idInput) {
      idInput.value = assetId;
      form.action = `${this.baseUrl}asset/delete`;
      modal.style.display = "block";
      console.log("Delete modal shown for asset:", assetId);
    } else {
      console.error("Delete modal elements not found:", { modal, form, idInput });
    }

    // Close modal when clicking outside
    window.addEventListener("click", (e) => {
      if (e.target === modal) {
        this.closeDeleteModal();
      }
    });

    // Close modal with close button
    const closeBtn = modal.querySelector(".modal-closebtn");
    const cancelBtn = modal.querySelector(".cancel-button");
    
    [closeBtn, cancelBtn].forEach(btn => {
      if (btn) {
        btn.addEventListener("click", () => {
          this.closeDeleteModal();
        });
      }
    });
  }

  closeDeleteModal() {
    const modal = document.getElementById("delete-modal");
    if (modal) {
      modal.style.display = "none";
      this.currentAssetId = null;
      console.log("Delete modal closed");
    }
  }

  updateDeleteModalActions() {
    const modal = document.getElementById("delete-modal");
    if (!modal) return;

    // Close modal functionality
    const closeBtn = modal.querySelector(".modal-closebtn");
    const cancelBtn = modal.querySelector(".cancel-button");
    
    [closeBtn, cancelBtn].forEach(btn => {
      if (btn) {
        btn.addEventListener("click", () => {
          modal.style.display = "none";
        });
      }
    });

    // Close modal when clicking outside
    window.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.style.display = "none";
      }
    });

    // Bind delete buttons
    this.bindDeleteButtons();
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
  console.log("DOM loaded, initializing AssetListManager...");
  window.AssetListManager = new AssetListManager();
  console.log("AssetListManager initialized successfully");

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
