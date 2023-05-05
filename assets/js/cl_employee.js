document.addEventListener("click", ev => {
  const employeeTable = document.getElementById("tbl_employees");
  if (employeeTable) {
    const modal = document.getElementById("modal_employeeInfo");
    console.log("11111111");
    employeeTable.addEventListener("click", e => {
      const target = e.target.closest("tr.emp_info");
      if (!target) return false;

      $('#modal_employeeInfo').modal('show');
    });
    
  }
});