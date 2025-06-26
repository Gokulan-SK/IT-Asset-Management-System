const deleteButtons = document.querySelectorAll(".delete-button");
const modal = document.getElementById("delete-modal");
const deleteForm = document.getElementById("delete-form");
const closeBtn = modal.querySelector(".modal-closebtn");
const cancelBtn = modal.querySelector(".cancel-button");
const hiddenIdInput = document.getElementById("delete-item-id");

deleteButtons.forEach((button) => {
  button.addEventListener("click", function () {
    const id = this.getAttribute("data-id");
    deleteForm.action = `delete`;
    hiddenIdInput.value = id;
    console.log("Form action set to:", deleteForm.action);
    console.log("id:", id);
    modal.style.display = "flex";
  });
});

const closeModal = () => {
  modal.style.display = "none";
};

closeBtn.addEventListener("click", closeModal);
cancelBtn.addEventListener("click", closeModal);

window.addEventListener("click", function (event) {
  if (event.target === modal) {
    closeModal();
  }
});
