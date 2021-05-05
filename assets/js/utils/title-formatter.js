export function getProductInformativeTitle(productInfo) {
  return (
    "#" +
    productInfo.id +
    " " +
    productInfo.title +
    " / P: " +
    productInfo.price +
    "$ " +
    "/ Q: " +
    productInfo.quantity
  );
}
