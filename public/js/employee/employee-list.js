/**
 * Employee List Management JavaScript
 * Handles search, filter, sort, export, and pagination functionality
 */

class EmployeeListManager {
  constructor() {
    this.baseUrl = window.BASE_URL || "/asset_management/";
    this.currentParams = this.parseUrlParams();
    console.log("EmployeeListManager initialized with baseUrl:", this.baseUrl);
    console.log("Current params:", this.currentParams);
    this.init();
  }

  init() {
    this.bindEvents();
    this.updateDeleteModalActions();
    this.updateUIFromFilters();
  }

  parseUrlParams() {
    const urlParams = new URLSearchParams(window.location.search);
    return {
      search: urlParams.get("search") || "",
      filter: urlParams.get("filter") || "",
      sort: urlParams.get("sort") || "emp_id",
      order: urlParams.get("order") || "ASC",
      page: parseInt(urlParams.get("page")) || 1,
      limit: parseInt(urlParams.get("limit")) || 10,
    };
  }

  bindEvents() {
    console.log("Binding events...");

    // Search functionality with debounce
    const searchInput = document.getElementById("table-search");
    if (searchInput) {
      let searchTimeout;
      searchInput.addEventListener("input", (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
          this.updateStateAndNavigate({ search: e.target.value });
        }, 500);
      });
    }

    // Clear search button
    const clearSearchBtn = document.getElementById("clear-search");
    if (clearSearchBtn && searchInput) {
      clearSearchBtn.addEventListener("click", () => {
        searchInput.value = "";
        this.updateStateAndNavigate({ search: "" });
      });
    }

    // Filter functionality
    const filterSelect = document.getElementById("designation-filter");
    if (filterSelect) {
      filterSelect.addEventListener("change", (e) => {
        this.updateStateAndNavigate({ filter: e.target.value });
      });
    }

    // Sort functionality
    const sortableHeaders = document.querySelectorAll(".sortable");
    sortableHeaders.forEach((header) => {
      header.addEventListener("click", () => {
        const sortField = header.getAttribute("data-sort");
        const newOrder =
          this.currentParams.sort === sortField &&
          this.currentParams.order === "ASC"
            ? "DESC"
            : "ASC";
        this.updateStateAndNavigate({ sort: sortField, order: newOrder });
      });
    });

    // Export functionality
    const exportBtn = document.getElementById("export-csv");
    if (exportBtn) {
      exportBtn.addEventListener("click", () => {
        this.handleExport();
      });
    }

    // Reset filters functionality
    const resetBtn = document.getElementById("reset-filters");
    if (resetBtn) {
      resetBtn.addEventListener("click", () => this.resetAllFilters());
    }

    // Limit/page-size selector (optional, like asset JS)
    const pageSizeSelect = document.getElementById("page-size");
    if (pageSizeSelect) {
      pageSizeSelect.addEventListener("change", (e) => {
        this.updateStateAndNavigate({ limit: parseInt(e.target.value) });
      });
    }

    // Delete button functionality
    this.bindDeleteButtons();
  }

  // --- Pagination / URL Logic ---
  buildURL() {
    const params = new URLSearchParams();
    for (const key in this.currentParams) {
      const value = this.currentParams[key];
      if (
        (key === "page" && value > 1) ||
        (key === "sort" && value !== "emp_id") ||
        (key === "order" && value !== "ASC") ||
        (key === "limit" && value !== 10) ||
        (!["page", "sort", "order", "limit"].includes(key) && value !== "")
      ) {
        params.set(key, value);
      }
    }
    return `${this.baseUrl}employee/view?${params.toString()}`;
  }

  updateStateAndNavigate(newParams) {
    this.currentParams = { ...this.currentParams, ...newParams };
    if (!newParams.hasOwnProperty("page")) {
      this.currentParams.page = 1;
    }
    window.location.href = this.buildURL();
  }

  // Called from PHP pagination buttons
  changePage(page) {
    if (page === this.currentParams.page) return;
    this.updateStateAndNavigate({ page });
  }

  // --- Other existing functionalities ---
  handleExport() {
    const exportUrl =
      this.buildURL() +
      (this.buildURL().includes("?") ? "&" : "?") +
      "export=csv";
    window.location.href = exportUrl;
  }

  resetAllFilters() {
    window.location.href = `${this.baseUrl}employee/view`;
  }

  updateUIFromFilters() {
    document.getElementById("table-search").value = this.currentParams.search;
    const filterSelect = document.getElementById("designation-filter");
    if (filterSelect) filterSelect.value = this.currentParams.filter;
    const pageSizeSelect = document.getElementById("page-size");
    if (pageSizeSelect) pageSizeSelect.value = this.currentParams.limit;
  }

  bindDeleteButtons() {
    const deleteButtons = document.querySelectorAll(".delete-button");
    deleteButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        const employeeId = button.getAttribute("data-id");
        this.showDeleteModal(employeeId);
      });
    });
  }

  showDeleteModal(employeeId) {
    const modal = document.getElementById("delete-modal");
    const form = document.getElementById("delete-form");
    const idInput = document.getElementById("delete-item-id");
    if (modal && form && idInput) {
      idInput.value = employeeId;
      form.action = `${this.baseUrl}employee/delete`;
      modal.style.display = "block";
    }
  }

  updateDeleteModalActions() {
    const modal = document.getElementById("delete-modal");
    if (!modal) return;
    const closeBtn = modal.querySelector(".modal-closebtn");
    const cancelBtn = modal.querySelector(".cancel-button");
    [closeBtn, cancelBtn].forEach((btn) => {
      if (btn)
        btn.addEventListener("click", () => (modal.style.display = "none"));
    });
    window.addEventListener("click", (e) => {
      if (e.target === modal) modal.style.display = "none";
    });
  }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  window.employeeListManager = new EmployeeListManager();

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
