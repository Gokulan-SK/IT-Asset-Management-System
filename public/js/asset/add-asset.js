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
    { value: "assigned", text: "Assigned" },
    { value: "available", text: "Available" },
    { value: "under-maintenance", text: "Under Maintenance" },
    { value: "retired", text: "Retired" },
    { value: "disposed", text: "Disposed" },
  ],
};

const subcategory = {
  hardware: [
    { value: "laptop", text: "Laptop" },
    { value: "desktop", text: "Desktop" },
    { value: "server", text: "Server" },
    { value: "mobile-device", text: "Mobile Device" },
    { value: "networking-equipment", text: "Networking Equipment" },
    { value: "storage-device", text: "Storage Device" },
    { value: "accessories", text: "Accessories" },
  ],
  software: [
    { value: "operating-system", text: "Operating System" },
    { value: "productivity-tool", text: "Productivity Tool" },
    { value: "development-tool", text: "Development Tool" },
    { value: "security-software", text: "Security Software" },
    { value: "enterprise-software", text: "Enterprise Software" },
    { value: "cloud-subscription", text: "Cloud Subscription" },
  ],

  office_equipments: [
    { value: "printer", text: "Printer" },
    { value: "scanner", text: "Scanner" },
    { value: "projector", text: "Projector" },
    { value: "audio-visual-equipment", text: "Audio Visual Equipment" },
    {
      value: "video-conferencing-equipment",
      text: "Video Conferencing Equipment",
    },
    { value: "telephone", text: "Telephone" },
    { value: "fax-machine", text: "Fax Machine" },
  ],

  other: [],
};

function updateStatusDropdown(category) {
  statusDropdown.innerHTML =
    '<option value="" disabled selected>select a status</option>';
  const options = statusOptions[category];
  options.forEach((option) => {
    const opt = document.createElement("option");
    opt.value = option.value;
    opt.textContent = option.text;
    if (
      (category === "office-equipments" ||
        category === "hardware" ||
        category === "other") &&
      opt.value === "available"
    ) {
      opt.setAttribute("selected", "selected");
    } else if (category === "software" && opt.value === "inactive") {
      opt.setAttribute("selected", "selected");
    }
    statusDropdown.appendChild(opt);
  });
}

function updateSubcategoryDropdown(category) {
  subcategorySelect.innerHTML =
    '<option value="" disabled selected>Select a subcategory</option>';
  const options = subcategory[category];
  options.forEach((option) => {
    const opt = document.createElement("option");
    opt.value = option.value;
    opt.textContent = option.text;
    subcategorySelect.appendChild(opt);
  });
}

category.addEventListener("change", function () {
  const selectedCategory = category.value;

  if (selectedCategory === "software") {
    subcategoryContainer.classList.remove("hidden");
    softwareGroup.forEach((group) => group.classList.remove("hidden"));
    hardwareGroup.forEach((group) => group.classList.add("hidden"));
    updateStatusDropdown("software");
    updateSubcategoryDropdown("software");
  } else if (selectedCategory === "hardware") {
    subcategoryContainer.classList.remove("hidden");
    softwareGroup.forEach((group) => group.classList.add("hidden"));
    hardwareGroup.forEach((group) => group.classList.remove("hidden"));
    updateStatusDropdown("hardware");
    updateSubcategoryDropdown("hardware");
  } else if (selectedCategory === "office-equipment") {
    subcategoryContainer.classList.remove("hidden");
    softwareGroup.forEach((group) => group.classList.add("hidden"));
    hardwareGroup.forEach((group) => group.classList.remove("hidden"));
    updateStatusDropdown("hardware");
    updateSubcategoryDropdown("office_equipments");
  } else if (selectedCategory === "other") {
    softwareGroup.forEach((group) => group.classList.add("hidden"));
    hardwareGroup.forEach((group) => group.classList.add("hidden"));
    updateStatusDropdown("hardware");
    subcategorySelect.classList.add("hidden");
  }
});

document.addEventListener("DOMContentLoaded", function () {
  subcategoryContainer.classList.add("hidden");
});
