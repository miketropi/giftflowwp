export const replaceContentBySelector = (selector, content) => {
  const elem = document.querySelector(selector);
  if (elem) {
    elem.innerHTML = content;
  } else {
    console.error(`Element not found for selector: ${selector}`);
  }
}