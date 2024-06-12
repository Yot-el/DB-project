const surfaceInput = document.querySelector("input[name='total_surface_limit']");
const surfaceRange = document.querySelector(".total-surface-limit");

surfaceRange.textContent = surfaceInput.value;

surfaceInput.addEventListener("input", (event) => {
  surfaceRange.textContent = event.target.value;
})