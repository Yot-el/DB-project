const deleteModal = document.querySelector(".delete-modal");
const deleteModalOpenButton = document.querySelector(".delete-modal-open");
const deleteModalCloseButton = document.querySelector(".delete-modal-close");

deleteModalOpenButton.addEventListener('click', () => {
  deleteModal.showModal();
})

deleteModalCloseButton.addEventListener('click', () => {
  deleteModal.close();
})