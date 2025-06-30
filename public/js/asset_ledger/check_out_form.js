document.addEventListener("DOMContentLoaded", function () {
  // Employee Dropdown
  $("#employee-id").select2({
    placeholder: "Search for an employee...",
    width: "100%",
    minimumInputLength: 1,
    ajax: {
      url: "/asset_management/api/employee/search",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          term: params.term,
        };
      },
      processResults: function (data) {
        return {
          results: data.results.map((emp) => ({
            id: emp.id,
            text: emp.text,
            name: emp.name || "",
          })),
        };
      },
      cache: true,
    },
    dropdownParent: $("#employee-id").parent(),
  });

  $("#employee-id").on("select2:open", function () {
    document.querySelector(".select2-search__field").focus();
  });

  $("#employee-id").on("select2:select", function (e) {
    const selectedData = e.params.data;
    document.getElementById("employee-name").value = selectedData.name || "";
  });

  // Asset Dropdown
  $("#asset").select2({
    placeholder: "Search for an asset...",
    width: "100%",
    minimumInputLength: 1,
    ajax: {
      url: "/asset_management/api/asset/search",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          term: params.term,
        };
      },
      processResults: function (data) {
        return {
          results: data.results.map((asset) => ({
            id: asset.id,
            text: asset.text,
          })),
        };
      },
      cache: true,
    },
    dropdownParent: $("#asset").parent(),
  });

  $("#asset").on("select2:open", function () {
    document.querySelector(".select2-search__field").focus();
  });
  $("#asset").on("select2:open", function () {
    document.querySelector(".select2-search__field").focus();
  });
});
