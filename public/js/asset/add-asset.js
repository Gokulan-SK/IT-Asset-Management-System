const assetCategory = document.getElementById("asset-category");
const softwareGroup = document.querySelectorAll(".software");
const hardwareGroup = document.querySelectorAll(".hardware");
const statusDropdown = document.getElementById("status");

const statusOptions = {
  software: [
    { value: "active", text: "Active" },
    { value: "expired", text: "Expired" },
    { value: "suspended", text: "Suspended" },
    { value: "inactive", text: "Inactive" },
  ],
  hardware: [
    { value: "assigned", text: "Assigned" },
    { value: "available", text: "Available" },
    { value: "under-maintenance", text: "Under Maintenance" },
    { value: "retired", text: "Retired" },
    { value: "disposed", text: "Disposed" },
  ],
};

function updateStatusDropdown(category) {
  statusDropdown.innerHTML =
    '<option value="" disabled selected>select a status</option>';
  const options = statusOptions[category];
  options.forEach((option) => {
    const opt = document.createElement("option");
    opt.value = option.value;
    opt.textContent = option.text;
    statusDropdown.appendChild(opt);
  });
}

assetCategory.addEventListener("change", function () {
  const selectedCategory = assetCategory.value;

  if (selectedCategory === "software") {
    softwareGroup.forEach((group) => group.classList.remove("hidden"));
    hardwareGroup.forEach((group) => group.classList.add("hidden"));
    updateStatusDropdown("software");
  } else {
    softwareGroup.forEach((group) => group.classList.add("hidden"));
    hardwareGroup.forEach((group) => group.classList.remove("hidden"));
    updateStatusDropdown("hardware");
  }
});
