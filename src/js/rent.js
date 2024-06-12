buttons = document.querySelectorAll(".rent__button");
modal = document.querySelector(".delete-modal");

const openDeleteModal = (event) => {
  const form = modal.querySelector(".form");
  const idField = modal.querySelector(".delete-rent-id");
  const idInput = modal.querySelector("input[name='id']");
  const terminationDateInput = modal.querySelector("input[name='termination_date']");
  idField.textContent = event.target.dataset.id;
  idInput.value = event.target.dataset.id;

  const startDate = new Date(event.target.dataset.startDate);
  const endDate = new Date(event.target.dataset.endDate);

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const terminationDate = new Date(terminationDateInput.value);

    if (terminationDate > endDate || terminationDate < startDate) {
      alert("Дата расторжения договора выходит за пределы установленных дат начала и окончания аренды.")
    } else {
      form.submit();
    }
  });

  modal.showModal();
}

buttons.forEach(button => {
  button.addEventListener('click', openDeleteModal);
});