<div id="search" class="search">
  <div class="search__field">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M1 15L4.38333 11.6167M2.55556 7.22222C2.55556 10.6587 5.34134 13.4444 8.77778 13.4444C12.2142 13.4444 15 10.6587 15 7.22222C15 3.78578 12.2142 1 8.77778 1C5.34134 1 2.55556 3.78578 2.55556 7.22222Z" stroke="#FFC34F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <input type="text" value="<?php echo $search; ?>" placeholder="<?php echo $text_search; ?>" name="search"  autocomplete="off">
  </div>
  <div class="search__select">
    <select>
      <option value="Каталог" selected>Каталог</option>
      <option value="Товар">Товар</option>
      <option value="Бренд">Бренд</option>
      <option value="Коллекции">Коллекции</option>
      <option value="Материал">Материал</option>
      <option value="Артикул">Артикул</option>
    </select>
  </div>
  <button class="search__btn btn" type="button">Найти</button>
</div>
