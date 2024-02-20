document.addEventListener("DOMContentLoaded", ev => {
  const saveCarBtn = document.getElementById("saveCar");
  if (saveCarBtn) {
    saveCarBtn.addEventListener("click", e => {
      if (!validityState()) return false;
      const modal = document.getElementById("modal_addCar");
      const data = {
        user: modal.querySelector("#driver").value,
        car_name: modal.querySelector("#car_name").value,
        consumption: modal.querySelector("#consumption").value,
      }

      url_path = base_url + '/save_car';
      console.log(url_path);

      $.ajax({
      url: url_path,
      data: data,
      method: 'POST',
      success: function (result) {
        console.log(result)
      },
      fail: function(result) {
        console.error(result);
        alert("Error while status update");
      }
    });
    });
  }
});

function validityState() {
  const modal = document.getElementById("modal_addCar");
  const select = modal.querySelector("#driver");
  if (!select.value) {
    alert("Выберите водителя!");
    select.focus();
    return false;
  }

  const car = modal.querySelector("#car_name");
  if (!car.value) {
    alert("Введите номер машины!");
    car.focus();
    return false;
  }

  const consumption = modal.querySelector("#consumption");
  if (!consumption.value) {
    alert("Введите расход на 100км!");
    consumption.focus();
    return false;
  }
return true;
}