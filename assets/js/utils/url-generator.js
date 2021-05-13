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

export function getUrlProductsByCategory(
  defaultUrl,
  categoryId,
  page,
  countLimit
) {
  return (
    defaultUrl +
    "?category=api/category/" +
    categoryId +
    "&page=" + page +
    "&itemsPerPage=" +
    countLimit +
    "&isHidden=0"
  );
}

export function getUrlProductsBySaleCollection(
  defaultUrl,
  saleCollectionId,
  page,
  countLimit
) {
  return (
    defaultUrl +
    "?saleCollection=api/sale_collection/" +
    saleCollectionId +
    "&page=" + page +
    "&itemsPerPage=" +
    countLimit +
    "&isHidden=0"
  );
}

export function concatUrlByParams(...params) {
  return params.join("/");
}
