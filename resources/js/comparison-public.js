"use strict";

document.addEventListener("click", function (event) {
  let termId = event.target.dataset.toggleId;
  if (!termId) return;

  event.target.classList.toggle("active");

  let elem = event.target.closest(".com-comparison-wrap").nextElementSibling;
  elem.classList.toggle("hidden-terms");
  elem.classList.toggle("shown-terms");
});

window.addEventListener("DOMContentLoaded", () => {
  //filter for items
  document.addEventListener("click", function (event) {
    var el = event.target;
    if (!el.classList.contains("isotop__grid--item")) {
      el = el.closest(".isotop__grid--item");
    }

    if (!el?.classList.contains("isotop__grid--item")) return true;

    event.preventDefault();
    event.stopPropagation();

    const elements = el
      .closest(".isotop__grid")
      .querySelectorAll(".isotop__grid--list");

    const collection = el
      .closest(".com-comparison-plugin")
      .getElementsByClassName("com_comarison__list--container");

    elements.forEach(function (element) {
      element.classList.remove("active");
    });

    Array.from(collection).forEach(function (item) {
      if (item.classList.contains(el.dataset.key)) {
        item.classList.remove("hidden");
      } else {
        item.classList.add("hidden");
      }
    });

    el.parentNode.classList.add("active");
    return false;
  });

  //Open extra info
  document.addEventListener("click", function (event) {
    let el = event.target;

    if (!el.classList.contains("js-info-open")) return true;
    event.preventDefault();
    event.stopPropagation();

    el.closest(".item.hidden-info")?.classList.remove("hidden-info");
  });

  //close extra info
  document.addEventListener("click", function (event) {
    let el = event.target;

    if (!el.classList.contains("js-info-close")) return true;
    event.preventDefault();
    event.stopPropagation();

    el.closest(".item").classList.add("hidden-info");
  });

  //toggle filter list
  document.addEventListener("click", function (event) {
    let el = event.target;
    if (
      !el.classList.contains("com__btn") &&
      !el.classList.contains("com__icon--menu")
    )
      return true;
    event.preventDefault();
    event.stopPropagation();

    const element = el
      .closest(".com-comparison-plugin")
      .getElementsByClassName("isotop__grid");

    element[0].classList.contains("hidden")
      ? element[0].classList.remove("hidden")
      : element[0].classList.add("hidden");

    return false;
  });

  //load more handler
  document.addEventListener("click", function (event) {
    let el = event.target;
    if (!el.classList.contains("com_btn--loadmore")) return true;
    event.preventDefault();
    event.stopPropagation();
    let category = el.getAttribute("data-list");
    let offset = el.getAttribute("data-limit");
    let rest_url = el.getAttribute("data-resturl");

    fetch(rest_url + "/wp-json/comparison/v1/getcards", {
      body: "p=" + JSON.stringify({ category, offset }),
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "post"
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (data.status == 200) {
          let parent = el.closest(".com_comarison__list--container");
          setTimeout(() => {
            parent.classList.add("animate");
          }, 500);
          el.insertAdjacentHTML("beforebegin", data.data);
          el.remove();
        }
      });
  });

  //load more list handler
  document.addEventListener("click", function (event) {
    let el = event.target;
    if (!el.classList.contains("com_btn-list--loadmore")) return true;
    event.preventDefault();
    event.stopPropagation();
    let category = el.getAttribute("data-list");
    let offset = el.getAttribute("data-limit");
    let rest_url = el.getAttribute("data-resturl");
    let list_id = el.getAttribute("data-id");

    fetch(rest_url + "/wp-json/comparison/v1/get_list_cards", {
      body: "p=" + JSON.stringify({ category, offset, list_id }),
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "post"
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (data.status == 200) {
          let parent = el.closest(".com_comarison__list--container");
          let child = parent.querySelector(".com_comparision_table-container");
          setTimeout(() => {
            parent.classList.add("animate");
          }, 500);
          child.insertAdjacentHTML("beforeend", data.data);
          el.remove();
        }
      });
  });
});
