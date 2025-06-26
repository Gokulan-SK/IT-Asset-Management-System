const category = document.getElementById("category");
const subcategoryContainer = document.getElementById("subcategory-dropdown");
const subcategorySelect = document.getElementById("subcategory");
const softwareGroup = document.querySelectorAll(".software");
const hardwareGroup = document.querySelectorAll(".hardware");
const statusDropdown = document.getElementById("status");

const statusOptions = {
  software: [
    { value: "active", text: "Active" },
    { value: "expired", text: "Expired" },
    { value: "revoked", text: "Revoked" },
    { value: "inactive", text: "Inactive" },
  ],
  hardware: [
    { value: "in_storage", text: "In Storage" },
    { value: "in_use", text: "In Use" },
    { value: "under_repair", text: "Under Repair" },
    { value: "retired", text: "Retired" },
    { value: "disposed", text: "Disposed" },
  ],
};

const subcategory = {
  hardware: [
    { value: "laptop", text: "Laptop" },
    { value: "desktop", text: "Desktop" },
    { value: "server", text: "Server" },
    { value: "mobile_device", text: "Mobile Device" },
    { value: "networking_equipment", text: "Networking Equipment" },
    { value: "storage_device", text: "Storage Device" },
    { value: "accessories", text: "Accessories" },
  ],
  software: [
    { value: "operating_system", text: "Operating System" },
    { value: "productivity_tool", text: "Productivity Tool" },
    { value: "development_tool", text: "Development Tool" },
    { value: "security_software", text: "Security Software" },
    { value: "enterprise_software", text: "Enterprise Software" },
    { value: "cloud_subscription", text: "Cloud Subscription" },
  ],
  office_equipment: [
    { value: "printer", text: "Printer" },
    { value: "scanner", text: "Scanner" },
    { value: "projector", text: "Projector" },
    { value: "audio_visual-equipment", text: "Audio Visual Equipment" },
    {
      value: "video_conferencing-equipment",
      text: "Video Conferencing Equipment",
    },
    { value: "telephone", text: "Telephone" },
    { value: "fax_machine", text: "Fax Machine" },
  ],
  other: [], // no subcategories for "other"
};

function updateStatusDropdown(categoryKey) {
  statusDropdown.innerHTML =
    '<option value="" disabled selected>Select a status</option>';
  const options =
    statusOptions[categoryKey === "software" ? "software" : "hardware"] || [];

  options.forEach((option, index) => {
    const opt = document.createElement("option");
    opt.value = option.value;
    opt.textContent = option.text;
    if (index === 0) opt.setAttribute("selected", "selected");
    statusDropdown.appendChild(opt);
  });
}

function updateSubcategoryDropdown(categoryKey) {
  const options = subcategory[categoryKey] || [];

  if (options.length === 0) {
    subcategoryContainer.classList.add("hidden");
    subcategorySelect.classList.add("hidden");
    subcategorySelect.innerHTML = "";
    return;
  }

  subcategorySelect.innerHTML =
    '<option value="" disabled selected>Select a subcategory</option>';
  options.forEach((option) => {
    const opt = document.createElement("option");
    opt.value = option.value;
    opt.textContent = option.text;
    subcategorySelect.appendChild(opt);
  });

  subcategoryContainer.classList.remove("hidden");
  subcategorySelect.classList.remove("hidden");
}

category.addEventListener("change", function () {
  const selectedCategory = category.value;

  // Reset visibility
  softwareGroup.forEach((group) => group.classList.add("hidden"));
  hardwareGroup.forEach((group) => group.classList.add("hidden"));

  if (selectedCategory === "software") {
    softwareGroup.forEach((group) => group.classList.remove("hidden"));
    updateStatusDropdown("software");
    updateSubcategoryDropdown("software");
  } else if (selectedCategory === "hardware") {
    hardwareGroup.forEach((group) => group.classList.remove("hidden"));
    updateStatusDropdown("hardware");
    updateSubcategoryDropdown("hardware");
  } else if (selectedCategory === "office_equipment") {
    hardwareGroup.forEach((group) => group.classList.remove("hidden"));
    updateStatusDropdown("hardware");
    updateSubcategoryDropdown("office_equipment");
  } else if (selectedCategory === "other") {
    hardwareGroup.forEach((group) => group.classList.remove("hidden"));
    updateStatusDropdown("hardware");
    updateSubcategoryDropdown("other"); // will auto-hide dropdown
  } else {
    updateStatusDropdown("hardware");
    updateSubcategoryDropdown("other");
  }
});

document.addEventListener("DOMContentLoaded", function () {
  subcategoryContainer.classList.add("hidden");
});
