class SearchDropdown extends HTMLElement {
  constructor() {
    super();
    this.title = "Выберите";

    const nid = this.getAttribute('nid');
    const items = OBJ[nid];
    
    this.title = this.getAttribute('ntitle');

    
    let lis = "";
    if (items) {      
      items.forEach(item => {
        lis += `<div class="dropdown-item">${item.material}</div>`;
      });
    }

    // элемент создан
    const shadow = this.attachShadow({mode: 'open'});
    const elemHTML = `
    <link rel="stylesheet" href="http://zrfinance/assets/css/webcomponents.css">
    <div class="dropdown dropdown--search">
    <button class="dropbtn"> ${this.title} </button>
    <div class="dropdown-content">
      <input type="text" placeholder="Поиск.." class="search-input">
      <div class="dropdown-items"> ${lis} </div>
    </div>
  </div>`;

  shadow.innerHTML = elemHTML;

  shadow.querySelector("button.dropbtn").addEventListener('click', this._onClick.bind(this));
  shadow.querySelector("input.search-input").addEventListener('keyup', this._filter.bind(this));
  document.addEventListener('keyup', this._hide.bind(this));
  shadow.querySelector(".dropdown-content").addEventListener('click', this._setValue.bind(this));
  }

  connectedCallback() {
    
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
    let shadow = this.shadowRoot;
    shadow.querySelector(".dropdown-content").classList.toggle("show");
    shadow.querySelector(".dropdown").classList.toggle("open");
  }
  
  _filter() {
    let input, filter, ul, li, item, i;
    let shadow = this.shadowRoot; 
    input = shadow.querySelector(".search-input");
    
    filter = input.value.toUpperCase();
    const div = input.closest(".dropdown-content");
    item = div.querySelectorAll(".dropdown-item");
    for (i = 0; i < item.length; i++) {
      let txtValue = item[i].textContent || item[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        item[i].style.display = "";
      } else {
        item[i].style.display = "none";
      }
    }
  }

  _showAllItems() {
    let input, filter, ul, li, item, i;
    
    let shadow = this.shadowRoot;
    input = shadow.querySelector(".search-input");
    
    filter = input.value.toUpperCase();
    const div = input.closest(".dropdown-content");
    item = div.querySelectorAll(".dropdown-item");
    for (i = 0; i < item.length; i++) {
      item[i].style.display = "";
    }
  }

  _hide(event) {
    let shadow = this.shadowRoot;
    if (event.key==='Escape') {
      shadow.querySelector(".dropdown-content").classList.remove("show");
      shadow.querySelector(".dropdown").classList.remove ("open");
      shadow.querySelector("button.dropbtn").textContent = this.title;
      shadow.querySelector("input.search-input").value = "";
      this._showAllItems();
    }
    
  }

  _setValue(event) {
    let shadow = this.shadowRoot;
    const target = event.target.closest(".dropdown-item");
    if (!target) {
      return false;
    }
    
    this.title = target.textContent || target.innerText;
    shadow.querySelector("button.dropbtn").textContent = this.title;

    shadow.querySelector(".dropdown-content").classList.remove("show");
    shadow.querySelector(".dropdown").classList.remove ("open");
    shadow.querySelector("input.search-input").value = "";
    this._showAllItems();
  }
}

