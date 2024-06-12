const form = document.querySelector(".form");
const terminationDateInput = form.querySelector("input[name='termination_date']");
const terminationReasonSelect = form.querySelector("select[name='termination_reason_id']");
const startDateInput = form.querySelector("input[name='start_date']");
const endDateInput = form.querySelector("input[name='end_date']");

form.addEventListener("submit", (event) => {
  event.preventDefault();

  const terminationDate = terminationDateInput.value;
  const terminationReason = terminationReasonSelect.value;
  const startDate = new Date(startDateInput.value);
  const endDate = new Date(endDateInput.value);

  if (!!terminationDate !== !!terminationReason) {
    alert("Укажите оба поля: и дату расторжения договора, и причину расторжения.");
    return;
  }

  if (startDate >= endDate) {
    alert("Укажите корректные даты начала и окончания аренды");
    return;
  }

  if (new Date(terminationDate) < startDate || new Date(terminationDate) > endDate) {
    alert("Укажите корректную дату расторжения аренды");
    return;
  }

  form.submit();
})