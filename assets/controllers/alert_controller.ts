import AbstractController from "@assets/controllers/AbstractController";

export default class extends AbstractController<HTMLDivElement> {
  connect() {
    setTimeout(() => {
      this.element.style.display = "none";
    }, 5000);
  }
}
