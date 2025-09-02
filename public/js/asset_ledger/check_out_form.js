document.addEventListener("DOMContentLoaded", function () {
  // Initialize the Employee Dropdown with client-side searching
  $("#employee-id").select2({
    placeholder: "Search for an employee...",
    width: "100%",
    dropdownParent: $("#employee-id").parent(),
  });

  // Automatically fill the employee name when an employee is selected
  $("#employee-id").on("select2:select", function (e) {
    // Get the full text of the selected option
    const selectedName = $(this).find("option:selected").text().trim();
    document.getElementById("employee-name").value = selectedName || "";
  });

  // Initialize the Asset Dropdown with client-side searching
  $("#asset").select2({
    placeholder: "Search for an asset...",
    width: "100%",
    dropdownParent: $("#asset").parent(),
  });

  // When a dropdown is opened, automatically focus the search field inside it
  $(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
  });
});
