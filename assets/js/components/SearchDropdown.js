class SearchDropdown extends HTMLElement {
  constructor() {
    super();
    this.title = "Выберите";
    // элемент создан
  }

  connectedCallback() {
    // браузер вызывает этот метод при добавлении элемента в документ
    // (может вызываться много раз, если элемент многократно добавляется/удаляется)
    const nid = this.getAttribute('nid');
    const items = OBJ[nid];
    
    this.title = this.getAttribute('ntitle');

    
    let lis = "";
    if (items) {      
      items.forEach(item => {
        lis += `<a href="#about">${item.material}</a>`;
      });
    }

    this.innerHTML = `<div class="dropdown dropdown--search">
    <button class="dropbtn"> ${this.title} </button>
    <div id="myDropdown" class="dropdown-content">
      <input type="text" placeholder="Поиск.." class="search-input">
      <div class="dropdown-items"> ${lis} </div>
    </div>
  </div>`;

  
  this.querySelector("button.dropbtn").addEventListener('click', this._onClick.bind(this));
  this.querySelector("input.search-input").addEventListener('keyup', this._filter.bind(this));
  document.addEventListener('keyup', this._hide.bind(this));
  this.querySelector(".dropdown-content").addEventListener('click', this._setValue.bind(this));
  
  }

  disconnectedCallback() {
    // браузер вызывает этот метод при удалении элемента из документа
    // (может вызываться много раз, если элемент многократно добавляется/удаляется)
  }

  static get observedAttributes() {
    return [/* массив имён атрибутов для отслеживания их изменений */];
  }

  attributeChangedCallback(name, oldValue, newValue) {
    // вызывается при изменении одного из перечисленных выше атрибутов
  }

  adoptedCallback() {
    // вызывается, когда элемент перемещается в новый документ
    // (происходит в document.adoptNode, используется очень редко)
  }

  // у элемента могут быть ещё другие методы и свойства

  _onClick() {
    this.querySelector("#myDropdown").classList.toggle("show");
    this.querySelector(".dropdown").classList.toggle("open");
  }
  
  _filter() {
    let input, filter, ul, li, a, i;
    input = this.querySelector(".search-input");
    
    filter = input.value.toUpperCase();
    const div = input.closest(".dropdown-content");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
      let txtValue = a[i].textContent || a[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        a[i].style.display = "";
      } else {
        a[i].style.display = "none";
      }
    }
  }

  _showAllItems() {
    let input, filter, ul, li, a, i;
    input = this.querySelector(".search-input");
    
    filter = input.value.toUpperCase();
    const div = input.closest(".dropdown-content");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
      a[i].style.display = "";
    }
  }

  _hide(event) {
    if (event.key==='Escape') {
      this.querySelector("#myDropdown").classList.remove("show");
      this.querySelector(".dropdown").classList.remove ("open");
      this.querySelector("button.dropbtn").textContent = this.title;
      this.querySelector("input.search-input").value = "";
      this._showAllItems();
    }
    
  }

  _setValue(event) {
    const target = event.target.closest("a");
    if (!target) {
      return false;
    }
    
    this.title = target.textContent || target.innerText;
    this.querySelector("button.dropbtn").textContent = this.title;

    this.querySelector("#myDropdown").classList.remove("show");
    this.querySelector(".dropdown").classList.remove ("open");
    this.querySelector("input.search-input").value = "";
    this._showAllItems();
  }
}

