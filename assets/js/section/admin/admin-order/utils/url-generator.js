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
