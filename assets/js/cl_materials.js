document.addEventListener('DOMContentLoaded', event => {
  $("#materials").DataTable();

  const arrivalMaterial = document.getElementById("arrivalMaterial");
  if (arrivalMaterial) {
    arrivalMaterial.addEventListener('click', e => {

    });
  }

  const consumptionMaterial = document.getElementById("consumptionMaterial");
  if (consumptionMaterial) {
    consumptionMaterial.addEventListener('click', e => {

    });
  }

  customElements.define("search-dropdown", SearchDropdown);

});