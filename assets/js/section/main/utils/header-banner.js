import Cookies from "js-cookie";

const headerBannerBlockElem = document.getElementById("header_banner_block");
const headerBannerBtnElem = document.getElementById("header_banner_btn");

function initHeaderBannerEvents() {
  if (headerBannerBtnElem) {
    headerBannerBtnElem.addEventListener("click", () => {
      Cookies.set("showHeaderBanner", 0, { expires: 1 });
      changeHeaderBannerBlockDisplay();
    });
  }

  changeHeaderBannerBlockDisplay();
}

function changeHeaderBannerBlockDisplay() {
  if (Cookies.get("showHeaderBanner") === "0") {
    headerBannerBlockElem.classList.add("hide");
  } else {
    headerBannerBlockElem.classList.remove("hide");
  }
}

initHeaderBannerEvents();
