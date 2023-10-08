let item = document.querySelector(".item");
let item_content = document.querySelector(".item_content");
let close_item = document.querySelector("#close_item");

item.onclick = () => {
    item_content.classList.toggle("active");
}
close_item.onclick = () => {
    item_content.classList.remove("active");
}