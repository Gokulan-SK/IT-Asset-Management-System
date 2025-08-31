// Production Dashboard Analytics JavaScript
class DashboardManager {
  constructor() {
    this.charts = {};
    this.originalData = window.dashboardData || {};
    this.initializeCharts();
    this.initializeFilters();
    this.initializeExports();
  }

  initializeCharts() {
    if (typeof window.dashboardData === "undefined") {
      console.warn("Dashboard data not available");
      return;
    }

    // Only create charts if there's data
    if (
      window.dashboardData.assetStatus &&
      window.dashboardData.assetStatus.data.some((val) => val > 0)
    ) {
      this.createAssetStatusChart(); // Donut chart
    } else {
      this.showNoDataMessage(
        "assetStatusChart",
        "No asset status data available"
      );
    }

    if (
      window.dashboardData.assetCategories &&
      window.dashboardData.assetCategories.data.length > 0
    ) {
      this.createCategoryChart(); // Pie chart
    } else {
      this.showNoDataMessage("categoryChart", "No category data available");
    }

    if (
      window.dashboardData.employeeRoles &&
      window.dashboardData.employeeRoles.data.length > 0
    ) {
      this.createEmployeeChart(); // Bar chart
    } else {
      this.showNoDataMessage("employeeChart", "No employee data available");
    }

    if (
      window.dashboardData.monthlyTrend &&
      window.dashboardData.monthlyTrend.data.length > 0
    ) {
      this.createTrendChart(); // Line chart
    } else {
      this.showNoDataMessage("trendChart", "No trend data available");
    }

    // Update statistics
    this.updateChartStats();
  }

  initializeFilters() {
    const dateFilter = document.getElementById("dateRangeFilter");
    const categoryFilter = document.getElementById("categoryFilter");
    const statusFilter = document.getElementById("statusFilter");
    const applyBtn = document.getElementById("applyFiltersBtn");

    if (applyBtn) {
      applyBtn.addEventListener("click", () => this.applyFilters());
    }

    // Auto-apply on change for better UX
    [dateFilter, categoryFilter, statusFilter].forEach((filter) => {
      if (filter) {
        filter.addEventListener("change", () => {
          // Debounce the filter application
          clearTimeout(this.filterTimeout);
          this.filterTimeout = setTimeout(() => this.applyFilters(), 300);
        });
      }
    });
  }

  initializeExports() {
    // Global export button
    const exportBtn = document.getElementById("exportBtn");
    if (exportBtn) {
      exportBtn.addEventListener("click", () => this.showExportOptions());
    }

    // Individual table export buttons
    document.querySelectorAll(".table-export").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const tableId = e.target.closest(".table-export").dataset.table;
        const filename = e.target.closest(".table-export").dataset.filename;
        this.exportTableToExcel(tableId, filename);
      });
    });
  }

  applyFilters() {
    this.showFilterLoading();

    setTimeout(() => {
      const dateRange = document.getElementById("dateRangeFilter")?.value;
      const category = document.getElementById("categoryFilter")?.value;
      const status = document.getElementById("statusFilter")?.value;

      const params = new URLSearchParams();
      if (dateRange && dateRange !== "30") params.append("days", dateRange);
      if (category && category !== "all") params.append("category", category);
      if (status && status !== "all") params.append("status", status);

      const url =
        window.location.pathname +
        (params.toString() ? "?" + params.toString() : "");
      window.location.href = url;
    }, 800);
  }

  showFilterLoading() {
    const tables = document.querySelectorAll(".data-table tbody");
    tables.forEach((tbody) => {
      tbody.innerHTML =
        '<tr><td colspan="100%" class="no-data">🔄 Applying filters...</td></tr>';
    });

    // Show loading on charts
    Object.values(this.charts).forEach((chart) => {
      if (chart && chart.canvas) {
        const ctx = chart.canvas.getContext("2d");
        ctx.save();
        ctx.globalAlpha = 0.5;
        ctx.fillStyle = "#f3f4f6";
        ctx.fillRect(0, 0, chart.canvas.width, chart.canvas.height);
        ctx.restore();
      }
    });
  }

  showExportOptions() {
    const options = [
      {
        id: "warranty-table",
        name: "Warranty Expiring Assets",
        filename: "warranty_expiring",
      },
      {
        id: "aging-table",
        name: "Aging Assets Report",
        filename: "aging_assets",
      },
      {
        id: "utilization-table",
        name: "Asset Utilization Report",
        filename: "asset_utilization",
      },
    ];

    const html = `
            <div class="export-modal" style="
                position: fixed; top: 0; left: 0; right: 0; bottom: 0; 
                background: rgba(0,0,0,0.5); z-index: 1000; 
                display: flex; align-items: center; justify-content: center;
            ">
                <div style="
                    background: white; border-radius: 16px; padding: 32px; 
                    max-width: 400px; width: 90%; box-shadow: 0 20px 40px rgba(0,0,0,0.2);
                ">
                    <h3 style="margin: 0 0 20px 0; color: #1F2937; font-size: 20px;">📊 Export Data</h3>
                    <p style="color: #6B7280; margin-bottom: 20px;">Choose which report to export:</p>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        ${options
                          .map(
                            (opt) => `
                            <button onclick="dashboardManager.exportTableToExcel('${opt.id}', '${opt.filename}'); this.closest('.export-modal').remove();" 
                                style="
                                    padding: 12px 16px; border: 2px solid #E5E7EB; border-radius: 8px; 
                                    background: white; color: #374151; cursor: pointer; text-align: left;
                                    transition: all 0.2s ease; font-weight: 500;
                                " 
                                onmouseover="this.style.borderColor='#3B82F6'; this.style.background='#F8FAFC';"
                                onmouseout="this.style.borderColor='#E5E7EB'; this.style.background='white';">
                                ${opt.name}
                            </button>
                        `
                          )
                          .join("")}
                    </div>
                    <button onclick="this.closest('.export-modal').remove();" 
                        style="
                            margin-top: 20px; padding: 10px 20px; background: #6B7280; 
                            color: white; border: none; border-radius: 6px; cursor: pointer;
                            width: 100%; font-weight: 600;
                        ">
                        Cancel
                    </button>
                </div>
            </div>
        `;

    document.body.insertAdjacentHTML("beforeend", html);
  }

  exportTableToExcel(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) {
      console.error("Table not found:", tableId);
      return;
    }

    // Check if XLSX library is available
    if (typeof XLSX === "undefined") {
      // Fallback to CSV export
      this.exportTableToCSV(tableId, filename);
      return;
    }

    // Create workbook
    const wb = XLSX.utils.book_new();

    // Convert table to worksheet
    const ws = XLSX.utils.table_to_sheet(table);

    // Style the worksheet
    const range = XLSX.utils.decode_range(ws["!ref"]);

    // Add header styling
    for (let col = range.s.c; col <= range.e.c; col++) {
      const cellAddress = XLSX.utils.encode_cell({ r: 0, c: col });
      if (ws[cellAddress]) {
        ws[cellAddress].s = {
          font: { bold: true, color: { rgb: "FFFFFF" } },
          fill: { fgColor: { rgb: "3B82F6" } },
          alignment: { horizontal: "center" },
        };
      }
    }

    // Set column widths
    const colWidths = [];
    for (let col = range.s.c; col <= range.e.c; col++) {
      colWidths.push({ wch: 20 });
    }
    ws["!cols"] = colWidths;

    // Add the worksheet to workbook
    XLSX.utils.book_append_sheet(wb, ws, "Data");

    // Generate Excel file and download
    const today = new Date().toISOString().split("T")[0];
    XLSX.writeFile(wb, `${filename}_${today}.xlsx`);

    this.showNotification("📊 Excel file exported successfully!", "success");
  }

  exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) {
      console.error("Table not found:", tableId);
      return;
    }

    // Create a simplified version for export
    const exportData = [];
    const headers = [];

    // Get headers
    table.querySelectorAll("thead th").forEach((th) => {
      headers.push(th.textContent.trim());
    });
    exportData.push(headers);

    // Get data rows
    table.querySelectorAll("tbody tr").forEach((tr) => {
      if (!tr.querySelector(".no-data")) {
        const row = [];
        tr.querySelectorAll("td").forEach((td) => {
          // Clean up the text content
          let text = td.textContent.trim();
          // Remove extra whitespace and special characters
          text = text.replace(/\s+/g, " ").replace(/[^\w\s\-\.\$\%\(\)]/g, "");
          row.push(text);
        });
        exportData.push(row);
      }
    });

    // Convert to CSV
    const csvContent = exportData
      .map((row) => row.map((cell) => `"${cell}"`).join(","))
      .join("\n");

    // Download
    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute(
      "download",
      `${filename}_${new Date().toISOString().split("T")[0]}.csv`
    );
    link.style.visibility = "hidden";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    this.showNotification("📊 CSV file exported successfully!", "success");
  }

  showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.style.cssText = `
            position: fixed; top: 20px; right: 20px; z-index: 1001;
            background: ${type === "success" ? "#10B981" : "#3B82F6"};
            color: white; padding: 12px 20px; border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            font-weight: 600; font-size: 14px;
            transform: translateX(100%); transition: transform 0.3s ease;
        `;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => (notification.style.transform = "translateX(0)"), 100);
    setTimeout(() => {
      notification.style.transform = "translateX(100%)";
      setTimeout(() => document.body.removeChild(notification), 300);
    }, 3000);
  }

  updateChartStats() {
    // Update most common status
    if (window.dashboardData.assetStatus) {
      const data = window.dashboardData.assetStatus;
      const maxIndex = data.data.indexOf(Math.max(...data.data));
      const mostCommon = document.getElementById("mostCommonStatus");
      if (mostCommon && data.labels[maxIndex]) {
        mostCommon.textContent = data.labels[maxIndex];
      }
    }
  }

  showNoDataMessage(canvasId, message) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const container = canvas.parentElement;
    container.innerHTML = `
            <div style="
                display: flex; 
                align-items: center; 
                justify-content: center; 
                height: 280px; 
                width: 100%;
                color: #6B7280; 
                font-style: italic;
            ">
                <div style="text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 12px; opacity: 0.5;">📊</div>
                    <div style="font-size: 14px; font-weight: 500;">${message}</div>
                </div>
            </div>
        `;
  }

  createAssetStatusChart() {
    const ctx = document.getElementById("assetStatusChart");
    if (!ctx) return;

    // Set canvas properties for consistent sizing
    ctx.width = 340;
    ctx.height = 280;
    ctx.style.width = "100%";
    ctx.style.height = "280px";

    const data = window.dashboardData.assetStatus;

    // Enhanced color palette for donut chart
    const colors = [
      "#10B981", // Available - Emerald
      "#F59E0B", // In Use - Amber
      "#EF4444", // Maintenance - Red
      "#6B7280", // Retired - Gray
    ];

    const config = {
      type: "doughnut",
      data: {
        labels: data.labels,
        datasets: [
          {
            data: data.data,
            backgroundColor: colors,
            borderWidth: chartType === "doughnut" ? 3 : 1,
            borderColor: "#ffffff",
            borderRadius: chartType === "bar" ? 6 : 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: chartType === "doughnut" ? "bottom" : "top",
            align: "center",
            labels: {
              padding: 20,
              usePointStyle: true,
              font: {
                size: 12,
                weight: "600",
              },
              boxWidth: 12,
              boxHeight: 12,
            },
          },
          tooltip: {
            backgroundColor: "rgba(17, 24, 39, 0.95)",
            titleColor: "#F9FAFB",
            bodyColor: "#F9FAFB",
            borderColor: "#374151",
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: true,
            callbacks: {
              label: function (context) {
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = ((context.parsed / total) * 100).toFixed(1);
                return `${context.label}: ${context.parsed} assets (${percentage}%)`;
              },
            },
          },
        },
        scales:
          chartType === "bar"
            ? {
                y: {
                  beginAtZero: true,
                  ticks: {
                    stepSize: 1,
                    font: { size: 11 },
                  },
                  grid: {
                    color: "#F3F4F6",
                  },
                },
                x: {
                  ticks: {
                    font: { size: 11 },
                  },
                  grid: {
                    display: false,
                  },
                },
              }
            : {},
        animation: {
          duration: 1500,
          easing: "easeOutQuart",
        },
      },
    };

    this.charts.assetStatus = new Chart(ctx, config);
  }

  createCategoryChart() {
    const ctx = document.getElementById("categoryChart");
    if (!ctx) return;

    ctx.width = 340;
    ctx.height = 280;
    ctx.style.width = "100%";
    ctx.style.height = "280px";

    const data = window.dashboardData.assetCategories;

    // Generate beautiful pie chart colors
    const colors = [
      "#3B82F6", // Blue
      "#10B981", // Emerald
      "#F59E0B", // Amber
      "#EF4444", // Red
      "#8B5CF6", // Violet
      "#06B6D4", // Cyan
      "#84CC16", // Lime
      "#F97316", // Orange
    ];

    const config = {
      type: "pie",
      data: {
        labels: data.labels,
        datasets: [
          {
            data: data.data,
            backgroundColor: colors.slice(0, data.labels.length),
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              font: { size: 11 },
              color: "#6B7280",
            },
            grid: {
              color: "#F3F4F6",
            },
          },
          x: {
            ticks: {
              maxRotation: chartType === "horizontalBar" ? 0 : 45,
              font: { size: 11 },
              color: "#6B7280",
            },
            grid: {
              display: chartType !== "horizontalBar",
            },
          },
        },
        animation: {
          duration: 1500,
          easing: "easeOutQuart",
        },
      },
    };

    this.charts.category = new Chart(ctx, config);
  }

  createEmployeeChart() {
    const ctx = document.getElementById("employeeChart");
    if (!ctx) return;

    ctx.width = 340;
    ctx.height = 280;
    ctx.style.width = "100%";
    ctx.style.height = "280px";

    const data = window.dashboardData.employeeRoles;

    // Professional color palette for bar chart
    const colors = [
      "#8B5CF6", // Violet
      "#EC4899", // Pink
      "#10B981", // Emerald
      "#F59E0B", // Amber
      "#EF4444", // Red
      "#06B6D4", // Cyan
      "#84CC16", // Lime
      "#F97316", // Orange
      "#6366F1", // Indigo
      "#14B8A6", // Teal
    ];

    const config = {
      type: "bar",
      data: {
        labels: data.labels,
        datasets: [
          {
            label: "Employees",
            data: data.data,
            backgroundColor: colors.slice(0, data.labels.length),
            borderColor: colors.slice(0, data.labels.length).map(color => color),
            borderWidth: 1,
            borderRadius: 6,
            borderSkipped: false,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            backgroundColor: "rgba(17, 24, 39, 0.95)",
            titleColor: "#F9FAFB",
            bodyColor: "#F9FAFB",
            borderColor: "#374151",
            borderWidth: 1,
            cornerRadius: 8,
            callbacks: {
              label: function (context) {
                return `${context.label}: ${context.parsed.y} employees`;
              },
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              font: { size: 11 },
              color: "#6B7280",
            },
            grid: {
              color: "#E5E7EB",
              borderColor: "#D1D5DB",
            },
          },
          x: {
            ticks: {
              maxRotation: 45,
              font: { size: 11 },
              color: "#6B7280",
            },
            grid: {
              display: false,
            },
          },
        },
        animation: {
          duration: 1500,
          easing: "easeOutQuart",
        },
      },
    };

    this.charts.employee = new Chart(ctx, config);
  }

  createTrendChart() {
    const ctx = document.getElementById("trendChart");
    if (!ctx) return;

    ctx.width = 340;
    ctx.height = 280;
    ctx.style.width = "100%";
    ctx.style.height = "280px";

    const data = window.dashboardData.monthlyTrend;

    const config = {
      type: "line",
      data: {
        labels: data.labels,
        datasets: [
          {
            label: "Allocations",
            data: data.data,
            borderColor: "#10B981",
            backgroundColor:
              chartType === "line"
                ? "rgba(16, 185, 129, 0.1)"
                : "rgba(16, 185, 129, 0.8)",
            borderWidth: 3,
            fill: chartType === "line",
            tension: chartType === "line" ? 0.4 : 0,
            pointBackgroundColor: "#10B981",
            pointBorderColor: "#ffffff",
            pointBorderWidth: 2,
            pointRadius: chartType === "line" ? 6 : 0,
            pointHoverRadius: 8,
            borderRadius: chartType === "bar" ? 8 : 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            backgroundColor: "rgba(17, 24, 39, 0.95)",
            titleColor: "#F9FAFB",
            bodyColor: "#F9FAFB",
            borderColor: "#374151",
            borderWidth: 1,
            cornerRadius: 8,
            callbacks: {
              label: function (context) {
                return `Allocations: ${context.parsed.y}`;
              },
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              font: { size: 11 },
              color: "#6B7280",
            },
            grid: {
              color: "#F3F4F6",
            },
          },
          x: {
            grid: {
              display: false,
            },
            ticks: {
              font: { size: 11 },
              color: "#6B7280",
            },
          },
        },
        interaction: {
          intersect: false,
          mode: "index",
        },
        animation: {
          duration: 1500,
          easing: "easeOutQuart",
        },
      },
    };

    this.charts.trend = new Chart(ctx, config);
  }

  // Utility method to refresh all charts
  refreshCharts() {
    Object.values(this.charts).forEach((chart) => {
      chart.update();
    });
  }

  // Utility method to destroy all charts
  destroyCharts() {
    Object.values(this.charts).forEach((chart) => {
      chart.destroy();
    });
    this.charts = {};
  }
}

// Initialize dashboard when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
  // Initialize dashboard manager
  window.dashboardManager = new DashboardManager();

  // Add refresh functionality if needed
  const refreshButton = document.querySelector(".refresh-dashboard");
  if (refreshButton) {
    refreshButton.addEventListener("click", function () {
      location.reload();
    });
  }

  // Auto-refresh every 5 minutes (optional)
  // setInterval(() => {
  //     location.reload();
  // }, 5 * 60 * 1000);
});

// Handle window resize
window.addEventListener("resize", function () {
  if (window.dashboardManager) {
    setTimeout(() => {
      window.dashboardManager.refreshCharts();
    }, 300);
  }
});
