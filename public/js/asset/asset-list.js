class AssetListManager {
  constructor() {
    this.searchTimer = null;
    this.searchDelay = 500;
    this.currentFilters = this.getFiltersFromURL();
    this.baseUrl = typeof BASE_URL !== "undefined" ? BASE_URL : "/";

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
      categoryFilter: urlParams.get("categoryFilter") || "",
      sort: urlParams.get("sort") || "asset_id",
      order: urlParams.get("order") || "ASC",
      page: parseInt(urlParams.get("page")) || 1,
      limit: parseInt(urlParams.get("limit")) || 10,
    };
  }

  // SIMPLIFIED AND FIXED: This now correctly builds the URL from the class's current state.
  buildURL() {
    const params = new URLSearchParams();
    for (const key in this.currentFilters) {
      const value = this.currentFilters[key];
      // Add param if it's not empty or a default value
      if (value && value !== "all") {
        if (
          (key === "page" && value > 1) ||
          (key === "limit" && value !== 10) ||
          (key === "sort" && value !== "asset_id") ||
          (key === "order" && value !== "ASC") ||
          !["page", "limit", "sort", "order"].includes(key)
        ) {
          params.set(key, value);
        }
      }
    }
    return `${this.baseUrl}asset/view?${params.toString()}`;
  }

  // FIXED: This is the central function to update state and navigate
  updateStateAndNavigate(newFilters) {
    // Merge new filters with current ones
    this.currentFilters = { ...this.currentFilters, ...newFilters };

    // Always reset to page 1 if any filter/sort/limit changes, EXCEPT for pagination itself
    if (!newFilters.hasOwnProperty("page")) {
      this.currentFilters.page = 1;
    }

    window.location.href = this.buildURL();
  }

  bindEvents() {
    // Search functionality
    document.getElementById("table-search")?.addEventListener("input", (e) => {
      clearTimeout(this.searchTimer);
      this.searchTimer = setTimeout(() => {
        this.updateStateAndNavigate({ search: e.target.value });
      }, this.searchDelay);
    });

    // Other filters
    document.getElementById("clear-search")?.addEventListener("click", () => {
      document.getElementById("table-search").value = "";
      this.updateStateAndNavigate({ search: "" });
    });
    document
      .getElementById("status-filter")
      ?.addEventListener("change", (e) =>
        this.updateStateAndNavigate({ statusFilter: e.target.value })
      );
    document
      .getElementById("category-filter")
      ?.addEventListener("change", (e) =>
        this.updateStateAndNavigate({ categoryFilter: e.target.value })
      );
    document
      .getElementById("reset-filters")
      ?.addEventListener("click", () => this.resetAllFilters());
    document
      .getElementById("reset-filters-inline")
      ?.addEventListener("click", () => this.resetAllFilters());
    document
      .getElementById("export-csv")
      ?.addEventListener("click", () => this.handleExport());
    document
      .getElementById("page-size")
      ?.addEventListener("change", (e) =>
        this.updateStateAndNavigate({ limit: parseInt(e.target.value) })
      );

    // Sort functionality
    document.querySelectorAll(".sortable").forEach((header) => {
      header.addEventListener("click", () => {
        const sortField = header.getAttribute("data-sort");
        const newOrder =
          this.currentFilters.sort === sortField &&
          this.currentFilters.order === "ASC"
            ? "DESC"
            : "ASC";
        this.updateStateAndNavigate({ sort: sortField, order: newOrder });
      });
    });

    // --- Modal and Delete Button Binding (Done once on init) ---
    this.bindDeleteButtons();
    this.bindModalCloseEvents();
  }

  resetAllFilters() {
    window.location.href = this.baseUrl + "asset/view";
  }

  handleExport() {
    const exportUrl =
      this.buildURL() +
      (this.buildURL().includes("?") ? "&" : "?") +
      "export=csv";
    window.location.href = exportUrl;
  }

  updateUIFromFilters() {
    document.getElementById("table-search").value = this.currentFilters.search;
    document.getElementById("status-filter").value =
      this.currentFilters.statusFilter;
    document.getElementById("category-filter").value =
      this.currentFilters.categoryFilter;
    document.getElementById("page-size").value =
      this.currentFilters.limit.toString();
  }

  // --- Modal Logic (Cleaned up) ---
  bindDeleteButtons() {
    document.querySelectorAll(".delete-button").forEach((button) => {
      button.addEventListener("click", (e) => {
        e.preventDefault();
        const assetId = button.getAttribute("data-id");
        this.showDeleteModal(assetId);
      });
    });
  }

  bindModalCloseEvents() {
    const modal = document.getElementById("delete-modal");
    if (!modal) return;
    const closeBtn = modal.querySelector(".modal-closebtn");
    const cancelBtn = modal.querySelector(".cancel-button");
    const closeModal = () => (modal.style.display = "none");

    closeBtn?.addEventListener("click", closeModal);
    cancelBtn?.addEventListener("click", closeModal);
    window.addEventListener("click", (e) => {
      if (e.target === modal) closeModal();
    });
  }

  showDeleteModal(assetId) {
    const modal = document.getElementById("delete-modal");
    const form = document.getElementById("delete-form");
    const idInput = document.getElementById("delete-item-id");
    if (modal && form && idInput) {
      idInput.value = assetId;
      form.action = `${this.baseUrl}asset/delete`;
      modal.style.display = "flex"; // Use flex for centering
    }
  }

  // Public method called by the onclick attribute in the PHP view
  changePage(page) {
    if (page === this.currentFilters.page) return;
    this.updateStateAndNavigate({ page: page });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  // This makes the object available globally for the inline `onclick` attributes
  window.AssetListManager = new AssetListManager();

  // Alert close functionality
  document.querySelectorAll(".alert .closebtn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.target.parentElement.style.display = "none";
    });
  });
});
