export function getUrlProductView(defaultUrlView, productId) {
  return (
    window.location.protocol +
    "//" +
    window.location.host +
    defaultUrlView +
    "/" +
    productId
  );
}

export function getUrlProductsByCategory(defaultUrl, categoryId, countLimit) {
  return (
    defaultUrl +
    "?category=api/products/" +
    categoryId +
    "&page=1" +
    "&itemsPerPage=" +
    countLimit +
    "&isHidden=0"
  );
}

export function concatUrlByParams(...params) {
  return params.join("/");
}
