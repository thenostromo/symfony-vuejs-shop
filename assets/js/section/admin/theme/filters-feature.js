window.toggleFiltersVisibility = function toggleFiltersVisibility(section) {
  const filtersVisibleJSON = localStorage.getItem('filtersVisible');
  const filtersVisible = filtersVisibleJSON ? JSON.parse(filtersVisibleJSON) : {};

  filtersVisible[section] = filtersVisible[section] === undefined
    ? 1
    : !filtersVisible[section]

  localStorage.setItem('filtersVisible', JSON.stringify(filtersVisible));
}

window.changeFiltersBlockVisibility = function changeFiltersBlockVisibility(filtersSection, element) {
  const filtersVisibleJSON = localStorage.getItem('filtersVisible');
  const filtersVisible = JSON.parse(filtersVisibleJSON);

  element.style.display = filtersVisible[filtersSection]
    ? "block"
    : "none";
}
