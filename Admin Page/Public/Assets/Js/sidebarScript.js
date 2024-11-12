const toggler = document.querySelector(".toggle-btn");
toggler.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("collapse");
});

const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});
