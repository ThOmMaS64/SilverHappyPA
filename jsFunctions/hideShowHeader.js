let lastScroll = 0;
const header = document.querySelector("header");

window.onscroll = function () {
  var currentScroll = window.pageYOffset;

  if (currentScroll > lastScroll) {
    header.classList.add("hide");
  } else {
    header.classList.remove("hide");
  }

  lastScroll = currentScroll;
};
